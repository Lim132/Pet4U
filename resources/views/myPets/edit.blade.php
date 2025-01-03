@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header text-center">
            <h2 class="mb-0 title2">Edit Pet Profile</h2>
        </div>
        
        <div class="card-body">
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
            <form action="{{ route('myPets.update', ['myPet' => $myPet]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        {{-- Photo Upload Section --}}
                        <div class="mb-4">
                            <label class="form-label card-heading">Pet Photos</label>
                            <input type="file" class="form-control" name="pet_photos[]" multiple accept="image/*">
                            
                            {{-- Current Photos Display --}}
                            @php
                                $photos = is_array($myPet->pet_photos) ? $myPet->pet_photos : json_decode($myPet->pet_photos, true);
                            @endphp
                            @if($photos && is_array($photos) && count($photos) > 0)
                                <div class="mt-3">
                                    <p>Current Photos:</p>
                                    <div class="row">
                                        @foreach($photos as $index => $photo)
                                            <div class="col-4 mb-2 position-relative">
                                                <img src="{{ Storage::url($photo) }}" class="img-thumbnail" alt="Pet Photo">
                                                <button type="button" 
                                                        class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 delete-photo" 
                                                        data-photo-index="{{ $index }}"
                                                        data-photo-path="{{ $photo }}">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                <input type="hidden" name="existing_photos[]" value="{{ $photo }}">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Basic Information --}}
                    <div class="col-md-6">
                        <h5 class="card-heading">Basic Information</h5>
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="pet_name" value="{{ $myPet->pet_name }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Breed</label>
                            <input type="text" class="form-control" name="pet_breed" value="{{ $myPet->pet_breed }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gender</label>
                            <select class="form-control" name="pet_gender" required>
                                <option value="Male" {{ $myPet->pet_gender == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ $myPet->pet_gender == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Age (years)</label>
                            <input type="number" class="form-control" name="pet_age" value="{{ $myPet->pet_age }}" min="0" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Size</label>
                            <select class="form-control" name="pet_size" required>
                                <option value="Small" {{ $myPet->pet_size == 'Small' ? 'selected' : '' }}>Small</option>
                                <option value="Medium" {{ $myPet->pet_size == 'Medium' ? 'selected' : '' }}>Medium</option>
                                <option value="Large" {{ $myPet->pet_size == 'Large' ? 'selected' : '' }}>Large</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Color</label>
                            <input type="text" class="form-control" name="pet_color" value="{{ $myPet->pet_color }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Location</label>
                            <input type="text" class="form-control" name="pet_area" value="{{ $myPet->pet_area }}">
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <h5 class="card-heading">Owner Information</h5><br>
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="owner_name" value="{{ $myPet->owner_name }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="owner_email" value="{{ $myPet->owner_email }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="tel" class="form-control" name="owner_phone" value="{{ $myPet->owner_phone }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h5 class="card-heading">Pet Description</h5><br>
                        <div class="mb-3">
                            <textarea class="form-control" name="pet_description" rows="5" required>{{ $myPet->pet_description }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-3">
                    <a href="{{ route('adoptedPet.profile', $myPet->id) }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-outline-danger ms-2">
                        <i class="fas fa-save me-1"></i>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>




<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.delete-photo');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to delete this photo?')) {
                const photoPath = this.dataset.photoPath;
                const photoContainer = this.closest('.col-4');
                
                fetch(`{{ route('myPets.deletePhoto', $myPet->id) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ photo_path: photoPath })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        photoContainer.remove();
                        alert(data.message);
                    } else {
                        alert('Failed to delete photo. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the photo.');
                });
            }
        });
    });
});
</script>
@endsection 