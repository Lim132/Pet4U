@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header text-center">
            <h2 class="mb-0 title2">Pet Profile</h2>
        </div>
        
        <div class="card-body">
            <div class="row">
                <div class="col-md-9">
                    {{-- Photo Carousel --}}
                    @php
                        $photos = is_string($myPet->pet_photos) ? json_decode($myPet->pet_photos, true) : $myPet->pet_photos;
                    @endphp
                    
                    @if($photos && is_array($photos) && count($photos) > 0)
                        <div id="petPhotos" class="carousel slide mb-4" data-bs-ride="carousel">
                            <div class="carousel-inner" style="height: 500px;">
                                @foreach($photos as $index => $photo)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <img src="{{ Storage::url($photo) }}" 
                                            class="d-block mx-auto" 
                                            alt="Pet Photo"
                                            style="max-height: 500px; max-width: 100%; width: auto; height: auto; object-fit: contain;">
                                    </div>
                                @endforeach
                            </div>
                            @if(count($photos) > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#petPhotos" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon"></span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#petPhotos" data-bs-slide="next">
                                    <span class="carousel-control-next-icon"></span>
                                </button>
                            @endif
                        </div>
                    @else
                        <img class="img-fluid rounded" 
                             src="{{ asset('images/default-pet.jpg') }}" 
                             alt="{{ $myPet->pet_name }}">
                    @endif
                </div>

                {{-- 基本信息 --}}
                <div class="col-md-3">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <h5 class="card-heading">Basic Information</h5>
                        @if($myPet->qr_code_path)
                            <img src="{{ asset($myPet->qr_code_path) }}" 
                                 alt="QR Code" 
                                 class="qr-code">
                        @endif
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th>Name:</th>
                                <td>{{ $myPet->pet_name }}</td>
                            </tr>
                            <tr>
                                <th>Breed:</th>
                                <td>{{ $myPet->pet_breed }}</td>
                            </tr>
                            <tr>
                                <th>Gender:</th>
                                <td>{{ $myPet->pet_gender }}</td>
                            </tr>
                            <tr>
                                <th>Age:</th>
                                <td>{{ $myPet->pet_age == 0 ? 'Under 1 year' : ($myPet->pet_age == 1 ? '1 year' : $myPet->pet_age . ' years') }}</td>
                            </tr>
                            <tr>
                                <th>Size:</th>
                                <td>{{ $myPet->pet_size }}</td>
                            </tr>
                            <tr>
                                <th>Color:</th>
                                <td>{{ $myPet->pet_color }}</td>
                            </tr>
                            <tr>
                                <th>Location:</th>
                                <td>{{ $myPet->pet_area ?? 'Not provided' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <h5 class="card-heading">Owner Information</h5>
                    <br>
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th>Name:</th>
                                <td>{{ $myPet->owner_name }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $myPet->owner_email }}</td>
                            </tr>
                            <tr>
                                <th>Phone:</th>
                                <td>{{ $myPet->owner_phone }}</td>
                            </tr>
                            <tr>
                                <th>Adoption Date:</th>
                                <td>{{ $myPet->created_at->format('Y-m-d') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="col-md-6">
                    <h5 class="card-heading">Pet Description</h5>
                    <br>
                    <p class="pet-description words2">{{ $myPet->pet_description }}</p>
                </div>
            </div>
            @if(Auth::check() && Auth::user()->id == $myPet->user_id)
            <div class="text-end mt-3">
                <a href="{{ route('myPets.edit', $myPet->id) }}" 
                   class="btn btn-outline-danger ms-2">
                    <i class="fas fa-edit me-1"></i>
                    Edit Pet Information
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.card-header h2 {
    color: orange;
    font-family: 'Arial Black', Arial, sans-serif;
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

.card-heading {
    color: orange;
    font-family: 'Arial Black', Arial, sans-serif;
    border-bottom: 2px solid orange;
    padding-bottom: 10px;
    margin-bottom: 20px;
}

.table th {
    color: orange;
    width: 30%;
}

.table td {
    vertical-align: middle;
}

.qr-code {
    width: 100px;
    height: 100px;
}

.pet-description {
    padding: 15px;
    background-color: #f8f9fa;
    border-radius: 5px;
    min-height: 100px;
}

.carousel-control-prev-icon,
.carousel-control-next-icon {
    background-color: rgba(255, 165, 0, 0.5);
    border-radius: 50%;
    padding: 20px;
}

.carousel {
    border-radius: 5px;
    overflow: hidden;
}
</style>
@endsection 