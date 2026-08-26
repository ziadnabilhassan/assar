<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\CategoryType;
use App\Models\Color;
use App\Models\Delivery;
use App\Models\Desgin;
use App\Models\DesignSticker;
use App\Models\Order;
use App\Models\Page;
use App\Models\PromoCode;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Review;
use App\Models\SavedDesign;
use App\Models\Size;
use App\Models\Slider;
use App\Models\User;
use App\Models\Variant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoCatalogSeeder extends Seeder
{
    /**
     * Seed a small demo catalog for local app testing.
     */
    public function run(): void
    {
        $users = $this->seedUsers();
        $catalog = $this->seedCatalog();
        $templates = $this->seedDesignAssets();

        $this->seedHomeContent();
        $this->seedCheckoutData();
        $this->seedCustomerActivity($users['customer'], $catalog, $templates);
    }

    private function seedUsers(): array
    {
        $customer = User::updateOrCreate(
            ['email' => 'customer@athar.test'],
            [
                'first_name' => 'Mona',
                'last_name' => 'Hassan',
                'phone' => '01000000002',
                'password' => Hash::make('password123'),
            ]
        );

        $designer = User::updateOrCreate(
            ['email' => 'designer@athar.test'],
            [
                'first_name' => 'Omar',
                'last_name' => 'Designer',
                'phone' => '01000000003',
                'password' => Hash::make('password123'),
            ]
        );

        return compact('customer', 'designer');
    }

    private function seedCatalog(): array
    {
        $categoryTypes = [
            'Fashion' => $this->categoryType('Fashion', 'ازياء', 'assets/img/ecommerce/03.jpg'),
            'Gifts' => $this->categoryType('Gifts', 'هدايا', 'assets/img/ecommerce/06.jpg'),
            'Home Decor' => $this->categoryType('Home Decor', 'ديكور المنزل', 'assets/img/ecommerce/08.jpg'),
        ];

        $categories = [
            'T-Shirts' => $this->category($categoryTypes['Fashion'], 'T-Shirts', 'تيشيرتات', 'website/assets/img/product/product1.png'),
            'Hoodies' => $this->category($categoryTypes['Fashion'], 'Hoodies', 'هوديز', 'website/assets/img/product/product5.png'),
            'Mugs' => $this->category($categoryTypes['Gifts'], 'Mugs', 'اكواب', 'website/assets/img/product/product9.png'),
            'Tote Bags' => $this->category($categoryTypes['Gifts'], 'Tote Bags', 'حقائب قماش', 'website/assets/img/product/product12.png'),
            'Posters' => $this->category($categoryTypes['Home Decor'], 'Posters', 'بوسترات', 'website/assets/img/product/product15.png'),
        ];

        $colors = [
            'White' => $this->color('White', 'ابيض', '#FFFFFF'),
            'Black' => $this->color('Black', 'اسود', '#111111'),
            'Navy' => $this->color('Navy', 'كحلي', '#1E3A8A'),
            'Sand' => $this->color('Sand', 'رملي', '#D6B98C'),
            'Rose' => $this->color('Rose', 'وردي', '#E88DA1'),
        ];

        $sizes = collect(['XS', 'S', 'M', 'L', 'XL'])
            ->mapWithKeys(fn (string $name) => [$name => Size::firstOrCreate(['name' => $name])])
            ->all();

        $products = [
            $this->product($categories['T-Shirts'], 'Custom White T-Shirt', 'تيشيرت ابيض مخصص', 'website/assets/img/product/product1.png', true, [
                [$colors['White'], $sizes['M'], 42, 350, 450],
                [$colors['White'], $sizes['L'], 36, 350, 450],
                [$colors['Black'], $sizes['M'], 18, 375, null],
            ]),
            $this->product($categories['T-Shirts'], 'Oversized Graphic Tee', 'تيشيرت اوفر سايز', 'website/assets/img/product/product2.png', true, [
                [$colors['Black'], $sizes['S'], 20, 420, 500],
                [$colors['Navy'], $sizes['M'], 14, 420, 500],
                [$colors['Rose'], $sizes['L'], 9, 430, null],
            ]),
            $this->product($categories['Hoodies'], 'Premium Print Hoodie', 'هودي بطباعة فاخرة', 'website/assets/img/product/product5.png', true, [
                [$colors['Navy'], $sizes['M'], 12, 780, 900],
                [$colors['Black'], $sizes['L'], 8, 790, null],
            ]),
            $this->product($categories['Mugs'], 'Personalized Ceramic Mug', 'كوب سيراميك باسمك', 'website/assets/img/product/product9.png', false, [
                [$colors['White'], $sizes['M'], 60, 180, 220],
                [$colors['Rose'], $sizes['M'], 28, 195, null],
            ]),
            $this->product($categories['Tote Bags'], 'Canvas Tote Bag', 'حقيبة كانفاس', 'website/assets/img/product/product12.png', false, [
                [$colors['Sand'], $sizes['M'], 25, 260, 320],
                [$colors['Black'], $sizes['M'], 16, 275, null],
            ]),
            $this->product($categories['Posters'], 'Minimal Wall Poster', 'بوستر حائط بسيط', 'website/assets/img/product/product15.png', false, [
                [$colors['White'], $sizes['S'], 30, 150, null],
                [$colors['Sand'], $sizes['M'], 22, 210, 250],
            ]),
            $this->product($categories['Posters'], 'Out Of Stock Poster', 'بوستر غير متوفر', 'website/assets/img/product/product14.png', false, [
                [$colors['White'], $sizes['M'], 0, 199, 240],
            ]),
        ];

        return compact('products', 'colors', 'sizes', 'categories');
    }

    private function seedHomeContent(): void
    {
        $sliders = [
            ['Custom apparel, made fast', 'صمم منتجك بسهولة', 'website/assets/img/slider/home1-slider1.png', '/products'],
            ['Save your designs', 'احفظ تصميماتك', 'website/assets/img/slider/home1-slider2.png', '/designs'],
            ['Gifts for every moment', 'هدايا لكل مناسبة', 'website/assets/img/slider/home1-slider3.png', '/products/category-type/2'],
        ];

        foreach ($sliders as [$title, $text, $image, $url]) {
            Slider::updateOrCreate(
                ['image' => $image],
                ['title' => ['en' => $title, 'ar' => $title], 'text' => ['en' => $text, 'ar' => $text], 'url' => $url]
            );
        }

        foreach ([
            ['website/assets/img/banner/banner1.png', '/products?discount=1'],
            ['website/assets/img/banner/banner2.png', '/products?stock=1'],
            ['website/assets/img/banner/banner3.png', '/designs/templates'],
        ] as [$image, $url]) {
            Banner::updateOrCreate(['image' => $image], ['url' => $url]);
        }

        foreach ([
            ['About Athar', 'عن اثر', 'Athar helps customers personalize everyday products and preview them before checkout.', 'اثر يساعد العملاء على تخصيص منتجاتهم اليومية ومعاينتها قبل الشراء.', 'website/assets/img/other/about-thumb-list1.png'],
            ['Shipping Policy', 'سياسة الشحن', 'Local demo orders include standard and express delivery options.', 'طلبات التجربة تحتوي على شحن عادي وسريع.', 'website/assets/img/other/shipping1.png'],
        ] as [$titleEn, $titleAr, $textEn, $textAr, $image]) {
            Page::updateOrCreate(
                ['title->en' => $titleEn],
                ['title' => ['en' => $titleEn, 'ar' => $titleAr], 'text' => ['en' => $textEn, 'ar' => $textAr], 'image' => $image]
            );
        }

        foreach ([
            ['Nadine', 'The preview matched my delivered hoodie exactly.', 'website/assets/img/other/testimonial-thumb1.png'],
            ['Karim', 'Great for testing gifts before ordering for a team.', 'website/assets/img/other/testimonial-thumb2.png'],
            ['Salma', 'Checkout was quick and the saved designs are handy.', 'website/assets/img/other/testimonial-thumb3.png'],
        ] as [$name, $text, $image]) {
            Review::updateOrCreate(
                ['name->en' => $name],
                ['name' => ['en' => $name, 'ar' => $name], 'text' => ['en' => $text, 'ar' => $text], 'image' => $image]
            );
        }
    }

    private function seedCheckoutData(): void
    {
        foreach ([
            ['Cairo Standard', 'القاهرة عادي', 50],
            ['Cairo Express', 'القاهرة سريع', 85],
            ['Alexandria', 'الاسكندرية', 70],
            ['Delta Cities', 'مدن الدلتا', 90],
        ] as [$nameEn, $nameAr, $cost]) {
            Delivery::updateOrCreate(
                ['name->en' => $nameEn],
                ['name' => ['en' => $nameEn, 'ar' => $nameAr], 'cost' => $cost]
            );
        }

        foreach ([
            ['ATHAR10', 'percentage', 10, 200],
            ['WELCOME50', 'fixed', 50, 100],
        ] as [$code, $type, $value, $maxUses]) {
            PromoCode::updateOrCreate(
                ['code' => $code],
                [
                    'discount_type' => $type,
                    'discount_value' => $value,
                    'max_uses' => $maxUses,
                    'uses_count' => 0,
                    'start_date' => now()->subDay(),
                    'end_date' => now()->addMonths(6),
                    'is_active' => true,
                ]
            );
        }
    }

    private function seedDesignAssets(): array
    {
        $templates = collect([
            ['Athar Classic Tee', '420', '320', '#FFFFFF', 'website/assets/img/product/product1.png'],
            ['Bold Hoodie Front', '520', '380', '#111111', 'website/assets/img/product/product5.png'],
            ['Gift Mug Wrap', '260', '520', '#FFFFFF', 'website/assets/img/product/product9.png'],
        ])->mapWithKeys(function (array $template) {
            [$name, $height, $width, $color, $image] = $template;

            return [$name => Desgin::updateOrCreate(
                ['name' => $name],
                compact('height', 'width', 'color', 'image')
            )];
        })->all();

        foreach ([
            ['Smile Badge', 'website/assets/img/icon/signature.png', 'emoji', 10, true],
            ['Star Mark', 'website/assets/img/icon/lamp.png', 'shapes', 20, true],
            ['Heart Patch', 'website/assets/img/icon/text-shape-icon.png', 'shapes', 30, true],
            ['Inactive Draft Sticker', 'website/assets/img/icon/bus.png', 'drafts', 40, false],
        ] as [$name, $image, $category, $sortOrder, $isActive]) {
            DesignSticker::updateOrCreate(
                ['name' => $name],
                ['image' => $image, 'category' => $category, 'sort_order' => $sortOrder, 'is_active' => $isActive]
            );
        }

        return $templates;
    }

    private function seedCustomerActivity(User $customer, array $catalog, array $templates): void
    {
        $product = $catalog['products'][0];
        $variant = $product->variants()->with(['color', 'size'])->first();
        $template = $templates['Athar Classic Tee'];

        $savedDesign = SavedDesign::updateOrCreate(
            ['user_id' => $customer->id, 'name' => 'Weekend Athar Tee'],
            [
                'product_id' => $product->id,
                'desgin_id' => $template->id,
                'preview_image' => 'website/assets/img/product/product1.png',
                'preview_image_url' => 'website/assets/img/product/product1.png',
                'design_data' => [
                    'canvas' => ['width' => 320, 'height' => 420],
                    'layers' => [
                        ['type' => 'text', 'text' => 'ATHAR', 'x' => 95, 'y' => 120, 'color' => '#111111'],
                        ['type' => 'sticker', 'name' => 'Star Mark', 'x' => 150, 'y' => 210],
                    ],
                ],
                'sticker_ids' => DesignSticker::where('is_active', true)->limit(2)->pluck('id')->all(),
            ]
        );

        CartItem::updateOrCreate(
            ['user_id' => $customer->id, 'name' => 'Cart Test Custom Tee'],
            [
                'product_id' => $product->id,
                'variant_id' => $variant->id,
                'design_id' => $savedDesign->id,
                'price' => $variant->price,
                'quantity' => 1,
                'color' => $variant->color->getTranslation('name', 'en'),
                'size' => $variant->size->name,
                'image_url' => $product->image,
                'preview_image_url' => 'website/assets/img/product/product1.png',
                'is_custom_design' => true,
                'design_data' => $savedDesign->design_data,
            ]
        );

        $order = Order::updateOrCreate(
            ['code' => 900001],
            [
                'user_id' => $customer->id,
                'user_name' => 'Mona Hassan',
                'phone' => '01000000002',
                'delivery' => 'Cairo Standard',
                'city' => 'Cairo',
                'address' => 'Nasr City, Demo Street 12',
                'shipping' => 50,
                'total' => 750,
                'status' => 'completed',
                'readed' => true,
                'note' => 'Seeded order for mobile order history testing.',
                'payment_method' => 'cashOnDelivery',
            ]
        );

        $order->products()->updateOrCreate(
            ['name' => 'Custom White T-Shirt'],
            [
                'product_id' => $product->id,
                'design_id' => $savedDesign->id,
                'color' => 'White',
                'size' => 'M',
                'quantity' => 2,
                'price' => 350,
                'image_url' => $product->image,
                'preview_image_url' => 'website/assets/img/product/product1.png',
                'is_custom_design' => true,
                'design_data' => $savedDesign->design_data,
                'total_price' => 700,
            ]
        );
    }

    private function categoryType(string $titleEn, string $titleAr, string $image): CategoryType
    {
        return CategoryType::updateOrCreate(
            ['title->en' => $titleEn],
            ['title' => ['en' => $titleEn, 'ar' => $titleAr], 'image' => $image, 'show' => 1]
        );
    }

    private function category(CategoryType $type, string $titleEn, string $titleAr, string $image): Category
    {
        return Category::updateOrCreate(
            ['category_type_id' => $type->id, 'title->en' => $titleEn],
            ['title' => ['en' => $titleEn, 'ar' => $titleAr], 'image' => $image]
        );
    }

    private function color(string $nameEn, string $nameAr, string $code): Color
    {
        return Color::updateOrCreate(
            ['code' => $code],
            ['name' => ['en' => $nameEn, 'ar' => $nameAr]]
        );
    }

    private function product(Category $category, string $nameEn, string $nameAr, string $image, bool $featured, array $variants): Product
    {
        $product = Product::updateOrCreate(
            ['name->en' => $nameEn],
            [
                'name' => ['en' => $nameEn, 'ar' => $nameAr],
                'description' => [
                    'en' => 'Demo item with enough variants and imagery to test browsing, filters, cart, checkout, and custom design flows.',
                    'ar' => 'منتج تجريبي يحتوي على الوان ومقاسات وصور لاختبار التصفح والفلاتر والسلة والدفع والتصميم المخصص.',
                ],
                'image' => $image,
                'category_id' => $category->id,
                'is_featured' => $featured ? 1 : 0,
            ]
        );

        foreach ($variants as [$color, $size, $quantity, $price, $oldPrice]) {
            Variant::updateOrCreate(
                ['product_id' => $product->id, 'color_id' => $color->id, 'size_id' => $size->id],
                ['quantity' => $quantity, 'price' => $price, 'old_price' => $oldPrice]
            );

            ProductImage::updateOrCreate(
                ['product_id' => $product->id, 'color_id' => $color->id, 'image' => $image],
                []
            );
        }

        return $product;
    }
}
