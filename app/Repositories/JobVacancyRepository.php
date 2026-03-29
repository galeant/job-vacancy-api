<?php

namespace App\Repositories;

use App\Models\JobVacancy;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Enums\JobVacancyStatus;
use App\Models\Applicant;

class JobVacancyRepository
{
    public function search($filters = [],): Collection|LengthAwarePaginato
    {
        $query = JobVacancy::query();

        if(isset($filters['applicant_id'])){
            $query->withExists(['applicants as is_applied' => function($query)use($filters) {
                $query->where('applicant_id', $filters['applicant_id']);
            }]);
        }

        if (isset($filters['deadline'])) {
            $query->whereDate('deadline', '>=', $filters['deadline']);
        }

        if (isset($filters['status'])) {
            $query->whereIn('status', $filters['status']);
        }

        if (isset($filters['company_id'])) {
            $query->where('company_id', $filters['company_id']);
        }

        if (isset($filters['search'])) {
            $query->where('title', 'like', '%' . $filters['search'] . '%');
        }

        $query->orderBy($filters['sort_by'] ?? 'id', $filters['sort_order'] ?? 'asc');

        return (isset($filters['per_page']))
            ? $query->paginate($filters['per_page'])->withQueryString()
            : $query->get();
    }

    public function findById(int $id, int $applicantId = null): ?JobVacancy
    {
        return JobVacancy::where('id',$id)
            ->when($applicantId, fn($q) => $q->with(['applicants' => fn($q1) =>  $q1->where('applicant_id',$applicantId)]))
            ->first();
    }

    public function create(array $data): JobVacancy
    {
        return JobVacancy::create($data);
    }

    public function update(int $id, array $data): ?JobVacancy
    {
        $row = JobVacancy::find($id);
        if ($row) {
            $row->update($data);
        }
        return $row->refresh();
    }

    public function delete(int $id): bool
    {
        $row = JobVacancy::find($id);
        if ($row) {
            return $row->delete();
        }
        return false;
    }

    public function apply(Applicant $applicant, $vacancyId): void
    {
        $assign = [
            $vacancyId => [
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        $applicant->vacancies()->sync($assign);
    }
}
