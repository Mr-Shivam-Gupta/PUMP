<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserExport implements FromQuery, WithMapping, WithHeadings
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        return User::with('tenant')
            ->when($this->filters['tenant_id'] ?? null, fn($q) => $q->where('tenant_id', $this->filters['tenant_id']))
            ->when($this->filters['role'] ?? null, fn($q) => $q->where('role', $this->filters['role']))
            ->when(isset($this->filters['status']), fn($q) => $q->where('status', $this->filters['status']));
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->phone,
            $user->tenant->name ?? '-',
            ucfirst($user->role),
            $user->status ? 'Active' : 'Inactive',
            $user->created_at->format('Y-m-d H:i'),
        ];
    }

    public function headings(): array
    {
        return ['ID', 'Name', 'Email', 'Phone', 'Tenant', 'Role', 'Status', 'Created At'];
    }
}
