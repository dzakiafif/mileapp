<?php

namespace App\Http\Requests;

use App\Libraries\ResponseBase;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StorePackageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'transaction' => 'required|array',
            'transaction.customer_name' => 'required',
            'transaction.amount' => 'required|numeric',
            'transaction.discount' => 'required|numeric',
            'transaction.customer_attribute' => 'required|array',
            'transaction.additional_field' => 'nullable',
            'transaction.customer_attribute.nama_sales' => 'required|string',
            'transaction.customer_attribute.top' => 'required|string',
            'transaction.customer_attribute.jenis_pelanggan'=>'required|string|min:3|max:3|alpha_num',
            'transaction.origin_data' => 'required|array',
            'transaction.origin_data.customer_name' => 'required|string',
            'transaction.origin_data.customer_address' => 'required|string',
            'transaction.origin_data.customer_email' => 'nullable|email',
            'transaction.origin_data.customer_phone' => 'required|regex:/^(?=024)(?=.{10,12}$)[0-9]+(?:[\s-]?[0-9]+)*$/i',
            'transaction.origin_data.customer_address_detail' => 'nullable|string',
            'transaction.origin_data.customer_zip_code' => 'required|numeric',
            'transaction.origin_data.customer_zone_code' => 'required|regex:/^[A-Z]{3,5}+$/',
            'transaction.destination_data' => 'required|array',
            'transaction.destination_data.customer_name' => 'required|string',
            'transaction.destination_data.customer_address' => 'required|string',
            'transaction.destination_data.customer_email' => 'nullable|email',
            'transaction.destination_data.customer_phone' => 'required|regex:/^(?=024)(?=.{10,12}$)[0-9]+(?:[\s-]?[0-9]+)*$/i',
            'transaction.destination_data.customer_address_detail' => 'nullable|string',
            'transaction.destination_data.customer_zip_code' => 'required|numeric',
            'transaction.destination_data.customer_zone_code' => 'required|regex:/^[A-Z]{3,5}+$/',
            'transaction.custom_field' => 'required|array',
            'transaction.custom_field.catatan_tambahan' => 'required|string',
            'connote' => 'required|array',
            'connote.actual_weight' => 'required|integer',
            'connote.volume_weight' => 'required|integer',
            'connote.chargeable_weight' => 'required|integer',
            'connote.location_name' => 'required|exists:location,name',
            'koli_data' => 'required|array',
            'koli_data.*.koli_length' => 'required|integer',
            'koli_data.*.koli_chargeable_weight' => 'required|integer',
            'koli_data.*.koli_height' => 'required|integer',
            'koli_data.*.koli_description' => 'required|string',
            'koli_data.*.koli_volume' => 'required|integer',
            'koli_data.*.koli_weight' => 'required|integer',
            'koli_data.*.koli_custom_field' => 'required|array',
            'koli_data.*.koli_custom_field.awb_sicepat' => 'nullable',
            'koli_data.*.koli_custom_field.harga_barang' => 'nullable' 
        ];
    }

    public function messages()
    {
        return [
            'transaction.origin_data.customer_phone.regex' => 'The transaction.origin data.customer phone format must be start 024 and following min number 10 and max number 12',
            'transaction.origin_data.zone_code.regex' => 'The transaction.origin data.zone code must be only uppercase and following number length between 2 untill 5',
            'connote.location_name.exists' => 'the selected connection.location name does not exist in database'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(ResponseBase::error(422, $validator->errors()));
    }
}
