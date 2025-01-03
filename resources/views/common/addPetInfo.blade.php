@extends('layouts.app')

@section('content')
<div class="container">
    {{-- Alert Messages --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-header">
            <span class="text-orange h4">{{ __('Add Pet Information') }}</span>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('pets.store') }}" enctype="multipart/form-data">
                @csrf
                
                {{-- 基本信息 --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>{{ __('Name') }}</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                            name="name" value="{{ old('name') }}"
                            {{ auth()->user()->role === 'customer' ? 'required' : '' }} placeholder="Please enter the pet's name">
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label>{{ __('Age') }}</label>
                        <input type="number" class="form-control @error('age') is-invalid @enderror" 
                            name="age" value="{{ old('age') }}"
                            {{ auth()->user()->role === 'customer' ? 'required' : '' }} placeholder="If less than 1 year old, please enter 0">
                        @error('age')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- 品种信息 --}}
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>{{ __('Species') }}</label>
                        <select id="species" class="form-control @error('species') is-invalid @enderror" 
                            name="species" {{ auth()->user()->role === 'customer' ? 'required' : '' }}>
                            <option value="">Select Species</option>
                            <option value="dog">Dog</option>
                            <option value="cat">Cat</option>
                            <option value="bird">Bird</option>
                            <option value="other">Other</option>
                        </select>
                        <input type="text" id="otherSpecies" class="form-control mt-2 d-none" 
                            name="other_species" placeholder="Please specify">
                        @error('species')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label>{{ __('Breed') }}</label>
                        <select id="breed" class="form-control @error('breed') is-invalid @enderror" 
                            name="breed" {{ auth()->user()->role === 'customer' ? 'required' : '' }}>
                            <option value="">Select Breed</option>
                        </select>
                        <input type="text" id="otherBreed" class="form-control mt-2 d-none" 
                            name="other_breed" placeholder="Please specify">
                        @error('breed')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label>{{ __('Gender') }}</label>
                        <select class="form-control @error('gender') is-invalid @enderror" 
                            name="gender" {{ auth()->user()->role === 'customer' ? 'required' : '' }}>
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                        @error('gender')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- 外观特征 --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>{{ __('Color') }}</label>
                        <select class="form-control @error('color') is-invalid @enderror" 
                            name="color" {{ auth()->user()->role === 'customer' ? 'required' : '' }}>
                            <option value="">Select Color</option>
                            <option value="black">Black</option>
                            <option value="white">White</option>
                            <option value="brown">Brown</option>
                            <option value="other">Other</option>
                        </select>
                        <input type="text" id="otherColor" class="form-control mt-2 d-none" 
                            name="other_color" placeholder="Please specify">
                        @error('color')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label>{{ __('Size') }}</label>
                        <select class="form-control @error('size') is-invalid @enderror" 
                            name="size" {{ auth()->user()->role === 'customer' ? 'required' : '' }}>
                            <option value="">Select Size</option>
                            <option value="small">Small</option>
                            <option value="medium">Medium</option>
                            <option value="large">Large</option>
                        </select>
                        @error('size')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- 健康状况 --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>{{ __('Vaccinated') }}</label>
                        <select class="form-control @error('vaccinated') is-invalid @enderror" 
                            name="vaccinated" {{ auth()->user()->role === 'customer' ? 'required' : '' }}>
                            <option value="">Select Status</option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                        @error('vaccinated')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label>{{ __('Health Status') }}</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="healthStatus[]" value="healthy">
                            <label class="form-check-label">Healthy</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="healthStatus[]" value="injured">
                            <label class="form-check-label">Injured</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="healthStatus[]" value="sick">
                            <label class="form-check-label">Sick</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="healthStatus[]" value="other">
                            <label class="form-check-label">Other</label>
                        </div>
                        <input type="text" id="otherHealthStatus" class="form-control mt-2 d-none" 
                            name="other_health_status" placeholder="Please specify">
                        @error('healthStatus')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- 性格和描述 --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>{{ __('Personality') }}</label>
                        <select class="form-control @error('personality') is-invalid @enderror" 
                            name="personality" {{ auth()->user()->role === 'customer' ? 'required' : '' }}>
                            <option value="">Select Personality</option>
                            <option value="friendly">Friendly</option>
                            <option value="aggressive">Aggressive</option>
                            <option value="shy">Shy</option>
                            <option value="other">Other</option>
                        </select>
                        <input type="text" id="otherPersonality" class="form-control mt-2 d-none" 
                            name="other_personality" placeholder="Please specify">
                        @error('personality')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label>{{ __('Description') }}</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                            name="description" rows="3"
                            {{ auth()->user()->role === 'customer' ? 'required' : '' }}>{{ old('description') }}</textarea>
                        @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- 媒体文件 --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>{{ __('Photos') }}</label>
                        <input type="file" class="form-control @error('photos') is-invalid @enderror" 
                            name="photos[]" multiple
                            {{ auth()->user()->role === 'customer' ? 'required' : '' }}>
                        @error('photos')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label>{{ __('Videos') }}</label>
                        <input type="file" class="form-control @error('videos') is-invalid @enderror" 
                            name="videos[]" multiple accept="video/*">
                        @error('videos')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- 隐藏字段 --}}
                <input type="hidden" name="addedBy" value="{{ auth()->id() }}">
                <input type="hidden" name="addedByRole" value="{{ auth()->user()->role }}">
                <input type="hidden" name="verified" value="{{ auth()->user()->role === 'admin' ? 1 : 0 }}">

                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Add Pet') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const speciesSelect = document.getElementById('species');
        const breedSelect = document.getElementById('breed');
        const otherSpeciesInput = document.getElementById('otherSpecies');
        const otherBreedInput = document.getElementById('otherBreed');
        const otherColorInput = document.getElementById('otherColor');
        const otherHealthStatusInput = document.getElementById('otherHealthStatus');
        const otherPersonalityInput = document.getElementById('otherPersonality');

        const breeds = {
            dog: ['Labrador', 'Golden Retriever', 'Bulldog', 'Other'],
            cat: ['Persian', 'Siamese', 'Maine Coon', 'Other'],
            bird: ['Parrot', 'Canary', 'Finch', 'Other'],
            other: ['Other']
        };

        speciesSelect.addEventListener('change', function() {
            const selectedSpecies = this.value;
            breedSelect.innerHTML = '<option value="">Select Breed</option>';
            if (breeds[selectedSpecies]) {
                breeds[selectedSpecies].forEach(function(breed) {
                    const option = document.createElement('option');
                    option.value = breed.toLowerCase();
                    option.textContent = breed;
                    breedSelect.appendChild(option);
                });
            }
            toggleOtherInput(speciesSelect, otherSpeciesInput);
        });

        breedSelect.addEventListener('change', function() {
            toggleOtherInput(breedSelect, otherBreedInput);
        });

        document.querySelectorAll('select').forEach(function(select) {
            select.addEventListener('change', function() {
                if (this.name === 'color') {
                    toggleOtherInput(this, otherColorInput);
                } else if (this.name === 'personality') {
                    toggleOtherInput(this, otherPersonalityInput);
                }
            });
        });

        document.querySelectorAll('input[type="checkbox"]').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                if (this.value === 'other') {
                    otherHealthStatusInput.classList.toggle('d-none', !this.checked);
                }
            });
        });

        function toggleOtherInput(selectElement, otherInputElement) {
            if (selectElement.value === 'other') {
                otherInputElement.classList.remove('d-none');
            } else {
                otherInputElement.classList.add('d-none');
            }
        }
    });
</script>
@endsection