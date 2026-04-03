<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $namaProduk = [
            'Nasi Goreng Instan',
            'Mie Instan Goreng',
            'Mie Instan Soto',
            'Keripik Kentang',
            'Keripik Singkong',
            'Biskuit Coklat',
            'Biskuit Keju',
            'Roti Tawar',
            'Roti Coklat',
            'Roti Keju',
            'Susu UHT Coklat',
            'Susu UHT Strawberry',
            'Susu UHT Vanilla',
            'Air Mineral Botol',
            'Air Mineral Gelas',
            'Teh Botol',
            'Teh Kotak',
            'Kopi Sachet',
            'Kopi Susu Instan',
            'Minuman Energi',
            'Minuman Isotonik',
            'Jus Mangga',
            'Jus Jeruk',
            'Soda Lemon',
            'Soda Cola'
        ];

        $satuan = ['pcs', 'botol', 'kotak', 'pack'];

        $produk = [];

        foreach ($namaProduk as $i => $nama) {

            $kategori = str_contains(strtolower($nama), 'air') ||
                str_contains(strtolower($nama), 'teh') ||
                str_contains(strtolower($nama), 'kopi') ||
                str_contains(strtolower($nama), 'jus') ||
                str_contains(strtolower($nama), 'soda') ||
                str_contains(strtolower($nama), 'susu') ||
                str_contains(strtolower($nama), 'minuman')
                ? 'Minuman'
                : 'Makanan';

            $produk[] = [
                'kode_produk' => 'PRD-' . str_pad($i + 1, 5, '0', STR_PAD_LEFT),
                'nama_produk' => $nama,
                'foto_produk' => null,
                'satuan' => $satuan[array_rand($satuan)],
                'kategori' => $kategori,
                'harga' => rand(5000, 25000),
                'stok' => rand(20, 150),
                'keterangan' => 'Produk makanan/minuman',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('data_produks')->insert($produk);
    }
}
