@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Users</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Users</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-table me-1"></i> User List</span>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Create New User
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>User Info</th>
                            <th>Contact</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Joined Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('img/avatar.jpg') }}" class="rounded-circle me-2 border" width="40" height="40" style="object-fit: cover;">
                                    <div>
                                        <div class="fw-bold">{{ $user->name }}</div>
                                        <div class="small text-muted">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                {{-- [UPDATED] Hiển thị phone_number --}}
                                @if($user->phone_number)
                                    <div class="small"><i class="fas fa-phone-alt text-muted me-1"></i> {{ $user->phone_number }}</div>
                                @else
                                    <div class="small text-muted">No Phone</div>
                                @endif
                                
                                {{-- [ADDED] Hiển thị address --}}
                                @if($user->address)
                                    <div class="small text-truncate" style="max-width: 150px;" title="{{ $user->address }}">
                                        <i class="fas fa-map-marker-alt text-muted me-1"></i> {{ $user->address }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                @php
                                    $roleName = optional($user->assignedRole)->name; 
                                    if (!$roleName) {
                                         $roleName = is_string($user->role) ? $user->role : 'customer';
                                    }
                                    $roleName = strtolower($roleName);
                                @endphp

                                @if($roleName === 'admin')
                                    <span class="badge bg-danger rounded-pill">Admin</span>
                                @elseif($roleName === 'staff' || $roleName === 'manager')
                                    <span class="badge bg-info text-dark rounded-pill">Staff</span>
                                @else
                                    <span class="badge bg-secondary rounded-pill">{{ ucfirst($roleName) }}</span>
                                @endif
                            </td>
                            <td>
                                @if($user->is_active ?? true) 
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Banned</span>
                                @endif
                            </td>
                            <td><small>{{ $user->created_at ? $user->created_at->format('d/m/Y') : 'N/A' }}</small></td>
                            <td class="text-center">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></a>
                                @if(auth()->id() !== $user->id)
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete user?');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center py-4">No users found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end mt-3">{{ $users->links() }}</div>
        </div>
    </div>
</div>
@endsection