<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\PetaFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class AdminLokasiController extends Controller
{
    public function kecamatanIndex()
    {
        $kecamatans = Kecamatan::withCount('kelurahans')->orderBy('name')->paginate(20);
        return view('admin.lokasi_kecamatan_index', compact('kecamatans'));
    }

    public function createKecamatan()
    {
                return view('admin.lokasi_kecamatan_form');
    }

    public function editKecamatan(Kecamatan $kecamatan)
    {
                return view('admin.lokasi_kecamatan_form', compact('kecamatan'));
    }

    public function storeKecamatan(Request $request)
    {
                $data = $request->validate([
                    'name' => 'required|string|max:255',
                    'code' => 'nullable|string|max:50',
                    'geojson' => 'nullable|string',
                ]);

                Kecamatan::create($data);

                return redirect()->route('admin.lokasi.kecamatan.index')->with('success', 'Kecamatan disimpan.');
    }

    public function updateKecamatan(Request $request, Kecamatan $kecamatan)
    {
                $data = $request->validate([
                    'name' => 'required|string|max:255',
                    'code' => 'nullable|string|max:50',
                    'geojson' => 'nullable|string',
                ]);

                $kecamatan->update($data);

                return redirect()->route('admin.lokasi.kecamatan.index')->with('success', 'Kecamatan diperbarui.');
    }

    public function destroyKecamatan(Kecamatan $kecamatan)
    {
                // prevent deletion if has kelurahan
                if ($kecamatan->kelurahans()->count() > 0) {
                    return redirect()->back()->with('error', 'Tidak dapat menghapus kecamatan yang masih memiliki kelurahan.');
                }

                $kecamatan->delete();
                return redirect()->route('admin.lokasi.kecamatan.index')->with('success', 'Kecamatan dihapus.');
    }

    public function kelurahanIndex()
    {
                $kelurahans = Kelurahan::with('kecamatan')->orderBy('name')->paginate(20);
                return view('admin.lokasi_kelurahan_index', compact('kelurahans'));
    }

    public function createKelurahan()
    {
                $kecamatans = Kecamatan::orderBy('name')->get();
                return view('admin.lokasi_kelurahan_form', compact('kecamatans'));
    }

    public function editKelurahan(Kelurahan $kelurahan)
    {
                $kecamatans = Kecamatan::orderBy('name')->get();
                return view('admin.lokasi_kelurahan_form', compact('kelurahan', 'kecamatans'));
    }

    public function storeKelurahan(Request $request)
    {
                $data = $request->validate([
                    'kecamatan_id' => 'required|exists:kecamatans,id',
                    'name' => 'required|string|max:255',
                    'code' => 'nullable|string|max:50',
                    'geojson' => 'nullable|string',
                ]);

                Kelurahan::create($data);

                return redirect()->route('admin.lokasi.kelurahan.index')->with('success', 'Kelurahan disimpan.');
    }

    public function updateKelurahan(Request $request, Kelurahan $kelurahan)
    {
                $data = $request->validate([
                    'kecamatan_id' => 'required|exists:kecamatans,id',
                    'name' => 'required|string|max:255',
                    'code' => 'nullable|string|max:50',
                    'geojson' => 'nullable|string',
                ]);

                $kelurahan->update($data);

                return redirect()->route('admin.lokasi.kelurahan.index')->with('success', 'Kelurahan diperbarui.');
    }

    public function destroyKelurahan(Kelurahan $kelurahan)
    {
                $kelurahan->delete();
                return redirect()->route('admin.lokasi.kelurahan.index')->with('success', 'Kelurahan dihapus.');
    }

    public function petaIndex()
    {
                $petaFiles = PetaFile::orderBy('created_at', 'desc')->paginate(20);
                return view('admin.lokasi_peta_index', compact('petaFiles'));
    }

    public function uploadPeta(Request $request)
    {
                $request->validate([
                    'peta' => 'required|file|mimes:json,geojson,txt',
                    'name' => 'required|string|max:255',
                ]);

                $file = $request->file('peta');
                $path = $file->store('public/peta');

                $peta = PetaFile::create([
                    'name' => $request->input('name'),
                    'file_path' => $path,
                    'uploaded_by' => auth()->id() ?? null,
                ]);

                return redirect()->route('admin.lokasi.peta.index')->with('success', 'Peta berhasil diunggah.');
    }

    public function destroyPeta(PetaFile $peta)
    {
                // delete file from storage
                if ($peta->file_path && \Illuminate\Support\Facades\Storage::exists($peta->file_path)) {
                    \Illuminate\Support\Facades\Storage::delete($peta->file_path);
                }
                $peta->delete();

                return redirect()->route('admin.lokasi.peta.index')->with('success', 'File peta dihapus.');
    }

    // Show import form for kelurahan CSV
    public function showKelurahanImportForm()
    {
        return view('admin.lokasi_kelurahan_import');
    }

    // Process uploaded CSV and run import:kelurahan command (dry-run default)
    public function processKelurahanImport(Request $request)
    {
        $request->validate([
            'csv' => 'required|file|mimes:csv,txt',
            'dry_run' => 'nullable|in:on,1',
        ]);

        $file = $request->file('csv');
        $filename = 'kelurahan_import_' . date('Ymd_His') . '_' . Str::random(6) . '.csv';
        $stored = $file->storeAs('imports', $filename);
        $fullPath = storage_path('app/' . $stored);

        $dry = $request->has('dry_run');

        // Run artisan command
        $params = ['--file' => $fullPath];
        if ($dry) {
            $params['--dry-run'] = true;
        }

        try {
            Artisan::call('import:kelurahan', $params);
            $output = Artisan::output();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }

        // Parse output for failure report path (robust): look for explicit message or any path containing import_reports
        $reportUrl = null;
        if (preg_match('/Failure report written to:\s*(\S+)/i', $output, $m)) {
            $reportPath = $m[1];
        } else {
            // try to find a path containing import_reports
            if (preg_match('/([A-Za-z]:\\[\\\S ]*import_reports[\\\S ]+)/i', $output, $m2)) {
                $reportPath = $m2[1];
            } else {
                // try unix style path
                if (preg_match('/(\/[^\s]*import_reports\/[^\s]+)/i', $output, $m3)) {
                    $reportPath = $m3[1];
                } else {
                    $reportPath = null;
                }
            }
        }

        if (!empty($reportPath)) {
            // normalize to basename and create route if file exists under storage/app/import_reports
            $base = basename($reportPath);
            $expected = storage_path('app/import_reports/' . $base);
            if (file_exists($expected)) {
                $reportUrl = route('lokasi.kelurahan.import.report', ['filename' => $base]);
            }
        }

        $flash = 'Import command executed. Output: ' . substr($output, 0, 1000);
        if ($reportUrl) {
            return redirect()->back()->with(['success' => $flash, 'import_report' => $reportUrl]);
        }

        return redirect()->back()->with('success', $flash);
    }

    // Download an import report file (stored in storage/app/import_reports)
    public function downloadImportReport($filename)
    {
        $safe = basename($filename);
        $path = storage_path('app/import_reports/' . $safe);
        if (!file_exists($path)) {
            return redirect()->back()->with('error', 'File laporan tidak ditemukan.');
        }

        return response()->download($path);
    }
}
