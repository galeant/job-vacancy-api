<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobVacancyResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
            'salary' => $this->salary,
            'status' => $this->status,
            'status_text' => $this->status->getLabel(),
            'deadline' => $this->deadline,
            'company' => new CompanyResource($this->whenLoaded('company')),
            'is_applied' => (bool) $this->is_applied,
        ];
    }
}
