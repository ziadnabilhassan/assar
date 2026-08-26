<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminOrderController extends Controller
{
    public function dashboard(): JsonResponse
    {
        $recentOrders = Order::with('user')
            ->latest()
            ->limit(10)
            ->get()
            ->map(fn (Order $order) => $this->adminOrderPayload($order))
            ->values();

        return response()->json([
            'success' => true,
            'status' => true,
            'message' => 'Admin dashboard fetched successfully',
            'data' => [
                'counts' => [
                    'total_orders' => Order::count(),
                    'pending_instapay_proofs' => Order::where('payment_provider', 'instapay')
                        ->where('payment_status', 'awaiting_transfer_proof')
                        ->count(),
                    'uploaded_instapay_proofs' => Order::where('payment_provider', 'instapay')
                        ->whereIn('payment_status', ['proof_uploaded', 'pending_review'])
                        ->count(),
                ],
                'recent_orders' => $recentOrders,
            ],
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        $data = $request->validate([
            'payment_method' => ['nullable', Rule::in(['instapay'])],
            'payment_status' => ['nullable', Rule::in(['proof_uploaded', 'pending_review', 'awaiting_transfer_proof', 'paid', 'rejected'])],
            'status' => ['nullable', Rule::in(['pending', 'processing', 'completed', 'cancelled'])],
        ]);

        $orders = Order::with('user')
            ->when($data['payment_method'] ?? null, fn ($query, $paymentMethod) => $query->where('payment_method', $paymentMethod))
            ->when($data['payment_status'] ?? null, fn ($query, $paymentStatus) => $query->where('payment_status', $paymentStatus))
            ->when($data['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->latest()
            ->paginate((int) $request->query('per_page', 20));

        return response()->json([
            'success' => true,
            'status' => true,
            'message' => 'Admin orders fetched successfully',
            'data' => [
                'orders' => $orders->getCollection()
                    ->map(fn (Order $order) => $this->adminOrderPayload($order))
                    ->values(),
                'pagination' => [
                    'current_page' => $orders->currentPage(),
                    'last_page' => $orders->lastPage(),
                    'per_page' => $orders->perPage(),
                    'total' => $orders->total(),
                ],
            ],
        ]);
    }

    public function updatePaymentProofStatus(Request $request, Order $order): JsonResponse
    {
        $data = $request->validate([
            'payment_status' => ['required', Rule::in(['paid', 'rejected', 'pending_review'])],
            'admin_note' => 'nullable|string|max:1000',
        ]);

        $order->forceFill([
            'payment_status' => $data['payment_status'],
            'payment_admin_note' => $data['admin_note'] ?? null,
        ])->save();

        return response()->json([
            'success' => true,
            'status' => true,
            'message' => 'Payment proof status updated successfully',
            'data' => [
                'order' => $this->adminOrderPayload($order->fresh('user')),
            ],
        ]);
    }

    private function adminOrderPayload(Order $order): array
    {
        $customerName = trim(($order->user?->first_name ?? '') . ' ' . ($order->user?->last_name ?? ''));

        return [
            'id' => $order->id,
            'customer' => [
                'name' => $customerName ?: $order->user_name,
                'email' => $order->user?->email,
            ],
            'total' => $this->money($order->total),
            'status' => $order->status,
            'payment_method' => $order->payment_method,
            'payment_provider' => $order->payment_provider,
            'payment_status' => $order->payment_status,
            'payment_proof_url' => $order->payment_proof_url,
            'admin_note' => $order->payment_admin_note,
            'created_at' => $order->created_at?->toJSON(),
        ];
    }

    private function money(mixed $value): int|float
    {
        $number = (float) $value;

        return floor($number) == $number ? (int) $number : round($number, 2);
    }
}
