@extends('layouts.app')

@section('content')
<div class="container">
    @include('common.alerts')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="text-orange h4">{{ __('Your Added Pets') }}</span>
            <a href="{{ route('pets.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>{{ __('Add New Pet') }}
            </a>
        </div>
        <div class="card-body">
            @if($pets->isEmpty())
                <div class="text-center py-4">
                    <i class="fas fa-paw fa-3x text-muted mb-3"></i>
                    <p class="text-muted">{{ __('You haven\'t added any pets yet.') }}</p>
                </div>
            @else
                <div class="row">
                    @foreach($pets as $pet)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100">
                                @if($pet->photos && count($pet->photos) > 0)
                                    <img src="{{ Storage::url($pet->photos[0]) }}" 
                                         class="card-img-top" alt="{{ $pet->name }}"
                                         style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                         style="height: 200px;">
                                        <i class="fas fa-paw fa-3x text-muted"></i>
                                    </div>
                                @endif
                                
                                <div class="card-body">
                                    <h5 class="card-title d-flex justify-content-between align-items-center">
                                        {{ $pet->name }}
                                        @if(!$pet->verified)
                                            <span class="badge bg-warning">{{ __('Pending Verification') }}</span>
                                        @endif
                                    </h5>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>{{ $pet->species }} - {{ $pet->breed }}
                                        </small>
                                    </p>
                                    <p class="card-text">
                                        @if($pet->age == 0)
                                            <i class="fas fa-birthday-cake me-1"></i>{{ __('Age') }}: {{ __('Under 1 year old') }}
                                        @elseif($pet->age == 1)
                                            <i class="fas fa-birthday-cake me-1"></i>{{ __('Age') }}: {{ __('1 year old') }}
                                        @else($pet->age > 1)
                                            <i class="fas fa-birthday-cake me-1"></i>{{ __('Age') }}: {{ __($pet->age . ' years old') }}
                                        @endif
                                        <br>
                                        <i class="fas fa-venus-mars me-1"></i>{{ __('Gender') }}: {{ __(ucfirst($pet->gender)) }}
                                    </p>
                                    
                                    <div class="mt-3">
                                        <a href="{{ route('pets.show', $pet) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>{{ __('View Details') }}
                                        </a> &nbsp;
                                        <a href="{{ route('pets.edit', $pet) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-edit me-1"></i>{{ __('Edit') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $pets->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection