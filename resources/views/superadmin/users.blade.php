@extends('layouts.admin')

@section('title', 'Kelola Pengguna — Superadmin')

@section('admin-content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Kelola Semua Pengguna</h1>
            <p class="text-sm text-slate-500">Daftar semua pengguna dengan role di sistem.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Nama</th>
                        <th class="px-4 py-3 font-semibold">Email</th>
                        <th class="px-4 py-3 font-semibold">Role</th>
                        <th class="px-4 py-3 font-semibold">Status</th>
                        <th class="px-4 py-3 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 font-semibold text-slate-900">{{ $user->name }}</td>
                            <td class="px-4 py-3">{{ $user->email }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                                    Aktif
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <form action="{{ route('superadmin.users.role', $user->id) }}" method="POST" class="inline-flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="role" class="rounded-lg border border-slate-200 bg-slate-50 px-2 py-1.5 text-xs text-slate-700">
                                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="superadmin" {{ $user->role === 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                                    </select>
                                    <button type="submit" class="rounded-lg bg-slate-900 px-2.5 py-1.5 text-[10px] font-bold text-white hover:bg-slate-700">
                                        Update
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-slate-500">Belum ada pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $users->links() }}
    </div>
</div>
@endsection
