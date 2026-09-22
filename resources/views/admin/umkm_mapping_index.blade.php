@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Mapping UMKM (kelurahan belum dipetakan)</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form method="get" class="mb-3">
        <div class="row g-2">
            <div class="col-md-4">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama usaha / kelurahan text" class="form-control">
            </div>
            <div class="col-md-3">
                <select name="kecamatan_id" class="form-control">
                    <option value="">-- Semua Kecamatan --</option>
                    @foreach($kecamatans as $k)
                        <option value="{{ $k->id }}" {{ (request('kecamatan_id') == $k->id) ? 'selected' : '' }}>{{ $k->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Usaha</th>
                    <th>Kecamatan (master)</th>
                    <th>Kel/Desa (as-is)</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($umkms as $u)
                <tr>
                    <td>{{ $u->id }}</td>
                    <td>{{ $u->nama_usaha }}</td>
                    <td>{{ optional($u->kecamatan)->name ?? $u->kecamatan }}</td>
                    <td>{{ $u->kelurahan_desa }}</td>
                    <td style="min-width:320px;">
                        <div class="d-flex align-items-center">
                            <form method="post" action="{{ route('admin.umkm.mapping.assign', $u->id) }}" class="d-flex align-items-center me-2 assign-form">
                                @csrf
                                <select name="kelurahan_id" class="form-control me-2 kelurahan-select" data-kecamatan-id="{{ $u->kecamatan_id }}">
                                    <option value="">-- Pilih Kelurahan --</option>
                                </select>
                                <button class="btn btn-sm btn-success">Assign</button>
                            </form>

                            <!-- Inline add kelurahan -->
                            @if($u->kecamatan_id)
                                <div class="input-group add-kelurahan-group" style="max-width:420px;">
                                    <input type="text" class="form-control form-control-sm add-kelurahan-name" placeholder="Tambah kelurahan (nama)">
                                    <button class="btn btn-sm btn-outline-primary add-kelurahan-btn" data-umkm-id="{{ $u->id }}" data-kecamatan-id="{{ $u->kecamatan_id }}">Tambah & Assign</button>
                                </div>
                            @else
                                <div class="text-muted small">Kecamatan belum teridentifikasi — tidak dapat menambah kelurahan</div>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $umkms->links() }}
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // For each select, load kelurahan via API
    document.querySelectorAll('.kelurahan-select').forEach(function (sel) {
        const kecamatanId = sel.dataset.kecamatanId;
        if (!kecamatanId) return;
        fetch(`/api/kutim/kelurahans?kecamatan_id=${kecamatanId}&per_page=200`)
            .then(r => r.json())
            .then(data => {
                (data.data || data).forEach(function (item) {
                    const opt = document.createElement('option');
                    opt.value = item.id;
                    opt.textContent = item.name || item.nama;
                    sel.appendChild(opt);
                });
            });
    });

    // Add kelurahan via AJAX: listen click on buttons
    document.querySelectorAll('.add-kelurahan-btn').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const umkmId = btn.dataset.umkmId;
            const kecamatanId = btn.dataset.kecamatanId;
            const wrapper = btn.closest('.add-kelurahan-group');
            const input = wrapper.querySelector('.add-kelurahan-name');
            const name = input.value && input.value.trim();
            if (!name) {
                alert('Masukkan nama kelurahan');
                return;
            }

            btn.disabled = true;
            btn.textContent = 'Menyimpan...';

            fetch(`/admin/umkm/mapping/${umkmId}/kelurahan-create`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({ kelurahan_name: name, kecamatan_id: kecamatanId })
            }).then(res => res.json())
              .then(json => {
                  if (json.error || !json.success) {
                      alert(json.error || 'Gagal membuat kelurahan');
                      return;
                  }

                  // find the select in same row and append option
                  const row = btn.closest('tr');
                  const sel = row.querySelector('.kelurahan-select');
                  if (sel) {
                      const opt = document.createElement('option');
                      opt.value = json.kelurahan.id;
                      opt.textContent = json.kelurahan.name;
                      sel.appendChild(opt);
                      sel.value = json.kelurahan.id;

                      // submit the assign form automatically
                      const form = row.querySelector('.assign-form');
                      if (form) {
                          form.querySelector('select[name="kelurahan_id"]').value = json.kelurahan.id;
                          form.submit();
                      }
                  }
              }).catch(err => {
                  console.error(err);
                  alert('Terjadi kesalahan saat membuat kelurahan');
              }).finally(() => {
                  btn.disabled = false;
                  btn.textContent = 'Tambah & Assign';
              });
        });
    });
});
</script>
@endsection
