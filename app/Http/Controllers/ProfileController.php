<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /**
     * Show the profile edit form.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }
    
    /**
     * Update user profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'address' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Please enter your name',
            'email.required' => 'Email address is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email is already taken',
            'avatar.image' => 'File must be an image',
            'avatar.mimes' => 'Only JPEG, PNG, JPG, GIF images are allowed',
            'avatar.max' => 'Image size must be less than 2MB',
        ]);
        
        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && file_exists(storage_path('app/public/' . $user->avatar))) {
                @unlink(storage_path('app/public/' . $user->avatar));
            }
            
            $image = $request->file('avatar');
            $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('avatars', $filename, 'public');
            $validated['avatar'] = $path;
        }
        
        $user->update($validated);
        
        return redirect()->route('profile.edit')
            ->with('success', 'Profile updated successfully!');
    }
    
    /**
     * Update user phone number.
     */
    public function updatePhone(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'phone' => 'required|string|max:20|regex:/^([0-9\-\+\(\)]*)$/',
        ], [
            'phone.required' => 'Phone number is required',
            'phone.regex' => 'Please enter a valid phone number (e.g., +8801712345678)',
        ]);
        
        $user->phone = $validated['phone'];
        $user->save();
        
        return redirect()->route('profile.edit')
            ->with('success', 'Phone number updated successfully!');
    }
    
    /**
     * Update user password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Current password is required',
            'current_password.current_password' => 'Current password is incorrect',
            'password.required' => 'New password is required',
            'password.min' => 'Password must be at least 8 characters',
            'password.confirmed' => 'Password confirmation does not match',
        ]);
        
        $user->password = Hash::make($validated['password']);
        $user->save();
        
        return redirect()->route('profile.edit')
            ->with('success', 'Password changed successfully!');
    }
    
    /**
     * Delete user account.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
        ], [
            'password.required' => 'Password is required to delete account',
            'password.current_password' => 'Password is incorrect',
        ]);
        
        $user = $request->user();
        Auth::logout();
        $user->delete();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'Account deleted successfully!');
    }
}