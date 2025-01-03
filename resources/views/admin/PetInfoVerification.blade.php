@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col title1">
            <h2>Pet Verification Management</h2>
        </div>
    </div>

    {{-- 过滤器部分 --}}
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.pets.verification') }}" method="GET" class="row g-3">
                <input type="hidden" name="status" value="{{ request()->get('status', 'unverified') }}">
                
                <div class="col-md-3">
                    <label class="form-label words">Species</label>
                    <select name="species" class="form-select words2">
                        <option value="">All Species</option>
                        <option value="dog" {{ request('species') === 'dog' ? 'selected' : '' }}>Dog</option>
                        <option value="cat" {{ request('species') === 'cat' ? 'selected' : '' }}>Cat</option>
                        <option value="bird" {{ request('species') === 'bird' ? 'selected' : '' }}>Bird</option>
                        <option value="other" {{ request('species') === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label words">Gender</label>
                    <select name="gender" class="form-select words2">
                        <option value="">All Genders</option>
                        <option value="male" {{ request('gender') === 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ request('gender') === 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label words">Size</label>
                    <select name="size" class="form-select words2">
                        <option value="">All Sizes</option>
                        <option value="small" {{ request('size') === 'small' ? 'selected' : '' }}>Small</option>
                        <option value="medium" {{ request('size') === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="large" {{ request('size') === 'large' ? 'selected' : '' }}>Large</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label words">Vaccinated</label>
                    <select name="vaccinated" class="form-select">
                        <option value="">All</option>
                        <option value="1" {{ request('vaccinated') === '1' ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ request('vaccinated') === '0' ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <div class="col-12">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-1"></i>Apply Filters
                        </button>
                        <a href="{{ route('admin.pets.verification', ['status' => request()->get('status', 'unverified')]) }}" 
                           class="btn btn-secondary">
                            <i class="fas fa-undo me-1"></i>Reset Filters
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Add Tab Navigation --}}
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link {{ request()->get('status', 'unverified') === 'unverified' ? 'active' : '' }}" 
               href="{{ route('admin.pets.verification', ['status' => 'unverified']) }}">
                Pending Verification
                @if($unverifiedCount > 0)
                    <span class="badge bg-danger ms-2">{{ $unverifiedCount }}</span>
                @endif
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->get('status') === 'verified' ? 'active' : '' }}" 
               href="{{ route('admin.pets.verification', ['status' => 'verified']) }}">
                Verified Pets
                @if($verifiedCount > 0)
                    <span class="badge bg-success ms-2">{{ $verifiedCount }}</span>
                @endif
            </a>
        </li>
    </ul>

    @if($pets->isEmpty())
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            @if(request()->get('status') === 'verified')
                No verified pets found.
            @else
                No pets pending verification at the moment.
            @endif
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($pets as $pet)
            <div class="col">
                <div class="card h-100">
                    @if($pet->photos && count($pet->photos) > 0)
                        <img src="{{ Storage::url($pet->photos[0]) }}" 
                            class="card-img-top" alt="Pet Photo"
                            style="height: 200px; object-fit: cover;">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $pet->name }}</h5>
                        <p class="card-text">
                            <small class="text-muted">Added by: {{ $pet->user->username }}</small>
                        </p>
                        <ul class="list-unstyled">
                            <li><strong>Species:</strong> {{ ucfirst($pet->species) }}</li>
                            <li><strong>Breed:</strong> {{ ucfirst($pet->breed) }}</li>
                            <li><strong>Age:</strong> @if ($pet->age < 1)
                                                        {{ __('Less than 1 year old') }}
                                                      @else
                                                        {{ $pet->age }}
                                                      @endif</li>
                            <li><strong>Gender:</strong> {{ ucfirst($pet->gender) }}</li>
                            <li><strong>Health Status:</strong> 
                                @foreach($pet->healthStatus as $status)
                                    <span class="badge bg-info">{{ $status }}</span>
                                @endforeach
                            </li>
                        </ul>
                        <p class="card-text">{{ Str::limit($pet->description, 100) }}</p>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <button type="button" class="btn btn-primary btn-sm" 
                                data-bs-toggle="modal" 
                                data-bs-target="#petModal{{ $pet->id }}">
                                View Details
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="petModal{{ $pet->id }}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $pet->name }} - Verification & Edit</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('pets.verify', $pet->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <div class="modal-body">
                                <div class="row">
                                    {{-- 左侧：图片和视频 --}}
                                    <div class="col-md-6">
                                        @if($pet->photos && count($pet->photos) > 0)
                                            <div id="petCarousel{{ $pet->id }}" class="carousel slide" data-bs-ride="carousel">
                                                <div class="carousel-inner">
                                                    @foreach($pet->photos as $index => $photo)
                                                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                            <img src="{{ Storage::url($photo) }}" 
                                                                class="d-block w-100" alt="Pet Photo"
                                                                style="height: 300px; object-fit: cover;">
                                                        </div>
                                                    @endforeach
                                                </div>
                                                @if(count($pet->photos) > 1)
                                                    <button class="carousel-control-prev" type="button" 
                                                        data-bs-target="#petCarousel{{ $pet->id }}" data-bs-slide="prev">
                                                        <span class="carousel-control-prev-icon"></span>
                                                    </button>
                                                    <button class="carousel-control-next" type="button" 
                                                        data-bs-target="#petCarousel{{ $pet->id }}" data-bs-slide="next">
                                                        <span class="carousel-control-next-icon"></span>
                                                    </button>
                                                @endif
                                            </div>
                                        @endif

                                        @if($pet->videos)
                                            @foreach($pet->videos as $video)
                                                <video controls class="w-100 mt-2">
                                                    <source src="{{ Storage::url($video) }}" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            @endforeach
                                        @endif

                                        {{-- 现有图片展示 --}}
                                        @if($pet->photos && count($pet->photos) > 0)
                                            <div class="mb-3">
                                                <label class="form-label">Current Photos</label>
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach($pet->photos as $index => $photo)
                                                    <div class="position-relative photo-container">
                                                        <img src="{{ Storage::url($photo) }}" 
                                                             class="img-thumbnail" alt="Pet Photo"
                                                             style="width: 100px; height: 100px; object-fit: cover;">
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
                                        @endif

                                        {{-- 添加新图片 --}}
                                        <div class="mb-3">
                                            <label class="form-label">Add New Photos</label>
                                            <input type="file" class="form-control" name="new_photos[]" 
                                                   multiple accept="image/*">
                                        </div>

                                        {{-- 现有视频展示 --}}
                                        @if($pet->videos && count($pet->videos) > 0)
                                            <div class="mb-3">
                                                <label class="form-label">Current Videos</label>
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
                                        @endif

                                        {{-- 添加新视频 --}}
                                        <div class="mb-3">
                                            <label class="form-label">Add New Videos</label>
                                            <input type="file" class="form-control" name="new_videos[]" 
                                                   multiple accept="video/*">
                                        </div>
                                    </div>

                                    {{-- 右侧：编辑表单 --}}
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Name</label>
                                            <input type="text" class="form-control" name="name" value="{{ $pet->name }}" required>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Age</label>
                                                    <input type="number" class="form-control" name="age" value="{{ $pet->age }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Gender</label>
                                                    <select class="form-control" name="gender" required>
                                                        <option value="male" {{ $pet->gender === 'male' ? 'selected' : '' }}>Male</option>
                                                        <option value="female" {{ $pet->gender === 'female' ? 'selected' : '' }}>Female</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Species</label>
                                                    <select id="species{{ $pet->id }}" class="form-control" name="species" required>
                                                        <option value="dog" {{ $pet->species === 'dog' ? 'selected' : '' }}>Dog</option>
                                                        <option value="cat" {{ $pet->species === 'cat' ? 'selected' : '' }}>Cat</option>
                                                        <option value="bird" {{ $pet->species === 'bird' ? 'selected' : '' }}>Bird</option>
                                                        <option value="other" {{ !in_array($pet->species, ['dog', 'cat', 'bird']) ? 'selected' : '' }}>Other</option>
                                                    </select>
                                                    <input type="text" id="otherSpecies{{ $pet->id }}" 
                                                        class="form-control mt-2 {{ in_array($pet->species, ['dog', 'cat', 'bird']) ? 'd-none' : '' }}" 
                                                        name="other_species" placeholder="Please specify"
                                                        value="{{ !in_array($pet->species, ['dog', 'cat', 'bird']) ? $pet->species : '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Breed</label>
                                                    <select id="breed{{ $pet->id }}" class="form-control" name="breed" required
                                                        data-selected-breed="{{ $pet->breed }}">
                                                    </select>
                                                    <input type="text" id="otherBreed{{ $pet->id }}" 
                                                        class="form-control mt-2 {{ in_array($pet->breed, ['labrador', 'golden retriever', 'bulldog', 'persian', 'siamese', 'maine coon', 'parrot', 'canary', 'finch']) ? 'd-none' : '' }}" 
                                                        name="other_breed" placeholder="Please specify"
                                                        value="{{ !in_array($pet->breed, ['labrador', 'golden retriever', 'bulldog', 'persian', 'siamese', 'maine coon', 'parrot', 'canary', 'finch']) ? $pet->breed : '' }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Color</label>
                                                    <select class="form-control" name="color" required>
                                                        <option value="black" {{ $pet->color === 'black' ? 'selected' : '' }}>Black</option>
                                                        <option value="white" {{ $pet->color === 'white' ? 'selected' : '' }}>White</option>
                                                        <option value="brown" {{ $pet->color === 'brown' ? 'selected' : '' }}>Brown</option>
                                                        <option value="other" {{ !in_array($pet->color, ['black', 'white', 'brown']) ? 'selected' : '' }}>Other</option>
                                                    </select>
                                                    <input type="text" id="otherColor{{ $pet->id }}" 
                                                        class="form-control mt-2 {{ in_array($pet->color, ['black', 'white', 'brown']) ? 'd-none' : '' }}" 
                                                        name="other_color" placeholder="Please specify"
                                                        value="{{ !in_array($pet->color, ['black', 'white', 'brown']) ? $pet->color : '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Size</label>
                                                    <select class="form-control" name="size" required>
                                                        <option value="small" {{ $pet->size === 'small' ? 'selected' : '' }}>Small</option>
                                                        <option value="medium" {{ $pet->size === 'medium' ? 'selected' : '' }}>Medium</option>
                                                        <option value="large" {{ $pet->size === 'large' ? 'selected' : '' }}>Large</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Vaccinated</label>
                                                    <select class="form-control" name="vaccinated" required>
                                                        <option value="1" {{ $pet->vaccinated ? 'selected' : '' }}>Yes</option>
                                                        <option value="0" {{ !$pet->vaccinated ? 'selected' : '' }}>No</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Health Status</label>
                                                    
                                                    {{-- Standard Health Status Options --}}
                                                    @php
                                                        $standardStatuses = ['healthy', 'injured', 'sick'];
                                                        $healthStatus = is_array($pet->healthStatus) ? $pet->healthStatus : [];
                                                        $otherStatuses = array_diff($healthStatus, $standardStatuses);
                                                    @endphp

                                                    {{-- Standard Options --}}
                                                    @foreach($standardStatuses as $status)
                                                        <div class="form-check">
                                                            <input class="form-check-input health-status-checkbox" 
                                                                   type="checkbox" 
                                                                   name="healthStatus[]" 
                                                                   value="{{ $status }}"
                                                                   {{ in_array($status, $healthStatus) ? 'checked' : '' }}>
                                                            <label class="form-check-label">{{ ucfirst($status) }}</label>
                                                        </div>
                                                    @endforeach

                                                    {{-- Other Option --}}
                                                    <div class="form-check">
                                                        <input class="form-check-input health-status-checkbox" 
                                                               type="checkbox" 
                                                               name="healthStatus[]" 
                                                               value="other"
                                                               id="otherHealthCheckbox{{ $pet->id }}"
                                                               {{ !empty($otherStatuses) ? 'checked' : '' }}>
                                                        <label class="form-check-label">Other</label>
                                                    </div>

                                                    {{-- Other Status Input Field --}}
                                                    <input type="text" 
                                                           id="otherHealthStatus{{ $pet->id }}" 
                                                           class="form-control mt-2 {{ empty($otherStatuses) ? 'd-none' : '' }}" 
                                                           name="other_health_status" 
                                                           value="{{ implode(', ', $otherStatuses) }}"
                                                           placeholder="Please specify other health conditions">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Personality</label>
                                            <select class="form-control" name="personality" required>
                                                <option value="friendly" {{ $pet->personality === 'friendly' ? 'selected' : '' }}>Friendly</option>
                                                <option value="aggressive" {{ $pet->personality === 'aggressive' ? 'selected' : '' }}>Aggressive</option>
                                                <option value="shy" {{ $pet->personality === 'shy' ? 'selected' : '' }}>Shy</option>
                                                <option value="other" {{ $pet->personality === 'other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            <input type="text" id="otherPersonality{{ $pet->id }}" 
                                                class="form-control mt-2 {{ $pet->personality !== 'other' ? 'd-none' : '' }}" 
                                                name="other_personality" placeholder="Please specify"
                                                value="{{ $pet->personality === 'other' ? $pet->personality : '' }}">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Description</label>
                                            <textarea class="form-control" name="description" rows="3" required>{{ $pet->description }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check me-1"></i>Verify & Update
                                </button>
                                <button type="button" class="btn btn-danger" 
                                    onclick="document.getElementById('rejectForm{{ $pet->id }}').submit();">
                                    <i class="fas fa-times me-1"></i>Reject
                                </button>
                            </div>
                        </form>

                        {{-- 拒绝表单 --}}
                        <form id="rejectForm{{ $pet->id }}" 
                            action="{{ route('pets.reject', $pet->id) }}" 
                            method="POST" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Add Pagination Links --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $pets->appends(['status' => request()->get('status', 'unverified')])->links() }}
        </div>

        {{-- JavaScript 代码移到这里 --}}
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 为每个宠物的模态框设置事件监听
            @foreach($pets as $pet)
                setupModalHandlers('{{ $pet->id }}');

                // 为每个宠物设置健康状态复选框监听器
                const otherCheckbox = document.getElementById('otherHealthCheckbox{{ $pet->id }}');
                const otherInput = document.getElementById('otherHealthStatus{{ $pet->id }}');

                if (otherCheckbox && otherInput) {
                    otherCheckbox.addEventListener('change', function() {
                        otherInput.classList.toggle('d-none', !this.checked);
                        if (this.checked) {
                            otherInput.focus();
                            otherInput.required = true;
                        } else {
                            otherInput.required = false;
                            otherInput.value = '';
                        }
                    });

                    // 验证至少选择一个健康状态
                    const form = otherCheckbox.closest('form');
                    form.addEventListener('submit', function(e) {
                        const checkboxes = form.querySelectorAll('.health-status-checkbox:checked');
                        if (checkboxes.length === 0) {
                            e.preventDefault();
                            alert('请至少选择一个健康状态！\nPlease select at least one health status!');
                        }
                    });
                }
            @endforeach
        });

        // ... rest of the JavaScript code ...
        </script>
    @endif
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 为每个宠物的模态框设置事件监听
    @foreach($pets as $pet)
        setupModalHandlers('{{ $pet->id }}');
    @endforeach

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

function setupModalHandlers(petId) {
    // 获取当前宠物的表单元素
    const speciesSelect = document.getElementById(`species${petId}`);
    const breedSelect = document.getElementById(`breed${petId}`);
    const otherSpeciesInput = document.getElementById(`otherSpecies${petId}`);
    const otherBreedInput = document.getElementById(`otherBreed${petId}`);
    const otherColorInput = document.getElementById(`otherColor${petId}`);
    const otherHealthStatusInput = document.getElementById(`otherHealthStatus${petId}`);
    const otherPersonalityInput = document.getElementById(`otherPersonality${petId}`);

    // 品种数据
    const breeds = {
        dog: ['Labrador', 'Golden Retriever', 'Bulldog', 'Other'],
        cat: ['Persian', 'Siamese', 'Maine Coon', 'Other'],
        bird: ['Parrot', 'Canary', 'Finch', 'Other'],
        other: ['Other']
    };

    // 物种变化时更新品种选项
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

    // 品种变化时处理"其他"选项
    breedSelect.addEventListener('change', function() {
        toggleOtherInput(breedSelect, otherBreedInput);
    });

    // 处理所有带"其他"选项的选择框
    const modal = document.getElementById(`petModal${petId}`);
    modal.querySelectorAll('select').forEach(function(select) {
        select.addEventListener('change', function() {
            switch(this.name) {
                case 'color':
                    toggleOtherInput(this, otherColorInput);
                    break;
                case 'personality':
                    toggleOtherInput(this, otherPersonalityInput);
                    break;
            }
        });
    });

    // 处理健康状态复选框
    modal.querySelectorAll('input[type="checkbox"][name="healthStatus[]"]').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            if (this.value === 'other') {
                otherHealthStatusInput.classList.toggle('d-none', !this.checked);
            }
        });
    });

    // 初始化品种选项（如果有选择）
    if (speciesSelect.value) {
        const event = new Event('change');
        speciesSelect.dispatchEvent(event);
        
        // 如果有预选的品种，选中它
        if (breedSelect.querySelector(`option[value="${breedSelect.dataset.selectedBreed}"]`)) {
            breedSelect.value = breedSelect.dataset.selectedBreed;
        }
    }
}

// 切换"其他"输入框的显示/隐藏
function toggleOtherInput(selectElement, otherInputElement) {
    if (selectElement.value === 'other') {
        otherInputElement.classList.remove('d-none');
        otherInputElement.required = true;
    } else {
        otherInputElement.classList.add('d-none');
        otherInputElement.required = false;
    }
}
</script>