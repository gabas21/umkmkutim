@extends('layouts.admin')

@section('title', 'Kelola File Peta')

@section('admin-content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-black">Kelola File Peta</h1>
            <p class="text-sm text-slate-500">Unggah dan kelola file peta GeoJSON/JSON.</p>
        </div>
        <div>
            <form action="{{ route('admin.lokasi.peta.upload') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
                @csrf
                <input type="text" name="name" placeholder="Nama file peta" required class="border rounded px-2 py-1" />
                <input type="file" name="peta" accept=".json,.geojson" required class="border rounded px-2 py-1" />
                <button class="btn btn-primary">Unggah</button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Nama</th>
                        <th class="px-4 py-3 font-semibold">File</th>
                        <th class="px-4 py-3 font-semibold">Uploaded</th>
                        <th class="px-4 py-3 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($petaFiles as $file)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 font-semibold text-slate-900">{{ $file->name }}</td>
                            <td class="px-4 py-3">{{ basename($file->file_path) }}</td>
                            <td class="px-4 py-3">{{ $file->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-3">
                                <form action="{{ route('admin.lokasi.peta.destroy', $file->id) }}" method="POST" onsubmit="return confirm('Hapus file peta ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-10 text-center text-slate-500">Belum ada file peta.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $petaFiles->links() }}
    </div>
</div>
@endsection
