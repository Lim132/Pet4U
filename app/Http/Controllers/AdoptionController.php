<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\Adoption;
use App\Models\MyPet;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AdoptionController extends Controller
{
    public function index(Request $request)
    {
        try {
            // 构建基础查询
            $query = Pet::where('verified', true)
            ->where('adopted', false);

            // 物种筛选
            if ($request->has('species')) {
                if ($request->species === 'other') {
                    // 排除常见物种（狗、猫、鸟）
                    $query->whereNotIn('species', ['dog', 'cat', 'bird']);
                } else {
                    $query->where('species', $request->species);
                }
            }

            // 名称搜索
            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            // 品种筛选
            if ($request->filled('breed')) {
                if ($request->breed === 'other') {
                    // 获取当前物种的所有常规品种
                    $commonBreeds = [];
                    switch($request->species) {
                        case 'dog':
                            $commonBreeds = ['labrador', 'golden retriever', 'bulldog'];
                            break;
                        case 'cat':
                            $commonBreeds = ['persian', 'siamese', 'maine coon'];
                            break;
                        case 'bird':
                            $commonBreeds = ['parrot', 'canary', 'finch'];
                            break;
                    }
                    // 排除常规品种
                    $query->whereNotIn('breed', $commonBreeds);
                } else {
                    $query->where('breed', $request->breed);
                }
            }

            // 年龄筛选
            if ($request->filled('age')) {
                switch($request->age) {
                    case '0-1':
                        $query->where('age', '<', 1);
                        break;
                    case '1-3':
                        $query->whereBetween('age', [1, 3]);
                        break;
                    case '3+':
                        $query->where('age', '>', 3);
                        break;
                }
            }

            // 获取分页数据
            $pets = $query->orderBy('created_at', 'desc')
                          ->paginate(6)
                          ->withQueryString();  // 保持 URL 参数

            // 添加调试信息
            \Log::info('Pets query executed', ['count' => $pets->count()]);

            return view('common.showAdpPet', compact('pets'));

        } catch (\Exception $e) {
            \Log::error('Error in AdoptionController@index: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while loading the pets.');
        }
    }

    public function search(Request $request)
    {
        $term = $request->input('term');
        $species = $request->input('species');

        $query = Pet::where('verified', true)
                   ->where('adopted', false)
                   ->where('name', 'LIKE', "%{$term}%");

        if ($species) {
            if ($species === 'other') {
                $query->whereNotIn('species', ['dog', 'cat', 'bird']);
            } else {
                $query->where('species', $species);
            }
        }

        $pets = $query->select('name')
                      ->distinct()
                      ->limit(10)
                      ->pluck('name')
                      ->toArray();

        return response()->json($pets);
    }

    public function adopt(Pet $pet, Request $request)
    {
        // 确保用户已登录
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        // 检查是否已经有正在进行的申请
        $existingAdoption = Adoption::where('pet_id', $pet->id)
            ->where('user_id', auth()->id())
            ->whereIn('status', [Adoption::STATUS_PENDING, Adoption::STATUS_APPROVED])
            ->first();

        if ($existingAdoption) {
            $status = $existingAdoption->status === Adoption::STATUS_PENDING ? 'pending' : 'approved';
            return redirect()->back()->with('error', "You already have a {$status} adoption application, please do not apply again.");
        }

        // 检查宠物是否已经被其他人领养
        $adoptedByOthers = Adoption::where('pet_id', $pet->id)
            ->whereIn('status', [Adoption::STATUS_APPROVED, Adoption::STATUS_DONE])
            ->exists();

        if ($adoptedByOthers) {
            return redirect()->back()->with('error', 'Sorry, this pet has already been adopted.');
        }

        // 创建新的领养申请
        $adoption = new Adoption();
        $adoption->pet_id = $pet->id;
        $adoption->user_id = auth()->id();
        $adoption->status = Adoption::STATUS_PENDING;
        $adoption->save();

        return redirect()->back()->with('success', 'Adoption application submitted, please wait for approval.');
    }

    public function adoptionApplication(Request $request)
    {
        $status = $request->get('status', 'all');
        $query = Adoption::with(['pet', 'user'])
            ->where('user_id', auth()->id());

        // 根据状态筛选
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $adoptions = $query->latest()->paginate(10);

        // 获取各状态的数量
        $counts = [
            'all' => Adoption::where('user_id', auth()->id())->count(),
            'pending' => Adoption::where('user_id', auth()->id())->where('status', 'pending')->count(),
            'approved' => Adoption::where('user_id', auth()->id())->where('status', 'approved')->count(),
            'rejected' => Adoption::where('user_id', auth()->id())->where('status', 'rejected')->count(),
            'done' => Adoption::where('user_id', auth()->id())->where('status', 'done')->count(),
        ];

        return view('common.adoptionApplication', compact('adoptions', 'status', 'counts'));
    }

    public function adminIndex(Request $request)
    {
        // 验证用户是否为管理员
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $status = $request->get('status', 'all');
        $query = Adoption::with(['pet', 'user']);

        // 根据状态筛选
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $adoptions = $query->latest()->paginate(6);

        // 获取各状态的数量
        $counts = [
            'all' => Adoption::count(),
            'pending' => Adoption::where('status', 'pending')->count(),
            'approved' => Adoption::where('status', 'approved')->count(),
            'rejected' => Adoption::where('status', 'rejected')->count(),
            'done' => Adoption::where('status', 'done')->count(),
        ];

        return view('admin.adoptionManagement', compact('adoptions', 'status', 'counts'));
    }

    // public function updateStatus(Request $request, Adoption $adoption)
    // {
    //     // 验证用户是否为管理员
    //     if (auth()->user()->role !== 'admin') {
    //         return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
    //     }

    //     // 验证请求数据
    //     $validated = $request->validate([
    //         'status' => 'required|in:pending,approved,rejected,done',
    //     ]);
        
    //     try {
    //         // 更新状态
    //         $adoption->update([
    //             'status' => $validated['status']
    //         ]);
    //         if ($validated['status'] === 'approved' && $adoption->pet->adopted === false) { //领养者和宠物曾经有领养关系
    //             // 更新宠物状态
    //             $adoption->pet->update(['adopted' => true]);
    //             // 拒绝其他申请
    //             Adoption::where('pet_id', $adoption->pet_id)
    //                 ->where('status', 'pending')
    //                 ->update(['status' => 'rejected']);
    //         }else if ($validated['status'] === 'approved' && $adoption->pet->adopted === true) {
    //             $existingDoneAdoption = Adoption::where('pet_id', $adoption->pet_id)
    //                 ->where('id', '!=', $adoption->id)
    //                 ->whereIn('status', [Adoption::STATUS_DONE, Adoption::STATUS_APPROVED])
    //                 ->exists();

    //             if ($existingDoneAdoption) {
    //                 $adoption->update(['status' => 'rejected']);
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'This pet already has an approved or completed adoption.',
    //                 ], 100);
    //             }
    //             // 更新宠物状态
    //             MyPet::where('pet_id', $adoption->pet_id)
    //                 ->where('user_id', $adoption->user_id)
    //                 ->update(['show' => false]);
    //         }else if ($validated['status'] === 'done') {

    //             // 检查是否已经有通过或完成的领养
    //             $existingDoneAdoption = Adoption::where('pet_id', $adoption->pet_id)
    //                 ->where('id', '!=', $adoption->id)
    //                 ->whereIn('status', [Adoption::STATUS_DONE, Adoption::STATUS_APPROVED])
    //                 ->exists();

    //             if ($existingDoneAdoption) {
    //                 $adoption->update(['status' => 'rejected']);
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'This pet already has an approved or completed adoption.',
    //                     'redirect' => route('admin.adoptions')
    //                 ], 400);
    //             }
                
    //             // 更新宠物状态
    //             $adoption->pet->update(['adopted' => true]);

    //             // 拒绝其他申请
    //             Adoption::where('pet_id', $adoption->pet_id)
    //                 ->where('status', 'pending')
    //                 ->update(['status' => 'rejected']);
                    
    //             // 检查是否存在相同宠物和用户的记录
    //             $existingRecord = MyPet::where('user_id', $adoption->user_id)
    //                 ->where('pet_id', $adoption->pet_id)
    //                 ->first();

    //             if ($existingRecord) {
    //                 \Log::info('Updating existing MyPet record', ['record_id' => $existingRecord->id]);
    //                 $existingRecord->update(['show' => true]);
    //                 $myPet = $existingRecord;
    //             } else {
    //                 \Log::info('Creating new MyPet record', [
    //                     'user_id' => $adoption->user_id,
    //                     'pet_id' => $adoption->pet_id
    //                 ]);
                        
    //                     // 确保 photos 是 JSON 字符串
    //                 $petPhotos = is_string($adoption->pet->photos) 
    //                     ? $adoption->pet->photos 
    //                     : json_encode($adoption->pet->photos);

    //                 $myPet = MyPet::create([
    //                     'user_id' => $adoption->user_id,
    //                     'pet_id' => $adoption->pet_id,
    //                     'adoption_id' => $adoption->id,
    //                     'pet_photos' => $petPhotos,  // 使用处理后的照片数据
    //                     'pet_name' => $adoption->pet->name,
    //                     'pet_breed' => $adoption->pet->breed,
    //                     'pet_gender' => $adoption->pet->gender,
    //                     'pet_age' => $adoption->pet->age,
    //                     'pet_size' => $adoption->pet->size,
    //                     'pet_color' => $adoption->pet->color,
    //                     'pet_description' => $adoption->pet->description,
    //                     'pet_area' => null,
    //                     'owner_name' => $adoption->user->username,
    //                     'owner_email' => $adoption->user->email,
    //                     'owner_phone' => $adoption->user->phone,
    //                     'show' => true
    //                 ]);
    //                     // 使用 my_pets 的 ID 生成 QR code
    //                 $qrCodeContent = route('adoptedPet.profile', ['id' => $myPet->id]);
    //                 $qrCodePath = 'qrcodes/my_pet_' . $myPet->id . '.svg';
                        
    //                     // 确保目录存在
    //                 if (!file_exists(public_path('qrcodes'))) {
    //                     mkdir(public_path('qrcodes'), 0777, true);
    //                 }

    //                 QrCode::generate($qrCodeContent, public_path($qrCodePath));

    //                     // 更新 QR code 路径
    //                 $myPet->update(['qr_code_path' => $qrCodePath]);
                        
    //             }
            
    //         } else {// pending 和 rejected 状态
    //             $existingDoneAdoption = Adoption::where('pet_id', $adoption->pet_id)
    //                 ->where('id', '!=', $adoption->id)
    //                 ->whereIn('status', [Adoption::STATUS_DONE, Adoption::STATUS_APPROVED])
    //                 ->exists();

    //             if ($existingDoneAdoption) { // 在有宠物完成领养的情况下，换领养申请状态为pending或rejected。
    //                 $adoption->update(['status' => 'rejected']);
    //                 MyPet::where('pet_id', $adoption->pet_id)
    //                     ->where('user_id', $adoption->user_id)
    //                     ->update(['show' => false]);
    //             }else{ // 如果宠物未完成领养，则更新宠物状态。在宠物未完成领养的情况下，更新领养申请状态为 pending或rejected。
    //                 $adoption->pet->update(['adopted' => false]);
    //                 MyPet::where('pet_id', $adoption->pet_id)
    //                     ->where('user_id', $adoption->user_id)
    //                     ->update(['show' => false]);
    //             }
    //         }

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Status updated successfully'
    //         ]);

    //     } catch (\Exception $e) {
    //         \Log::error('Error updating adoption status: ' . $e->getMessage(), [
    //             'exception' => $e,
    //             'user_id' => $adoption->user_id,
    //             'pet_id' => $adoption->pet_id,  
    //             'adoption_id' => $adoption->id,
    //             'status' => $validated['status'] ?? null
    //         ]);
            
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Error updating status: ' . $e->getMessage(),
    //             'route' => route('admin.adoptions')
    //         ], 500);
    //     }
    // }

    public function updateStatus(Request $request, Adoption $adoption)
    {
        // 1. 权限检查
        if (auth()->user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        // 2. 验证请求数据
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected,done',
        ]);
        
        try {
            // 3. 检查是否存在其他已批准或完成的领养
            $existingDoneAdoption = $this->checkExistingAdoption($adoption);

            // 4. 根据不同状态处理
            switch ($validated['status']) {
                case 'approved': // approved 状态
                    return $this->handleApprovedStatus($adoption, $existingDoneAdoption);
                case 'done': // done 状态
                    return $this->handleDoneStatus($adoption, $existingDoneAdoption);
                default: // pending 和 rejected 状态
                    return $this->handleOtherStatus($adoption, $validated['status'], $existingDoneAdoption);
            }

        } catch (\Exception $e) {
            \Log::error('Error updating adoption status: ' . $e->getMessage(), [
                'adoption_id' => $adoption->id,
                'status' => $validated['status'] ?? null
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage()
            ], 500);
        }
    }

    // 检查是否存在其他已批准或完成的领养
    private function checkExistingAdoption(Adoption $adoption)
    {
        return Adoption::where('pet_id', $adoption->pet_id)
            ->where('id', '!=', $adoption->id)
            ->whereIn('status', [Adoption::STATUS_DONE, Adoption::STATUS_APPROVED])
            ->exists();
    }

    // 处理 approved 状态
    private function handleApprovedStatus(Adoption $adoption, bool $existingDoneAdoption)
    {
        if ($existingDoneAdoption) { // 如果存在其他已批准或完成的领养，则拒绝当前申请
            $adoption->update(['status' => 'rejected']);
            return response()->json([ // 返回错误信息
                'success' => false,
                'message' => 'This pet already has an approved or completed adoption.'
            ], 400);
        }

        $adoption->update(['status' => 'approved']);

        if (!$adoption->pet->adopted) { // 如果宠物未被领养，则更新宠物状态
            $adoption->pet->update(['adopted' => true]);
            // 拒绝其他待处理的申请
            Adoption::where('pet_id', $adoption->pet_id)
                ->where('status', 'pending')
                ->update(['status' => 'rejected']);
        } else { // 如果领养申请从done换为approved，则隐藏相关的 MyPet 记录
            // 隐藏相关的 MyPet 记录
            MyPet::where('pet_id', $adoption->pet_id)
                ->where('user_id', $adoption->user_id)
                ->update(['show' => false]);
        }

        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }

    // 处理 done 状态
    private function handleDoneStatus(Adoption $adoption, bool $existingDoneAdoption)
    {
        if ($existingDoneAdoption) { // 如果存在其他已批准或完成的领养，则拒绝当前申请
            $adoption->update(['status' => 'rejected']);
            return response()->json([
                'success' => false,
                'message' => 'This pet already has an approved or completed adoption.',
            ], 400);
        }

        $adoption->update(['status' => 'done']); // 更新领养申请状态为done
        $adoption->pet->update(['adopted' => true]); // 更新宠物状态为已领养

        // 拒绝其他待处理的申请
        Adoption::where('pet_id', $adoption->pet_id)
            ->where('status', 'pending')
            ->update(['status' => 'rejected']);

        // 创建或更新 MyPet 记录
        $this->createOrUpdateMyPet($adoption);

        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }

    // 处理 pending 和 rejected 状态
    private function handleOtherStatus(Adoption $adoption, string $status, bool $existingDoneAdoption)
    {
        if ($existingDoneAdoption) { // 如果存在其他已批准或完成的领养，则拒绝当前申请 // 尝试从rejected换为pending
            $adoption->update(['status' => 'rejected']);
            MyPet::where('pet_id', $adoption->pet_id) 
                ->where('user_id', $adoption->user_id)
                ->update(['show' => false]);
            return response()->json(['success' => false, 'message' => 'This pet already has an approved or completed adoption.']);
        } else {
            $adoption->update(['status' => $status]); // 更新领养申请状态为pending或rejected, 从done换为pending或rejected
            $adoption->pet->update(['adopted' => false]); // 更新宠物状态为未领养
            MyPet::where('pet_id', $adoption->pet_id)
                ->where('user_id', $adoption->user_id)
                ->update(['show' => false]); // 隐藏相关的 MyPet 记录
            return response()->json(['success' => true, 'message' => 'Status updated successfully']);
        }

        
    }

    private function createOrUpdateMyPet(Adoption $adoption)
    {
        $existingRecord = MyPet::where('user_id', $adoption->user_id)
            ->where('pet_id', $adoption->pet_id)
            ->first();

        if ($existingRecord) { // 如果存在相同的宠物和用户记录，则更新show为true
            $existingRecord->update(['show' => true]);
            return $existingRecord;
        }

        // 确保 photos 是 JSON 字符串
        $petPhotos = is_string($adoption->pet->photos) 
            ? $adoption->pet->photos 
            : json_encode($adoption->pet->photos);

        // 如果不存在相同的宠物和用户记录，则创建 MyPet 记录
        $myPet = MyPet::create([
            'user_id' => $adoption->user_id,
            'pet_id' => $adoption->pet_id,
            'adoption_id' => $adoption->id,
            'pet_photos' => $petPhotos,
            'pet_name' => $adoption->pet->name,
            'pet_breed' => $adoption->pet->breed,
            'pet_gender' => $adoption->pet->gender,
            'pet_age' => $adoption->pet->age,
            'pet_size' => $adoption->pet->size,
            'pet_color' => $adoption->pet->color,
            'pet_description' => $adoption->pet->description,
            'pet_area' => null,
            'owner_name' => $adoption->user->username,
            'owner_email' => $adoption->user->email,
            'owner_phone' => $adoption->user->phone,
            'show' => true
        ]);

        $this->generateQrCode($myPet); // 生成 QR code
        return $myPet;
    }

    private function generateQrCode(MyPet $myPet)
    {
        $qrCodeContent = route('adoptedPet.profile', ['id' => $myPet->id]);
        $qrCodePath = 'qrcodes/pet_' . $myPet->adoption_id . '_' . $myPet->id . '_' . time() . '.svg';
        
        if (!file_exists(public_path('qrcodes'))) {
            mkdir(public_path('qrcodes'), 0777, true);
        }

        QrCode::generate($qrCodeContent, public_path($qrCodePath)); // 生成 QR code
        $myPet->update(['qr_code_path' => $qrCodePath]); // 更新 QR code 路径
    }
}
