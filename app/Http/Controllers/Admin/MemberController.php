<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BorrowRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class MemberController extends Controller
{
    /**
     * Display a listing of members.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'member');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }
        
        $members = $query->latest()->paginate(15);
        return view('admin.members.index', compact('members'));
    }

    /**
     * Show the form for creating a new member.
     */
    public function create()
    {
        return view('admin.members.create');
    }

    /**
     * Store a newly created member in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|ends_with:@gmail.com',
            'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ], [
            'email.ends_with' => 'Only Gmail addresses (@gmail.com) are allowed.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*?&).',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'member';
        $validated['membership_date'] = Carbon::now();
        $validated['is_active'] = true;
        
        User::create($validated);

        return redirect()->route('admin.members.index')
            ->with('success', 'Member added successfully!');
    }

    /**
     * Display the specified member.
     */
    public function show(User $member)
    {
        // Get active borrows
        $activeBorrows = BorrowRecord::with('book')
            ->where('user_id', $member->id)
            ->whereNull('returned_at')
            ->get();
        
        // Get borrow history
        $borrowHistory = BorrowRecord::with('book')
            ->where('user_id', $member->id)
            ->whereNotNull('returned_at')
            ->latest()
            ->get();
        
        // Calculate statistics
        $stats = [
            'total_borrowed' => BorrowRecord::where('user_id', $member->id)->count(),
            'active_borrows' => $activeBorrows->count(),
            'total_fine' => BorrowRecord::where('user_id', $member->id)->sum('fine_amount'),
        ];
        
        return view('admin.members.show', compact('member', 'activeBorrows', 'borrowHistory', 'stats'));
    }

    /**
     * Show the form for editing the specified member.
     */
    public function edit(User $member)
    {
        return view('admin.members.edit', compact('member'));
    }

    /**
     * Update the specified member in storage.
     */
    public function update(Request $request, User $member)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|ends_with:@gmail.com|unique:users,email,' . $member->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ], [
            'email.ends_with' => 'Only Gmail addresses (@gmail.com) are allowed.',
        ]);

        // Password update with validation (optional - no current_password required for admin)
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            ], [
                'password.min' => 'Password must be at least 8 characters.',
                'password.confirmed' => 'Password confirmation does not match.',
                'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*?&).',
            ]);
            $validated['password'] = Hash::make($request->password);
        }
        
        $member->update($validated);

        return redirect()->route('admin.members.index')
            ->with('success', 'Member updated successfully!');
    }

    /**
     * Remove the specified member from storage.
     */
    public function destroy(User $member)
    {
        // Check if member has active borrows
        if ($member->activeBorrows()->count() > 0) {
            return back()->with('error', 'Cannot delete member with active borrows! Please return all books first.');
        }
        
        // Check if member has unpaid fines
        $unpaidFines = BorrowRecord::where('user_id', $member->id)
            ->where('fine_amount', '>', 0)
            ->where('fine_paid', false)
            ->exists();
            
        if ($unpaidFines) {
            return back()->with('error', 'Cannot delete member with unpaid fines!');
        }
        
        $member->delete();
        return redirect()->route('admin.members.index')
            ->with('success', 'Member deleted successfully!');
    }
    
    /**
     * Toggle member active status.
     */
    public function toggleStatus(User $member)
    {
        $member->is_active = !$member->is_active;
        $member->save();
        
        $status = $member->is_active ? 'activated' : 'deactivated';
        return redirect()->route('admin.members.index')
            ->with('success', "Member {$status} successfully!");
    }
}