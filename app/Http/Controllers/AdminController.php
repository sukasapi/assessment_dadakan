<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Peserta;
use App\Models\SesiPenilaian;
use App\Models\SesiAssessment;
use App\Models\AssessmentParticipant;

use App\Models\Penilaian;
use App\Models\KemajuanPenilaian;
use App\Models\JawabanStudiKasus;
use App\Models\JawabanInTray;
use App\Models\CatatanRoleplay;
use App\Models\CatatanFgd;
use App\Models\LatihanInTray;
 

use Carbon\Carbon;

/**
 * Aplikasi ini dikembangkan oleh Kusuma Dewangga
 * Hak Cipta © 2025
 * Email: kdewangga85@gmail.com
 */
class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    // ========================================
    // DASHBOARD
    // ========================================

    /**
     * Tampilkan dashboard admin
     */
    public function dashboard()
    {
        $totalPeserta = Peserta::count();
        $totalPenilaian = Penilaian::count();
        $pesertaAktif = Peserta::where('aktif', true)->count();
        
        // Progress summary untuk setiap jenis assessment
        $progressSummary = [];
        
        // Progress Studi Kasus
        $studiKasusProgress = KemajuanPenilaian::whereHas('penilaian', function($query) {
            $query->where('jenis', 'studi_kasus');
        })->get();
        
        $progressSummary['studi_kasus'] = [
            'total' => $studiKasusProgress->count(),
            'belum_mulai' => $studiKasusProgress->where('status', 'belum_mulai')->count(),
            'sedang_berlangsung' => $studiKasusProgress->where('status', 'sedang_berlangsung')->count(),
            'selesai' => $studiKasusProgress->where('status', 'selesai')->count()
        ];
        
        // Progress In-Tray
        $inTrayProgress = KemajuanPenilaian::whereHas('penilaian', function($query) {
            $query->where('jenis', 'in_tray');
        })->get();
        
        $progressSummary['in_tray'] = [
            'total' => $inTrayProgress->count(),
            'belum_mulai' => $inTrayProgress->where('status', 'belum_mulai')->count(),
            'sedang_berlangsung' => $inTrayProgress->where('status', 'sedang_berlangsung')->count(),
            'selesai' => $inTrayProgress->where('status', 'selesai')->count()
        ];
        
        // Progress Roleplay
        $roleplayProgress = KemajuanPenilaian::whereHas('penilaian', function($query) {
            $query->where('jenis', 'roleplay');
        })->get();
        
        $progressSummary['roleplay'] = [
            'total' => $roleplayProgress->count(),
            'belum_mulai' => $roleplayProgress->where('status', 'belum_mulai')->count(),
            'sedang_berlangsung' => $roleplayProgress->where('status', 'sedang_berlangsung')->count(),
            'selesai' => $roleplayProgress->where('status', 'selesai')->count()
        ];
        
        // Progress FGD
        $fgdProgress = KemajuanPenilaian::whereHas('penilaian', function($query) {
            $query->where('jenis', 'fgd');
        })->get();
        
        $progressSummary['fgd'] = [
            'total' => $fgdProgress->count(),
            'belum_mulai' => $fgdProgress->where('status', 'belum_mulai')->count(),
            'sedang_berlangsung' => $fgdProgress->where('status', 'sedang_berlangsung')->count(),
            'selesai' => $fgdProgress->where('status', 'selesai')->count()
        ];
        

            
        $recentPeserta = Peserta::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Recent activities untuk dashboard
        $recentActivities = [];
        
        // Ambil aktivitas dari kemajuan penilaian
        $recentKemajuan = KemajuanPenilaian::with(['peserta', 'penilaian'])
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();
            
        foreach ($recentKemajuan as $kemajuan) {
            $recentActivities[] = [
                'type' => $kemajuan->penilaian->jenis,
                'peserta' => $kemajuan->peserta->nama_lengkap,
                'action' => 'Status berubah menjadi ' . $kemajuan->status,
                'time' => $kemajuan->updated_at,
                'status' => $kemajuan->status === 'selesai' ? 'final' : 'progress'
            ];
        }
        
        // Ambil aktivitas dari jawaban assessment
        $recentJawaban = JawabanStudiKasus::with(['peserta', 'penilaian'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        foreach ($recentJawaban as $jawaban) {
            $recentActivities[] = [
                'type' => 'studi_kasus',
                'peserta' => $jawaban->peserta->nama_lengkap,
                'action' => 'Mengumpulkan jawaban',
                'time' => $jawaban->created_at,
                'status' => 'final'
            ];
        }
        
        $recentInTray = JawabanInTray::with(['peserta', 'penilaian'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        foreach ($recentInTray as $jawaban) {
            $recentActivities[] = [
                'type' => 'in_tray',
                'peserta' => $jawaban->peserta->nama_lengkap,
                'action' => 'Mengumpulkan jawaban',
                'time' => $jawaban->created_at,
                'status' => 'final'
            ];
        }
        
        // Urutkan berdasarkan waktu terbaru
        usort($recentActivities, function($a, $b) {
            return strtotime($b['time']) - strtotime($a['time']);
        });
        
        // Ambil 10 aktivitas terbaru
        $recentActivities = array_slice($recentActivities, 0, 10);

        return view('admin.dashboard', compact(
            'totalPeserta', 
            'totalPenilaian', 
            'pesertaAktif',
            'progressSummary',
            'recentPeserta',
            'recentActivities'
        ));
    }



    // ========================================
    // PESERTA MANAGEMENT
    // ========================================

    /**
     * Tampilkan daftar peserta
     */
    public function pesertaIndex()
    {
        $pesertaList = Peserta::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.peserta.index', compact('pesertaList'));
    }

    /**
     * Tampilkan form tambah peserta
     */
    public function pesertaCreate()
    {
        return view('admin.peserta.create');
    }

    /**
     * Simpan peserta baru
     */
    public function pesertaStore(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'instansi' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'grade' => 'nullable|string|max:50',
            'pin' => 'required|string|min:6|max:10|regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,10}$/|unique:peserta,pin',
            'aktif' => 'boolean'
        ]);

        try {
            // Buat user
            $user = User::create([
                'name' => $request->nama_lengkap,
                'email' => $request->email,
                'password' => Hash::make($request->pin),
                'role' => 'peserta'
            ]);

            // Buat peserta
            Peserta::create([
                'user_id' => $user->id,
                'nama_lengkap' => $request->nama_lengkap,
                'email' => $request->email,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'alamat' => $request->alamat,
                'telepon' => $request->telepon,
                'instansi' => $request->instansi,
                'jabatan' => $request->jabatan,
                'grade' => $request->grade,
                'pin' => $request->pin,
                'aktif' => $request->aktif ?? true
            ]);

            return redirect()->route('admin.peserta.index')->with('success', 'Peserta berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error creating peserta: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menambah peserta.');
        }
    }

    /**
     * Tampilkan detail peserta
     */
    public function pesertaShow($id)
    {
        $peserta = Peserta::with(['user', 'kemajuanPenilaian.penilaian'])->findOrFail($id);
        $progressList = $peserta->kemajuanPenilaian()->with('penilaian')->get();
        
        return view('admin.peserta.show', compact('peserta', 'progressList'));
    }

    /**
     * Tampilkan form edit peserta
     */
    public function pesertaEdit($id)
    {
        $peserta = Peserta::with('user')->findOrFail($id);
        return view('admin.peserta.edit', compact('peserta'));
    }

    /**
     * Update peserta
     */
    public function pesertaUpdate(Request $request, $id)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'instansi' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'grade' => 'nullable|string|max:50',
            'pin' => 'required|string|min:6|max:10|regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,10}$/',
            'aktif' => 'boolean'
        ]);

        try {
            $peserta = Peserta::findOrFail($id);
            $peserta->update($request->except(['email']));
            
            // Update user email jika berubah
            if ($peserta->user && $peserta->user->email !== $request->email) {
                $peserta->user->update(['email' => $request->email]);
            }

            return redirect()->route('admin.peserta.index')->with('success', 'Data peserta berhasil diupdate.');
        } catch (\Exception $e) {
            Log::error('Error updating peserta: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat update peserta.');
        }
    }

    /**
     * Hapus peserta
     */
    public function pesertaDestroy($id)
    {
        try {
            $peserta = Peserta::findOrFail($id);
            if ($peserta->user) {
                $peserta->user->delete();
            }
            $peserta->delete();
            return redirect()->route('admin.peserta.index')->with('success', 'Peserta berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error deleting peserta: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus peserta.');
        }
    }

    /**
     * Import peserta dari CSV
     */
    public function importPeserta(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048'
        ]);

        try {
            $file = $request->file('csv_file');
            $filename = 'peserta_import_' . time() . '.csv';
            
            // Debug: Log file info
            Log::info('File upload info:', [
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType()
            ]);
            
            // Pastikan direktori temp ada
            $tempPath = storage_path('app/temp');
            if (!file_exists($tempPath)) {
                if (!mkdir($tempPath, 0755, true)) {
                    Log::error('Failed to create temp directory: ' . $tempPath);
                    throw new \Exception('Gagal membuat direktori temporary');
                }
            }
            
            // Cek permission direktori
            if (!is_writable($tempPath)) {
                Log::error('Temp directory not writable: ' . $tempPath);
                throw new \Exception('Direktori temporary tidak dapat ditulis');
            }
            
            // Simpan file langsung ke direktori temp
            $fullPath = $tempPath . DIRECTORY_SEPARATOR . $filename;
            
            Log::info('Attempting to save file to: ' . $fullPath);
            
            // Coba simpan menggunakan move_uploaded_file
            if (move_uploaded_file($file->getPathname(), $fullPath)) {
                Log::info('File saved successfully using move_uploaded_file: ' . $fullPath);
                
                // Verifikasi file tersimpan
            if (!file_exists($fullPath)) {
                    Log::error('File not found after move_uploaded_file: ' . $fullPath);
                    throw new \Exception('File tidak ditemukan setelah penyimpanan');
                }
                
                $storedFileSize = filesize($fullPath);
                Log::info('Stored file size: ' . $storedFileSize . ' bytes');
                
            } else {
                // Fallback: coba copy file
                Log::warning('move_uploaded_file failed, trying copy');
                
                if (copy($file->getPathname(), $fullPath)) {
                    Log::info('File saved using copy method: ' . $fullPath);
                } else {
                    Log::error('All file saving methods failed');
                    throw new \Exception('Semua method penyimpanan file gagal');
                }
            }

            $created = 0;
            $skipped = 0;
            $errors = [];

            if (($handle = fopen($fullPath, "r")) !== FALSE) {
                $header = fgetcsv($handle);
                
                // Simpan header asli untuk debugging
                $originalHeader = $header;
                
                // Bersihkan header dari BOM dan karakter khusus
                $header = array_map(function($col) {
                    // Hapus BOM (Byte Order Mark) dan karakter khusus
                    $col = preg_replace('/^\xEF\xBB\xBF/', '', $col); // UTF-8 BOM
                    $col = preg_replace('/^\xFE\xFF/', '', $col);     // UTF-16 BE BOM
                    $col = preg_replace('/^\xFF\xFE/', '', $col);     // UTF-16 LE BOM
                    $col = trim($col); // Hapus spasi di awal dan akhir
                    return $col;
                }, $header);
                
                // Debug: Log header sebelum dan sesudah cleaning
                Log::info('Original CSV Header:', $originalHeader);
                Log::info('Cleaned CSV Header:', $header);
                
                // Simpan header ke session untuk debugging
                session(['csv_header' => $header]);
                session(['csv_header_original' => $originalHeader]);
                
                while (($data = fgetcsv($handle)) !== FALSE) {
                    // Debug: Log raw data untuk troubleshooting
                    Log::info('Raw CSV row data:', [
                        'row_number' => $created + $skipped + 1,
                        'data' => $data,
                        'count' => count($data),
                        'is_empty' => empty(array_filter($data, function($value) { return trim($value) !== ''; }))
                    ]);
                    
                    // Skip baris kosong atau baris yang hanya berisi spasi
                    if (empty(array_filter($data, function($value) { return trim($value) !== ''; }))) {
                        Log::info('Skipping empty row:', $data);
                        continue;
                    }
                    
                    if (count($data) >= 11) { // Minimal 11 kolom sesuai template
                        // Pastikan semua kolom wajib tidak kosong
                        // Email tidak wajib pada import
                        $requiredColumns = [0, 10]; // Index untuk Nama Lengkap, PIN
                        $hasEmptyRequired = false;
                        foreach ($requiredColumns as $colIndex) {
                            if (empty(trim($data[$colIndex] ?? ''))) {
                                $hasEmptyRequired = true;
                                break;
                            }
                        }
                        
                        if ($hasEmptyRequired) {
                            $errors[] = "Baris " . ($created + $skipped + 1) . ": Data wajib kosong pada kolom Nama Lengkap atau PIN";
                            $skipped++;
                            continue;
                        }
                        
                        $row = array_combine($header, $data);
                        
                        // Debug: Log row data
                        Log::info('Processing row:', $row);
                        Log::info('Available keys in row:', array_keys($row));
                        
                        try {
                            // Normalisasi data dengan debugging
                            $namaLengkap = trim($row['Nama Lengkap'] ?? $row['nama_lengkap'] ?? '');
                            $email = trim($row['Email'] ?? $row['email'] ?? '');
                            $tanggalLahir = trim($row['Tanggal Lahir (YYYY-MM-DD)'] ?? $row['tanggal_lahir'] ?? '');
                            $jenisKelamin = trim($row['Jenis Kelamin (L/P)'] ?? $row['jenis_kelamin'] ?? '');
                            $alamat = trim($row['Alamat Rumah'] ?? $row['alamat'] ?? '');
                            $telepon = trim($row['Nomor Telepon'] ?? $row['telepon'] ?? '');
                            $instansi = trim($row['Instansi'] ?? $row['instansi'] ?? '');
                            $jabatan = trim($row['Jabatan Saat Ini'] ?? $row['jabatan'] ?? '');
                            $grade = trim($row['Grade'] ?? $row['grade'] ?? '');
                            $pin = trim($row['PIN'] ?? $row['pin'] ?? '');
                            
                            // Debug: Log nilai yang diambil
                            Log::info('Extracted values:', [
                                'namaLengkap' => $namaLengkap,
                                'email' => $email,
                                'tanggalLahir' => $tanggalLahir,
                                'jenisKelamin' => $jenisKelamin,
                                'pin' => $pin
                            ]);
                            
                            // Debug: Log validasi tanggal
                            Log::info('Tanggal validation:', [
                                'original' => $tanggalLahir,
                                'matches_yyyy_mm_dd' => preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggalLahir),
                                'matches_dd_mm_yyyy' => preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $tanggalLahir),
                                'matches_dd_mm_yyyy_dash' => preg_match('/^\d{1,2}-\d{1,2}-\d{4}$/', $tanggalLahir)
                            ]);
                            
                            // Debug: Log raw data untuk troubleshooting
                            Log::info('Raw row data:', $row);
                            Log::info('Header mapping check:', [
                                'Nama Lengkap exists' => isset($row['Nama Lengkap']),
                                'nama_lengkap exists' => isset($row['nama_lengkap']),
                                'Email exists' => isset($row['Email']),
                                'email exists' => isset($row['email'])
                            ]);
                            
                            // Validasi data wajib dengan detail kolom (hanya Nama & PIN wajib)
                            $missingFields = [];
                            if (empty($namaLengkap)) $missingFields[] = 'Nama Lengkap';
                            if (empty($pin)) $missingFields[] = 'PIN';

                            if (!empty($missingFields)) {
                                $errors[] = "Baris " . ($created + $skipped + 1) . ": Kolom wajib kosong: " . implode(', ', $missingFields);
                                $skipped++;
                                continue;
                            }

                            // Validasi email jika diisi (email opsional)
                            if (!empty($email)) {
                                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                    $errors[] = "Baris " . ($created + $skipped + 1) . ": Kolom Email - Format email tidak valid ('{$email}')";
                                    $skipped++;
                                    continue;
                                }
                            }
                            
                            // Validasi tanggal lahir (opsional). Jika kosong, biarkan null; jika diisi, validasi format.
                            if (!empty($tanggalLahir)) {
                                try {
                                    $tanggalLahirObj = null;
                                    // Coba format YYYY-MM-DD
                                    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggalLahir)) {
                                        $tanggalLahirObj = \Carbon\Carbon::createFromFormat('Y-m-d', $tanggalLahir);
                                    }
                                    // Coba format DD/MM/YYYY
                                    elseif (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $tanggalLahir)) {
                                        $tanggalLahirObj = \Carbon\Carbon::createFromFormat('d/m/Y', $tanggalLahir);
                                    }
                                    // Coba format DD-MM-YYYY
                                    elseif (preg_match('/^\d{1,2}-\d{1,2}-\d{4}$/', $tanggalLahir)) {
                                        $tanggalLahirObj = \Carbon\Carbon::createFromFormat('d-m-Y', $tanggalLahir);
                                    }
                                    if (!$tanggalLahirObj || !$tanggalLahirObj->isValid()) {
                                        throw new \Exception("Format tanggal tidak valid");
                                    }
                                    $tanggalLahir = $tanggalLahirObj->format('Y-m-d');
                                } catch (\Exception $e) {
                                    $errors[] = "Baris " . ($created + $skipped + 1) . ": Kolom Tanggal Lahir - Format tidak valid ('{$tanggalLahir}'). Gunakan format YYYY-MM-DD, DD/MM/YYYY, atau DD-MM-YYYY";
                                    $skipped++;
                                    continue;
                                }
                            } else {
                                $tanggalLahir = null;
                            }
                            
                            // Validasi jenis kelamin (opsional)
                            if (!empty($jenisKelamin)) {
                                if (!in_array(strtoupper($jenisKelamin), ['L', 'P'])) {
                                    $errors[] = "Baris " . ($created + $skipped + 1) . ": Kolom Jenis Kelamin - Nilai tidak valid ('{$jenisKelamin}'). Gunakan L untuk Laki-laki atau P untuk Perempuan";
                                    $skipped++;
                                    continue;
                                }
                                $jenisKelamin = strtoupper($jenisKelamin);
                            } else {
                                $jenisKelamin = null;
                            }
                            
                            // Validasi PIN
                            if (strlen($pin) < 6 || strlen($pin) > 10) {
                                $errors[] = "Baris " . ($created + $skipped + 1) . ": Kolom PIN - Panjang tidak valid ('{$pin}'). PIN harus 6-10 karakter";
                                $skipped++;
                                continue;
                            }
                            
                            // Cek email unik jika diisi
                            if (!empty($email)) {
                                if (User::where('email', $email)->exists()) {
                                    $errors[] = "Baris " . ($created + $skipped + 1) . ": Kolom Email - Email '{$email}' sudah terdaftar dalam sistem";
                                    $skipped++;
                                    continue;
                                }
                            }
                            
                            // Cek PIN unik
                            if (Peserta::where('pin', $pin)->exists()) {
                                $errors[] = "Baris " . ($created + $skipped + 1) . ": Kolom PIN - PIN '{$pin}' sudah digunakan oleh peserta lain";
                                $skipped++;
                                continue;
                            }

                            // Buat user
                            // email di users Wajib unik dan tidak null. Jika email kosong, buat placeholder unik berbasis PIN/nama.
                            $userEmail = $email;
                            if (empty($userEmail)) {
                                $baseSlug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '.', $namaLengkap));
                                $baseSlug = trim($baseSlug, '.');
                                if (empty($baseSlug)) {
                                    $baseSlug = 'peserta';
                                }
                                $candidate = $baseSlug . '+' . strtolower($pin) . '@noemail.local';
                                $suffix = 1;
                                while (User::where('email', $candidate)->exists()) {
                                    $candidate = $baseSlug . '+' . strtolower($pin) . '.' . $suffix . '@noemail.local';
                                    $suffix++;
                                }
                                $userEmail = $candidate;
                            }

                            $user = User::create([
                                'name' => $namaLengkap,
                                'email' => $userEmail,
                                'password' => Hash::make($pin),
                                'role' => 'peserta'
                            ]);

                            // Buat peserta
                            Peserta::create([
                                'user_id' => $user->id,
                                'nama_lengkap' => $namaLengkap,
                                'email' => $email ?: null,
                                'tanggal_lahir' => $tanggalLahir ?: null,
                                'jenis_kelamin' => $jenisKelamin ?: null,
                                'alamat' => $alamat ?: null,
                                'telepon' => $telepon ?: null,
                                'instansi' => $instansi ?: null,
                                'jabatan' => $jabatan ?: null,
                                'grade' => $grade ?: null,
                                'pin' => $pin,
                                'aktif' => true
                            ]);

                            $created++;
                            Log::info("Successfully created peserta: {$namaLengkap}");
                            
                        } catch (\Exception $e) {
                            $errors[] = "Baris " . ($created + $skipped + 1) . ": " . $e->getMessage();
                            Log::error("Error processing row: " . $e->getMessage(), $row);
                            $skipped++;
                        }
                    } else {
                        $errors[] = "Baris " . ($created + $skipped + 1) . ": Jumlah kolom tidak sesuai (ditemukan: " . count($data) . ", diharapkan: minimal 11). Pastikan semua kolom wajib terisi";
                        $skipped++;
                    }
                }
                
                // Debug: Log summary
                Log::info('CSV processing completed:', [
                    'total_rows_processed' => $created + $skipped,
                    'created' => $created,
                    'skipped' => $skipped,
                    'errors_count' => count($errors)
                ]);
                
                fclose($handle);
            }

            // Hapus file temporary
            if (file_exists($fullPath)) {
            unlink($fullPath);
            }

            $message = "Berhasil import {$created} peserta.";
            if ($skipped > 0) {
                $message .= " {$skipped} data dilewati.";
            }

            if (!empty($errors)) {
                $message .= " Error: " . implode('; ', array_slice($errors, 0, 5));
                if (count($errors) > 5) {
                    $message .= " (dan " . (count($errors) - 5) . " error lainnya)";
                }
            }

            // Clean up session data setelah import selesai
            session()->forget(['csv_header', 'csv_header_original']);
            
            return redirect()->route('admin.peserta.index')
                ->with('success', $message)
                ->with('imported_count', $created)
                ->with('skipped_count', $skipped)
                ->with('import_errors', $errors);

        } catch (\Exception $e) {
            // Clean up session data jika terjadi error
            session()->forget(['csv_header', 'csv_header_original']);
            
            Log::error('Error importing peserta: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }

    // ========================================
    // PROGRESS MANAGEMENT
    // ========================================

    /**
     * Tampilkan daftar progress
     */
    public function progressIndex() 
    {
        // Progress sudah dihitung langsung di view dengan logika yang sama seperti dashboard user
        // Tidak perlu mengirim data progressList karena view sudah mengambil data sendiri
        return view('admin.progress.index');
    }

    /**
     * Tampilkan progress per peserta
     */
    public function progressPeserta($pesertaId)
    {
        $peserta = Peserta::with(['kemajuanPenilaian.penilaian'])->findOrFail($pesertaId);
        $progressList = $peserta->kemajuanPenilaian()->with('penilaian')->get();
        
        return view('admin.progress.peserta', compact('peserta', 'progressList'));
    }

    /**
     * Update status progress
     */
    public function updateProgressStatus(Request $request, $kemajuanId)
    {
        $request->validate([
            'status' => 'required|in:belum_mulai,sedang_berlangsung,selesai,dibatalkan'
        ]);

        try {
            $kemajuan = KemajuanPenilaian::findOrFail($kemajuanId);
            $kemajuan->update(['status' => $request->status]);
            
            return response()->json(['success' => true, 'message' => 'Status berhasil diupdate']);
        } catch (\Exception $e) {
            Log::error('Error updating progress status: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat update status']);
        }
    }

    /**
     * Export data progress assessment
     */
    public function exportProgress(\Illuminate\Http\Request $request)
    {
        try {
            $progressList = KemajuanPenilaian::with(['peserta', 'penilaian'])
                ->orderBy('created_at', 'desc')
                ->get();

            $delimiterParam = $request->get('delimiter', ',');
            $delimiter = ($delimiterParam === ';' || $delimiterParam === 'semicolon') ? ';' : ',';

            $filename = "progress_assessment_" . date('Y-m-d_H-i-s') . ".csv";
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename={$filename}",
            ];

            $callback = function() use ($progressList, $delimiter) {
                $file = fopen('php://output', 'w');
                
                // Header CSV
                fputcsv($file, [
                    'Nama Peserta',
                    'Email',
                    'Assessment',
                    'Jenis Assessment',
                    'Status',
                    'Waktu Mulai',
                    'Waktu Selesai',
                    'Durasi (menit)',
                    'Progress (%)',
                    'Catatan'
                ], $delimiter);

                // Data
                foreach ($progressList as $progress) {
                    fputcsv($file, [
                        $progress->peserta->nama_lengkap,
                        $progress->peserta->email,
                        $progress->penilaian->nama_penilaian,
                        $progress->penilaian->jenis,
                        $progress->status,
                        $progress->waktu_mulai ? $progress->waktu_mulai->format('Y-m-d H:i:s') : '-',
                        $progress->waktu_selesai ? $progress->waktu_selesai->format('Y-m-d H:i:s') : '-',
                        $progress->durasi_menit ?? '-',
                        $progress->progress ?? '-',
                        $progress->catatan ?? '-'
                    ], $delimiter);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            Log::error('Error exporting progress: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat export data.');
        }
    }

    // ========================================
    // REVIEW MANAGEMENT
    // ========================================

    /**
     * Tampilkan review studi kasus
     */
    public function reviewStudiKasus()
    {
        $jawabanList = JawabanStudiKasus::with(['peserta', 'penilaian'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.review.studi-kasus', compact('jawabanList'));
    }

    /**
     * Tampilkan review in-tray
     */
    public function reviewInTray()
    {
        $jawabanList = JawabanInTray::with(['peserta', 'penilaian'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.review.in-tray', compact('jawabanList'));
    }

    /**
     * Tampilkan review roleplay
     */
    public function reviewRoleplay()
    {
        $catatanList = CatatanRoleplay::with(['peserta', 'penilaian'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.review.roleplay', compact('catatanList'));
    }

    /**
     * Tampilkan review FGD
     */
    public function reviewFgd()
    {
        $catatanList = CatatanFgd::with(['peserta', 'penilaian'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.review.fgd', compact('catatanList'));
    }

    /**
     * Export data roleplay
     */
    public function exportRoleplay()
    {
        try {
            $catatanList = CatatanRoleplay::with(['peserta', 'penilaian'])
                ->orderBy('created_at', 'desc')
                ->get();

            $filename = "roleplay_review_" . date('Y-m-d_H-i-s') . ".csv";
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename={$filename}",
            ];

            $callback = function() use ($catatanList) {
                $file = fopen('php://output', 'w');
                
                // Header CSV
                fputcsv($file, [
                    'Nama Peserta',
                    'Email',
                    'Assessment',
                    'Jenis Assessment',
                    'Catatan',
                    'Waktu Submit',
                    'Status'
                ]);

                // Data
                foreach ($catatanList as $catatan) {
                    fputcsv($file, [
                        $catatan->peserta->nama_lengkap,
                        $catatan->peserta->email,
                        $catatan->penilaian->nama_penilaian,
                        $catatan->penilaian->jenis,
                        $catatan->catatan,
                        $catatan->created_at->format('Y-m-d H:i:s'),
                        'Submitted'
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            Log::error('Error exporting roleplay: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat export data.');
        }
    }

    /**
     * Export data FGD
     */
    public function exportFgd()
    {
        try {
            $catatanList = CatatanFgd::with(['peserta', 'penilaian'])
                ->orderBy('created_at', 'desc')
                ->get();

            $filename = "fgd_review_" . date('Y-m-d_H-i-s') . ".csv";
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename={$filename}",
            ];

            $callback = function() use ($catatanList) {
                $file = fopen('php://output', 'w');
                
                // Header CSV
                fputcsv($file, [
                    'Nama Peserta',
                    'Email',
                    'Assessment',
                    'Jenis Assessment',
                    'Catatan',
                    'Waktu Submit',
                    'Status'
                ]);

                // Data
                foreach ($catatanList as $catatan) {
                    fputcsv($file, [
                        $catatan->peserta->nama_lengkap,
                        $catatan->peserta->email,
                        $catatan->penilaian->nama_penilaian,
                        $catatan->penilaian->jenis,
                        $catatan->catatan,
                        $catatan->created_at->format('Y-m-d H:i:s'),
                        'Submitted'
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            Log::error('Error exporting FGD: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat export data.');
        }
    }

    // ========================================
    // URUTAN MANAGEMENT
    // ========================================

    /**
     * Tampilkan pengaturan urutan assessment
     */


    // ========================================
    // ASSESSMENT PARTICIPANT MANAGEMENT
    // ========================================

    /**
     * Tampilkan halaman pemasangan assessment dengan peserta
     */




    // ========================================
    // PESERTA MANAGEMENT
    // ========================================

    // ========================================
    // SESI MANAGEMENT
    // ========================================

    /**
     * Tampilkan daftar sesi penilaian
     */
    public function sesiIndex()
    {
        $sesiList = SesiPenilaian::with('assessments.penilaian')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.sesi.index', compact('sesiList'));
    }

    /**
     * Tampilkan form create sesi
     */
    public function sesiCreate()
    {
        $assessmentTypes = Penilaian::select('id', 'nama', 'jenis')
            ->whereIn('jenis', ['studi_kasus', 'in_tray', 'roleplay', 'fgd'])
            ->orderBy('jenis')
            ->orderBy('nama')
            ->get();

        return view('admin.sesi.create', compact('assessmentTypes'));
    }

    /**
     * Simpan sesi baru
     */
    public function sesiStore(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'durasi_menit' => 'nullable|integer|min:1',
            'catatan' => 'nullable|string',
            'assessments' => 'required|array|min:1',
            'assessments.*.penilaian_id' => 'required|exists:penilaian,id',
            'assessments.*.urutan' => 'required|integer|min:1',
            'assessments.*.durasi_default' => 'nullable|integer|min:1',
            'assessments.*.instruksi_khusus' => 'nullable|string',
            'assessments.*.file_pdf' => 'nullable|file|mimes:pdf|max:10240'
        ]);

        try {
            DB::beginTransaction();

            // Buat sesi baru
            $sesi = SesiPenilaian::create([
                'nama' => $request->nama,
                        'durasi_menit' => $request->durasi_menit,
                'catatan' => $request->catatan,
                'status' => 'draft',
                'aktif' => true
            ]);

            // Simpan assessment yang dipilih
            foreach ($request->assessments as $index => $assessment) {
                $sesiAssessment = SesiAssessment::create([
                    'sesi_penilaian_id' => $sesi->id,
                    'penilaian_id' => $assessment['penilaian_id'],
                    'urutan' => $assessment['urutan'],
                    'durasi_default' => $assessment['durasi_default'] ?? null,
                    'instruksi_khusus' => $assessment['instruksi_khusus'] ?? null,
                    'aktif' => true
                ]);

                // Simpan memos untuk in_tray bila ada (ke tabel latihan_in_tray)
                try {
                    $penilaian = Penilaian::find($assessment['penilaian_id']);
                    if ($penilaian && $penilaian->jenis === 'in_tray') {
                        $memos = $assessment['memos'] ?? [];
                        if (is_array($memos) && count(array_filter($memos, fn($m) => trim((string)$m) !== '')) > 0) {
                            // Hapus memo lama untuk sesi penilaian ini
                            LatihanInTray::where('sesi_penilaian_id', $sesi->id)
                                ->where('penilaian_id', $assessment['penilaian_id'])
                                ->delete();
                            $order = 1;
                            foreach ($memos as $memo) {
                                if (trim((string)$memo) === '') continue;
                                LatihanInTray::create([
                                    'penilaian_id' => $assessment['penilaian_id'],
                                    'sesi_penilaian_id' => $sesi->id,
                                    'konten_memo' => $memo,
                                    'urutan' => $order++,
                                    'aktif' => true,
                                ]);
                            }
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Gagal menyimpan memos in-tray (create): ' . $e->getMessage());
                }
            }

            DB::commit();

            return redirect()->route('admin.sesi.index')
                ->with('success', 'Sesi berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating session: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat membuat sesi: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Tampilkan detail sesi
     */
    public function sesiShow($id)
    {
        $sesi = SesiPenilaian::with(['assessments.penilaian', 'participants.peserta'])
            ->findOrFail($id);

        return view('admin.sesi.show', compact('sesi'));
    }

    /**
     * Tampilkan form edit sesi
     */
    public function sesiEdit($id)
    {
        $sesi = SesiPenilaian::with('assessments.penilaian')->findOrFail($id);

        // Sertakan file_pdf agar bisa ditampilkan pada opsi studi_kasus
        $assessmentTypes = Penilaian::select('id', 'nama', 'jenis', 'file_pdf')
            ->whereIn('jenis', ['studi_kasus', 'in_tray', 'roleplay', 'fgd'])
            ->orderBy('jenis')
            ->orderBy('nama')
            ->get();

        // Siapkan existing assessments lengkap dengan memos untuk jenis in_tray
        $existingAssessments = $sesi->assessments
            ->sortBy('urutan')
            ->values()
            ->map(function ($sesiAssessment) use ($sesi) {
                $memos = [];
                if ($sesiAssessment->penilaian && $sesiAssessment->penilaian->jenis === 'in_tray') {
                    $memos = \App\Models\LatihanInTray::select('urutan', 'konten_memo')
                        ->where('sesi_penilaian_id', $sesi->id)
                        ->where('penilaian_id', $sesiAssessment->penilaian_id)
                        ->orderBy('urutan')
                        ->get()
                        ->unique('urutan')
                        ->sortBy('urutan')
                        ->pluck('konten_memo')
                        ->values()
                        ->toArray();
                }

                return [
                    'penilaian_id' => $sesiAssessment->penilaian_id,
                    'urutan' => $sesiAssessment->urutan,
                    'durasi_default' => $sesiAssessment->durasi_default,
                    'instruksi_khusus' => $sesiAssessment->instruksi_khusus,
                    'memos' => $memos,
                ];
            });

        return view('admin.sesi.edit', compact('sesi', 'assessmentTypes', 'existingAssessments'));
    }

    /**
     * Update sesi
     */
    public function sesiUpdate(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'durasi_menit' => 'nullable|integer|min:1',
            'catatan' => 'nullable|string',
            'assessments' => 'required|array|min:1',
            'assessments.*.penilaian_id' => 'required|exists:penilaian,id',
            'assessments.*.urutan' => 'required|integer|min:1',
            'assessments.*.durasi_default' => 'nullable|integer|min:1',
            'assessments.*.instruksi_khusus' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $sesi = SesiPenilaian::findOrFail($id);

            // Update sesi
            $sesi->update([
                'nama' => $request->nama,
                'durasi_menit' => $request->durasi_menit,
                'catatan' => $request->catatan
            ]);

            // Hapus assessment lama
            $sesi->assessments()->delete();

            // Simpan assessment baru
            foreach ($request->assessments as $index => $assessment) {
                $sesiAssessment = SesiAssessment::create([
                    'sesi_penilaian_id' => $sesi->id,
                    'penilaian_id' => $assessment['penilaian_id'],
                    'urutan' => $assessment['urutan'],
                    'durasi_default' => $assessment['durasi_default'] ?? null,
                    'instruksi_khusus' => $assessment['instruksi_khusus'] ?? null,
                    'aktif' => true
                ]);

                // Simpan memos untuk in_tray (ke tabel latihan_in_tray)
                try {
                    $penilaian = Penilaian::find($assessment['penilaian_id']);
                    if ($penilaian && $penilaian->jenis === 'in_tray') {
                        $memos = $assessment['memos'] ?? [];
                        if (is_array($memos) && count(array_filter($memos, fn($m) => trim((string)$m) !== '')) > 0) {
                            // hapus data lama untuk sesi penilaian ini
                            LatihanInTray::where('sesi_penilaian_id', $sesi->id)
                                ->where('penilaian_id', $assessment['penilaian_id'])
                                ->delete();
                            $order = 1;
                            foreach ($memos as $memo) {
                                if (trim((string)$memo) === '') continue;
                                LatihanInTray::create([
                                    'penilaian_id' => $assessment['penilaian_id'],
                                    'sesi_penilaian_id' => $sesi->id,
                                    'konten_memo' => $memo,
                                    'urutan' => $order++,
                                    'aktif' => true,
                                ]);
                            }
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Gagal menyimpan memos in-tray (update): ' . $e->getMessage());
                }

                // Jika jenis studi_kasus dan ada file upload, simpan ke Penilaian
                try {
                    $penilaian = Penilaian::find($assessment['penilaian_id']);
                    if ($penilaian && $penilaian->jenis === 'studi_kasus') {
                        if ($request->hasFile("assessments.$index.file_pdf")) {
                            // Hapus file lama jika ada
                            if (!empty($penilaian->file_pdf)) {
                                Storage::disk('public')->delete($penilaian->file_pdf);
                            }
                            // Upload baru
                            $filePath = $request->file("assessments.$index.file_pdf")->store('assessments/pdf', 'public');
                            $penilaian->update(['file_pdf' => $filePath]);
                        }
                    }
        } catch (\Exception $e) {
                    Log::error('Gagal menyimpan PDF studi kasus pada update sesi: ' . $e->getMessage());
                }
            }

            DB::commit();

            return redirect()->route('admin.sesi.index')
                ->with('success', 'Sesi berhasil diupdate!');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating session: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengupdate sesi.')
                ->withInput();
        }
    }

    /**
     * Upload PDF untuk assessment studi kasus
     */
    public function uploadAssessmentPdf(Request $request, $penilaianId)
    {
        $request->validate([
            'file_pdf' => 'required|file|mimes:pdf|max:10240' // Max 10MB
        ]);

        try {
            $penilaian = Penilaian::findOrFail($penilaianId);
            
            // Hapus file lama jika ada
            if ($penilaian->file_pdf) {
                Storage::disk('public')->delete($penilaian->file_pdf);
            }
            
            // Upload file baru
            $filePath = $request->file('file_pdf')->store('assessments/pdf', 'public');
            
            // Update database
            $penilaian->update(['file_pdf' => $filePath]);
            
            return response()->json([
                'success' => true,
                'message' => 'PDF berhasil diupload!',
                'file_path' => $filePath
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error uploading PDF: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat upload PDF.'
            ], 500);
        }
    }

    /**
     * Hapus PDF assessment
     */
    public function deleteAssessmentPdf($penilaianId)
    {
        try {
            $penilaian = Penilaian::findOrFail($penilaianId);
            
            if ($penilaian->file_pdf) {
                Storage::disk('public')->delete($penilaian->file_pdf);
                $penilaian->update(['file_pdf' => null]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'PDF berhasil dihapus!'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada PDF untuk dihapus.'
            ], 404);

        } catch (\Exception $e) {
            Log::error('Error deleting PDF: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus PDF.'
            ], 500);
        }
    }

    /**
     * Update status sesi
     */
    public function sesiUpdateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:draft,pending,active,paused,completed'
        ]);

        try {
            $sesi = SesiPenilaian::findOrFail($id);
            $sesi->update(['status' => $request->status]);
            
            return response()->json([
                'success' => true,
                'message' => 'Status sesi berhasil diupdate!',
                'new_status' => $request->status,
                'status_label' => $sesi->fresh()->status_label
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating session status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengupdate status sesi.'
            ], 500);
        }
    }

    /**
     * Hapus sesi
     */
    public function sesiDestroy($id)
    {
        try {
            $sesi = SesiPenilaian::findOrFail($id);
            $sesi->delete();

            return redirect()->route('admin.sesi.index')
                ->with('success', 'Sesi berhasil dihapus!');

        } catch (\Exception $e) {
            Log::error('Error deleting session: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus sesi.');
        }
    }

    // ========================================
    // MANAJEMEN PESERTA DI SESI
    // ========================================

    /**
     * Tampilkan daftar peserta yang sudah terdaftar di sesi
     */
    public function sesiPeserta($id)
    {
        $sesi = SesiPenilaian::with(['participants.peserta'])->findOrFail($id);
        
        // Ambil semua peserta yang tersedia untuk didaftarkan
        $availablePeserta = Peserta::where('aktif', true)
            ->whereNotIn('id', $sesi->participants->pluck('peserta_id'))
            ->orderBy('nama_lengkap')
            ->get();

        return view('admin.sesi.peserta', compact('sesi', 'availablePeserta'));
    }

    /**
     * Daftarkan peserta ke sesi
     */
    public function sesiPesertaStore(Request $request, $id)
    {
        $request->validate([
            'peserta_ids' => 'required|array|min:1',
            'peserta_ids.*' => 'required|exists:peserta,id'
        ]);

        try {
            $sesi = SesiPenilaian::with('assessments')->findOrFail($id);
            
            // Cek apakah peserta sudah terdaftar
            $existingPesertaIds = $sesi->participants->pluck('peserta_id')->toArray();
            $newPesertaIds = array_diff($request->peserta_ids, $existingPesertaIds);

            if (empty($newPesertaIds)) {
                return redirect()->back()->with('error', 'Semua peserta yang dipilih sudah terdaftar di sesi ini.');
            }

            // Daftarkan peserta baru
            foreach ($newPesertaIds as $pesertaId) {
                AssessmentParticipant::create([
                    'sesi_penilaian_id' => $sesi->id,
                    'peserta_id' => $pesertaId,
                    'status' => 'aktif',
                    'waktu_mulai' => null,
                    'waktu_selesai' => null,
                    'durasi_menit' => $sesi->durasi_menit,
                    'catatan_admin' => null
                ]);
            }

            return redirect()->back()
                ->with('success', 'Peserta berhasil didaftarkan ke sesi!');

        } catch (\Exception $e) {
            Log::error('Error registering participants: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mendaftarkan peserta: ' . $e->getMessage());
        }
    }

    /**
     * Hapus peserta dari sesi
     */
    public function sesiPesertaDestroy($id, $pesertaId)
    {
        try {
            $participant = AssessmentParticipant::where('sesi_penilaian_id', $id)
                ->where('peserta_id', $pesertaId)
                ->firstOrFail();

            $participant->delete();

            return redirect()->back()
                ->with('success', 'Peserta berhasil dihapus dari sesi!');

        } catch (\Exception $e) {
            Log::error('Error removing participant: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus peserta dari sesi.');
        }
    }
}
