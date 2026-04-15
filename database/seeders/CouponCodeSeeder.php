<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;
use App\Models\CouponCode;

class CouponCodeSeeder extends Seeder
{
    public function run(): void
    {
        $coupons = Coupon::all();

        if ($coupons->isEmpty()) {
            $this->command->error('Aucun coupon trouvé. Lancez CouponSeeder d\'abord.');
            return;
        }

        $bookmakers = ['1xbet', 'betwinner', 'melbet', '1win'];

        foreach ($coupons as $coupon) {
            // Chaque coupon a 2 à 4 bookmakers aléatoires
            $selection = collect($bookmakers)->shuffle()->take(rand(2, 4));

            foreach ($selection as $bookmaker) {
                CouponCode::create([
                    'coupon_id' => $coupon->id,
                    'bookmaker' => $bookmaker,
                    'code'      => strtoupper($bookmaker[0]) . rand(10000, 99999),
                ]);
            }
        }

        $this->command->info('Codes bookmakers créés pour ' . $coupons->count() . ' coupons.');
    }
}