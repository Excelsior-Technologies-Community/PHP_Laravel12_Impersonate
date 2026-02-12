<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', Auth::id())->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function impersonate($id)
    {
        $user = User::findOrFail($id);
        
        if (Auth::user()->canImpersonate() && $user->canBeImpersonated()) {
            Auth::user()->impersonate($user);
            return redirect()->route('dashboard')->with('success', "You are now impersonating {$user->name}");
        }
        
        return redirect()->back()->with('error', 'Cannot impersonate this user');
    }

    public function leaveImpersonate()
    {
        Auth::user()->leaveImpersonation();
        return redirect()->route('admin.users.index')->with('success', 'You have stopped impersonating');
    }
}