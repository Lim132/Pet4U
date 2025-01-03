<?php $__env->startSection('content'); ?>
<div class="container">
    <h2>Change Avatar</h2>

    <!-- Display success message -->
    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <!-- Profile form -->
    <form action="<?php echo e(route('profile.updateAvatar')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div class="form-group">
            <label for="avatar">Upload New Avatar</label>
            <input type="file" name="avatar" id="avatar" accept="image/*" required class="form-control">
            <?php $__errorArgs = ['avatar'];
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

        <button type="submit" class="btn btn-primary mt-3">Update Avatar</button>
    </form>


    <hr>

    <!-- Display current avatar -->
    <h4>Current Avatar</h4>
    <?php if(auth()->user()->avatar): ?>
        <img src="<?php echo e(Storage::url(auth()->user()->avatar)); ?>" alt="User Avatar" style="width: 300px; height: 300px; border-radius: 13px;">
    <?php else: ?>
        <img src="<?php echo e(asset('images/image1.png')); ?>" alt="Default Avatar" style="width: 300px; height: 300px; border-radius: 13px;">
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\CourseTools\Laragon\laragon\www\BTPR2\resources\views/common/editAvatar.blade.php ENDPATH**/ ?>