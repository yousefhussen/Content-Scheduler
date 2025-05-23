<?php

namespace Modules\Post\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TogglePlatformRequest extends FormRequest
{
    public function rules(): array
    {
        return [

                'platform_id' => 'required|exists:platforms,id',
                'is_active' => 'required|boolean',

        ];
    }

    public function authorize(): bool
    {

        return true;
    }
}
