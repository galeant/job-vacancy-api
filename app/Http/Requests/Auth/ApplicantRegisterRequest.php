<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ApplicantRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:applicants,email'],
            'password' => ['required', 'string'],
            'profile_picture' => ['nullable', 'file', 'mimes:jpg,jpeg,png'],

            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20','unique:applicants,phone'],
            'resume' => ['nullable', 'string'],
            'cover_letter' => ['nullable', 'string'],
            'skills' => ['nullable','string'],
            'experience' => ['nullable', 'string'],
            'education' => ['nullable', 'string'],
            'certifications' => ['nullable', 'string'],
            'projects' => ['nullable', 'string'],
            'languages' => ['nullable', 'string'],
            'cv_file' => ['nullable', 'file', 'mimes:pdf,doc,docx'],
        ];
    }
}
