@extends('layouts.admin')

@section('title', 'Kelola Admin — Superadmin')

@section('admin-content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Kelola Admin</h1>
            <p class="text-sm text-slate-500">Daftar akun admin dan superadmin yang dapat mengakses panel backend.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Nama</th>
                        <th class="px-4 py-3 font-semibold">Email</th>
                        <th class="px-4 py-3 font-semibold">Role</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($admins as $admin)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 font-semibold text-slate-900">{{ $admin->name }}</td>
                            <td class="px-4 py-3">{{ $admin->email }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                    {{ $admin->role }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-10 text-center text-slate-500">Belum ada admin yang terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $admins->links() }}
    </div>
</div>
@endsection
