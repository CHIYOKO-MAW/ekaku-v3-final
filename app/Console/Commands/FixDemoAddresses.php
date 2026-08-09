<?php

namespace App\Console\Commands;

use App\Models\Biodata;
use App\Models\User;
use Illuminate\Console\Command;

class FixDemoAddresses extends Command
{
    protected $signature = 'demo:fix-addresses';
    protected $description = 'Fix demo user addresses to match Kec. format for dashboard grouping';

    public function handle(): int
    {
        $districts = $this->loadDistricts();
        if (empty($districts)) {
            $this->error('No districts found in pandeglang_wilayah.json');

            return 1;
        }

        $users = User::where('role', 'user')
            ->where('email', 'like', 'demo.user.%@ekaku.demo')
            ->with('biodata')
            ->get();

        $this->info("Found {$users->count()} demo users");

        $fixed = 0;
        $alreadyOk = 0;

        foreach ($users as $user) {
            $biodata = $user->biodata;
            if (! $biodata) {
                $this->line("  [SKIP] {$user->email} - no biodata");

                continue;
            }

            $alamat = strtolower((string) $biodata->alamat);
            $hasKec = false;
            foreach ($districts as $d) {
                if (str_contains($alamat, 'kec. ' . strtolower($d['name']))) {
                    $hasKec = true;
                    break;
                }
            }

            if ($hasKec) {
                $alreadyOk++;

                continue;
            }

            $district = $districts[array_rand($districts)];
            $village = $district['villages'][array_rand($district['villages'])] ?? ['name' => 'Tidak diketahui'];

            $faker = fake('id_ID');
            $newAddress = sprintf(
                'Kp. %s, Desa/Kel. %s, Kec. %s, Kab. Pandeglang, Banten',
                $faker->streetName(),
                $village['name'],
                $district['name']
            );

            $biodata->update([
                'alamat' => $newAddress,
                'kecamatan' => $district['name'],
                'desa_kelurahan' => $village['name'],
            ]);

            $fixed++;
            $this->line("  [FIXED] {$user->email} → {$district['name']}");
        }

        $this->newLine();
        $this->info("Done: {$fixed} fixed, {$alreadyOk} already ok");

        return 0;
    }

    private function loadDistricts(): array
    {
        $path = database_path('data/pandeglang_wilayah.json');
        if (is_file($path)) {
            $payload = json_decode((string) file_get_contents($path), true);
            if (is_array($payload) && isset($payload['districts']) && is_array($payload['districts']) && count($payload['districts']) > 0) {
                return $payload['districts'];
            }
        }

        return [];
    }
}
