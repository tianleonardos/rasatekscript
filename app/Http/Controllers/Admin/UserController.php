<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'customer')
            ->withCount('orders')
            ->latest()
            ->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load('orders.orderItems.product');
        return view('admin.users.show', compact('user'));
    }

    public function updateRole(Request $request, User $user)
    {
        // Prevent admin from changing their own role
        if (auth()->id() === $user->id) {
            return redirect()->back()->with('error', 'Tidak bisa mengubah role sendiri!');
        }

        $validated = $request->validate([
            'role' => 'required|in:admin,customer'
        ]);

        $user->update(['role' => $validated['role']]);

        return redirect()->back()
            ->with('success', 'Role pengguna berhasil diupdate!');
    }

    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Tidak bisa menghapus admin!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil dihapus!');
    }
}