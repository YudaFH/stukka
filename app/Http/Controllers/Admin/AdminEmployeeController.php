<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminEmployeeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $role = $request->input('role');

        $query = User::where('usertype', '!=', 'user');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($status === 'registered') {
                $query->whereNotNull('face_descriptor');
            } elseif ($status === 'unregistered') {
                $query->whereNull('face_descriptor');
            }
        }

        if ($request->filled('role')) {
            if ($role === 'full_access') {
                $query->where(function ($q) {
                    $q->where('role', 'full_access')->orWhere('usertype', 'admin');
                });
            } else {
                $query->where('role', $role);
            }
        }

        $employees = $query->orderByRaw("CASE WHEN id = 1 THEN 0 ELSE 1 END, id DESC")
            ->paginate(15)
            ->withQueryString();
        $roles = User::getRoles();

        return view('admin.employees.index', compact('employees', 'search', 'status', 'role', 'roles'));
    }

    public function create()
    {
        $roles = User::getRoles();
        return view('admin.employees.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'nip' => 'nullable|string|max:50|unique:users',
            'position' => 'nullable|string|max:100',
            'role' => 'required|string|in:full_access,content_management,event_finance,field_staff',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'nip' => $validated['nip'],
            'position' => $validated['position'],
            'role' => $validated['role'],
            'usertype' => 'karyawan',
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(),
        ]);

        $roleName = User::getRoles()[$validated['role']]['name'] ?? $validated['role'];
        return redirect()->route('admin.employees.index')->with('success', 'Karyawan baru berhasil ditambahkan dengan role ' . $roleName . '!');
    }

    public function edit($id)
    {
        $employee = User::where('usertype', '!=', 'user')->findOrFail($id);
        $roles = User::getRoles();
        return view('admin.employees.edit', compact('employee', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $employee = User::where('usertype', '!=', 'user')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($employee->id)],
            'nip' => ['nullable', 'string', 'max:50', Rule::unique('users')->ignore($employee->id)],
            'position' => 'nullable|string|max:100',
            'role' => 'required|string|in:full_access,content_management,event_finance,field_staff',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $employee->name = $validated['name'];
        $employee->email = $validated['email'];
        $employee->nip = $validated['nip'];
        $employee->position = $validated['position'];

        // Jangan ubah role ID 1 (Super Admin utama)
        if ($employee->id !== 1) {
            $employee->role = $validated['role'];
        }

        if ($request->filled('password')) {
            $employee->password = Hash::make($validated['password']);
        }

        $employee->save();

        return redirect()->route('admin.employees.index')->with('success', 'Data karyawan dan role berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $employee = User::where('usertype', '!=', 'user')->findOrFail($id);

        if ($employee->id === 1) {
            return back()->with('error', 'Akun Utama Administrator tidak dapat dihapus.');
        }

        if ($employee->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Hapus foto wajah jika ada
        if ($employee->face_photo && Storage::disk('public')->exists($employee->face_photo)) {
            Storage::disk('public')->delete($employee->face_photo);
        }

        $employee->delete();

        return redirect()->route('admin.employees.index')->with('success', 'Data karyawan berhasil dihapus!');
    }

    public function resetFace($id)
    {
        $employee = User::where('usertype', '!=', 'user')->findOrFail($id);

        if ($employee->face_photo && Storage::disk('public')->exists($employee->face_photo)) {
            Storage::disk('public')->delete($employee->face_photo);
        }

        $employee->face_descriptor = null;
        $employee->face_photo = null;
        $employee->face_registered_at = null;
        $employee->save();

        return back()->with('success', 'Data biometrik wajah karyawan ' . $employee->name . ' berhasil direset. Karyawan dapat mendaftarkan wajah baru.');
    }
}
