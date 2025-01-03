<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Adoption;

class PetController extends Controller
{
    public function create()
    {
        return view('common.addPetInfo');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'age' => 'required|numeric|min:0',
                'species' => 'required|string|max:255',
                'breed' => 'required|string|max:255',
                'gender' => 'required|in:male,female',
                'color' => 'required|string|max:255',
                'size' => 'required|in:small,medium,large',
                'vaccinated' => 'required|boolean',
                'healthStatus' => 'required|array',
                'personality' => 'required|string|max:255',
                'description' => 'nullable|string',
                'photos.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'videos.*' => 'nullable|mimetypes:video/mp4,video/quicktime|max:20480'
            ]);

            // 处理照片
            $photoPaths = [];
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $path = $photo->store('pets/photos', 'public');
                    $photoPaths[] = $path;
                }
            }

            // 处理视频
            $videoPaths = [];
            if ($request->hasFile('videos')) {
                foreach ($request->file('videos') as $video) {
                    $path = $video->store('pets/videos', 'public');
                    $videoPaths[] = $path;
                }
            }

            // 处理 species 和 breed 和 color 和 personality
            $species = $request->species === 'other' ? $request->other_species : $request->species;
            $breed = $request->breed === 'other' ? $request->other_breed : $request->breed;
            $color = $request->color === 'other' ? $request->other_color : $request->color;
            $personality = $request->personality === 'other' ? $request->other_personality : $request->personality;

            // 处理 healthStatus
            $healthStatus = $request->healthStatus ?? [];
            if (in_array('other', $healthStatus) && $request->other_health_status) {
                $healthStatus = array_filter($healthStatus, fn($status) => $status !== 'other');
                $healthStatus[] = $request->other_health_status;
            }

            // 创建宠物记录
            Pet::create([
                'name' => $validated['name'],
                'age' => $validated['age'],
                'species' => $species,
                'breed' => $breed,
                'gender' => $validated['gender'],
                'color' => $color,
                'size' => $validated['size'],
                'vaccinated' => $validated['vaccinated'],
                'healthStatus' => $healthStatus,
                'personality' => $personality,
                'description' => $validated['description'],
                'photos' => $photoPaths,
                'videos' => $videoPaths,
                'addedBy' => auth()->id(),
                'addedByRole' => auth()->user()->role,
                'verified' => auth()->user()->role === 'admin'
            ]);

            return redirect()
                ->route('pets.create')
                ->with('success', '🎉 Pet information has been successfully added!');

        } catch (\Exception $e) {
            // 如果出错，删除已上传的文件
            if (!empty($photoPaths)) {
                foreach ($photoPaths as $path) {
                    Storage::disk('public')->delete($path);
                }
            }
            if (!empty($videoPaths)) {
                foreach ($videoPaths as $path) {
                    Storage::disk('public')->delete($path);
                }
            }

            \Log::error('Error in pet store: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->with('error', '❌ Failed to add pet information. Please try again.');
        }
    }

    //verification
    public function verify(Request $request, Pet $pet)
    {
        try {
            // 验证请求数据
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'age' => 'required|numeric|min:0',
                'species' => 'required|string|max:255',
                'breed' => 'required|string|max:255',
                'gender' => 'required|in:male,female',
                'size' => 'required|in:small,medium,large',
                'vaccinated' => 'required|boolean',
                'healthStatus' => 'required|array',
                'healthStatus.*' => 'string',
                'description' => 'string',
                'color' => 'required|string',
                'personality' => 'required|string'
            ]);

            // 处理 "other" 选项
            if ($validated['species'] === 'other' && $request->has('other_species')) {
                $validated['species'] = $request->other_species;
            }
            if ($validated['breed'] === 'other' && $request->has('other_breed')) {
                $validated['breed'] = $request->other_breed;
            }
            if ($validated['color'] === 'other' && $request->has('other_color')) {
                $validated['color'] = $request->other_color;
            }
            if ($validated['personality'] === 'other' && $request->has('other_personality')) {
                $validated['personality'] = $request->other_personality;
            }
            $healthStatus = $request->healthStatus ?? [];
            if (in_array('other', $healthStatus)) {
                // 移除 'other' 选项
                $healthStatus = array_filter($healthStatus, fn($status) => $status !== 'other');
                
                // 添加其他健康状态的具体内容
                if ($request->has('other_health_status')) {
                    $healthStatus[] = $request->other_health_status;
                }
            }
            $validated['healthStatus'] = $healthStatus;
            // 处理照片
            $photosToKeep = $request->input('photos_to_keep', []);
            if ($request->hasFile('new_photos')) {
                foreach ($request->file('new_photos') as $photo) {
                    $path = $photo->store('pets/photos', 'public');
                    $photosToKeep[] = $path;
                }
            }
            $pet->photos = $photosToKeep;

            // 处理视频
            $videosToKeep = $request->input('videos_to_keep', []);
            if ($request->hasFile('new_videos')) {
                foreach ($request->file('new_videos') as $video) {
                    $path = $video->store('pets/videos', 'public');
                    $videosToKeep[] = $path;
                }
            }
            $pet->videos = $videosToKeep;

            $pet->save();

            // 更新宠物信息
            $pet->update([
                'name' => $validated['name'],
                'age' => $validated['age'],
                'species' => $validated['species'],
                'breed' => $validated['breed'],
                'gender' => $validated['gender'],
                'color' => $validated['color'],
                'size' => $validated['size'],
                'vaccinated' => $validated['vaccinated'],
                'healthStatus' => $validated['healthStatus'],
                'personality' => $validated['personality'],
                'description' => $validated['description'],
                'verified' => true  // 设置为已验证
            ]);

            return redirect()
                ->route('admin.pets.verification')
                ->with('success', '✅ Pet information has been verified and updated successfully!');

        } catch (\Exception $e) {
            \Log::error('Error verifying pet: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', '❌ Failed to verify pet. Please try again.');
        }
    }

    public function reject(Pet $pet)
    {
        try {
            if (auth()->user()->role !== 'admin') {
                abort(403, 'Unauthorized action.');
            }
            // 删除相关的媒体文件
            if ($pet->photos) {
                foreach ($pet->photos as $photo) {
                    Storage::delete($photo);
                }
            }
            if ($pet->videos) {
                foreach ($pet->videos as $video) {
                    Storage::delete($video);
                }
            }

            // 删除宠物记录
            $pet->delete();

            return redirect()
                ->route('admin.pets.verification')
                ->with('success', '❌ Pet has been rejected and removed.');

        } catch (\Exception $e) {
            \Log::error('Error rejecting pet: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Failed to reject pet. Please try again.');
        }
    }

    public function showVerificationPage(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $status = $request->get('status', 'unverified');
        $query = Pet::with('user');  // 预加载用户关系

        // 基础验证状态过滤
        if ($status === 'verified') {
            $query->where('verified', true);
        } else {
            $query->where('verified', false);
        }

        // 应用过滤条件
        if ($request->filled('species')) {
            if ($request->species === 'other') {
                $query->whereNotIn('species', ['dog', 'cat', 'bird']);
            } else {
                $query->where('species', $request->species);
            }
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->filled('size')) {
            $query->where('size', $request->size);
        }

        if ($request->filled('vaccinated')) {
            $query->where('vaccinated', $request->vaccinated);
        }

        $pets = $query->orderBy('created_at', 'desc')
                      ->paginate(6)
                      ->withQueryString(); // 保持过滤参数在分页链接中
        
        // Get count of unverified pets for badge
        $unverifiedCount = Pet::where('verified', false)->count();
        $verifiedCount = Pet::where('verified', true)->count();

        return view('admin.petInfoVerification', compact('pets', 'unverifiedCount', 'verifiedCount'));
    }

    public function show(Pet $pet)
    {
        // 确保只显示已验证的宠物
        if (!$pet->verified) {
            abort(404);
        }

        return view('common.petDetails', compact('pet'));
    }

    public function update(Request $request, Pet $pet)
    {
        // 验证权限
        if (auth()->user()->role === 'customer' && $pet->addedBy !== auth()->id()) {
            return redirect()->back()->with('error', '您没有权限编辑此宠物信息。');
        }

        // 验证请求数据
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:0',
            'species' => 'required|string',
            'breed' => 'required|string',
            'gender' => 'required|string',
            'color' => 'required|string',
            'size' => 'required|string',
            'vaccinated' => 'required|boolean',
            'healthStatus' => 'required|array',
            'personality' => 'required|string',
            'description' => 'required|string',
            'photos.*' => 'nullable|image|max:5120', // 每张图片最大 5MB
            'videos.*' => 'nullable|mimes:mp4,mov,avi|max:51200', // 每个视频最大 50MB
        ]);

        // 处理保留的照片
        $photosToKeep = $request->input('photos_to_keep', []);
        if (empty($photosToKeep)) {
            return redirect()->back()->with('error', '至少需要保留一张照片！');
        }

        // 处理新上传的照片
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('pets/photos', 'public');
                $photosToKeep[] = $path;
            }
        }
        $validated['photos'] = $photosToKeep;

        // 处理保留的视频
        $videosToKeep = $request->input('videos_to_keep', []);

        // 处理新上传的视频
        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $video) {
                $path = $video->store('pets/videos', 'public');
                $videosToKeep[] = $path;
            }
        }
        $validated['videos'] = $videosToKeep;

        // 删除不再使用的文件
        $oldPhotos = array_diff($pet->photos ?? [], $photosToKeep);
        $oldVideos = array_diff($pet->videos ?? [], $videosToKeep);

        foreach ($oldPhotos as $photo) {
            Storage::disk('public')->delete($photo);
        }

        foreach ($oldVideos as $video) {
            Storage::disk('public')->delete($video);
        }

        // 处理其他字段
        if ($request->has('other_species') && $validated['species'] === 'other') {
            $validated['species'] = $request->other_species;
        }
        if ($request->has('other_breed') && $validated['breed'] === 'other') {
            $validated['breed'] = $request->other_breed;
        }
        if ($request->has('other_color') && $validated['color'] === 'other') {
            $validated['color'] = $request->other_color;
        }
        if ($request->has('other_personality') && $validated['personality'] === 'other') {
            $validated['personality'] = $request->other_breed;
        }
        $healthStatus = $request->healthStatus ?? [];
        if (in_array('other', $healthStatus)) {
            // 移除 'other' 选项
            $healthStatus = array_filter($healthStatus, fn($status) => $status !== 'other');
            
            // 添加其他健康状态的具体内容
            if ($request->has('other_health_status')) {
                $healthStatus[] = $request->other_health_status;
            }
        }
        $validated['healthStatus'] = $healthStatus;

        // 如果是客户更新，需要重新验证
        if (auth()->user()->role === 'customer') {
            $validated['verified'] = false;
        }

        // 更新宠物信息
        $pet->update($validated);

        // 返回成功消息
        $message = auth()->user()->role === 'customer' 
            ? 'Pet information has been updated and is awaiting admin approval.'
            : 'Pet information has been successfully updated.';

        return redirect()->route('pets.myAdded')->with('success', $message);
    }

    public function myAdded()
    {
        $pets = Pet::where('addedBy', auth()->id())
            ->latest()
            ->paginate(9);

        return view('common.petInfoAdded', compact('pets'));
    }

    public function edit(Pet $pet)
    {
        // 检查权限
        if (auth()->user()->role === 'customer' && $pet->addedBy !== auth()->id()) {
            return redirect()->back()->with('error', 'You do not have permission to edit this pet information.');
        }

        return view('common.petEdit', compact('pet'));
    }

    public function deletePhoto(Request $request, Pet $pet)
    {
        $photoPath = 'pets/photos/' . basename($request->photo);
        if (Storage::exists($photoPath)) {
            Storage::delete($photoPath);
            $photos = array_diff($pet->photos, [$request->photo]);
            $pet->update(['photos' => array_values($photos)]);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }

    public function deleteVideo(Request $request, Pet $pet)
    {
        $videoPath = 'pets/videos/' . basename($request->video);
        if (Storage::exists($videoPath)) {
            Storage::delete($videoPath);
            $videos = array_diff($pet->videos, [$request->video]);
            $pet->update(['videos' => array_values($videos)]);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }

    
}

