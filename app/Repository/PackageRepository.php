<?php

namespace App\Repository;

use App\Interfaces\PackageInterfaces;
use App\Models\Connote;
use App\Models\Koli;
use App\Models\Transaction;
use Illuminate\Support\Str;

class PackageRepository implements PackageInterfaces
{
    public function createPackage($request)
    {
        $transaction = new Transaction();
        $transaction->transaction_id = (string) Str::uuid();
        $transaction->customer_name = $request->transaction['customer_name'];
        $transaction->customer_code = rand(pow(10, 7 - 1), pow(10, 7) - 1);
        $transaction->amount = $request->transaction['amount'];
        $transaction->discount = $request->transaction['discount'];
        $transaction->additional_field = null;

        if (!empty($request->transaction['additional_field'])) {
            $transaction->additional_field = $request->transaction['additional_field'];
        }

        $transaction->payment_type = "29";
        $transaction->state = "PAID";
        $code = rand(pow(10, 3 - 1), pow(10, 3) - 1);
        $transaction->code = $request->transaction['origin_data']['customer_zone_code'] . date('Ymd') . $code;
        $transaction->order = $code;
        $transaction->location_id = "5cecb20b6c49615b174c3e74";
        $transaction->organization_id = 6;
        $transaction->payment_type_name = "Invoice";
        $transaction->cash_amount = 0;

        if ($request->transaction['cash_amount'] != 0) {
            $transaction->cash_amount = $request->transaction['cash_amount'];
        }

        $transaction->cash_charge = 0;

        if ($request->transaction['cash_charge'] != 0) {
            $transaction->cash_charge = $request->transaction['cash_charge'];
        }

        $transaction->customer_attribute = $request->transaction['customer_attribute'];
        $transaction->custom_field = $request->transaction['custom_field'];
        $transaction->origin_data = $request->transaction['origin_data'];
        $transaction->destination_data = $request->transaction['destination_data'];
        $transaction->save();

        $connote = new Connote();
        $connote->connote_id = (string) Str::uuid();
        $connote->number = 1;
        $connote->service = "ECO";
        $connote->service_price = $request->transaction['amount'];
        $connote->amount = $request->transaction['amount'];
        $connote->code = "AWB" . rand(pow(10, 14 - 1), pow(10, 14) - 1);
        $connote->order = rand(pow(10, 6 - 1), pow(10, 6) - 1);
        $connote->state = "PAID";
        $connote->state_id = 2;
        $connote->zone_code_from = $request->transaction['origin_data']['customer_zone_code'];
        $connote->zone_code_to = $request->transaction['destination_data']['customer_zone_code'];
        $connote->surcharge_amount = null;
        $connote->transaction_id = $transaction->transaction_id;
        $connote->actual_weight = $request->connote['actual_weight'];
        $connote->volume_weight = $request->connote['volume_weight'];
        $connote->chargeable_weight = $request->connote['chargeable_weight'];
        $connote->organization_id = 6;
        $connote->surcharge_amount = "0";
        $connote->sla_day = "4";
        $connote->location_name = $request->connote['location_name'];
        $connote->location_type = "HUB";
        $connote->source_tariff_db = "tariff_customers";
        $connote->id_source_tariff = "1576868";
        $connote->pod = null;
        $connote->history = [];
        $connote->save();

        $updateTransaction = Transaction::find($transaction->_id);
        $updateTransaction->connote_id = $connote->connote_id;
        $updateTransaction->save();

        $koliData = $request->koli_data;

        foreach ($koliData as $key => $value) {
            $koliData[$key]['koli_code'] = $connote->code . '.' . ($key + 1);
            $koliData[$key]['koli_id'] = (string) Str::uuid();
            $koliData[$key]['awb_url'] = "https:\/\/tracking.mile.app\/label\/" . $connote->code . '.' . ($key + 1);
            $koliData[$key]['koli_formula_id'] = null;
            $koliData[$key]['connote_id'] = $connote->connote_id;
        }

        Koli::insert($koliData);

        return $transaction->load('connote.koli');
    }

