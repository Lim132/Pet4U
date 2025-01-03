@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 title1">My Adoption Applications</h2>

    {{-- 状态标签页 --}}
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link {{ $status === 'all' ? 'active' : '' }}" 
               href="{{ route('adoptions.application', ['status' => 'all']) }}">
                All <span class="badge bg-secondary">{{ $counts['all'] }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $status === 'pending' ? 'active' : '' }}" 
               href="{{ route('adoptions.application', ['status' => 'pending']) }}">
                Pending <span class="badge bg-warning">{{ $counts['pending'] }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $status === 'approved' ? 'active' : '' }}" 
               href="{{ route('adoptions.application', ['status' => 'approved']) }}">
                Approved <span class="badge bg-success">{{ $counts['approved'] }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $status === 'rejected' ? 'active' : '' }}" 
               href="{{ route('adoptions.application', ['status' => 'rejected']) }}">
                Rejected <span class="badge bg-danger">{{ $counts['rejected'] }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $status === 'done' ? 'active' : '' }}" 
               href="{{ route('adoptions.application', ['status' => 'done']) }}">
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
                            <a href="{{ route('pets.show', $adoption->pet) }}" 
                               class="btn btn-sm btn-primary">
                                View Pet
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No adoption applications found.</td>
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

<style>
.nav-tabs .nav-link {
    color: #495057;
}
.nav-tabs .nav-link.active {
    font-weight: bold;
}
.badge {
    margin-left: 5px;
}
.table td {
    vertical-align: middle;
}
</style>
@endsection