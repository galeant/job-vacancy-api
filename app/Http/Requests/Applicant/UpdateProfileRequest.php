<?php

namespace App\Http\Requests\Applicant;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'profile_picture' => ['nullable', 'file', 'mimes:jpg,jpeg,png'],

            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
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
