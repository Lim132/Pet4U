@extends('layouts.app')

@section('content')
<div class="container">
    {{-- 添加 Flash Messages 显示 --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>{{ $pet->name }}'s Details</h4>
                </div>
                <div class="card-body">
                    {{-- 照片轮播 --}}
                    @if($pet->photos && count($pet->photos) > 0)
                        <div id="petPhotos" class="carousel slide mb-4" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($pet->photos as $index => $photo)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <img src="{{ Storage::url($photo) }}" 
                                            class="d-block w-100" 
                                            alt="Pet Photo"
                                            style="max-height: 400px; object-fit: contain;">
                                    </div>
                                @endforeach
                            </div>
                            @if(count($pet->photos) > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#petPhotos" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon"></span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#petPhotos" data-bs-slide="next">
                                    <span class="carousel-control-next-icon"></span>
                                </button>
                            @endif
                        </div>
                    @endif

                    {{-- 宠物信息 --}}
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Basic Information</h5>
                            <ul class="list-unstyled">
                                @if($pet->age == 0)
                                    <li><strong>Age:</strong> Under 1 year</li>
                                @elseif($pet->age == 1)
                                    <li><strong>Age:</strong> 1 year</li>
                                @else
                                    <li><strong>Age:</strong> {{ $pet->age }} years</li>
                                @endif
                                <li><strong>Species:</strong> {{ ucfirst($pet->species) }}</li>
                                <li><strong>Breed:</strong> {{ ucfirst($pet->breed) }}</li>
                                <li><strong>Gender:</strong> {{ ucfirst($pet->gender) }}</li>
                                <li><strong>Color:</strong> {{ ucfirst($pet->color) }}</li>
                                <li><strong>Size:</strong> {{ ucfirst($pet->size) }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5>Health Information</h5>
                            <ul class="list-unstyled">
                                <li>
                                    <strong>Vaccinated:</strong>
                                    <span class="badge {{ $pet->vaccinated ? 'bg-success' : 'bg-warning' }}">
                                        {{ $pet->vaccinated ? 'Yes' : 'No' }}
                                    </span>
                                </li>
                                <li>
                                    <strong>Health Status:</strong><br>
                                    @foreach($pet->healthStatus as $status)
                                        <span class="badge bg-info me-1">{{ $status }}</span>
                                    @endforeach
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h5>Description</h5>
                        <p>{{ $pet->description }}</p>
                    </div>

                    {{-- 视频（如果有） --}}
                    @if($pet->videos && count($pet->videos) > 0)
                        <div class="mt-4">
                            <h5>Videos</h5>
                            @foreach($pet->videos as $video)
                                <video controls class="w-100 mb-3">
                                    <source src="{{ Storage::url($video) }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            @endforeach
                        </div>
                    @endif

                    {{-- 操作按钮 --}}
                    <div class="mt-4 text-center">
                        <a href="{{ route('showAdp') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                        <form action="{{ route('pets.adopt', $pet) }}" method="POST" class="d-inline" id="adoptForm">
                            @csrf
                            <button type="button" class="btn btn-danger" onclick="confirmAdoption()">
                                <i class="fas fa-heart"></i> Adopt Me
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
    margin-bottom: 20px;
}

.carousel-item img {
    border-radius: 8px;
}

.badge {
    font-size: 0.9em;
    padding: 6px 10px;
}
</style>

{{-- 添加确认对话框的 JavaScript --}}
<script>
function confirmAdoption() {
    if (confirm('Are you sure you want to apply for adoption of this pet?')) {
        document.getElementById('adoptForm').submit();
    }
}
</script>

{{-- 添加自动隐藏提示的 JavaScript --}}
<script>
// 自动隐藏提示信息
document.addEventListener('DOMContentLoaded', function() {
    // 获取所有警告框
    var alerts = document.querySelectorAll('.alert');
    
    // 为每个警告框设置自动隐藏
    alerts.forEach(function(alert) {
        setTimeout(function() {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000); // 5秒后自动隐藏
    });
});
</script>
@endsection 