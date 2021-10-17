<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CampaignUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:256',
            'from' => 'required_unless:to,null|date_format:Y-m-d|after:today', // assuming campaigns can start at least in +1day
            'to' => 'required_unless:from,null|date_format:Y-m-d|after:from',
            'daily_budget' => 'nullable|numeric|min:1',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:png,jpeg,jpg',
            'imagesToRemove' => 'nullable|array',
            'imagesToRemove.*' => 'numeric'
        ];
    }
}
