@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Import Kelurahan (CSV)</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <form method="post" action="{{ route('admin.lokasi.kelurahan.import.process') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">CSV File (header: name,kecamatan)</label>
                    <input type="file" name="csv" class="form-control" accept=".csv,text/csv">
                    @error('csv') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" name="dry_run" class="form-check-input" id="dryRun" checked>
                    <label for="dryRun" class="form-check-label">Dry-run (tidak menyimpan data, hanya laporkan)</label>
                </div>

                <button class="btn btn-primary">Upload & Run Import</button>
            </form>

            @if(session('import_report'))
                <div class="mt-3">
                    <a href="{{ session('import_report') }}" class="btn btn-sm btn-outline-secondary" target="_blank">Unduh Laporan Import</a>
                </div>
            @endif

        </div>
    </div>

    <hr>
    <p>Template: <a href="{{ asset('storage/kelurahan_template.csv') }}" target="_blank">storage/kelurahan_template.csv</a></p>
</div>
@endsection
