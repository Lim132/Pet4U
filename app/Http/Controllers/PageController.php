<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pet;
use App\Models\Adoption;
use App\Models\User;
use App\Models\Activity;

class PageController extends Controller
{
    public function show()
    {
        // Logic to fetch and display adoptable pets
        return view('common.showAdpPet'); // Ensure this view file exists
    }

    public function donate()
    {
        // Logic to fetch and display adoptable pets
        return view('common.donation'); // Ensure this view file exists
    }

    public function showProfile() {
        return view('common.userProfile');
    }

    public function adminDashboard()
    {
        return view('admin.dashboard', [
            'totalPets' => Pet::count(),
            'pendingAdoptions' => Adoption::where('status', 'pending')->count(),
            'totalUsers' => User::count(),
            'successfulAdoptions' => Adoption::where('status', 'done')->count(),
            'adoptionStats' => Adoption::selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d") as month, COUNT(*) as count')//select month and count
                ->groupBy('month')//group by month
                ->orderBy('month')//order by month
                ->take(6)//take 6 months
                ->get(),
            'petCategories' => Pet::selectRaw('species, COUNT(*) as count')
                ->groupBy('species')
                ->get()
        ]);
    }

}
