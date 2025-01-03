<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetController;
use App\Http\Controllers\AdoptionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DonationController;
use App\Exports\DonationsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\MyPetController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [App\Http\Controllers\AdoptionController::class, 'index']);

Route::get('/showAdp', [App\Http\Controllers\AdoptionController::class, 'index'])->name('showAdp');

Route::get('/search', [App\Http\Controllers\AdoptionController::class, 'search'])->name('search');
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home'); //empty

//donation
Route::get('/donate', [App\Http\Controllers\DonationController::class, 'showDonationForm'])->name('donate.form');
Route::post('/donate/payment', [App\Http\Controllers\DonationController::class, 'paymentPost'])->name('donation.post');
Route::get('/donate/thank-you/{donation}', [App\Http\Controllers\DonationController::class, 'showThankYou'])->name('donation.thank-you');
Route::get('/donate/records', [App\Http\Controllers\DonationController::class, 'showDonationRecords'])->name('donations.records');
Route::get('/donation/receipt/{donation}', [DonationController::class, 'generateReceipt'])
    ->name('donation.receipt')
    ->middleware('auth');

Route::get('/admin/donations/donationsExcel', [DonationController::class, 'donationsExcel'])->name('donationsExcel');

// 查看领养宠物详情
Route::get('/adoptedPet/profile/{id}', [MyPetController::class, 'show'])->name('adoptedPet.profile');

//adminPage
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [PageController::class, 'adminDashboard'])->name('admin.dashboard');
    //user management
    Route::get('/admin/users', [ProfileController::class, 'adminUsers'])->name('admin.users');
    Route::patch('/admin/users/{user}/role', [ProfileController::class, 'updateUserRole'])->name('admin.users.role');
    Route::delete('/admin/users/{user}', [ProfileController::class, 'deleteUser'])->name('admin.users.delete');
    Route::get('/admin/users/{user}/edit', [ProfileController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [ProfileController::class, 'updateUser'])->name('admin.users.update');
    //pet verification
    Route::get('/admin/pets/verification', [PetController::class, 'showVerificationPage'])
        ->name('admin.pets.verification');
    Route::patch('/admin/pets/{pet}/verify', [PetController::class, 'verify'])
        ->name('pets.verify');
    Route::delete('/admin/pets/{pet}/reject', [PetController::class, 'reject'])
        ->name('pets.reject');
    //adoption verification
    Route::get('/admin/adoptions', [AdoptionController::class, 'adminIndex'])->name('admin.adoptions');
    Route::post('/admin/adoptions/{adoption}/status', [AdoptionController::class, 'updateStatus'])->name('admin.adoptions.status');

    //donation records
    Route::get('/admin/donations', [DonationController::class, 'showDonationRecordsAdmin'])->name('admin.donationRecords');
    Route::get('/admin/donations/{donation}', [DonationController::class, 'donationDetails'])->name('admin.donations.details');
    Route::get('/admin/donations/donationsExcel', [DonationController::class, 'donationsExcel'])->name('donationsExcel');
    // 查看捐款详情
    Route::get('/donations/{id}/details', [DonationController::class, 'donationDetails'])
        ->name('donations.details');
});

Route::middleware(['auth'])->group(function () {
    //profile
    Route::get('/profile', [App\Http\Controllers\PageController::class, 'showProfile'])->name('showProfile'); //show all the user profile like username, address and so on.
    Route::get('/profileAvatar', [ProfileController::class, 'showAvatarEdit'])->name('updateAvatar'); //profile a platform to user to update their images
    Route::post('/profileAvadat/update', [ProfileController::class, 'updateAvatar'])->name('profile.updateAvatar'); //post the image to the database
    Route::post('/profile/update-username', [ProfileController::class, 'updateUsername'])->name('profile.updateUsername');
    Route::post('/update-password', [ProfileController::class, 'updatePassword'])->name('user.updatePassword'); //new password
    Route::post('/profile/update-address', [ProfileController::class, 'updateAddress'])->name('profile.updateAddress');
    // add pet info
    Route::get('/pets/create', [PetController::class, 'create'])->name('pets.create');
    Route::post('/pets', [PetController::class, 'store'])->name('pets.store');
    Route::get('/pets/{pet}', [PetController::class, 'show'])->name('pets.show');  
    Route::get('/my-added', [PetController::class, 'myAdded'])->name('pets.myAdded');
    Route::get('/pets/{pet}/edit', [PetController::class, 'edit'])->name('pets.edit');
    Route::put('/pets/{pet}/update', [PetController::class, 'update'])->name('pets.update');
    //adoption system
    Route::post('/pets/{pet}/adopt', [AdoptionController::class, 'adopt'])->name('pets.adopt'); // 领养宠物
    Route::get('/adoptions', [AdoptionController::class, 'adoptionApplication'])->name('adoptions.application'); // 查看领养申请
    Route::get('/my-pets', [MyPetController::class, 'index'])->name('myPets.index'); // 查看领养宠物
    Route::get('/my-pets/{pet}/downloadQRCode', [MyPetController::class, 'downloadQRCode'])->name('myPets.downloadQRCode'); // 下载领养宠物二维码
    Route::get('/my-pets/search', [MyPetController::class, 'search'])->name('myPets.search');
    Route::get('/my-pets/{myPet}/edit', [MyPetController::class, 'edit'])->name('myPets.edit');
    Route::put('/my-pets/{myPet}', [MyPetController::class, 'update'])->name('myPets.update');
    Route::post('/my-pets/{myPet}/delete-photo', [MyPetController::class, 'deletePhoto'])
        ->name('myPets.deletePhoto');
});






