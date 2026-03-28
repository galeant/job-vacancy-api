<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'profile_picture' => $this->profile_picture,
            'email' => $this->email,
            'phone' => $this->phone,
            'resume' => $this->resume,
            'cover_letter' => $this->cover_letter,
            'skills' => $this->skills,
            'experience' => $this->experience,
            'education' => $this->education,
            'certifications' => $this->certifications,
            'projects' => $this->projects,
            'languages' => $this->languages,
            'cv_file' => $this->cv_file,
        ];
    }
}
