

<?php $__env->startSection('content'); ?>
<div class="container">
    
    <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        <?php echo e(session('error')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <span class="text-orange h4"><?php echo e(__('Add Pet Information')); ?></span>
        </div>
        <div class="card-body">
            <form method="POST" action="<?php echo e(route('pets.store')); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label><?php echo e(__('Name')); ?></label>
                        <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            name="name" value="<?php echo e(old('name')); ?>"
                            <?php echo e(auth()->user()->role === 'customer' ? 'required' : ''); ?> placeholder="Please enter the pet's name">
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div class="col-md-6">
                        <label><?php echo e(__('Age')); ?></label>
                        <input type="number" class="form-control <?php $__errorArgs = ['age'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            name="age" value="<?php echo e(old('age')); ?>"
                            <?php echo e(auth()->user()->role === 'customer' ? 'required' : ''); ?> placeholder="If less than 1 year old, please enter 0">
                        <?php $__errorArgs = ['age'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label><?php echo e(__('Species')); ?></label>
                        <select id="species" class="form-control <?php $__errorArgs = ['species'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            name="species" <?php echo e(auth()->user()->role === 'customer' ? 'required' : ''); ?>>
                            <option value="">Select Species</option>
                            <option value="dog">Dog</option>
                            <option value="cat">Cat</option>
                            <option value="bird">Bird</option>
                            <option value="other">Other</option>
                        </select>
                        <input type="text" id="otherSpecies" class="form-control mt-2 d-none" 
                            name="other_species" placeholder="Please specify">
                        <?php $__errorArgs = ['species'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div class="col-md-4">
                        <label><?php echo e(__('Breed')); ?></label>
                        <select id="breed" class="form-control <?php $__errorArgs = ['breed'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            name="breed" <?php echo e(auth()->user()->role === 'customer' ? 'required' : ''); ?>>
                            <option value="">Select Breed</option>
                        </select>
                        <input type="text" id="otherBreed" class="form-control mt-2 d-none" 
                            name="other_breed" placeholder="Please specify">
                        <?php $__errorArgs = ['breed'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div class="col-md-4">
                        <label><?php echo e(__('Gender')); ?></label>
                        <select class="form-control <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            name="gender" <?php echo e(auth()->user()->role === 'customer' ? 'required' : ''); ?>>
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                        <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label><?php echo e(__('Color')); ?></label>
                        <select class="form-control <?php $__errorArgs = ['color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            name="color" <?php echo e(auth()->user()->role === 'customer' ? 'required' : ''); ?>>
                            <option value="">Select Color</option>
                            <option value="black">Black</option>
                            <option value="white">White</option>
                            <option value="brown">Brown</option>
                            <option value="other">Other</option>
                        </select>
                        <input type="text" id="otherColor" class="form-control mt-2 d-none" 
                            name="other_color" placeholder="Please specify">
                        <?php $__errorArgs = ['color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div class="col-md-6">
                        <label><?php echo e(__('Size')); ?></label>
                        <select class="form-control <?php $__errorArgs = ['size'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            name="size" <?php echo e(auth()->user()->role === 'customer' ? 'required' : ''); ?>>
                            <option value="">Select Size</option>
                            <option value="small">Small</option>
                            <option value="medium">Medium</option>
                            <option value="large">Large</option>
                        </select>
                        <?php $__errorArgs = ['size'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label><?php echo e(__('Vaccinated')); ?></label>
                        <select class="form-control <?php $__errorArgs = ['vaccinated'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            name="vaccinated" <?php echo e(auth()->user()->role === 'customer' ? 'required' : ''); ?>>
                            <option value="">Select Status</option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                        <?php $__errorArgs = ['vaccinated'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div class="col-md-6">
                        <label><?php echo e(__('Health Status')); ?></label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="healthStatus[]" value="healthy">
                            <label class="form-check-label">Healthy</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="healthStatus[]" value="injured">
                            <label class="form-check-label">Injured</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="healthStatus[]" value="sick">
                            <label class="form-check-label">Sick</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="healthStatus[]" value="other">
                            <label class="form-check-label">Other</label>
                        </div>
                        <input type="text" id="otherHealthStatus" class="form-control mt-2 d-none" 
                            name="other_health_status" placeholder="Please specify">
                        <?php $__errorArgs = ['healthStatus'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label><?php echo e(__('Personality')); ?></label>
                        <select class="form-control <?php $__errorArgs = ['personality'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            name="personality" <?php echo e(auth()->user()->role === 'customer' ? 'required' : ''); ?>>
                            <option value="">Select Personality</option>
                            <option value="friendly">Friendly</option>
                            <option value="aggressive">Aggressive</option>
                            <option value="shy">Shy</option>
                            <option value="other">Other</option>
                        </select>
                        <input type="text" id="otherPersonality" class="form-control mt-2 d-none" 
                            name="other_personality" placeholder="Please specify">
                        <?php $__errorArgs = ['personality'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div class="col-md-6">
                        <label><?php echo e(__('Description')); ?></label>
                        <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            name="description" rows="3"
                            <?php echo e(auth()->user()->role === 'customer' ? 'required' : ''); ?>><?php echo e(old('description')); ?></textarea>
                        <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label><?php echo e(__('Photos')); ?></label>
                        <input type="file" class="form-control <?php $__errorArgs = ['photos'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            name="photos[]" multiple
                            <?php echo e(auth()->user()->role === 'customer' ? 'required' : ''); ?>>
                        <?php $__errorArgs = ['photos'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div class="col-md-6">
                        <label><?php echo e(__('Videos')); ?></label>
                        <input type="file" class="form-control <?php $__errorArgs = ['videos'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            name="videos[]" multiple accept="video/*">
                        <?php $__errorArgs = ['videos'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                
                <input type="hidden" name="addedBy" value="<?php echo e(auth()->id()); ?>">
                <input type="hidden" name="addedByRole" value="<?php echo e(auth()->user()->role); ?>">
                <input type="hidden" name="verified" value="<?php echo e(auth()->user()->role === 'admin' ? 1 : 0); ?>">

                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <?php echo e(__('Add Pet')); ?>

                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const speciesSelect = document.getElementById('species');
        const breedSelect = document.getElementById('breed');
        const otherSpeciesInput = document.getElementById('otherSpecies');
        const otherBreedInput = document.getElementById('otherBreed');
        const otherColorInput = document.getElementById('otherColor');
        const otherHealthStatusInput = document.getElementById('otherHealthStatus');
        const otherPersonalityInput = document.getElementById('otherPersonality');

        const breeds = {
            dog: ['Labrador', 'Golden Retriever', 'Bulldog', 'Other'],
            cat: ['Persian', 'Siamese', 'Maine Coon', 'Other'],
            bird: ['Parrot', 'Canary', 'Finch', 'Other'],
            other: ['Other']
        };

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

        breedSelect.addEventListener('change', function() {
            toggleOtherInput(breedSelect, otherBreedInput);
        });

        document.querySelectorAll('select').forEach(function(select) {
            select.addEventListener('change', function() {
                if (this.name === 'color') {
                    toggleOtherInput(this, otherColorInput);
                } else if (this.name === 'personality') {
                    toggleOtherInput(this, otherPersonalityInput);
                }
            });
        });

        document.querySelectorAll('input[type="checkbox"]').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                if (this.value === 'other') {
                    otherHealthStatusInput.classList.toggle('d-none', !this.checked);
                }
            });
        });

        function toggleOtherInput(selectElement, otherInputElement) {
            if (selectElement.value === 'other') {
                otherInputElement.classList.remove('d-none');
            } else {
                otherInputElement.classList.add('d-none');
            }
        }
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\CourseTools\Laragon\laragon\www\BTPR2\resources\views/common/addPetInfo.blade.php ENDPATH**/ ?>