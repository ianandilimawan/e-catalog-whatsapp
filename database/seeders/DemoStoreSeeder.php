<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class DemoStoreSeeder extends Seeder
{
    public function run(): void
    {
        // ─────────────────────────────────────────────
        // 1. USER
        // ─────────────────────────────────────────────
        // Assign ke admin@redtech.co.id (User ID 1)
        $userId = 1;
        $user = User::find($userId);

        // Pastikan role admin-toko terpasang
        if ($user) {
            $user->assignRole('admin-toko');
        }


        // ─────────────────────────────────────────────
        // 2. STORE
        // ─────────────────────────────────────────────
        $storeId = DB::table('stores')->insertGetId([
            'user_id'         => $userId,
            'name'            => 'Lumière Skincare',
            'slug'            => 'lumiere-skincare',
            'wa_number'       => '6281234567890',
            'theme_color'     => '#f43f5e', // Rose 500 (elegant pink/red for skincare)
            'welcome_message' => 'Selamat datang di Lumière! Skincare premium untuk kulit sehat & glowing ✨',
            'logo'            => 'https://images.unsplash.com/photo-1599305090598-fe179d501227?w=200&q=80&fit=crop', // Elegant logo-like image
            'banner'          => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=1200&q=80&fit=crop', // Verified working skincare banner
            'button_rounded'  => true,
            'dark_mode'       => false,
            'wa_checkout_clicks' => 127,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // ─────────────────────────────────────────────
        // 3. CATEGORIES
        // ─────────────────────────────────────────────
        $categories = [
            ['name' => 'Pembersih',      'slug' => 'pembersih'],
            ['name' => 'Toner & Essence','slug' => 'toner-essence'],
            ['name' => 'Serum',          'slug' => 'serum'],
            ['name' => 'Pelembap',       'slug' => 'pelembap'],
            ['name' => 'Sunscreen',      'slug' => 'sunscreen'],
        ];

        $catIds = [];
        foreach ($categories as $cat) {
            $catIds[$cat['slug']] = DB::table('categories')->insertGetId([
                'store_id'   => $storeId,
                'name'       => $cat['name'],
                'slug'       => $cat['slug'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ─────────────────────────────────────────────
        // 4. PRODUCTS (real Unsplash images)
        // views_count > 100 = badge TERLARIS
        // ─────────────────────────────────────────────
        $products = [
            // ── Pembersih ─────────────────────────────
            [
                'category' => 'pembersih',
                'name'     => 'Gentle Foam Cleanser',
                'slug'     => 'gentle-foam-cleanser',
                'desc'     => 'Pembersih wajah berbusa lembut yang mengangkat kotoran & makeup tanpa membuat kulit terasa kering. Cocok untuk semua jenis kulit, termasuk kulit sensitif.',
                'price'    => 89000,
                'views'    => 215,
                'images'   => [
                    'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=600&q=80&fit=crop',
                    'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?w=600&q=80&fit=crop'
                ],
            ],
            [
                'category' => 'pembersih',
                'name'     => 'Oil Cleansing Balm',
                'slug'     => 'oil-cleansing-balm',
                'desc'     => 'Cleansing balm berbasis minyak yang efektif melarutkan sunscreen, makeup tebal, dan sebum berlebih. Tekstur buttery yang meleleh di kulit.',
                'price'    => 145000,
                'views'    => 88,
                'images'   => [
                    'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=600&q=80&fit=crop',
                    'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=600&q=80&fit=crop'
                ],
            ],

            // ── Toner & Essence ───────────────────────
            [
                'category' => 'toner-essence',
                'name'     => '🔥 TERLARIS - Hydrating Toner AHA/BHA',
                'slug'     => 'hydrating-toner-aha-bha',
                'desc'     => 'Toner eksfoliasi dengan kandungan AHA/BHA yang membantu mengangkat sel kulit mati, menyamarkan pori, dan mencerahkan kulit secara bertahap.',
                'price'    => 119000,
                'views'    => 302,
                'images'   => [
                    'https://images.unsplash.com/photo-1601049676869-702ea24cfd58?w=600&q=80&fit=crop',
                    'https://images.unsplash.com/photo-1629198688000-71f23e745b6e?w=600&q=80&fit=crop'
                ],
            ],
            [
                'category' => 'toner-essence',
                'name'     => '🔥 TERLARIS - Rice Water Essence',
                'slug'     => 'rice-water-essence',
                'desc'     => 'Essence berbahan dasar air beras fermentasi yang kaya niacinamide & ceramide. Memperkuat skin barrier, mencerahkan, dan melembapkan.',
                'price'    => 135000,
                'views'    => 167,
                'images'   => [
                    'https://images.unsplash.com/photo-1629198688000-71f23e745b6e?w=600&q=80&fit=crop', // Verified working image
                    'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?w=600&q=80&fit=crop',
                    'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=600&q=80&fit=crop'
                ],
            ],

            // ── Serum ─────────────────────────────────
            [
                'category' => 'serum',
                'name'     => '🔥 TERLARIS - Vitamin C Brightening Serum',
                'slug'     => 'vitamin-c-brightening-serum',
                'desc'     => 'Serum Vitamin C 15% yang mencerahkan, antioksidan kuat, dan membantu menyamarkan bekas jerawat. Formula stabil dengan Vitamin E & Ferulic Acid.',
                'price'    => 189000,
                'views'    => 451,
                'images'   => [
                    'https://images.unsplash.com/photo-1608248543803-ba4f8c70ae0b?w=600&q=80&fit=crop',
                    'https://images.unsplash.com/photo-1629198688000-71f23e745b6e?w=600&q=80&fit=crop'
                ],
            ],
            [
                'category' => 'serum',
                'name'     => '🔥 TERLARIS - Niacinamide 10% + Zinc Serum',
                'slug'     => 'niacinamide-serum',
                'desc'     => 'Serum niacinamide konsentrasi tinggi untuk mengurangi tampilan pori, mengontrol produksi sebum, dan mencerahkan kulit kusam.',
                'price'    => 129000,
                'views'    => 389,
                'images'   => [
                    'https://images.unsplash.com/photo-1512290923902-8a9f81dc236c?w=600&q=80&fit=crop',
                    'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?w=600&q=80&fit=crop'
                ],
            ],
            [
                'category' => 'serum',
                'name'     => '🔥 TERLARIS - Retinol 0.5% Night Serum',
                'slug'     => 'retinol-night-serum',
                'desc'     => 'Serum retinol untuk pemakaian malam yang membantu mempercepat regenerasi sel kulit, mengurangi garis halus, dan menyamarkan hiperpigmentasi.',
                'price'    => 229000,
                'views'    => 178,
                'images'   => [
                    'https://images.unsplash.com/photo-1556760544-74068565f05c?w=600&q=80&fit=crop',
                    'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=600&q=80&fit=crop'
                ],
            ],

            // ── Pelembap ──────────────────────────────
            [
                'category' => 'pelembap',
                'name'     => '🔥 TERLARIS - Ceramide Barrier Cream',
                'slug'     => 'ceramide-barrier-cream',
                'desc'     => 'Moisturizer kaya ceramide & hyaluronic acid yang memperkuat skin barrier, mengurangi kemerahan, dan memberikan kelembapan intensif 72 jam.',
                'price'    => 165000,
                'views'    => 523,
                'images'   => [
                    'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?w=600&q=80&fit=crop',
                    'https://images.unsplash.com/photo-1608248543803-ba4f8c70ae0b?w=600&q=80&fit=crop'
                ],
            ],
            [
                'category' => 'pelembap',
                'name'     => 'Lightweight Gel Moisturizer',
                'slug'     => 'gel-moisturizer',
                'desc'     => 'Pelembap gel ringan yang cocok untuk kulit berminyak dan kombinasi. Memberikan hidrasi tanpa rasa berminyak, mengandung green tea extract & aloe vera.',
                'price'    => 115000,
                'views'    => 94,
                'images'   => [
                    'https://images.unsplash.com/photo-1585421514738-01798e348b17?w=600&q=80&fit=crop',
                    'https://images.unsplash.com/photo-1512290923902-8a9f81dc236c?w=600&q=80&fit=crop'
                ],
            ],

            // ── Sunscreen ─────────────────────────────
            [
                'category' => 'sunscreen',
                'name'     => '🔥 TERLARIS - SPF 50+ Invisible Sunscreen',
                'slug'     => 'spf50-invisible-sunscreen',
                'desc'     => 'Sunscreen SPF 50+ PA++++ dengan tekstur ringan & transparan. Tidak meninggalkan whitecast, cocok untuk semua skintone. Perlindungan UVA & UVB.',
                'price'    => 159000,
                'views'    => 612,
                'images'   => [
                    'https://images.unsplash.com/photo-1521590832167-7bcbfaa6381f?w=600&q=80&fit=crop',
                    'https://images.unsplash.com/photo-1629198688000-71f23e745b6e?w=600&q=80&fit=crop'
                ],
            ],
            [
                'category' => 'sunscreen',
                'name'     => '🔥 TERLARIS - Sunscreen Stick SPF 45',
                'slug'     => 'sunscreen-stick-spf45',
                'desc'     => 'Sunscreen praktis dalam bentuk stick yang mudah dibawa dan diaplikasikan ulang kapan saja. Formula tahan air dengan kandungan niacinamide untuk mencerahkan.',
                'price'    => 125000,
                'views'    => 143,
                'images'   => [
                    'https://images.unsplash.com/photo-1556227834-09f1de7a7d14?w=600&q=80&fit=crop',
                    'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?w=600&q=80&fit=crop'
                ],
            ],
            [
                'category' => 'sunscreen',
                'name'     => '🔥 TERLARIS - Tone-up Sunscreen SPF 50',
                'slug'     => 'tone-up-sunscreen',
                'desc'     => 'Sunscreen multifungsi yang memberikan efek tone-up natural sekaligus perlindungan SPF 50. Cocok dipakai sebagai base makeup atau pemakaian sehari-hari.',
                'price'    => 149000,
                'views'    => 287,
                'images'   => [
                    'https://images.unsplash.com/photo-1590439471364-192aa70c0b53?w=600&q=80&fit=crop',
                    'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=600&q=80&fit=crop'
                ],
            ],
        ];

        foreach ($products as $p) {
            $productId = DB::table('products')->insertGetId([
                'store_id'    => $storeId,
                'category_id' => $catIds[$p['category']],
                'name'        => $p['name'],
                'slug'        => $p['slug'],
                'description' => $p['desc'],
                'price'       => $p['price'],
                'image'       => $p['images'][0], // Legacy main image
                'views_count' => $p['views'],
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            foreach ($p['images'] as $imgUrl) {
                DB::table('product_images')->insert([
                    'product_id' => $productId,
                    'image_path' => $imgUrl,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('✅ Demo store "Lumière Skincare" berhasil di-assign ke admin@redtech.co.id');
        $this->command->info('   🌐 Katalog : /lumiere-skincare');
        $this->command->info('   🛍️  Produk  : ' . count($products) . ' produk (di-inject "🔥 TERLARIS" pada nama)');
    }
}
