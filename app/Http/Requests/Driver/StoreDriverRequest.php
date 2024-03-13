<?php

namespace App\Http\Requests\Driver;

use App\Models\League;
use Illuminate\Foundation\Http\FormRequest;

class StoreDriverRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->league_id
            ? League::where('id', $this->league_id)
                ->where('user_id', auth()->user()->id)
                ->exists()
            : true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'league_id' => 'required|exists:leagues,id',
            'nickname' => 'required|unique:drivers|max:255',
            'name' => 'string|max:255'
        ];
    }
}
