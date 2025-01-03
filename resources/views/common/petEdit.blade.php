@extends('layouts.app')

@section('content')
<div class="container">
    @include('common.alerts')

    <div class="card">
        <div class="card-header">
            <h4>{{ __('Edit Pet Information') }}</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('pets.update', $pet) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                {{-- 基本信息 --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>{{ __('Name') }}</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                            name="name" value="{{ old('name', $pet->name) }}" required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label>{{ __('Age') }}</label>
                        <input type="number" class="form-control @error('age') is-invalid @enderror" 
                            name="age" value="{{ old('age', $pet->age) }}" required>
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
                            name="species" required>
                            <option value="">Select Species</option>
                            <option value="dog" {{ old('species', $pet->species) == 'dog' ? 'selected' : '' }}>Dog</option>
                            <option value="cat" {{ old('species', $pet->species) == 'cat' ? 'selected' : '' }}>Cat</option>
                            <option value="bird" {{ old('species', $pet->species) == 'bird' ? 'selected' : '' }}>Bird</option>
                            <option value="other" {{ old('species', $pet->species) == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        <input type="text" id="otherSpecies" 
                            class="form-control mt-2 {{ !in_array($pet->species, ['dog', 'cat', 'bird']) ? '' : 'd-none' }}" 
                            name="other_species" 
                            value="{{ !in_array($pet->species, ['dog', 'cat', 'bird']) ? $pet->species : '' }}" 
                            placeholder="Please specify">
                        @error('species')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label>{{ __('Breed') }}</label>
                        <select id="breed" class="form-control @error('breed') is-invalid @enderror" 
                            name="breed" required data-selected-breed="{{ old('breed', $pet->breed) }}">
                            <option value="">Select Breed</option>
                            {{-- Will be populated by JavaScript --}}
                        </select>
                        <input type="text" id="otherBreed" 
                            class="form-control mt-2 {{ !in_array($pet->breed, ['labrador', 'golden retriever', 'bulldog', 'persian', 'siamese', 'maine coon', 'parrot', 'canary', 'finch']) ? '' : 'd-none' }}" 
                            name="other_breed" 
                            value="{{ !in_array($pet->breed, ['labrador', 'golden retriever', 'bulldog', 'persian', 'siamese', 'maine coon', 'parrot', 'canary', 'finch']) ? $pet->breed : '' }}" 
                            placeholder="Please specify">
                        @error('breed')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label>{{ __('Gender') }}</label>
                        <select class="form-control @error('gender') is-invalid @enderror" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender', $pet->gender) == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $pet->gender) == 'female' ? 'selected' : '' }}>Female</option>
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
                            name="color" id="color" required>
                            <option value="">Select Color</option>
                            <option value="black" {{ old('color', $pet->color) == 'black' ? 'selected' : '' }}>Black</option>
                            <option value="white" {{ old('color', $pet->color) == 'white' ? 'selected' : '' }}>White</option>
                            <option value="brown" {{ old('color', $pet->color) == 'brown' ? 'selected' : '' }}>Brown</option>
                            <option value="other" {{ !in_array(old('color', $pet->color), ['black', 'white', 'brown']) ? 'selected' : '' }}>Other</option>
                        </select>
                        <input type="text" id="otherColor" 
                            class="form-control mt-2 {{ in_array($pet->color, ['black', 'white', 'brown']) ? 'd-none' : '' }}" 
                            name="other_color" placeholder="Please specify"
                            value="{{ !in_array($pet->color, ['black', 'white', 'brown']) ? $pet->color : '' }}">
                        @error('color')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        @error('other_color')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label>{{ __('Size') }}</label>
                        <select class="form-control @error('size') is-invalid @enderror" 
                            name="size" required>
                            <option value="">Select Size</option>
                            <option value="small" {{ old('size', $pet->size) == 'small' ? 'selected' : '' }}>Small</option>
                            <option value="medium" {{ old('size', $pet->size) == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="large" {{ old('size', $pet->size) == 'large' ? 'selected' : '' }}>Large</option>
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
                            name="vaccinated" required>
                            <option value="">Select Status</option>
                            <option value="1" {{ old('vaccinated', $pet->vaccinated) ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ !old('vaccinated', $pet->vaccinated) ? 'selected' : '' }}>No</option>
                        </select>
                        @error('vaccinated')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label>{{ __('Health Status') }}</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="healthStatus[]" value="healthy"
                                {{ in_array('healthy', old('healthStatus', is_array($pet->healthStatus) ? $pet->healthStatus : [])) ? 'checked' : '' }}>
                            <label class="form-check-label">Healthy</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="healthStatus[]" value="injured"
                                {{ in_array('injured', old('healthStatus', is_array($pet->healthStatus) ? $pet->healthStatus : [])) ? 'checked' : '' }}>
                            <label class="form-check-label">Injured</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="healthStatus[]" value="sick"
                                {{ in_array('sick', old('healthStatus', is_array($pet->healthStatus) ? $pet->healthStatus : [])) ? 'checked' : '' }}>
                            <label class="form-check-label">Sick</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="healthStatus[]" value="other"
                                {{ is_array($pet->healthStatus) && !empty(array_diff($pet->healthStatus, ['healthy', 'injured', 'sick'])) ? 'checked' : '' }}>
                            <label class="form-check-label">Other</label>
                        </div>
                        <input type="text" id="otherHealthStatus" 
                            class="form-control mt-2 {{ is_array($pet->healthStatus) && !empty(array_diff($pet->healthStatus, ['healthy', 'injured', 'sick'])) ? '' : 'd-none' }}" 
                            name="other_health_status" 
                            value="{{ is_array($pet->healthStatus) ? implode(', ', array_diff($pet->healthStatus, ['healthy', 'injured', 'sick'])) : '' }}" 
                            placeholder="Please specify">
                        @error('healthStatus')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        @error('other_health_status')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- 性格和描述 --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>{{ __('Personality') }}</label>
                        <select class="form-control @error('personality') is-invalid @enderror" 
                            name="personality" id="personality" required>
                            <option value="">Select Personality</option>
                            <option value="friendly" {{ old('personality', $pet->personality) == 'friendly' ? 'selected' : '' }}>Friendly</option>
                            <option value="aggressive" {{ old('personality', $pet->personality) == 'aggressive' ? 'selected' : '' }}>Aggressive</option>
                            <option value="shy" {{ old('personality', $pet->personality) == 'shy' ? 'selected' : '' }}>Shy</option>
                            <option value="other" {{ old('personality', $pet->personality) == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        <input type="text" id="otherPersonality" 
                            class="form-control mt-2 {{ !in_array($pet->personality, ['friendly', 'aggressive', 'shy']) ? '' : 'd-none' }}" 
                            name="other_personality" 
                            value="{{ !in_array($pet->personality, ['friendly', 'aggressive', 'shy']) ? $pet->personality : '' }}" 
                            placeholder="Please specify">
                        @error('personality')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        @error('other_personality')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label>{{ __('Description') }}</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                            name="description" rows="3" required>{{ old('description', $pet->description) }}</textarea>
                        @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- 现有照片预览 --}}
                @if($pet->photos)
                <div class="row mb-3">
                    <div class="col-12">
                        <label>{{ __('Current Photos') }}</label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($pet->photos as $index => $photo)
                            <div class="position-relative photo-container">
                                <img src="{{ Storage::url($photo) }}" alt="Pet photo" 
                                    class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                @if(count($pet->photos) > 1)
                                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 delete-photo"
                                    data-photo-index="{{ $index }}">
                                    <i class="fas fa-times"></i>
                                </button>
                                @endif
                                <input type="hidden" name="photos_to_keep[]" value="{{ $photo }}" class="photo-keep-input">
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                {{-- 现有视频预览 --}}
                @if($pet->videos)
                <div class="row mb-3">
                    <div class="col-12">
                        <label>{{ __('Current Videos') }}</label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($pet->videos as $index => $video)
                            <div class="position-relative">
                                <video width="200" height="150" controls class="img-thumbnail">
                                    <source src="{{ Storage::url($video) }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 delete-video"
                                    data-video-index="{{ $index }}">
                                    <i class="fas fa-times"></i>
                                </button>
                                <input type="hidden" name="videos_to_keep[]" value="{{ $video }}">
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                {{-- 媒体文件 --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>{{ __('Add New Photos') }}</label>
                        <input type="file" class="form-control @error('photos') is-invalid @enderror" 
                            name="photos[]" multiple accept="image/*">
                        <small class="text-muted">{{ __('Leave empty to keep current photos') }}</small>
                        @error('photos')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label>{{ __('Add New Videos') }}</label>
                        <input type="file" class="form-control @error('videos') is-invalid @enderror" 
                            name="videos[]" multiple accept="video/*">
                        <small class="text-muted">{{ __('Leave empty to keep current videos') }}</small>
                        @error('videos')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                @if(auth()->user()->role === 'customer')
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        {{ __('Your changes will need to be verified by an administrator before becoming visible.') }}
                    </div>
                @endif

                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Update Pet') }}
                        </button> &nbsp;
                        <a href="{{ route('pets.myAdded') }}" class="btn btn-secondary">
                            {{ __('Cancel') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        

        // 处理照片删除
        document.querySelectorAll('.delete-photo').forEach(button => {
            button.addEventListener('click', function() {
                const photoContainer = this.closest('.photo-container');
                const remainingPhotos = document.querySelectorAll('.photo-keep-input').length;
                
                if (remainingPhotos <= 1) {
                    alert('至少需要保留一张照片！');
                    return;
                }
                
                if (confirm('确定要删除这张照片吗？')) {
                    photoContainer.remove();
                }
            });
        });

        // 处理视频删除
        document.querySelectorAll('.delete-video').forEach(button => {
            button.addEventListener('click', function() {
                const videoContainer = this.closest('.position-relative');
                if (confirm('确定要删除这个视频吗？')) {
                    videoContainer.remove();
                }
            });
        });
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 品种数据
    const breeds = {
        dog: ['Labrador', 'Golden Retriever', 'Bulldog', 'Other'],
        cat: ['Persian', 'Siamese', 'Maine Coon', 'Other'],
        bird: ['Parrot', 'Canary', 'Finch', 'Other'],
        other: ['Other']
    };

    // 获取所有需要的DOM元素
    const elements = {
        species: {
            select: document.getElementById('species'),
            other: document.getElementById('otherSpecies')
        },
        breed: {
            select: document.getElementById('breed'),
            other: document.getElementById('otherBreed')
        },
        color: {
            select: document.getElementById('color'),
            other: document.getElementById('otherColor')
        },
        personality: {
            select: document.getElementById('personality'),
            other: document.getElementById('otherPersonality')
        },
        healthStatus: {
            checkboxes: document.querySelectorAll('input[type="checkbox"][name="healthStatus[]"]'),
            other: document.getElementById('otherHealthStatus')
        }
    };

    // 通用的切换"其他"输入框函数
    function toggleOtherInput(selectElement, otherInputElement) {
        const isOther = selectElement.value === 'other';
        otherInputElement.classList.toggle('d-none', !isOther);
        otherInputElement.required = isOther;
    }

    // 品种相关处理
    elements.species.select.addEventListener('change', function() {
        const selectedSpecies = this.value;
        elements.breed.select.innerHTML = '<option value="">Select Breed</option>';
        
        if (breeds[selectedSpecies]) {
            breeds[selectedSpecies].forEach(breed => {
                const option = document.createElement('option');
                option.value = breed.toLowerCase();
                option.textContent = breed;
                elements.breed.select.appendChild(option);
            });
        }
        
        toggleOtherInput(elements.species.select, elements.species.other);
    });

    // 品种变化处理
    elements.breed.select.addEventListener('change', function() {
        toggleOtherInput(elements.breed.select, elements.breed.other);
    });

    // 颜色处理
    elements.color.select.addEventListener('change', function() {
        toggleOtherInput(elements.color.select, elements.color.other);
    });

    // 性格处理
    elements.personality.select.addEventListener('change', function() {
        toggleOtherInput(elements.personality.select, elements.personality.other);
    });

    // 健康状态处理
    elements.healthStatus.checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.value === 'other') {
                elements.healthStatus.other.classList.toggle('d-none', !this.checked);
                elements.healthStatus.other.required = this.checked;
            }
        });
    });

    // 初始化状态
    if (elements.species.select.value) {
        elements.species.select.dispatchEvent(new Event('change'));
        
        const selectedBreed = elements.breed.select.dataset.selectedBreed;
        if (selectedBreed && elements.breed.select.querySelector(`option[value="${selectedBreed}"]`)) {
            elements.breed.select.value = selectedBreed;
        }
    }

    // 初始化其他字段状态
    if (elements.color.select.value === 'other') {
        toggleOtherInput(elements.color.select, elements.color.other);
    }
    if (elements.personality.select.value === 'other') {
        toggleOtherInput(elements.personality.select, elements.personality.other);
    }
});
</script>
@endsection