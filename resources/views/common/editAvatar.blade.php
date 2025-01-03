@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Change Avatar</h2>

    <!-- Display success message -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Profile form -->
    <form action="{{ route('profile.updateAvatar') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="avatar">Upload New Avatar</label>
            <input type="file" name="avatar" id="avatar" accept="image/*" required class="form-control">
            @error('avatar')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary mt-3">Update Avatar</button>
    </form>


    <hr>

    <!-- Display current avatar -->
    <h4>Current Avatar</h4>
    @if(auth()->user()->avatar)
        <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="User Avatar" style="width: 300px; height: 300px; border-radius: 13px;">
    @else
        <img src="{{ asset('images/image1.png') }}" alt="Default Avatar" style="width: 300px; height: 300px; border-radius: 13px;">
    @endif

</div>
@endsection
