<?php

namespace App\Repositories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CompanyRepository
{
    public function search($filters = [], ?int $perPage = null): Collection|LengthAwarePaginato
    {
        $query = Company::query();

        if (isset($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        $query->orderBy($filters['sort_by'] ?? 'id', $filters['sort_order'] ?? 'asc');

        return ($perPage)
            ? $query->paginate($perPage)->withQueryString()
            : $query->get();
    }

    public function create(array $data): Company
    {
        return Company::create($data);
    }

    public function update(int $id, array $data): ?Company
    {
        $row = Company::find($id);
        if ($row) {
            $row->update($data);
        }
        return $row->refresh();
    }

    public function delete(int $id): bool
    {
        $row = Company::find($id);
        if ($row) {
            return $row->delete();
        }
        return false;
    }
}
