<?php

namespace App\Http\Controllers\Debug;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfDebugController extends Controller
{
    public function checkPdfEnvironment(Request $request)
    {
        $debug = [];
        
        // 1. PHP Environment Check
        $debug['php'] = [
            'version' => PHP_VERSION,
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'current_memory' => memory_get_usage(true) / 1024 / 1024 . 'MB',
            'peak_memory' => memory_get_peak_usage(true) / 1024 / 1024 . 'MB',
        ];
        
        // 2. Laravel Environment
        $debug['laravel'] = [
            'version' => app()->version(),
            'environment' => app()->environment(),
            'debug_mode' => config('app.debug'),
            'timezone' => config('app.timezone'),
        ];
        
        // 3. DomPDF Check
        $debug['dompdf'] = [
            'class_exists' => class_exists('\\Dompdf\\Dompdf'),
            'facade_available' => class_exists('\\Barryvdh\\DomPDF\\Facade\\Pdf'),
            'config_exists' => config('dompdf') !== null,
        ];
        
        // 4. Storage Check
        $debug['storage'] = [
            'writable' => is_writable(storage_path()),
            'free_space' => disk_free_space(storage_path()) / 1024 / 1024 . 'MB',
            'temp_dir' => sys_get_temp_dir(),
            'temp_writable' => is_writable(sys_get_temp_dir()),
        ];
        
        // 5. Template Check
        $templates = ['domisili', 'ktp', 'kk', 'skck', 'belum_menikah', 'tidak_mampu', 'usaha', 'kematian', 'kelahiran', 'kehilangan'];
        $debug['templates'] = [];
        
        foreach ($templates as $template) {
            $templatePath = 'user.surat.print.' . $template;
            $debug['templates'][$template] = [
                'exists' => view()->exists($templatePath),
                'path' => $templatePath,
            ];
        }
        
        // 6. Test Simple PDF Generation
        try {
            $pdf = Pdf::loadHTML('<html><body><h1>Test PDF</h1><p>Basic PDF generation test</p></body></html>');
            $pdfContent = $pdf->output();
            $debug['simple_pdf_test'] = [
                'success' => true,
                'content_length' => strlen($pdfContent),
            ];
        } catch (\Exception $e) {
            $debug['simple_pdf_test'] = [
                'success' => false,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ];
        }
        
        // 7. Recent PDF Errors
        $debug['recent_errors'] = [];
        if (Storage::exists('pdf_errors.log')) {
            $errors = Storage::get('pdf_errors.log');
            $lines = explode("\n", $errors);
            $debug['recent_errors'] = array_slice(array_filter($lines), -10);
        }
        
        return response()->json($debug, 200, [], JSON_PRETTY_PRINT);
    }
    
    public function testTemplate(Request $request, $type)
    {
        $debug = [];
        
        try {
            $dummyData = $this->createDummyData($type);
            $templatePath = 'user.surat.print.' . $type;
            
            if (!view()->exists($templatePath)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Template tidak ditemukan: ' . $templatePath
                ], 404);
            }
            
            $viewContent = view($templatePath, $dummyData)->render();
            
            $debug['template_test'] = [
                'template' => $templatePath,
                'view_success' => true,
                'content_length' => strlen($viewContent),
                'contains_html' => str_contains($viewContent, '<html>'),
                'contains_body' => str_contains($viewContent, '<body>'),
            ];
            
            try {
                $pdf = Pdf::loadView($templatePath, $dummyData);
                $pdfContent = $pdf->output();
                
                $debug['pdf_test'] = [
                    'success' => true,
                    'pdf_size' => strlen($pdfContent),
                ];
                
                if ($request->get('download') === 'true') {
                    return $pdf->download('test-' . $type . '.pdf');
                }
                
            } catch (\Exception $e) {
                $debug['pdf_test'] = [
                    'success' => false,
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ];
            }
            
        } catch (\Exception $e) {
            $debug['template_test'] = [
                'success' => false,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ];
        }
        
        return response()->json($debug, 200, [], JSON_PRETTY_PRINT);
    }
    
    public function clearErrorLogs()
    {
        if (Storage::exists('pdf_errors.log')) {
            Storage::delete('pdf_errors.log');
            return response()->json(['success' => true, 'message' => 'PDF error logs cleared']);
        }
        
        return response()->json(['success' => false, 'message' => 'No error logs found']);
    }
    
    private function createDummyData($type)
    {
        $baseData = [
            'tanggal_cetak' => now()->format('d F Y'),
            'user' => (object) [
                'name' => 'Test User',
                'nik' => '1234567890123456'
            ]
        ];
        
        switch ($type) {
            case 'domisili':
                $baseData['surat'] = (object) [
                    'nama' => 'John Doe',
                    'nik' => '1234567890123456',
                    'tempat_lahir' => 'Jakarta',
                    'tanggal_lahir' => '1990-01-01',
                    'jenis_kelamin' => 'Laki-laki',
                    'agama' => 'Islam',
                    'status_perkawinan' => 'Belum Kawin',
                    'pekerjaan' => 'Programmer',
                    'alamat' => 'Jl. Test No. 123',
                    'keperluan' => 'Testing PDF'
                ];
                break;
                
            case 'ktp':
                $baseData['surat'] = (object) [
                    'nama_lengkap' => 'John Doe',
                    'nik' => '1234567890123456',
                    'tempat_lahir' => 'Jakarta',
                    'tanggal_lahir' => '1990-01-01',
                    'jenis_kelamin' => 'Laki-laki',
                    'agama' => 'Islam',
                    'status_perkawinan' => 'Belum Kawin',
                    'pekerjaan' => 'Programmer',
                    'alamat' => 'Jl. Test No. 123',
                    'keperluan' => 'Testing PDF'
                ];
                break;
                
            default:
                $baseData['surat'] = (object) [
                    'nama' => 'John Doe',
                    'nama_lengkap' => 'John Doe',
                    'nik' => '1234567890123456',
                    'tempat_lahir' => 'Jakarta',
                    'tanggal_lahir' => '1990-01-01',
                    'jenis_kelamin' => 'Laki-laki',
                    'agama' => 'Islam',
                    'pekerjaan' => 'Programmer',
                    'alamat' => 'Jl. Test No. 123',
                    'keperluan' => 'Testing PDF'
                ];
        }
        
        return $baseData;
    }
}