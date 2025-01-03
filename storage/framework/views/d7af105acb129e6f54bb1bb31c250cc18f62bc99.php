<?php $__env->startSection('content'); ?>

    <div class="container-fluid">
      <div class="row" style="margin-top: 10px;">
        <div class="col-md-3"></div>
        
        <div class="col-md-6">
          <div class="card border-0">
            <h5 class="title1 card-title"><?php echo e(Auth::user()->username); ?>'s Profile</h5>
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-body profile-card"> <!--Change 头像-->
                    <h5 class="pet-title"style="padding-bottom: 13px;">Avatar:</h5>
                    <div class="text-center mb-4">
                        <?php if(Auth::user()->avatar): ?>
                            <img src="<?php echo e(Storage::url(Auth::user()->avatar)); ?>" 
                                alt="Profile Picture" 
                                style="width: 350px; height: 350px; object-fit: cover; border-radius: 18px;">
                        <?php else: ?>
                            <img src="<?php echo e(asset('images/image1.png')); ?>" 
                                alt="Default Profile Picture" 
                                style="width: 350px; height: 350px; object-fit: cover; border-radius: 18px;">
                        <?php endif; ?>
                    </div>
                    <a class="btn btn-danger btn-xs" style="float: right;" href="<?php echo e(route('updateAvatar')); ?>"><?php echo e(__('Update')); ?></a>
                  </div>

                  <div class="card-body profile-card"><!--Username-->
                    <?php if(session('success')): ?>
                        <div class="alert alert-success">
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>

                    <?php if($errors->has('username')): ?>
                        <div class="alert alert-danger">
                            <?php echo e($errors->first('username')); ?>

                        </div>
                    <?php endif; ?>

                    <span class="pet-title h5" style="padding-bottom: 13px;">Username: <?php echo e(Auth::user()->username); ?></span>
                    <button class="btn btn-danger btn-xs" style="float: right;" type="button" onclick="openModal()">Update</button>

                    <div id="updateUsernameModal" style="display: none;">
                      <div class="modal-content">
                          <span onclick="closeModal()" class="close">&times;</span>
                          <h5 class="">Update Username</h5>
                          <form id="updateUsernameForm" action="<?php echo e(route('profile.updateUsername')); ?>" method="POST">
                              <?php echo csrf_field(); ?>
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
                      First Name: <?php echo e(Auth::user()->firstName); ?> <br>
                      Last Name: <?php echo e(Auth::user()->lastName); ?> <br>
                    </span>
                    <span class="pet-title h5" style="padding-bottom: 13px;">
                      Account Creation Date: <?php echo e(Auth::user()->created_at->format('d M Y, g : i A')); ?> <br>
                    </span>
                  </div>

                  <div class="card-body profile-card"><!--password-->
                    <?php if(session('successChangePassword')): ?>
                      <div class="alert alert-success">
                        <?php echo e(session('successChangePassword')); ?>

                      </div>
                    <?php endif; ?>

                    <?php if($errors->has('current_password')): ?>
                      <div class="alert alert-danger">
                        <?php echo e($errors->first('current_password')); ?>

                      </div>
                    <?php endif; ?>
    
                    <span class="pet-title h5" style="padding-bottom: 13px;">
                     Change New Password 
                    </span>
                    <button class="btn btn-danger btn-xs" style="float: right;" type="button" onclick="openModal1()">Update</button>

                    <div id="updatePasswordModal" style="display: none;">
                      <div class="modal-content">
                        <span onclick="closeModal1()" class="close">&times;</span>
                        <h5 class="">Change New Password</h5>
                        <form action="<?php echo e(route('user.updatePassword')); ?>" method="POST">
                          <?php echo csrf_field(); ?>

                          <div style="margin-bottom:10px;">
                              <label for="current_password" style="width: 200px;">Current Password: </label>
                              <input type="password" id="current_password" name="current_password" class="newUsername" required>
                              <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger"><?php echo e($message); ?></div>
                              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                          </div>

                          <div style="margin-bottom:10px;">
                              <label for="new_password" style="width: 200px;">New Password: </label>
                              <input type="password" id="new_password" name="new_password" class="newUsername" required>
                              <?php $__errorArgs = ['new_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                  <div class="text-danger"><?php echo e($message); ?></div>
                              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                          </div>

                          <div style="margin-bottom:10px;">
                              <label for="new_password_confirmation" style="width: 200px;">Confirm New Password: </label>
                              <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="newUsername"required>
                              <?php $__errorArgs = ['new_password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                  <div class="text-danger"><?php echo e($message); ?></div>
                              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                          </div>

                          <button type="submit" class="btn btn-danger btn-xs" style="float: right;">Update</button>
                          <?php if($errors->has('current_password') || $errors->has('new_password') || $errors->has('new_password_confirmation')): ?>
                              <script>
                                  openModal1();
                              </script>
                          <?php endif; ?>
                        </form>  
                      </div>
                    </div>
                  </div>
                  
                  <div class="card-body profile-card">
                    <?php if(session('successUpdateAddress')): ?>
                      <div class="alert alert-success">
                        <?php echo e(session('successUpdateAddress')); ?>

                      </div>
                    <?php endif; ?>

                    <?php if($errors->has('address')): ?>
                      <div class="alert alert-danger">
                        <?php echo e($errors->first('address')); ?>

                      </div>
                    <?php endif; ?>

                    <div style="margin-bottom:10px;">
                      <span class="pet-title h5" style="padding-bottom: 13px;">
                        Address: <br> <?php echo e(Auth::user()->address); ?> 
                      </span>
                    </div>
                    <button class="btn btn-danger btn-xs" style="float: right;" type="button" onclick="openModal2()">Update</button>

                    <div id="updateAddressModal" style="display: none;">
                      <div class="modal-content">
                          <span onclick="closeModal2()" class="close">&times;</span>
                          <h5>Update Address</h5>
                          <form action="<?php echo e(route('profile.updateAddress')); ?>" method="POST">
                              <?php echo csrf_field(); ?>
                              <div class="form-group">
                                  <label for="address" class="h6">New Address:</label>
                                  <input type="text" 
                                         id="address" 
                                         name="address" 
                                         class="form-control" 
                                         value="<?php echo e(old('address', Auth::user()->address)); ?>"
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

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\CourseTools\Laragon\laragon\www\BTPR2\resources\views/common/userProfile.blade.php ENDPATH**/ ?>