    public function updatePackage($request, $id)
    {
        $response = ['status' => true, 'message' => null];
        $transaction = Transaction::find($id);
        if (!$transaction) {
            $response['status'] = false;
            $response['message'] = "data package with ${id} not found";
            return $response;
        }

        $transaction->amount = $request->transaction['amount'] ?? $transaction->amount;
        $transaction->discount = $request->transaction['discount'] ?? $transaction->discount;
        $transaction->cash_amount = $request->transaction['cash_amount'] ?? $transaction->cash_amount;
        $transaction->cash_charge = $request->transaction['cash_charge'] ?? $transaction->cash_charge;

        $transaction->customer_attribute = $request->transaction['customer_attribute'] ?? $transaction->customer_attribute;
        $transaction->custom_field = $request->transaction['custom_field'] ?? $transaction->custom_field;
        $transaction->origin_data = $request->transaction['origin_data'] ?? $transaction->origin_data;
        $transaction->destination_data = $request->transaction['destination_data'] ?? $transaction->destination_data;
        $transaction->save();
        $connote = Connote::where('transaction_id', $transaction->transaction_id)->first();
        if ($connote) {
            if ($request->connote) {
                $connote->service_price = $request->transaction['amount'] ?? $connote->service_price;
                $connote->amount = $request->transaction['amount'] ?? $connote->amount;
                $connote->zone_code_from = $request->transaction['origin_data']['customer_zone_code'] ?? $connote->zone_code_from;
                $connote->zone_code_to = $request->transaction['destination_data']['customer_zone_code'] ?? $connote->zone_code_to;
                $connote->actual_weight = $request->connote['actual_weight'] ?? $connote->actual_weight;
                $connote->volume_weight = $request->connote['volume_weight'] ?? $connote->volume_weight;
                $connote->chargeable_weight = $request->connote['chargeable_weight'] ?? $connote->chargeable_weight;
                $connote->location_name = $request->connote['location_name'] ?? $connote->location_name;
                $connote->save();
            }

            if ($request->koli_data) {
                $koliData = collect($request->koli_data)->pluck('koli_kode');
                $koli = Koli::whereIn('koli_code', $koliData)->get();
                if ($koli->isNotEmpty()) {
                    foreach ($koli as $key => $value) {
                        if ($koli[$key]['koli_code'] == (isset($request->koli_data[$key]) ? $request->koli_data[$key]['koli_kode'] : null)) {
                            $value->koli_length = $request->koli_data[$key]['koli_length'] ?? $value->koli_length;
                            $value->koli_chargeable_weight = $request->koli_data[$key]['koli_chargeable_weight'] ?? $value->koli_chargeable_weight;
                            $value->koli_height = $request->koli_data[$key]['koli_height'] ?? $value->koli_height;
                            $value->koli_description = $request->koli_data[$key]['koli_description'] ?? $value->koli_description;
                            $value->koli_volume = $request->koli_data[$key]['koli_volume'] ?? $value->koli_volume;
                            $value->koli_weight = $request->koli_data[$key]['koli_weight'] ?? $value->koli_weight;
                            $value->koli_custom_field = $request->koli_data[$key]['koli_custom_field'] ?? $$value->koli_custom_field;
                            $value->save();
                        }
                    }
                }
            }
        }

        $response['data'] = $transaction->fresh();
        return $response;
    }

    public function deletePackage($id)
    {
        $response = ['status' => true, 'message' => null];
        $transaction = Transaction::find($id);
        if (!$transaction) {
            $response['status'] = false;
            $response['message'] = "data package with ${id} not found";
            return $response;
        }
        $connote = Connote::where('transaction_id', $transaction->transaction_id)->first();

        $connote->koli()->delete();
        $connote->delete();
        $transaction->delete();

        return $response;
    }

    public function getAllPackage($limit, $order, $direction)
    {
        return Transaction::orderBy($order, $direction)->paginate($limit)->toArray();
    }

    public function showPackage($id)
    {
        $response = ['status' => true, 'message' => null];

        $package = Transaction::with('connote.koli')->find($id);

        if (!$package) {
            $response['status'] = false;
            $response['message'] = "data package with ${id} not found";
            return $response;
        }

        $response['data'] = $package;

        return $response;
    }
}
