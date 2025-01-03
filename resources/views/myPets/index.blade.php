@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-2">
            {{-- 左侧过滤器 --}}
            <div class="list-group">
                <!-- <div class="list-group-item bg-danger text-white">
                    <i class="fas fa-filter me-2"></i>Filter Pets
                </div> -->
                
                <a href="{{ route('myPets.index') }}" 
                   class="list-group-item list-group-item-action {{ !request('species') ? 'active' : '' }}">
                    All Pets
                </a>
                
                <a href="{{ route('myPets.index', ['species' => 'dog']) }}" 
                   class="list-group-item list-group-item-action {{ request('species') === 'dog' ? 'active' : '' }}">
                    <i class="fas fa-dog me-2"></i>Dogs
                </a>
                
                <a href="{{ route('myPets.index', ['species' => 'cat']) }}" 
                   class="list-group-item list-group-item-action {{ request('species') === 'cat' ? 'active' : '' }}">
                    <i class="fas fa-cat me-2"></i>Cats
                </a>

                <a href="{{ route('myPets.index', ['species' => 'bird']) }}" 
                   class="list-group-item list-group-item-action {{ request('species') === 'bird' ? 'active' : '' }}">
                    <i class="fas fa-bird me-2"></i>Birds
                </a>
                
                <a href="{{ route('myPets.index', ['species' => 'other']) }}" 
                   class="list-group-item list-group-item-action {{ request('species') === 'other' ? 'active' : '' }}">
                    <i class="fas fa-paw me-2"></i>Other Pets
                </a>
            </div>
            <br>
            {{-- 搜索框 --}}
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title mb-3"><p>Search Pets</p></h6>
                    <form action="{{ route('myPets.index') }}" method="GET">
                        @if(request('species'))
                            <input type="hidden" name="species" value="{{ request('species') }}">
                        @endif
                        <div class="mb-3">
                            <input type="text" 
                                   class="form-control words" 
                                   name="search" 
                                   id="searchInput"
                                   placeholder="Search by name..."
                                   value="{{ request('search') }}"
                                   autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <select class="form-select words" name="breed" id="breedSelect">
                                <option value="" >All Breeds</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <select class="form-select words" name="age">
                                <option value="">All Ages</option>
                                <option value="0-1" {{ request('age') === '0-1' ? 'selected' : '' }}>Under 1 year</option>
                                <option value="1-3" {{ request('age') === '1-3' ? 'selected' : '' }}>1-3 years</option>
                                <option value="3+" {{ request('age') === '3+' ? 'selected' : '' }}>3+ years</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-danger w-100">Search</button>
                    </form>
                </div>
            </div>
            <br>
        </div>

        <div class="col-md-9">
            <div class="card border-0">
                <h5 class="title1 card-title">My Adopted Pets</h5>

                @if($myPets->isEmpty())
                    <div class="alert alert-info text-center">
                        <p class="mb-3">You have not adopted any pets yet.</p>
                        <a href="{{ route('showAdp') }}" class="btn btn-primary">
                            Adopt a Pet
                        </a>
                    </div>
                @else
                    @foreach($myPets as $pet)
                        <div class="card mb-4">
                            <div class="row g-0">
                                <div class="col-md-4">
                                    @php
                                        $photos = is_array($pet->pet_photos) ? $pet->pet_photos : json_decode($pet->pet_photos, true);
                                        $firstPhoto = $photos[0] ?? null;
                                    @endphp
                                    <div style="height: 250px; width: 100%; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa;">
                                        <img src="{{ $firstPhoto ? Storage::url($firstPhoto) : asset('images/default-pet.jpg') }}" 
                                             alt="{{ $pet->pet_name }}" 
                                             class="rounded-start"
                                             style="max-height: 250px; max-width: 100%; width: auto; height: auto; object-fit: contain;">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <strong><h5 class="card-title pet-title title1">{{ $pet->pet_name }}</h5></strong>
                                            <span class="badge bg-success">Adopted</span>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <p class="mb-1"><strong>Breed:</strong> {{ $pet->pet_breed }}</p>
                                                @if($pet->pet_age == 0)
                                                    <p class="mb-1"><strong>Age:</strong> Under 1 year</p>
                                                @elseif($pet->pet_age == 1)
                                                    <p class="mb-1"><strong>Age:</strong> 1 year</p>
                                                @else
                                                    <p class="mb-1"><strong>Age:</strong> {{ $pet->pet_age }} years</p>
                                                @endif
                                                <p class="mb-1"><strong>Gender:</strong> {{ ucfirst($pet->pet_gender) }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1"><strong>Size:</strong> {{ ucfirst($pet->pet_size) }}</p>
                                                <p class="mb-1"><strong>Color:</strong> {{ $pet->pet_color }}</p>
                                                <p class="mb-1"><strong>Adoption Date:</strong> {{ $pet->created_at->format('Y-m-d') }}</p>
                                            </div>
                                        </div>
                                        <div class="text-end mt-3">
                                            <a href="{{ route('adoptedPet.profile', $pet->id) }}" 
                                               class="btn btn-danger">
                                                View Details
                                            </a>
                                            <a href="{{ route('myPets.downloadQRCode', $pet->id) }}" 
                                               class="btn btn-outline-danger ms-2">
                                                <i class="fas fa-qrcode me-1"></i>
                                                Download QR Code
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="d-flex justify-content-end mt-4">
                        {{ $myPets->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.title1 {
    color: #dc3545;
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #dc3545;
}

.pet-title {
    color: #dc3545;
    font-size: 20px;
    font-weight: bold;
}

.card {
    transition: transform 0.2s;
    border: 1px solid rgba(0,0,0,.125);
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0,0,0,.1);
}

.badge {
    font-size: 0.8rem;
    padding: 0.5em 1em;
}

.list-group-item.active {
    background-color: #dc3545;
    border-color: #dc3545;
}

.list-group-item:hover:not(.active) {
    background-color: #f8d7da;
    color: #dc3545;
}

.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
}

.btn-danger:hover {
    background-color: #c82333;
    border-color: #bd2130;
}
</style>



<script>
const breeds = {
    dog: ['Labrador', 'Golden Retriever', 'Bulldog', 'Other'],
    cat: ['Persian', 'Siamese', 'Maine Coon', 'Other'],
    bird: ['Parrot', 'Canary', 'Finch', 'Other'],
    other: ['Other']
};

function updateBreeds() {
    const species = '{{ request('species') }}' || 'all';
    const currentBreed = '{{ request('breed') }}';
    const breedSelect = document.getElementById('breedSelect');
    
    // 清空现有选项
    breedSelect.innerHTML = '<option value="">All Breeds</option>';
    
    // 如果选择了特定物种，添加对应的品种
    if (species && species !== 'all' && breeds[species]) {
        breeds[species].forEach(breed => {
            const option = document.createElement('option');
            option.value = breed.toLowerCase();
            option.textContent = breed;
            option.selected = currentBreed === breed.toLowerCase();
            breedSelect.appendChild(option);
        });
    }
}

// 页面加载时更新品种列表
document.addEventListener('DOMContentLoaded', updateBreeds);

$(document).ready(function() {
    $("#searchInput").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: "{{ route('myPets.search') }}", // 需要创建这个路由
                dataType: "json",
                data: {
                    term: request.term,
                    species: $('input[name="species"]').val()
                },
                success: function(data) {
                    response(data);
                }
            });
        },
        minLength: 1
    });
});
</script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
@endsection