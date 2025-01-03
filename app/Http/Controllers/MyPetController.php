<?php

namespace App\Http\Controllers;

use App\Models\MyPet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MyPetController extends Controller
{
    public function index(Request $request)
    {
        $query = MyPet::with('pet')
                      ->where('user_id', auth()->id())
                      ->where('show', true);

        // 通过关联查询 pet 表中的 species
        if ($request->has('species')) {
            if ($request->species === 'other') {
                $query->whereHas('pet', function($q) use ($request) {
                    $q->whereNotIn('pet_species', ['dog', 'cat', 'bird']);
                });
            } else {
                $query->whereHas('pet', function($q) use ($request) {
                    $q->where('pet_species', $request->species);
                });
            }
        }
        
        //search by name
        if ($request->filled('search')) {
            $query->whereHas('pet', function($q) use ($request) {
                $q->where('pet_name', 'like', '%' . $request->search . '%');
            });
        }

        //search by breed
        if ($request->filled('breed')) {
            if ($request->breed === 'other') {
                $query->whereHas('pet', function($q) use ($request) {
                    $q->whereNotIn('pet_breed', ['labrador', 'golden retriever', 'bulldog']);
                });
            } else {
                $query->whereHas('pet', function($q) use ($request) {
                    $q->where('pet_breed', $request->breed);
                });
            }
        }

        //search by age
        if ($request->filled('age')) {
            switch($request->age) {
                case '0-1':
                    $query->where('pet_age', '<', 1);
                    break;
                case '1-3':
                    $query->where('pet_age', '>=', 1)->where('pet_age', '<', 3);
                    break;
                case '3+':
                    $query->where('pet_age', '>=', 3);
                    break;
            }
        }

        $myPets = $query->latest()
                        ->paginate(6)
                        ->withQueryString(); // 保持分页时的查询参数

        return view('myPets.index', compact('myPets'));
    }

    public function search(Request $request)
    {
        $term = $request->input('term');
        $species = $request->input('species');

        $query = MyPet::with('pet')
            ->where('user_id', auth()->id())
            ->where('show', true)
            ->whereHas('pet', function($q) use ($term) {
                $q->where('pet_name', 'LIKE', "%{$term}%");
            });

        if ($species) {
            $query->whereHas('pet', function($q) use ($species) {
                if ($species === 'other') {
                    $q->whereNotIn('pet_species', ['dog', 'cat', 'bird']);
                } else {
                    $q->where('pet_species', $species);
                }
            });
        }

        $pets = $query->select('my_pets.pet_name')
                      ->join('pets', 'my_pets.pet_id', '=', 'pets.id')
                      ->distinct()
                      ->limit(10)
                      ->pluck('my_pets.pet_name')
                      ->toArray();

        return response()->json($pets);
    }

    public function show($id)
    {
        $myPet = MyPet::findOrFail($id);
        
        return view('myPets.show', compact('myPet'));
    }

    public function downloadQRCode(MyPet $pet)
    {
        try {
            // 检查权限
            if (auth()->id() !== $pet->user_id) {
                return redirect()->back()->with('error', 'Unauthorized to download this QR code.');
            }

            // 检查 QR code 是否存在
            if (!$pet->qr_code_path || !file_exists(public_path($pet->qr_code_path))) {
                return redirect()->back()->with('error', 'QR code not found.');
            }

            // 设置文件名
            $fileName = 'pet_' . $pet->pet_name . '_' . $pet->user_id . '_qrcode.svg';

            // 返回下载响应
            return response()->download(
                public_path($pet->qr_code_path),
                $fileName,
                ['Content-Type' => 'image/svg+xml']
            );

        } catch (\Exception $e) {
            \Log::error('Error downloading QR code: ' . $e->getMessage(), [
                'pet_id' => $pet->id,
                'user_id' => auth()->id()
            ]);
            
            return redirect()->back()->with('error', 'Error downloading QR code. Please try again.');
        }
    }

    public function edit(MyPet $myPet)
    {
        return view('myPets.edit', compact('myPet'));
    }

    public function update(Request $request, MyPet $myPet)
    {
        // 验证权限
        if (auth()->id() !== $myPet->user_id) {
            return redirect()->back()->with('error', 'Unauthorized to edit this pet.');
        }

        // 验证请求数据
        $validated = $request->validate([
            'pet_name' => 'required|string|max:255',
            'pet_breed' => 'required|string|max:255',
            'pet_gender' => 'required|in:Male,Female',
            'pet_age' => 'required|numeric|min:0',
            'pet_size' => 'required|in:Small,Medium,Large',
            'pet_color' => 'required|string|max:255',
            'pet_area' => 'nullable|string|max:255',
            'owner_name' => 'required|string|max:255',
            'owner_email' => 'required|email',
            'owner_phone' => 'required|string|max:20',
            'pet_description' => 'required|string',
            'pet_photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            // 处理图片上传
            if ($request->hasFile('pet_photos')) {
                $photos = [];
                foreach ($request->file('pet_photos') as $photo) {
                    $path = $photo->store('pet-photos', 'public');
                    $photos[] = $path;
                }
                
                // 修改这部分代码来正确处理现有照片
                $existingPhotos = $myPet->pet_photos ?? [];
                if (is_string($existingPhotos)) {
                    $existingPhotos = json_decode($existingPhotos, true) ?? [];
                }
                
                $validated['pet_photos'] = array_merge($existingPhotos, $photos);
            } else {
                // 如果没有新上传的照片，保持现有的照片
                $validated['pet_photos'] = $myPet->pet_photos ?? [];
            }

            // 更新宠物信息
            $myPet->update($validated);

            return redirect()
                ->back()
                ->with('success', 'Pet information updated successfully.');
                
        } catch (\Exception $e) {
            \Log::error('Error updating pet: ' . $e->getMessage(), [
                'pet_id' => $myPet->id,
                'user_id' => auth()->id()
            ]);
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error updating pet information. Please try again.');
        }
    }

    public function deletePhoto(Request $request, MyPet $myPet)
    {
        // 验证权限
        if (auth()->id() !== $myPet->user_id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $photoPath = $request->input('photo_path');
            
            // 获取当前照片数组
            $photos = is_array($myPet->pet_photos) ? $myPet->pet_photos : json_decode($myPet->pet_photos, true);
            
            // 从数组中移除指定照片
            $photos = array_filter($photos, function($photo) use ($photoPath) {
                return $photo !== $photoPath;
            });
            
            // 更新数据库
            $myPet->update(['pet_photos' => array_values($photos)]);
            
            // 删除存储中的实际文件
            Storage::disk('public')->delete($photoPath);
            
            return response()->json(['success' => true, 'message' => 'Photo deleted successfully']);
            
        } catch (\Exception $e) {
            \Log::error('Error deleting photo: ' . $e->getMessage(), [
                'pet_id' => $myPet->id,
                'user_id' => auth()->id()
            ]);
            
            return response()->json(['success' => false, 'message' => 'Error deleting photo'], 500);
        }
    }
}
