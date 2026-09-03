<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        $roles = Role::orderBy('name')->get();
        return view('users.index', compact('roles'));
    }

    public function data(Request $request)
    {
        $query = User::with('roles');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('role', fn ($u) => $u->roles->pluck('name')->map(fn ($r) => ucfirst($r))->implode(', '))
            ->addColumn('status_badge', fn ($u) => $u->is_active
                ? '<span class="badge bg-success">Aktif</span>'
                : '<span class="badge bg-secondary">Nonaktif</span>')
            ->addColumn('aksi', fn ($u) => view('users.partials.aksi', ['row' => $u])->render())
            ->rawColumns(['status_badge', 'aksi'])
            ->make(true);
    }

    public function create()
    {
        // Hanya tampilkan role internal yang bisa di-assign oleh admin
        $roles = Role::whereIn('name', ['admin', 'pengawas', 'pimpinan'])->orderBy('name')->get();
        return view('users.create', compact('roles'));
    }

    public function store(UserStoreRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'no_hp' => $data['no_hp'] ?? null,
            'jabatan' => $data['jabatan'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);
        $user->assignRole($data['role']);

        ActivityLog::catat('Tambah', 'User Management', "Menambahkan user: {$user->name}");

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);

        // Hanya tampilkan role internal yang bisa di-assign oleh admin
        $roles = Role::whereIn('name', ['admin', 'pengawas', 'pimpinan'])->orderBy('name')->get();

        // Cek apakah super-admin sedang mengedit dirinya sendiri
        $isSelfEdit = auth()->id() === $user->id && $user->hasRole('super-admin');

        return view('users.edit', compact('user', 'roles', 'isSelfEdit'));
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        $this->authorize('update', $user);

        $data = $request->validated();

        // Blokir jika mencoba assign role yang tidak valid via form ini
        if (!in_array($data['role'] ?? '', ['admin', 'pengawas', 'pimpinan'])) {
            return back()->with('error', 'Role tersebut tidak dapat diberikan melalui form ini.');
        }

        $isSelfEdit = auth()->id() === $user->id && $user->hasRole('super-admin');
        $isGoogleUser = $user->auth_provider === 'google';

        if ($isSelfEdit) {
            // Super-admin hanya boleh mengubah nama sendiri
            $user->update([
                'name' => $data['name'],
            ]);
            // Role tetap super-admin, tidak diubah
        } else {
            $user->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'no_hp' => $data['no_hp'] ?? null,
                'jabatan' => $data['jabatan'] ?? null,
                'is_active' => $request->boolean('is_active', true),
                ...(! empty($data['password']) ? ['password' => Hash::make($data['password'])] : []),
            ]);
            
            // Jangan sinkronisasi role jika user adalah akun perusahaan (Google)
            if (!$isGoogleUser) {
                $user->syncRoles([$data['role']]);
            }
        }

        ActivityLog::catat('Edit', 'User Management', "Mengubah user: {$user->name}");

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        if ($user->id === auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Anda tidak dapat menghapus akun sendiri.'], 422);
        }

        ActivityLog::catat('Hapus', 'User Management', "Menghapus user: {$user->name}");
        $user->delete();

        return response()->json(['success' => true, 'message' => 'User berhasil dihapus.']);
    }
}
