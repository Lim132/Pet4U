@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="dashboard-title title1">Edit User</h2>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    {{-- First Name --}}
                    <div class="col-md-6 mb-3">
                        <label for="firstName" class="form-label words">First Name</label>
                        <input type="text" 
                               class="form-control @error('firstName') is-invalid @enderror" 
                               id="firstName" 
                               name="firstName" 
                               value="{{ old('firstName', $user->firstName) }}" 
                               required>
                        @error('firstName')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Last Name --}}
                    <div class="col-md-6 mb-3">
                        <label for="lastName" class="form-label words">Last Name</label>
                        <input type="text" 
                               class="form-control @error('lastName') is-invalid @enderror" 
                               id="lastName" 
                               name="lastName" 
                               value="{{ old('lastName', $user->lastName) }}" 
                               required>
                        @error('lastName')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Username --}}
                    <div class="col-md-6 mb-3">
                        <label for="username" class="form-label words">Username</label>
                        <input type="text" 
                               class="form-control @error('username') is-invalid @enderror" 
                               id="username" 
                               name="username" 
                               value="{{ old('username', $user->username) }}" 
                               required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label words">Email</label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $user->email) }}" 
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label words">Phone</label>
                        <input type="text" 
                               class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone', $user->phone) }}" 
                               required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Age --}}
                    <div class="col-md-6 mb-3">
                        <label for="age" class="form-label words">Age</label>
                        <input type="number" 
                               class="form-control @error('age') is-invalid @enderror" 
                               id="age" 
                               name="age" 
                               value="{{ old('age', $user->age) }}" 
                               required>
                        @error('age')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Gender --}}
                    <div class="col-md-6 mb-3">
                        <label for="gender" class="form-label words">Gender</label>
                        <select class="form-select @error('gender') is-invalid @enderror" 
                                id="gender" 
                                name="gender" 
                                required>
                            <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Role --}}
                    <div class="col-md-6 mb-3">
                        <label for="role" class="form-label words">Role</label>
                        <select class="form-select @error('role') is-invalid @enderror" 
                                id="role" 
                                name="role" 
                                required>
                            <option value="customer" {{ old('role', $user->role) === 'customer' ? 'selected' : '' }}>Customer</option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Address --}}
                    <div class="col-12 mb-3">
                        <label for="address" class="form-label words">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" 
                                  name="address" 
                                  rows="3" 
                                  required>{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12 d-flex justify-content-between">
                        <div>
                            <a href="{{ route('admin.users') }}" class="btn btn-secondary">Cancel</a> &nbsp;
                            <button type="submit" class="btn btn-orange">Update User</button>
                        </div>
            </form>
                        <div>
                            <form action="{{ route('admin.users.delete', $user) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" 
                                        onclick="return confirm('Are you sure you want to delete this user?')">
                                    <i class="fas fa-trash"></i>Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            
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

.form-control:focus,
.form-select:focus {
    border-color: orange;
    box-shadow: 0 0 0 0.2rem rgba(255, 165, 0, 0.25);
}
</style>
@endsection 