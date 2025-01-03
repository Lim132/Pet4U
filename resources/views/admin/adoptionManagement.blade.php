@extends('layouts.app')

@section('content')
<div class="container">
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

    <div class="card">
        <div class="card-header text-center">
            <h2 class="mb-0 title2">Adoption Applications Management</h2>
        </div>
        
        <div class="card-body">
            {{-- 状态标签页 --}}
            <ul class="nav nav-tabs mb-4">
                <li class="nav-item">
                    <a class="nav-link {{ $status === 'all' ? 'active' : '' }}" 
                       href="{{ route('admin.adoptions', ['status' => 'all']) }}">
                        All <span class="badge bg-secondary">{{ $counts['all'] }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $status === 'pending' ? 'active' : '' }}" 
                       href="{{ route('admin.adoptions', ['status' => 'pending']) }}">
                        Pending <span class="badge bg-warning">{{ $counts['pending'] }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $status === 'approved' ? 'active' : '' }}" 
                       href="{{ route('admin.adoptions', ['status' => 'approved']) }}">
                        Approved <span class="badge bg-success">{{ $counts['approved'] }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $status === 'rejected' ? 'active' : '' }}" 
                       href="{{ route('admin.adoptions', ['status' => 'rejected']) }}">
                        Rejected <span class="badge bg-danger">{{ $counts['rejected'] }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $status === 'done' ? 'active' : '' }}" 
                       href="{{ route('admin.adoptions', ['status' => 'done']) }}">
                        Done <span class="badge bg-info">{{ $counts['done'] }}</span>
                    </a>
                </li>
            </ul>

            {{-- 申请列表 --}}
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Pet Name</th>
                            <th>Species</th>
                            <th>Applicant</th>
                            <th>Application Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($adoptions as $adoption)
                            <tr>
                                <td>{{ $adoption->pet->name }}</td>
                                <td>{{ ucfirst($adoption->pet->species) }}</td>
                                <td>{{ $adoption->user->username }}</td>
                                <td>{{ $adoption->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    @switch($adoption->status)
                                        @case('pending')
                                            <span class="badge bg-warning">Pending</span>
                                            @break
                                        @case('approved')
                                            <span class="badge bg-success">Approved</span>
                                            @break
                                        @case('rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                            @break
                                        @case('done')
                                            <span class="badge bg-info">Done</span>
                                            @break
                                    @endswitch
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="#" class="btn btn-sm btn-primary me-1" 
                                           data-bs-toggle="modal" 
                                           data-bs-target="#detailModal{{ $adoption->id }}">
                                            View Details
                                        </a>
                                        <div class="dropdown d-inline">
                                            <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                Change Status
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <button class="dropdown-item {{ $adoption->status === 'pending' ? 'active' : '' }}"
                                                            onclick="updateStatus('{{ $adoption->id }}', 'pending')">
                                                        Pending
                                                    </button>
                                                </li>
                                                <li>
                                                    <button class="dropdown-item {{ $adoption->status === 'approved' ? 'active' : '' }}"
                                                            onclick="updateStatus('{{ $adoption->id }}', 'approved')">
                                                        Approved
                                                    </button>
                                                </li>
                                                <li>
                                                    <button class="dropdown-item {{ $adoption->status === 'rejected' ? 'active' : '' }}"
                                                            onclick="updateStatus('{{ $adoption->id }}', 'rejected')">
                                                        Rejected
                                                    </button>
                                                </li>
                                                <li>
                                                    <button class="dropdown-item {{ $adoption->status === 'done' ? 'active' : '' }}"
                                                            onclick="updateStatus('{{ $adoption->id }}', 'done')">
                                                        Done
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No adoption applications found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- 分页 --}}
            <div class="d-flex justify-content-center">
                {{ $adoptions->links() }}
            </div>
        </div>
    </div>
</div>

{{-- 在页面底部添加模态框 --}}
@foreach($adoptions as $adoption)
    <div class="modal fade" id="detailModal{{ $adoption->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Adoption Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {{-- 宠物信息 --}}
                        <div class="col-md-6">
                            <h5 class="card-heading">Pet Information</h5>
                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <th>Name:</th>
                                        <td>{{ $adoption->pet->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Species:</th>
                                        <td>{{ ucfirst($adoption->pet->species) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Breed:</th>
                                        <td>{{ $adoption->pet->breed }}</td>
                                    </tr>
                                    <tr>
                                        <th>Age:</th>
                                        <td>{{ $adoption->pet->age }} years</td>
                                    </tr>
                                    <tr>
                                        <th>Gender:</th>
                                        <td>{{ ucfirst($adoption->pet->gender) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Health Status:</th>
                                        <td>
                                            @foreach($adoption->pet->healthStatus as $status)
                                                <span class="badge bg-info">{{ $status }}</span>
                                            @endforeach
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            @if($adoption->pet->photos)
                                <div class="pet-photos mt-3">
                                    <img src="{{ Storage::url($adoption->pet->photos[0]) }}" 
                                         class="img-fluid rounded" 
                                         alt="Pet Photo">
                                </div>
                            @endif
                        </div>

                        {{-- 申请人信息 --}}
                        <div class="col-md-6">
                            <h5 class="card-heading">Applicant Information</h5>
                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <th>Username:</th>
                                        <td>{{ $adoption->user->username }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td>{{ $adoption->user->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone:</th>
                                        <td>{{ $adoption->user->phone ?? 'Not provided' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Application Date:</th>
                                        <td>{{ $adoption->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status:</th>
                                        <td>
                                            @switch($adoption->status)
                                                @case('pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                    @break
                                                @case('approved')
                                                    <span class="badge bg-success">Approved</span>
                                                    @break
                                                @case('rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                    @break
                                                @case('done')
                                                    <span class="badge bg-info">Done</span>
                                                    @break
                                            @endswitch
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            @if($adoption->user->avatar)
                                <div class="user-avatar mt-3 text-center">
                                    <img src="{{ Storage::url($adoption->user->avatar) }}" 
                                         class="rounded-circle" 
                                         style="width: 100px; height: 100px; object-fit: cover;"
                                         alt="User Avatar">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

<style>
.card-header h2 {
    color: orange;
    font-family: 'Arial Black', Arial, sans-serif;
}

.nav-tabs .nav-link {
    color: #495057;
}

.nav-tabs .nav-link.active {
    font-weight: bold;
    color: orange;
    border-bottom: 2px solid orange;
}

.badge {
    margin-left: 5px;
}

.table td {
    vertical-align: middle;
}

.card {
    margin-top: 20px;
    margin-bottom: 20px;
    border: 1px solid orange !important;
}

.card-header {
    background-color: white;
    border-bottom: 2px solid orange;
}

.btn-group .btn {
    margin-right: 5px;
}

.dropdown-item.active {
    background-color: orange !important;
    color: white !important;
}

.dropdown-item:hover {
    background-color: rgba(255, 165, 0, 0.2) !important;
}

.modal-content {
    border: 1px solid orange;
}

.modal-header {
    border-bottom: 2px solid orange;
}

.modal-footer {
    border-top: 2px solid orange;
}

.card-heading {
    color: orange;
    font-family: 'Arial Black', Arial, sans-serif;
    border-bottom: 2px solid orange;
    padding-bottom: 10px;
    margin-bottom: 20px;
}

.table th {
    color: orange;
    width: 40%;
}

.badge {
    margin: 2px;
}

.pet-photos img {
    max-height: 200px;
    width: auto;
    display: block;
    margin: 0 auto;
}
</style>

<script>
function updateStatus(adoptionId, status) {
    if (confirm('Are you sure you want to change the status to ' + status + '?')) {
        fetch(`/admin/adoptions/${adoptionId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Status updated successfully');
                location.reload();
            } else {
                alert('Error updating status: ' + (data.message || 'Please try again.'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating status. Please try again.');
        });
    }
}
</script>
@endsection 