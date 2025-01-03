@extends('layouts.app')

@section('content')
<div class="container">
    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-12">
            <h2 class="dashboard-title title1">Manage Users</h2>
        </div>
    </div>

    {{-- Search Bar --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <form action="{{ route('admin.users') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search users..." value="{{ request('search') }}"> &nbsp; &nbsp;
                    <button class="btn btn-orange" type="submit">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Users Table --}}
    <div class="card">
        <div class="card-body">
            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Avatar</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>
                                    <img src="{{ $user->avatar ? Storage::url($user->avatar) : asset('images/image1.png') }}" 
                                         alt="Avatar" 
                                         class="rounded-circle"
                                         width="40"
                                         height="40"
                                         style="object-fit: cover;">
                                </td>
                                <td>
                                    {{ $user->firstName }} {{ $user->lastName }}
                                    <br>
                                    <small class="text-muted">{{ $user->username }}</small>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>
                                    <form action="{{ route('admin.users.role', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <select name="role" class="form-select form-select-sm words2" onchange="this.form.submit()">
                                            <option value="customer" {{ $user->role === 'customer' ? 'selected' : '' }}>Customer</option>
                                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                        </select>
                                    </form>
                                </td>
                                <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary btn-sm">{{ __('Edit') }}</a>
                                    <form action="{{ route('admin.users.delete', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" 
                                                onclick="return confirm('Are you sure you want to delete this user?')">
                                            <i class="fas fa-trash"></i>Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $users->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <p>No users found.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.dashboard-title {
    color: orange;
    font-family: 'Arial Black', Arial, sans-serif;
    margin-bottom: 30px;
    padding-bottom: 10px;
    border-bottom: 2px solid orange;
}

.btn-orange {
    background-color: orange;
    color: white;
    border: none;
}

.btn-orange:hover {
    background-color: darkorange;
    color: white;
}

.card {
    border: 1px solid orange !important;
    margin-bottom: 20px;
}

.table th {
    border-bottom: 2px solid orange;
}

.table td {
    vertical-align: middle;
}

.form-select {
    border-color: orange;
}

.form-select:focus {
    border-color: darkorange;
    box-shadow: 0 0 0 0.2rem rgba(255, 165, 0, 0.25);
}

.pagination {
    --bs-pagination-color: orange;
    --bs-pagination-active-bg: orange;
    --bs-pagination-active-border-color: orange;
}
</style>
@endsection 