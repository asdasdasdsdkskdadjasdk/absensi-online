<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password; // <-- Tambahkan ini

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            
            // --- TAMBAHKAN ATURAN VALIDASI INI ---
            'current_password' => ['nullable', 'string', 'current_password'],
            'password' => ['nullable', 'string', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            // ------------------------------------
        ];
    }
} 