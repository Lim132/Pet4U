

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row" style="margin-top: 10px;">
        
        <div class="col-md-2">
            <div class="list-group">
                <a href="<?php echo e(route('showAdp')); ?>" 
                    class="list-group-item list-group-item-action <?php echo e(!request('species') ? 'active' : ''); ?>">
                    All Categories
                </a>
                <a href="<?php echo e(route('showAdp', ['species' => 'cat'])); ?>" 
                    class="list-group-item list-group-item-action <?php echo e(request('species') === 'cat' ? 'active' : ''); ?>">
                    Cat
                </a>
                <a href="<?php echo e(route('showAdp', ['species' => 'dog'])); ?>" 
                    class="list-group-item list-group-item-action <?php echo e(request('species') === 'dog' ? 'active' : ''); ?>">
                    Dog
                </a>
                <a href="<?php echo e(route('showAdp', ['species' => 'bird'])); ?>" 
                    class="list-group-item list-group-item-action <?php echo e(request('species') === 'bird' ? 'active' : ''); ?>">
                    Bird
                </a>
                <a href="<?php echo e(route('showAdp', ['species' => 'other'])); ?>" 
                    class="list-group-item list-group-item-action <?php echo e(request('species') === 'other' ? 'active' : ''); ?>">
                    Other
                </a>
            </div>
            <br>
            
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title mb-3"><p>Search Pets</p></h6>
                    <form action="<?php echo e(route('showAdp')); ?>" method="GET">
                        <?php if(request('species')): ?>
                            <input type="hidden" name="species" value="<?php echo e(request('species')); ?>">
                        <?php endif; ?>
                        <div class="mb-3">
                            <input type="text" 
                                   class="form-control words" 
                                   name="search" 
                                   id="searchInput"
                                   placeholder="Search by name..."
                                   value="<?php echo e(request('search')); ?>"
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
                                <option value="0-1" <?php echo e(request('age') === '0-1' ? 'selected' : ''); ?>>Under 1 year</option>
                                <option value="1-3" <?php echo e(request('age') === '1-3' ? 'selected' : ''); ?>>1-3 years</option>
                                <option value="3+" <?php echo e(request('age') === '3+' ? 'selected' : ''); ?>>3+ years</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-danger w-100">Search</button>
                    </form>
                </div>
            </div>
            <br>
        </div>

        <div class="col-md-1"></div>

        
        <div class="col-md-8">
            <div class="card border-0">
                <h5 class="title1 card-title">Pets for Adoption</h5>
                <div class="row">
                    <?php $__empty_1 = true; $__currentLoopData = $pets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="card-title pet-title pet-title2 col-12"><?php echo e($pet->name); ?></div>
                                    <div class="pet-img">
                                        <?php if($pet->photos && count($pet->photos) > 0): ?>
                                            <img src="<?php echo e(Storage::url($pet->photos[0])); ?>" 
                                                class="pet-img" 
                                                alt="<?php echo e($pet->name); ?>"
                                                style="height: 200px; object-fit: cover;">
                                        <?php endif; ?>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <div class="card-heading words2" style="padding-left: 10%; padding-right: 10%;">
                                                <table class="mb-3">
                                                    <tr>
                                                        <td style="width: 80px;"><strong>Age:</strong></td>
                                                        <?php if($pet->age == 0): ?>
                                                            <td>Under 1 year</td>
                                                        <?php elseif($pet->age == 1): ?>
                                                            <td>1 year</td>
                                                        <?php else: ?>
                                                            <td><?php echo e($pet->age); ?> years</td>
                                                        <?php endif; ?>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Species:</strong></td>
                                                        <td><?php echo e(ucfirst($pet->species)); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Breed:</strong></td>
                                                        <td><?php echo e(ucfirst($pet->breed)); ?></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-12 text-center">
                                            <a href="<?php echo e(route('pets.show', $pet->id)); ?>" 
                                                class="btn btn-danger btn-sm">
                                                See Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="col-12">
                            <div class="alert alert-info">
                                No pets available for adoption at the moment.
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                
                <div class="d-flex justify-content-end">
                    <?php echo e($pets->links()); ?>

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
    const species = '<?php echo e(request('species')); ?>' || 'all';
    const currentBreed = '<?php echo e(request('breed')); ?>';
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
                url: "<?php echo e(route('search')); ?>", // 需要创建这个路由
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

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\CourseTools\Laragon\laragon\www\BTPR2\resources\views/common/showAdpPet.blade.php ENDPATH**/ ?>