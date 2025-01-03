@extends('layouts.app')

@section('content')

    <div class="container-fluid">
      <div class="row" style="margin-top: 10px;">
        <div class="col-md-3"></div>
        
        <div class="col-md-6">
          <div class="card border-0">
            <h5 class="title1 card-title">{{ Auth::user()->username }}'s Profile</h5>
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-body profile-card"> <!--Change 头像-->
                    <h5 class="pet-title"style="padding-bottom: 13px;">Avatar:</h5>
                    <div class="text-center mb-4">
                        @if(Auth::user()->avatar)
                            <img src="{{ Storage::url(Auth::user()->avatar) }}" 
                                alt="Profile Picture" 
                                style="width: 350px; height: 350px; object-fit: cover; border-radius: 18px;">
                        @else
                            <img src="{{ asset('images/image1.png') }}" 
                                alt="Default Profile Picture" 
                                style="width: 350px; height: 350px; object-fit: cover; border-radius: 18px;">
                        @endif
                    </div>
                    <a class="btn btn-danger btn-xs" style="float: right;" href="{{ route('updateAvatar') }}">{{ __('Update') }}</a>
                  </div>

                  <div class="card-body profile-card"><!--Username-->
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->has('username'))
                        <div class="alert alert-danger">
                            {{ $errors->first('username') }}
                        </div>
                    @endif

                    <span class="pet-title h5" style="padding-bottom: 13px;">Username: {{ Auth::user()->username }}</span>
                    <button class="btn btn-danger btn-xs" style="float: right;" type="button" onclick="openModal()">Update</button>

                    <div id="updateUsernameModal" style="display: none;">
                      <div class="modal-content">
                          <span onclick="closeModal()" class="close">&times;</span>
                          <h5 class="">Update Username</h5>
                          <form id="updateUsernameForm" action="{{ route('profile.updateUsername') }}" method="POST">
                              @csrf
                              <label for="username" class="h6">New Username: &nbsp;&nbsp;&nbsp;</label>
                              <input type="text" id="username" name="username" class="newUsername"required>
                              <button type="submit" class="btn btn-danger btn-xs" style="float: right;">Update</button>
                          </form>
                      </div>
                    </div>
                  </div>
                </div><br>

                <div class="card"><!--User's Information-->
                  <div class="card-body profile-card">
                    <span class="pet-title h5" style="padding-bottom: 13px;">
                      First Name: {{ Auth::user()->firstName}} <br>
                      Last Name: {{ Auth::user()->lastName}} <br>
                    </span>
                    <span class="pet-title h5" style="padding-bottom: 13px;">
                      Account Creation Date: {{ Auth::user()->created_at->format('d M Y, g : i A') }} <br>
                    </span>
                  </div>

                  <div class="card-body profile-card"><!--password-->
                    @if(session('successChangePassword'))
                      <div class="alert alert-success">
                        {{ session('successChangePassword') }}
                      </div>
                    @endif

                    @if ($errors->has('current_password'))
                      <div class="alert alert-danger">
                        {{ $errors->first('current_password') }}
                      </div>
                    @endif
    
                    <span class="pet-title h5" style="padding-bottom: 13px;">
                     Change New Password 
                    </span>
                    <button class="btn btn-danger btn-xs" style="float: right;" type="button" onclick="openModal1()">Update</button>

                    <div id="updatePasswordModal" style="display: none;">
                      <div class="modal-content">
                        <span onclick="closeModal1()" class="close">&times;</span>
                        <h5 class="">Change New Password</h5>
                        <form action="{{ route('user.updatePassword') }}" method="POST">
                          @csrf

                          <div style="margin-bottom:10px;">
                              <label for="current_password" style="width: 200px;">Current Password: </label>
                              <input type="password" id="current_password" name="current_password" class="newUsername" required>
                              @error('current_password')
                                <div class="text-danger">{{ $message }}</div>
                              @enderror
                          </div>

                          <div style="margin-bottom:10px;">
                              <label for="new_password" style="width: 200px;">New Password: </label>
                              <input type="password" id="new_password" name="new_password" class="newUsername" required>
                              @error('new_password')
                                  <div class="text-danger">{{ $message }}</div>
                              @enderror
                          </div>

                          <div style="margin-bottom:10px;">
                              <label for="new_password_confirmation" style="width: 200px;">Confirm New Password: </label>
                              <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="newUsername"required>
                              @error('new_password_confirmation')
                                  <div class="text-danger">{{ $message }}</div>
                              @enderror
                          </div>

                          <button type="submit" class="btn btn-danger btn-xs" style="float: right;">Update</button>
                          @if ($errors->has('current_password') || $errors->has('new_password') || $errors->has('new_password_confirmation'))
                              <script>
                                  openModal1();
                              </script>
                          @endif
                        </form>  
                      </div>
                    </div>
                  </div>
                  
                  <div class="card-body profile-card">
                    @if(session('successUpdateAddress'))
                      <div class="alert alert-success">
                        {{ session('successUpdateAddress') }}
                      </div>
                    @endif

                    @if($errors->has('address'))
                      <div class="alert alert-danger">
                        {{ $errors->first('address') }}
                      </div>
                    @endif

                    <div style="margin-bottom:10px;">
                      <span class="pet-title h5" style="padding-bottom: 13px;">
                        Address: <br> {{ Auth::user()->address}} 
                      </span>
                    </div>
                    <button class="btn btn-danger btn-xs" style="float: right;" type="button" onclick="openModal2()">Update</button>

                    <div id="updateAddressModal" style="display: none;">
                      <div class="modal-content">
                          <span onclick="closeModal2()" class="close">&times;</span>
                          <h5>Update Address</h5>
                          <form action="{{ route('profile.updateAddress') }}" method="POST">
                              @csrf
                              <div class="form-group">
                                  <label for="address" class="h6">New Address:</label>
                                  <input type="text" 
                                         id="address" 
                                         name="address" 
                                         class="form-control" 
                                         value="{{ old('address', Auth::user()->address) }}"
                                         required>
                              </div>
                              <button type="submit" class="btn btn-danger btn-xs">Update</button>
                          </form>
                      </div>
                    </div>
                  </div>
                </div><br>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-3"></div>
      </div>
    </div>
    <br>

@endsection

