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
            'phone' => ['required', 'string', 'max:20'],
            'resume' => ['nullable', 'file', 'mimes:pdf,doc,docx'],
            'cover_letter' => ['nullable', 'file', 'mimes:pdf,doc,docx'],
            'skills' => ['nullable', 'json'],
            'experience' => ['nullable', 'json'],
            'education' => ['nullable', 'json'],
            'certifications' => ['nullable', 'json'],
            'projects' => ['nullable', 'json'],
            'languages' => ['nullable', 'json'],
            'cv_file' => ['nullable', 'file', 'mimes:pdf,doc,docx'],
        ];
    }
}
