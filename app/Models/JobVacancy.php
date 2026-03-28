<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['company_id', 'title', 'description', 'location', 'salary','status','deadline'])]
class JobVacancy extends Model
{
    /** @use HasFactory<\Database\Factories\JobVacancyFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
           'status' => \App\Enums\JobVacancyStatus::class,
        ];
    }

    public function applicants(): BelongsToMany
    {
        return $this->belongsToMany(Applicant::class, 'applied_tables')
            ->withTimestamps();
    }
}
