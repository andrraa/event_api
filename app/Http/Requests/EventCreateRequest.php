<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class EventCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'event_id' => ['required', 'integer', 'exists:tbl_master_events,id'],
            'province_id' => ['required', 'integer', 'exists:tbl_provinces,id'],
            'location' => ['required', 'string', 'max:100'],
            'category_id' => ['required', 'integer', 'exists:tbl_categories,id'],
            'description' => ['string'],
            'information' => ['string'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'start_date' => ['required', 'date_format:Y-m-d H:i:s', 'before:end_date'],
            'end_date' => ['required', 'date_format:Y-m-d H:i:s', 'after:start_date']
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'code' => 400,
            'message' => 'VALIDATION_FAILED',
            'errors' => $validator->errors()
        ], 400));
    }
}
