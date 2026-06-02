<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\OrderMail;
use App\Models\Category;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\PromoCode;
use App\Models\User;
use App\Models\Variant;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderCartController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        if (Cart::isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Cart is empty'
            ], 400);
        }

        $user = null;
        $discount = 0;
        $subtotalAfterDiscount = Cart::getSubTotal();

        $deliveries = Delivery::get();

        if (auth()->check()) {
            $user = User::with('address')
                ->where('id', auth()->id())
                ->first();
        }

        if ($request->coupon_code) {
            $promoCode = PromoCode::where(
                'code',
                $request->coupon_code
            )->first();

            if ($promoCode && $promoCode->isValid()) {
                $discount = $promoCode->calculateDiscount(
                    Cart::getSubTotal()
                );

                $subtotalAfterDiscount -= $discount;
            }
        }

        $categories = Category::withCount('products')
            ->latest()
            ->get([
                'id',
                'title',
                'image'
            ]);

        return response()->json([
            'status' => true,
            'message' => 'Checkout data fetched successfully',
            'data' => [
                'user' => $user,
                'subtotal' => Cart::getSubTotal(),
                'discount' => $discount,
                'subtotalAfterDiscount' => $subtotalAfterDiscount,
                'deliveries' => $deliveries,
                'categories' => $categories,
                'cartItems' => Cart::getContent(),
            ]
        ]);
    }

    public function storeOrder(Request $request): JsonResponse
    {
        if (Cart::isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Cart is empty'
            ], 400);
        }

        $data = $this->validateAndPrepareOrderData($request);

        DB::beginTransaction();

        try {

            $order = $this->createOrder($data, $request);

            $this->addProductsToOrder($order);

            DB::commit();

            Cart::clear();

            try {
                Mail::to($order->email)
                    ->send(new OrderMail($order));
            } catch (\Exception $mailError) {

                \Log::error(
                    'Mail sending failed: ' .
                    $mailError->getMessage()
                );
            }

            return response()->json([
                'status' => true,
                'message' => 'Order created successfully',
                'data' => [
                    'order' => $order,
                    'payment_url' => $request->pay == 'card'
                        ? route(
                            'payment',
                            ['id' => Crypt::encrypt($order->id)]
                        )
                        : null
                ]
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    protected function validateAndPrepareOrderData(
        Request $request
    ): array {

        $data = $request->validate([
            'first_name' => 'required|max:255|string',
            'last_name' => 'required|max:255|string',
            'phone' => 'required|max:20|string',
            'delivery' => 'required|integer|exists:deliveries,id',
            'city' => 'required|max:255|string',
            'address' => 'required|max:500|string',
            'note' => 'nullable|max:500|string',
            'pay' => 'nullable|string|max:255',
            'coupon_code' => 'nullable|string',
        ]);

        $data['user_name'] = $data['first_name']
            . ' ' .
            $data['last_name'];

        unset(
            $data['first_name'],
            $data['last_name']
        );

        $data['code'] = rand(100000, 999999);

        $data['user_id'] = auth()->check()
            ? auth()->id()
            : null;

        $delivery = Delivery::find($request->delivery);

        $data['delivery'] = $delivery->getTranslation(
            'name',
            'ar'
        );

        $data['shipping'] = $delivery->cost;

        return $data;
    }

    protected function createOrder(
        array $data,
        Request $request
    ) {
        $discount = $this->applyCoupon($request, $data);

        $data['total'] = (
            Cart::getSubTotal()
            - $discount
            + $data['shipping']
        );

        return Order::create($data);
    }

    protected function applyCoupon(
        Request $request,
        array &$data
    ) {

        if (!$request->has('coupon_code')) {
            return 0;
        }

        $promoCode = PromoCode::where(
            'code',
            $request->coupon_code
        )->first();

        if (!$promoCode || !$promoCode->isValid()) {
            return 0;
        }

        $discount = $promoCode->calculateDiscount(
            Cart::getSubTotal()
        );

        $data['coupon'] = $request->coupon_code;

        $type = $promoCode->discount_type == 'percentage'
            ? '% '
            : 'LE ';

        $data['discount'] = $type .
            $promoCode->discount_value;

        $promoCode->increment('uses_count');

        return $discount;
    }

    protected function addProductsToOrder(Order $order): void
    {
        foreach (Cart::getContent() as $item) {

            $variant = Variant::where(
                'id',
                $item->attributes->variant_id
            )
                ->lockForUpdate()
                ->first();

            if (
                !$variant ||
                $variant->quantity < $item->quantity
            ) {

                throw new \Exception(
                    __('main.Requested quantity exceeds available stock')
                );
            }

            $order->products()->create([
                'product_id' => $item->attributes->product_id,
                'name' => $item->name['en'],
                'quantity' => $item->quantity,
                'color' => $item->attributes?->color['ar'] ?? '',
                'size' => $item->attributes?->size ?? '',
                'price' => $item->price,
                'total_price' => (
                    $item->price * $item->quantity
                ),
            ]);

            $variant->decrement(
                'quantity',
                $item->quantity
            );
        }
    }
}
