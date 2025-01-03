@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="dashboard-title title1">Admin Dashboard</h2>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-body">
                    <i class="fas fa-paw stat-icon"></i>
                    <h3>{{ $totalPets }}</h3>
                    <p>Total Pets</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-body">
                    <i class="fas fa-file-alt stat-icon"></i>
                    <h3>{{ $pendingAdoptions }}</h3>
                    <p>Pending Adoptions</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-body">
                    <i class="fas fa-check-circle stat-icon"></i>
                    <h3>{{ $successfulAdoptions }}</h3>
                    <p>Successful Adoptions</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-body">
                    <i class="fas fa-users stat-icon"></i>
                    <h3>{{ $totalUsers }}</h3>
                    <p>Registered Users</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Pet Actions --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Quick Pet Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('pets.create') }}" class="btn btn-orange">
                            <i class="fas fa-plus-circle"></i> Add New Pet
                        </a>
                        <a href="{{ route('admin.pets.verification') }}" class="btn btn-orange">
                            <i class="fas fa-user-cog"></i> Pet Info Verification and Management
                        </a>
                        <a href="{{ route('admin.adoptions') }}" class="btn btn-orange">
                            <i class="fas fa-list"></i> Manage Adoptions
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Other Actions --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Other Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.donationRecords') }}" class="btn btn-orange">
                            <i class="fas fa-plus-circle"></i> Donate Records
                        </a>
                        <a href="{{ route('admin.users') }}" class="btn btn-orange">
                            <i class="fas fa-user-cog"></i> Manage Users
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Adoption Statistics</h5>
                </div>
                <div class="card-body">
                    <canvas id="adoptionChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Pet Categories</h5>
                </div>
                <div class="card-body">
                    <canvas id="petCategoryChart"></canvas>
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

.stat-card {
    background: white;
    border: 1px solid orange;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    transition: transform 0.3s;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-icon {
    color: orange;
    font-size: 2em;
    margin-bottom: 10px;
}

.btn-orange {
    background-color: orange;
    color: white;
    border: none;
    margin-bottom: 10px;
}

.btn-orange:hover {
    background-color: darkorange;
    color: white;
}

.activity-list {
    max-height: 300px;
    overflow-y: auto;
}

.activity-item {
    display: flex;
    align-items: start;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
}

.activity-dot {
    color: orange;
    margin-right: 10px;
    margin-top: 5px;
    font-size: 0.5em;
}

.activity-content {
    flex: 1;
}

.activity-content p {
    margin-bottom: 0;
}

.activity-content small {
    color: #666;
}

.card {
    border: 1px solid orange !important;
    margin-bottom: 20px;
}

.card-header {
    background-color: white;
    border-bottom: 2px solid orange;
}

.card-header h5 {
    color: orange;
    margin-bottom: 0;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Adoption Statistics Chart
    new Chart(document.getElementById('adoptionChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($adoptionStats->pluck('month')) !!},
            datasets: [{
                label: 'Adoptions',
                data: {!! json_encode($adoptionStats->pluck('count')) !!},
                borderColor: 'orange',
                tension: 0.1
            }]
        }
    });

    // Pet Categories Chart
    new Chart(document.getElementById('petCategoryChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($petCategories->pluck('species')) !!},
            datasets: [{
                data: {!! json_encode($petCategories->pluck('count')) !!},
                backgroundColor: ['#FFA500', '#FF8C00', '#FFD700', '#DAA520']
            }]
        }
    });
});
</script>
@endsection 