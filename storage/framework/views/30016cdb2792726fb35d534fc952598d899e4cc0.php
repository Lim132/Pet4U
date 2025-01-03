

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row mb-4">
        <div class="col title1">
            <h2>Pet Verification Management</h2>
        </div>
    </div>

    
    <div class="card mb-4">
        <div class="card-body">
            <form action="<?php echo e(route('admin.pets.verification')); ?>" method="GET" class="row g-3">
                <input type="hidden" name="status" value="<?php echo e(request()->get('status', 'unverified')); ?>">
                
                <div class="col-md-3">
                    <label class="form-label words">Species</label>
                    <select name="species" class="form-select words2">
                        <option value="">All Species</option>
                        <option value="dog" <?php echo e(request('species') === 'dog' ? 'selected' : ''); ?>>Dog</option>
                        <option value="cat" <?php echo e(request('species') === 'cat' ? 'selected' : ''); ?>>Cat</option>
                        <option value="bird" <?php echo e(request('species') === 'bird' ? 'selected' : ''); ?>>Bird</option>
                        <option value="other" <?php echo e(request('species') === 'other' ? 'selected' : ''); ?>>Other</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label words">Gender</label>
                    <select name="gender" class="form-select words2">
                        <option value="">All Genders</option>
                        <option value="male" <?php echo e(request('gender') === 'male' ? 'selected' : ''); ?>>Male</option>
                        <option value="female" <?php echo e(request('gender') === 'female' ? 'selected' : ''); ?>>Female</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label words">Size</label>
                    <select name="size" class="form-select words2">
                        <option value="">All Sizes</option>
                        <option value="small" <?php echo e(request('size') === 'small' ? 'selected' : ''); ?>>Small</option>
                        <option value="medium" <?php echo e(request('size') === 'medium' ? 'selected' : ''); ?>>Medium</option>
                        <option value="large" <?php echo e(request('size') === 'large' ? 'selected' : ''); ?>>Large</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label words">Vaccinated</label>
                    <select name="vaccinated" class="form-select">
                        <option value="">All</option>
                        <option value="1" <?php echo e(request('vaccinated') === '1' ? 'selected' : ''); ?>>Yes</option>
                        <option value="0" <?php echo e(request('vaccinated') === '0' ? 'selected' : ''); ?>>No</option>
                    </select>
                </div>

                <div class="col-12">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-1"></i>Apply Filters
                        </button>
                        <a href="<?php echo e(route('admin.pets.verification', ['status' => request()->get('status', 'unverified')])); ?>" 
                           class="btn btn-secondary">
                            <i class="fas fa-undo me-1"></i>Reset Filters
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link <?php echo e(request()->get('status', 'unverified') === 'unverified' ? 'active' : ''); ?>" 
               href="<?php echo e(route('admin.pets.verification', ['status' => 'unverified'])); ?>">
                Pending Verification
                <?php if($unverifiedCount > 0): ?>
                    <span class="badge bg-danger ms-2"><?php echo e($unverifiedCount); ?></span>
                <?php endif; ?>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo e(request()->get('status') === 'verified' ? 'active' : ''); ?>" 
               href="<?php echo e(route('admin.pets.verification', ['status' => 'verified'])); ?>">
                Verified Pets
                <?php if($verifiedCount > 0): ?>
                    <span class="badge bg-success ms-2"><?php echo e($verifiedCount); ?></span>
                <?php endif; ?>
            </a>
        </li>
    </ul>

    <?php if($pets->isEmpty()): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            <?php if(request()->get('status') === 'verified'): ?>
                No verified pets found.
            <?php else: ?>
                No pets pending verification at the moment.
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php $__currentLoopData = $pets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col">
                <div class="card h-100">
                    <?php if($pet->photos && count($pet->photos) > 0): ?>
                        <img src="<?php echo e(Storage::url($pet->photos[0])); ?>" 
                            class="card-img-top" alt="Pet Photo"
                            style="height: 200px; object-fit: cover;">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo e($pet->name); ?></h5>
                        <p class="card-text">
                            <small class="text-muted">Added by: <?php echo e($pet->user->username); ?></small>
                        </p>
                        <ul class="list-unstyled">
                            <li><strong>Species:</strong> <?php echo e(ucfirst($pet->species)); ?></li>
                            <li><strong>Breed:</strong> <?php echo e(ucfirst($pet->breed)); ?></li>
                            <li><strong>Age:</strong> <?php if($pet->age < 1): ?>
                                                        <?php echo e(__('Less than 1 year old')); ?>

                                                      <?php else: ?>
                                                        <?php echo e($pet->age); ?>

                                                      <?php endif; ?></li>
                            <li><strong>Gender:</strong> <?php echo e(ucfirst($pet->gender)); ?></li>
                            <li><strong>Health Status:</strong> 
                                <?php $__currentLoopData = $pet->healthStatus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="badge bg-info"><?php echo e($status); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </li>
                        </ul>
                        <p class="card-text"><?php echo e(Str::limit($pet->description, 100)); ?></p>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <button type="button" class="btn btn-primary btn-sm" 
                                data-bs-toggle="modal" 
                                data-bs-target="#petModal<?php echo e($pet->id); ?>">
                                View Details
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="petModal<?php echo e($pet->id); ?>" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><?php echo e($pet->name); ?> - Verification & Edit</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="<?php echo e(route('pets.verify', $pet->id)); ?>" method="POST" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PATCH'); ?>
                            <div class="modal-body">
                                <div class="row">
                                    
                                    <div class="col-md-6">
                                        <?php if($pet->photos && count($pet->photos) > 0): ?>
                                            <div id="petCarousel<?php echo e($pet->id); ?>" class="carousel slide" data-bs-ride="carousel">
                                                <div class="carousel-inner">
                                                    <?php $__currentLoopData = $pet->photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div class="carousel-item <?php echo e($index === 0 ? 'active' : ''); ?>">
                                                            <img src="<?php echo e(Storage::url($photo)); ?>" 
                                                                class="d-block w-100" alt="Pet Photo"
                                                                style="height: 300px; object-fit: cover;">
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                                <?php if(count($pet->photos) > 1): ?>
                                                    <button class="carousel-control-prev" type="button" 
                                                        data-bs-target="#petCarousel<?php echo e($pet->id); ?>" data-bs-slide="prev">
                                                        <span class="carousel-control-prev-icon"></span>
                                                    </button>
                                                    <button class="carousel-control-next" type="button" 
                                                        data-bs-target="#petCarousel<?php echo e($pet->id); ?>" data-bs-slide="next">
                                                        <span class="carousel-control-next-icon"></span>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>

                                        <?php if($pet->videos): ?>
                                            <?php $__currentLoopData = $pet->videos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <video controls class="w-100 mt-2">
                                                    <source src="<?php echo e(Storage::url($video)); ?>" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>

                                        
                                        <?php if($pet->photos && count($pet->photos) > 0): ?>
                                            <div class="mb-3">
                                                <label class="form-label">Current Photos</label>
                                                <div class="d-flex flex-wrap gap-2">
                                                    <?php $__currentLoopData = $pet->photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="position-relative photo-container">
                                                        <img src="<?php echo e(Storage::url($photo)); ?>" 
                                                             class="img-thumbnail" alt="Pet Photo"
                                                             style="width: 100px; height: 100px; object-fit: cover;">
                                                        <?php if(count($pet->photos) > 1): ?>
                                                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 delete-photo"
                                                                data-photo-index="<?php echo e($index); ?>">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                        <?php endif; ?>
                                                        <input type="hidden" name="photos_to_keep[]" value="<?php echo e($photo); ?>" class="photo-keep-input">
                                                    </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        
                                        <div class="mb-3">
                                            <label class="form-label">Add New Photos</label>
                                            <input type="file" class="form-control" name="new_photos[]" 
                                                   multiple accept="image/*">
                                        </div>

                                        
                                        <?php if($pet->videos && count($pet->videos) > 0): ?>
                                            <div class="mb-3">
                                                <label class="form-label">Current Videos</label>
                                                <div class="d-flex flex-wrap gap-2">
                                                    <?php $__currentLoopData = $pet->videos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="position-relative">
                                                        <video width="200" height="150" controls class="img-thumbnail">
                                                            <source src="<?php echo e(Storage::url($video)); ?>" type="video/mp4">
                                                            Your browser does not support the video tag.
                                                        </video>
                                                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 delete-video"
                                                                data-video-index="<?php echo e($index); ?>">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                        <input type="hidden" name="videos_to_keep[]" value="<?php echo e($video); ?>">
                                                    </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        
                                        <div class="mb-3">
                                            <label class="form-label">Add New Videos</label>
                                            <input type="file" class="form-control" name="new_videos[]" 
                                                   multiple accept="video/*">
                                        </div>
                                    </div>

                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Name</label>
                                            <input type="text" class="form-control" name="name" value="<?php echo e($pet->name); ?>" required>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Age</label>
                                                    <input type="number" class="form-control" name="age" value="<?php echo e($pet->age); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Gender</label>
                                                    <select class="form-control" name="gender" required>
                                                        <option value="male" <?php echo e($pet->gender === 'male' ? 'selected' : ''); ?>>Male</option>
                                                        <option value="female" <?php echo e($pet->gender === 'female' ? 'selected' : ''); ?>>Female</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Species</label>
                                                    <select id="species<?php echo e($pet->id); ?>" class="form-control" name="species" required>
                                                        <option value="dog" <?php echo e($pet->species === 'dog' ? 'selected' : ''); ?>>Dog</option>
                                                        <option value="cat" <?php echo e($pet->species === 'cat' ? 'selected' : ''); ?>>Cat</option>
                                                        <option value="bird" <?php echo e($pet->species === 'bird' ? 'selected' : ''); ?>>Bird</option>
                                                        <option value="other" <?php echo e(!in_array($pet->species, ['dog', 'cat', 'bird']) ? 'selected' : ''); ?>>Other</option>
                                                    </select>
                                                    <input type="text" id="otherSpecies<?php echo e($pet->id); ?>" 
                                                        class="form-control mt-2 <?php echo e(in_array($pet->species, ['dog', 'cat', 'bird']) ? 'd-none' : ''); ?>" 
                                                        name="other_species" placeholder="Please specify"
                                                        value="<?php echo e(!in_array($pet->species, ['dog', 'cat', 'bird']) ? $pet->species : ''); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Breed</label>
                                                    <select id="breed<?php echo e($pet->id); ?>" class="form-control" name="breed" required
                                                        data-selected-breed="<?php echo e($pet->breed); ?>">
                                                    </select>
                                                    <input type="text" id="otherBreed<?php echo e($pet->id); ?>" 
                                                        class="form-control mt-2 <?php echo e(in_array($pet->breed, ['labrador', 'golden retriever', 'bulldog', 'persian', 'siamese', 'maine coon', 'parrot', 'canary', 'finch']) ? 'd-none' : ''); ?>" 
                                                        name="other_breed" placeholder="Please specify"
                                                        value="<?php echo e(!in_array($pet->breed, ['labrador', 'golden retriever', 'bulldog', 'persian', 'siamese', 'maine coon', 'parrot', 'canary', 'finch']) ? $pet->breed : ''); ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Color</label>
                                                    <select class="form-control" name="color" required>
                                                        <option value="black" <?php echo e($pet->color === 'black' ? 'selected' : ''); ?>>Black</option>
                                                        <option value="white" <?php echo e($pet->color === 'white' ? 'selected' : ''); ?>>White</option>
                                                        <option value="brown" <?php echo e($pet->color === 'brown' ? 'selected' : ''); ?>>Brown</option>
                                                        <option value="other" <?php echo e(!in_array($pet->color, ['black', 'white', 'brown']) ? 'selected' : ''); ?>>Other</option>
                                                    </select>
                                                    <input type="text" id="otherColor<?php echo e($pet->id); ?>" 
                                                        class="form-control mt-2 <?php echo e(in_array($pet->color, ['black', 'white', 'brown']) ? 'd-none' : ''); ?>" 
                                                        name="other_color" placeholder="Please specify"
                                                        value="<?php echo e(!in_array($pet->color, ['black', 'white', 'brown']) ? $pet->color : ''); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Size</label>
                                                    <select class="form-control" name="size" required>
                                                        <option value="small" <?php echo e($pet->size === 'small' ? 'selected' : ''); ?>>Small</option>
                                                        <option value="medium" <?php echo e($pet->size === 'medium' ? 'selected' : ''); ?>>Medium</option>
                                                        <option value="large" <?php echo e($pet->size === 'large' ? 'selected' : ''); ?>>Large</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Vaccinated</label>
                                                    <select class="form-control" name="vaccinated" required>
                                                        <option value="1" <?php echo e($pet->vaccinated ? 'selected' : ''); ?>>Yes</option>
                                                        <option value="0" <?php echo e(!$pet->vaccinated ? 'selected' : ''); ?>>No</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Health Status</label>
                                                    
                                                    
                                                    <?php
                                                        $standardStatuses = ['healthy', 'injured', 'sick'];
                                                        $healthStatus = is_array($pet->healthStatus) ? $pet->healthStatus : [];
                                                        $otherStatuses = array_diff($healthStatus, $standardStatuses);
                                                    ?>

                                                    
                                                    <?php $__currentLoopData = $standardStatuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div class="form-check">
                                                            <input class="form-check-input health-status-checkbox" 
                                                                   type="checkbox" 
                                                                   name="healthStatus[]" 
                                                                   value="<?php echo e($status); ?>"
                                                                   <?php echo e(in_array($status, $healthStatus) ? 'checked' : ''); ?>>
                                                            <label class="form-check-label"><?php echo e(ucfirst($status)); ?></label>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                    
                                                    <div class="form-check">
                                                        <input class="form-check-input health-status-checkbox" 
                                                               type="checkbox" 
                                                               name="healthStatus[]" 
                                                               value="other"
                                                               id="otherHealthCheckbox<?php echo e($pet->id); ?>"
                                                               <?php echo e(!empty($otherStatuses) ? 'checked' : ''); ?>>
                                                        <label class="form-check-label">Other</label>
                                                    </div>

                                                    
                                                    <input type="text" 
                                                           id="otherHealthStatus<?php echo e($pet->id); ?>" 
                                                           class="form-control mt-2 <?php echo e(empty($otherStatuses) ? 'd-none' : ''); ?>" 
                                                           name="other_health_status" 
                                                           value="<?php echo e(implode(', ', $otherStatuses)); ?>"
                                                           placeholder="Please specify other health conditions">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Personality</label>
                                            <select class="form-control" name="personality" required>
                                                <option value="friendly" <?php echo e($pet->personality === 'friendly' ? 'selected' : ''); ?>>Friendly</option>
                                                <option value="aggressive" <?php echo e($pet->personality === 'aggressive' ? 'selected' : ''); ?>>Aggressive</option>
                                                <option value="shy" <?php echo e($pet->personality === 'shy' ? 'selected' : ''); ?>>Shy</option>
                                                <option value="other" <?php echo e($pet->personality === 'other' ? 'selected' : ''); ?>>Other</option>
                                            </select>
                                            <input type="text" id="otherPersonality<?php echo e($pet->id); ?>" 
                                                class="form-control mt-2 <?php echo e($pet->personality !== 'other' ? 'd-none' : ''); ?>" 
                                                name="other_personality" placeholder="Please specify"
                                                value="<?php echo e($pet->personality === 'other' ? $pet->personality : ''); ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Description</label>
                                            <textarea class="form-control" name="description" rows="3" required><?php echo e($pet->description); ?></textarea>
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
                                    onclick="document.getElementById('rejectForm<?php echo e($pet->id); ?>').submit();">
                                    <i class="fas fa-times me-1"></i>Reject
                                </button>
                            </div>
                        </form>

                        
                        <form id="rejectForm<?php echo e($pet->id); ?>" 
                            action="<?php echo e(route('pets.reject', $pet->id)); ?>" 
                            method="POST" class="d-none">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        
        <div class="d-flex justify-content-center mt-4">
            <?php echo e($pets->appends(['status' => request()->get('status', 'unverified')])->links()); ?>

        </div>

        
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 为每个宠物的模态框设置事件监听
            <?php $__currentLoopData = $pets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                setupModalHandlers('<?php echo e($pet->id); ?>');

                // 为每个宠物设置健康状态复选框监听器
                const otherCheckbox = document.getElementById('otherHealthCheckbox<?php echo e($pet->id); ?>');
                const otherInput = document.getElementById('otherHealthStatus<?php echo e($pet->id); ?>');

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
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        });

        // ... rest of the JavaScript code ...
        </script>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 为每个宠物的模态框设置事件监听
    <?php $__currentLoopData = $pets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        setupModalHandlers('<?php echo e($pet->id); ?>');
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

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
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\CourseTools\Laragon\laragon\www\BTPR2\resources\views/admin/petInfoVerification.blade.php ENDPATH**/ ?>