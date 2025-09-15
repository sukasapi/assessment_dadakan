<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penilaian;
use App\Models\Peserta;
use App\Models\JawabanStudiKasus;
use App\Models\JawabanInTray;
use App\Models\CatatanRoleplay;
use App\Models\CatatanFgd;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class AssessmentInputController extends Controller
{
    /**
     * Display a listing of assessment inputs with filters
     */
    public function index(Request $request)
    {
        $query = $this->buildQuery($request);
        
        $perPage = $request->get('per_page', 15);
        $inputs = $query->paginate($perPage);
        
        // Get filter options
        $assessments = Penilaian::with('sesiPenilaian')
            ->where('aktif', true)
            ->orderBy('nama')
            ->get();
            
        $institutions = Peserta::select('instansi')
            ->whereNotNull('instansi')
            ->where('instansi', '!=', '')
            ->distinct()
            ->orderBy('instansi')
            ->pluck('instansi');
        
        return view('admin.assessment-inputs.index', compact(
            'inputs', 
            'assessments', 
            'institutions'
        ));
    }

    /**
     * Export assessment inputs to CSV
     */
    public function export(Request $request)
    {
        $delimiter = $request->get('delimiter', ';');
        $query = $this->buildQuery($request);
        $inputs = $query->get();
        
        $filename = 'assessment_inputs_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($inputs, $delimiter) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // CSV Headers
            $headers = [
                'Nama Peserta',
                'Instansi',
                'Jabatan',
                'Assessment',
                'Jenis Assessment',
                'Sesi Penilaian',
                'Jawaban/Catatan',
                'Status',
                'Waktu Simpan'
            ];
            fputcsv($file, $headers, $delimiter);
            
            foreach ($inputs as $input) {
                $row = [
                    $input->peserta_nama,
                    $input->peserta_instansi,
                    $input->peserta_jabatan,
                    $input->assessment_nama,
                    $input->assessment_jenis,
                    $input->sesi_nama,
                    $this->formatAnswer($input),
                    $input->status,
                    $input->waktu_simpan ? \Carbon\Carbon::parse($input->waktu_simpan)->format('d/m/Y H:i:s') : '-'
                ];
                fputcsv($file, $row, $delimiter);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Build the query for filtering assessment inputs
     */
    private function buildQuery(Request $request)
    {
        $queries = [];
        
        // Get filters first
        $filters = $this->getFilters($request);
        
        // Build queries for each table that has data
        $tables = [
            'jawaban_studi_kasus' => [
                'table' => 'jawaban_studi_kasus as jsk',
                'select' => [
                    'jsk.id',
                    'p.nama_lengkap as peserta_nama',
                    'p.instansi as peserta_instansi',
                    'p.jabatan_saat_ini as peserta_jabatan',
                    'pen.nama as assessment_nama',
                    'pen.jenis as assessment_jenis',
                    'sp.nama as sesi_nama',
                    'jsk.jawaban as jawaban_catatan',
                    'jsk.status',
                    'jsk.waktu_simpan',
                    DB::raw("'studi_kasus' as input_type")
                ],
                'joins' => [
                    'peserta as p' => ['jsk.peserta_id', '=', 'p.id'],
                    'penilaian as pen' => ['jsk.penilaian_id', '=', 'pen.id'],
                    'sesi_penilaian as sp' => ['pen.sesi_penilaian_id', '=', 'sp.id']
                ]
            ],
            'jawaban_in_tray' => [
                'table' => 'jawaban_in_tray as jit',
                'select' => [
                    'jit.id',
                    'p.nama_lengkap as peserta_nama',
                    'p.instansi as peserta_instansi',
                    'p.jabatan_saat_ini as peserta_jabatan',
                    'pen.nama as assessment_nama',
                    'pen.jenis as assessment_jenis',
                    'sp.nama as sesi_nama',
                    DB::raw("CONCAT('Prioritas: ', jit.urutan_prioritas, ', Disposisi: ', jit.disposisi, ', Item: ', COALESCE(lit.konten_memo, 'N/A')) as jawaban_catatan"),
                    'jit.status',
                    'jit.waktu_simpan',
                    DB::raw("'in_tray' as input_type")
                ],
                'joins' => [
                    'peserta as p' => ['jit.peserta_id', '=', 'p.id'],
                    'penilaian as pen' => ['jit.penilaian_id', '=', 'pen.id'],
                    'sesi_penilaian as sp' => ['pen.sesi_penilaian_id', '=', 'sp.id'],
                    'latihan_in_tray as lit' => ['jit.latihan_in_tray_id', '=', 'lit.id']
                ]
            ],
            'catatan_roleplay' => [
                'table' => 'catatan_roleplay as cr',
                'select' => [
                    'cr.id',
                    'p.nama_lengkap as peserta_nama',
                    'p.instansi as peserta_instansi',
                    'p.jabatan_saat_ini as peserta_jabatan',
                    'pen.nama as assessment_nama',
                    'pen.jenis as assessment_jenis',
                    'sp.nama as sesi_nama',
                    'cr.catatan as jawaban_catatan',
                    'cr.status',
                    'cr.waktu_simpan',
                    DB::raw("'roleplay' as input_type")
                ],
                'joins' => [
                    'peserta as p' => ['cr.peserta_id', '=', 'p.id'],
                    'penilaian as pen' => ['cr.penilaian_id', '=', 'pen.id'],
                    'sesi_penilaian as sp' => ['pen.sesi_penilaian_id', '=', 'sp.id']
                ]
            ],
            'catatan_fgd' => [
                'table' => 'catatan_fgd as cf',
                'select' => [
                    'cf.id',
                    'p.nama_lengkap as peserta_nama',
                    'p.instansi as peserta_instansi',
                    'p.jabatan_saat_ini as peserta_jabatan',
                    'pen.nama as assessment_nama',
                    'pen.jenis as assessment_jenis',
                    'sp.nama as sesi_nama',
                    'cf.catatan as jawaban_catatan',
                    'cf.status',
                    'cf.waktu_simpan',
                    DB::raw("'fgd' as input_type")
                ],
                'joins' => [
                    'peserta as p' => ['cf.peserta_id', '=', 'p.id'],
                    'penilaian as pen' => ['cf.penilaian_id', '=', 'pen.id'],
                    'sesi_penilaian as sp' => ['pen.sesi_penilaian_id', '=', 'sp.id']
                ]
            ]
        ];
        
        // Build queries for tables that have data
        foreach ($tables as $tableName => $config) {
            $query = DB::table($config['table'])->select($config['select']);
            
            // Add joins
            foreach ($config['joins'] as $joinTable => $joinCondition) {
                $query->join($joinTable, $joinCondition[0], $joinCondition[1], $joinCondition[2]);
            }
            
            // Apply filters
            if (!empty($filters['nama'])) {
                $query->where('p.nama_lengkap', 'like', '%' . $filters['nama'] . '%');
            }
            
            if (!empty($filters['instansi'])) {
                $query->where('p.instansi', 'like', '%' . $filters['instansi'] . '%');
            }
            
            if (!empty($filters['assessment_id'])) {
                $query->where('pen.id', $filters['assessment_id']);
            }
            
            if (!empty($filters['input_type'])) {
                $inputType = $filters['input_type'];
                if (($inputType === 'studi_kasus' && $tableName === 'jawaban_studi_kasus') ||
                    ($inputType === 'in_tray' && $tableName === 'jawaban_in_tray') ||
                    ($inputType === 'roleplay' && $tableName === 'catatan_roleplay') ||
                    ($inputType === 'fgd' && $tableName === 'catatan_fgd')) {
                    // Keep this query
                } else {
                    continue; // Skip this query if filter doesn't match
                }
            }
            
            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }
            
            $queries[] = $query;
        }
        
        if (empty($queries)) {
            // Return empty query if no tables have data
            return DB::table('peserta')->whereRaw('1 = 0');
        }
        
        // Union all queries
        $unionQuery = $queries[0];
        for ($i = 1; $i < count($queries); $i++) {
            $unionQuery = $unionQuery->union($queries[$i]);
        }
        
        // Create a subquery for ordering and pagination
        $finalQuery = DB::table(DB::raw("({$unionQuery->toSql()}) as combined"))
            ->mergeBindings($unionQuery)
            ->orderBy('waktu_simpan', 'desc');

        return $finalQuery;
    }

    /**
     * Get filters from request
     */
    private function getFilters(Request $request)
    {
        return [
            'nama' => $request->get('nama'),
            'instansi' => $request->get('instansi'),
            'assessment_id' => $request->get('assessment_id'),
            'input_type' => $request->get('input_type'),
            'status' => $request->get('status'),
        ];
    }

    /**
     * Format answer based on input type
     */
    private function formatAnswer($input)
    {
        $answer = $input->jawaban_catatan;
        
        // Truncate long answers for display
        if (strlen($answer) > 200) {
            $answer = substr($answer, 0, 200) . '...';
        }
        
        return $answer;
    }
}
