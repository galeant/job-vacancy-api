<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository
{
    public function search($filters = [], ?int $perPage = null): Collection|LengthAwarePaginato
    {
        $query = User::query();

        if (isset($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        $query->orderBy($filters['sort_by'] ?? 'id', $filters['sort_order'] ?? 'asc');

        return ($perPage)
            ? $query->paginate($perPage)->withQueryString()
            : $query->get();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(int $id, array $data): ?User
    {
        $row = User::find($id);
        if ($row) {
            $row->update($data);
        }
        return $row->refresh();
    }

    public function delete(int $id): bool
    {
        $row = User::find($id);
        if ($row) {
            return $row->delete();
        }
        return false;
    }
}
