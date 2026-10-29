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

        $query = User::where('usertype', 'karyawan');

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

        $employees = $query->latest()->paginate(15)->withQueryString();

        return view('admin.employees.index', compact('employees', 'search', 'status'));
    }

    public function create()
    {
        return view('admin.employees.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'nip' => 'nullable|string|max:50|unique:users',
            'position' => 'nullable|string|max:100',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'nip' => $validated['nip'],
            'position' => $validated['position'],
            'password' => Hash::make($validated['password']),
            'usertype' => 'karyawan',
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.employees.index')->with('success', 'Karyawan baru berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $employee = User::where('usertype', 'karyawan')->findOrFail($id);
        return view('admin.employees.edit', compact('employee'));
    }

    public function update(Request $request, $id)
    {
        $employee = User::where('usertype', 'karyawan')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($employee->id)],
            'nip' => ['nullable', 'string', 'max:50', Rule::unique('users')->ignore($employee->id)],
            'position' => 'nullable|string|max:100',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $employee->name = $validated['name'];
        $employee->email = $validated['email'];
        $employee->nip = $validated['nip'];
        $employee->position = $validated['position'];

        if ($request->filled('password')) {
            $employee->password = Hash::make($validated['password']);
        }

        $employee->save();

        return redirect()->route('admin.employees.index')->with('success', 'Data karyawan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $employee = User::where('usertype', 'karyawan')->findOrFail($id);

        // Hapus foto wajah jika ada
        if ($employee->face_photo && Storage::disk('public')->exists($employee->face_photo)) {
            Storage::disk('public')->delete($employee->face_photo);
        }

        $employee->delete();

        return redirect()->route('admin.employees.index')->with('success', 'Data karyawan berhasil dihapus!');
    }

    public function resetFace($id)
    {
        $employee = User::where('usertype', 'karyawan')->findOrFail($id);

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
