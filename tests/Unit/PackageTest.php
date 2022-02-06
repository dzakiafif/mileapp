<?php

namespace Tests\Unit;

use App\Models\Transaction;
use Tests\TestCase;

class PackageTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_store_package()
    {
        $data = [
            "transaction" => [
                "customer_name"=> "PT. AMARA PRIMATIGA",
                "amount"=> "70700",
                "discount"=> "0",
                "cash_amount"=> 0,
                "cash_charge"=> 0,
                "additional_field"=> "",
                "customer_attribute"=> [
                    "nama_sales"=> "Radit Fitrawikarsa",
                    "top"=>"14 Hari",
                    "jenis_pelanggan"=>"B2B"
                ],
                "origin_data"=> [
                    "customer_name"=> "PT. NARA OKA PRAKARSA",
                    "customer_address"=> "JL. KH. AHMAD DAHLAN NO. 100, SEMARANG TENGAH 12420",
                    "customer_email"=> "info@naraoka.co.id",
                    "customer_phone"=> "024-1234567",
                    "customer_address_detail" =>null,
                    "customer_zip_code"=>"12420",
                    "customer_zone_code"=> "CGKFT"
                ],
                "destination_data"=> [
                    "customer_name"=> "PT AMARIS HOTEL SIMPANG LIMA",
                    "customer_address"=> "JL. KH. AHMAD DAHLAN NO. 01, SEMARANG TENGAH",
                    "customer_email"=> null,
                    "customer_phone"=> "0248453499",
                    "customer_address_detail" =>"KOTA SEMARANG SEMARANG TENGAH KARANGKIDUL",
                    "customer_zip_code"=>"50241",
                    "customer_zone_code"=> "SMG"
                ],
                "custom_field"=> [
                    "catatan_tambahan"=> "JANGAN DI BANTING \/ DI TINDIH"
                ]
            ],
            "connote"=> [
                "actual_weight"=> 20,
                "volume_weight"=> 20,
                "chargeable_weight"=> 20,
                "location_name"=> "Hub Jakarta Selatan"
            ],
            "koli_data"=> [
                [
                    "koli_length"=> 0,
                    "koli_chargeable_weight"=> 9,
                    "koli_height"=>0,
                    "koli_description"=>"V WARP",
                    "koli_volume"=> 0,
                    "koli_weight"=> 9,
                    "koli_custom_field"=> [
                        "awb_sicepat"=> null,
                        "harga_barang"=> null
                    ]
                ]
            ]
        ];

        $response = $this->post('/api/package', $data);
        $response->assertStatus(200);
    }

    public function test_get_all_package()
    {
        $response = $this->get('/api/package');
        $response->assertStatus(200);
    }

}
