<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;


class ProfileController extends Controller
{
    public function showAvatarEdit() {
        return view('common.editAvatar');
    }
    public function updateAvatar(Request $request)
    {
        try {
            $request->validate([
                'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            $user = auth()->user();

            // 如果用户已有头像，先删除旧的
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // 存储新头像
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            
            // 更新用户头像路径
            $user->update([
                'avatar' => $avatarPath
            ]);

            return redirect()
                ->back()
                ->with('success', 'Avatar updated successfully!');

        } catch (\Exception $e) {
            \Log::error('Avatar update error: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Failed to update avatar. Please try again.');
        }
    }

    public function updateUsername(Request $request)
    {
        // 验证输入
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
        ]);
        // 获取当前用户
        $user = Auth::user();
        // 更新用户名
        $user->username = $request->username;
        $user->save();
        // 返回成功消息
        return redirect()->back()->with('success', 'Username updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        // 验证输入
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);
        // 检查当前密码是否正确
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }
        // 更新密码
        Auth::user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('successChangePassword', 'Password updated successfully.');
    }

    public function updateAddress(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:255',
        ]);
        $user = Auth::user();
        $user->address = $request->address;
        $user->save();
        return back()->with('successUpdateAddress', 'Address updated successfully.');
    }

    //admin page
    public function adminUsers()
    {
        $users = User::query()
            ->when(request('search'), function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('username', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('firstName', 'like', "%{$search}%")
                      ->orWhere('lastName', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(6);

        return view('admin.users', compact('users'));
    }

    //update user role
    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,customer'
        ]);

        $user->update(['role' => $request->role]);
        return back()->with('success', 'User role updated successfully');
    }

    //delete user
    public function deleteUser(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot delete admin user');
        }
        if ($user->myPets->count() > 0 || $user->pets->count() > 0) {
            return back()->with('error', 'Cannot delete user with pets');
        }
        //delete image
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }
        $user->delete();
        return back()->with('success', 'User deleted successfully');
    }

    //edit user
    public function editUser(User $user)
    {
        return view('admin.edit', compact('user'));
    }

    //update user
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:255|unique:users,phone,' . $user->id,
            'age' => 'required|integer|min:1',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string|max:255',
            'role' => 'required|in:admin,customer',
        ]);

        $user->update($request->all());

        return redirect()
            ->route('admin.users')
            ->with('success', 'User updated successfully');
    }

}
