@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row" style="margin-top: 10px;">
        {{-- 左侧分类 --}}
        <div class="col-md-2">
            <div class="list-group">
                <a href="{{ route('showAdp') }}" 
                    class="list-group-item list-group-item-action {{ !request('species') ? 'active' : '' }}">
                    All Categories
                </a>
                <a href="{{ route('showAdp', ['species' => 'cat']) }}" 
                    class="list-group-item list-group-item-action {{ request('species') === 'cat' ? 'active' : '' }}">
                    Cat
                </a>
                <a href="{{ route('showAdp', ['species' => 'dog']) }}" 
                    class="list-group-item list-group-item-action {{ request('species') === 'dog' ? 'active' : '' }}">
                    Dog
                </a>
                <a href="{{ route('showAdp', ['species' => 'bird']) }}" 
                    class="list-group-item list-group-item-action {{ request('species') === 'bird' ? 'active' : '' }}">
                    Bird
                </a>
                <a href="{{ route('showAdp', ['species' => 'other']) }}" 
                    class="list-group-item list-group-item-action {{ request('species') === 'other' ? 'active' : '' }}">
                    Other
                </a>
            </div>
            <br>
            {{-- 搜索框 --}}
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title mb-3"><p>Search Pets</p></h6>
                    <form action="{{ route('showAdp') }}" method="GET">
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

        <div class="col-md-1"></div>

        {{-- 宠物列表 --}}
        <div class="col-md-8">
            <div class="card border-0">
                <h5 class="title1 card-title">Pets for Adoption</h5>
                <div class="row">
                    @forelse($pets as $pet)
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="card-title pet-title pet-title2 col-12">{{ $pet->name }}</div>
                                    <div class="pet-img">
                                        @if($pet->photos && count($pet->photos) > 0)
                                            <img src="{{ Storage::url($pet->photos[0]) }}" 
                                                class="pet-img" 
                                                alt="{{ $pet->name }}"
                                                style="height: 200px; object-fit: cover;">
                                        @endif
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <div class="card-heading words2" style="padding-left: 10%; padding-right: 10%;">
                                                <table class="mb-3">
                                                    <tr>
                                                        <td style="width: 80px;"><strong>Age:</strong></td>
                                                        @if($pet->age == 0)
                                                            <td>Under 1 year</td>
                                                        @elseif($pet->age == 1)
                                                            <td>1 year</td>
                                                        @else
                                                            <td>{{ $pet->age }} years</td>
                                                        @endif
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Species:</strong></td>
                                                        <td>{{ ucfirst($pet->species) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Breed:</strong></td>
                                                        <td>{{ ucfirst($pet->breed) }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-12 text-center">
                                            <a href="{{ route('pets.show', $pet->id) }}" 
                                                class="btn btn-danger btn-sm">
                                                See Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info">
                                No pets available for adoption at the moment.
                            </div>
                        </div>
                    @endforelse
                </div>

                {{-- 分页 --}}
                <div class="d-flex justify-content-end">
                    {{ $pets->links() }}
                </div>
            </div>
        </div>
        <div class="col-md-1"></div>
    </div>
</div>

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
                url: "{{ route('search') }}", // 需要创建这个路由
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