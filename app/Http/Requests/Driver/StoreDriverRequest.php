<?php

namespace App\Http\Requests\Driver;

use App\Models\League;
use App\Rules\LeagueBelongsToUser;
use Closure;
use Illuminate\Foundation\Http\FormRequest;

class StoreDriverRequest extends FormRequest
{
    /**
     * Authorize only if user is creating a driver to his own league.
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
            'league_id' => ['required', 'exists:leagues,id'],
            'nickname' => ['required', 'unique:drivers', 'max:255'],
            'name' => ['string', 'max:255']
        ];
    }
}
