<?php

namespace App\Services;

class VerificationStageService
{
    /**
     * Definisi tahapan verifikasi untuk setiap jenis surat
     */
    public static function getStagesForSuratType($type)
    {
        $stages = [
            'domisili' => [
                1 => [
                    'name' => 'Penerimaan Berkas',
                    'description' => 'Berkas permohonan diterima dan dicatat dalam sistem',
                    'required_documents' => ['KTP', 'KK', 'Surat Permohonan'],
                    'duration_days' => 1
                ],
                2 => [
                    'name' => 'Verifikasi Dokumen',
                    'description' => 'Pengecekan kelengkapan dan keabsahan dokumen pendukung',
                    'required_documents' => ['Verifikasi KTP', 'Verifikasi KK', 'Cek NIK di database'],
                    'duration_days' => 2
                ],
                3 => [
                    'name' => 'Survey Lapangan',
                    'description' => 'Peninjauan langsung ke alamat domisili yang dimohonkan',
                    'required_documents' => ['Foto lokasi', 'Konfirmasi RT/RW', 'Berita acara survey'],
                    'duration_days' => 3
                ],
                4 => [
                    'name' => 'Verifikasi Data',
                    'description' => 'Pencocokan data dengan database kependudukan',
                    'required_documents' => ['Cek database Dukcapil', 'Konfirmasi status domisili'],
                    'duration_days' => 1
                ],
                5 => [
                    'name' => 'Persetujuan Kepala Desa',
                    'description' => 'Review dan persetujuan akhir dari Kepala Desa',
                    'required_documents' => ['Laporan verifikasi lengkap'],
                    'duration_days' => 2
                ],
                6 => [
                    'name' => 'Penerbitan Surat',
                    'description' => 'Pembuatan dan penandatanganan surat keterangan domisili',
                    'required_documents' => ['Surat yang sudah ditandatangani'],
                    'duration_days' => 1
                ]
            ],
            
            'tidak_mampu' => [
                1 => [
                    'name' => 'Penerimaan Berkas',
                    'description' => 'Berkas permohonan diterima dan dicatat dalam sistem',
                    'required_documents' => ['KTP', 'KK', 'Surat Permohonan', 'Surat Keterangan RT/RW'],
                    'duration_days' => 1
                ],
                2 => [
                    'name' => 'Verifikasi Dokumen',
                    'description' => 'Pengecekan kelengkapan dokumen dan identitas pemohon',
                    'required_documents' => ['Verifikasi KTP', 'Verifikasi KK', 'Cek data keluarga'],
                    'duration_days' => 2
                ],
                3 => [
                    'name' => 'Survey Ekonomi Keluarga',
                    'description' => 'Peninjauan kondisi ekonomi dan sosial keluarga',
                    'required_documents' => ['Foto rumah', 'Wawancara keluarga', 'Konfirmasi tetangga'],
                    'duration_days' => 4
                ],
                4 => [
                    'name' => 'Verifikasi Penghasilan',
                    'description' => 'Pengecekan sumber penghasilan dan aset keluarga',
                    'required_documents' => ['Slip gaji (jika ada)', 'Keterangan usaha', 'Cek kepemilikan aset'],
                    'duration_days' => 2
                ],
                5 => [
                    'name' => 'Analisis Kelayakan',
                    'description' => 'Analisis apakah keluarga memenuhi kriteria tidak mampu',
                    'required_documents' => ['Laporan survey lengkap', 'Analisis ekonomi'],
                    'duration_days' => 2
                ],
                6 => [
                    'name' => 'Persetujuan Kepala Desa',
                    'description' => 'Review dan persetujuan akhir dari Kepala Desa',
                    'required_documents' => ['Rekomendasi tim verifikasi'],
                    'duration_days' => 2
                ],
                7 => [
                    'name' => 'Penerbitan Surat',
                    'description' => 'Pembuatan dan penandatanganan surat keterangan tidak mampu',
                    'required_documents' => ['Surat yang sudah ditandatangani'],
                    'duration_days' => 1
                ]
            ],
            
            'belum_menikah' => [
                1 => [
                    'name' => 'Penerimaan Berkas',
                    'description' => 'Berkas permohonan diterima dan dicatat dalam sistem',
                    'required_documents' => ['KTP', 'KK', 'Surat Permohonan', 'Pas foto'],
                    'duration_days' => 1
                ],
                2 => [
                    'name' => 'Verifikasi Dokumen',
                    'description' => 'Pengecekan kelengkapan dan keabsahan dokumen',
                    'required_documents' => ['Verifikasi KTP', 'Verifikasi KK', 'Cek status di database'],
                    'duration_days' => 1
                ],
                3 => [
                    'name' => 'Verifikasi Status Pernikahan',
                    'description' => 'Pengecekan status pernikahan di database Dukcapil dan KUA',
                    'required_documents' => ['Cek database Dukcapil', 'Konfirmasi ke KUA setempat'],
                    'duration_days' => 2
                ],
                4 => [
                    'name' => 'Konfirmasi Keluarga',
                    'description' => 'Konfirmasi status belum menikah dengan keluarga dan RT/RW',
                    'required_documents' => ['Keterangan orang tua', 'Konfirmasi RT/RW'],
                    'duration_days' => 2
                ],
                5 => [
                    'name' => 'Persetujuan Kepala Desa',
                    'description' => 'Review dan persetujuan akhir dari Kepala Desa',
                    'required_documents' => ['Laporan verifikasi status'],
                    'duration_days' => 1
                ],
                6 => [
                    'name' => 'Penerbitan Surat',
                    'description' => 'Pembuatan dan penandatanganan surat keterangan belum menikah',
                    'required_documents' => ['Surat yang sudah ditandatangani'],
                    'duration_days' => 1
                ]
            ],
            
            'ktp' => [
                1 => [
                    'name' => 'Penerimaan Berkas',
                    'description' => 'Berkas permohonan diterima dan dicatat',
                    'required_documents' => ['Formulir permohonan', 'Pas foto', 'KK asli'],
                    'duration_days' => 1
                ],
                2 => [
                    'name' => 'Verifikasi Dokumen',
                    'description' => 'Pengecekan kelengkapan dokumen pendukung',
                    'required_documents' => ['Verifikasi KK', 'Cek foto sesuai ketentuan'],
                    'duration_days' => 1
                ],
                3 => [
                    'name' => 'Penandatanganan Pengantar',
                    'description' => 'Pembuatan surat pengantar ke Dukcapil',
                    'required_documents' => ['Surat pengantar'],
                    'duration_days' => 1
                ]
            ],
            
            'kk' => [
                1 => [
                    'name' => 'Penerimaan Berkas',
                    'description' => 'Berkas permohonan diterima dan dicatat',
                    'required_documents' => ['Formulir permohonan', 'KTP anggota keluarga', 'Dokumen pendukung'],
                    'duration_days' => 1
                ],
                2 => [
                    'name' => 'Verifikasi Data Keluarga',
                    'description' => 'Pengecekan data semua anggota keluarga',
                    'required_documents' => ['Verifikasi KTP semua anggota', 'Cek hubungan keluarga'],
                    'duration_days' => 2
                ],
                3 => [
                    'name' => 'Penandatanganan Pengantar',
                    'description' => 'Pembuatan surat pengantar ke Dukcapil',
                    'required_documents' => ['Surat pengantar'],
                    'duration_days' => 1
                ]
            ],
            
            'skck' => [
                1 => [
                    'name' => 'Penerimaan Berkas',
                    'description' => 'Berkas permohonan diterima dan dicatat',
                    'required_documents' => ['KTP', 'KK', 'Surat permohonan', 'Pas foto'],
                    'duration_days' => 1
                ],
                2 => [
                    'name' => 'Verifikasi Identitas',
                    'description' => 'Pengecekan identitas dan domisili pemohon',
                    'required_documents' => ['Verifikasi KTP', 'Konfirmasi domisili'],
                    'duration_days' => 1
                ],
                3 => [
                    'name' => 'Penandatanganan Pengantar',
                    'description' => 'Pembuatan surat pengantar ke Polsek',
                    'required_documents' => ['Surat pengantar'],
                    'duration_days' => 1
                ]
            ],
            
            'kematian' => [
                1 => [
                    'name' => 'Penerimaan Berkas',
                    'description' => 'Berkas permohonan diterima dan dicatat',
                    'required_documents' => ['KTP pelapor', 'KTP almarhum', 'KK', 'Surat keterangan RS/dokter'],
                    'duration_days' => 1
                ],
                2 => [
                    'name' => 'Verifikasi Data Kematian',
                    'description' => 'Pengecekan data kematian dan identitas almarhum',
                    'required_documents' => ['Verifikasi KTP', 'Konfirmasi kematian', 'Berita acara'],
                    'duration_days' => 2
                ],
                3 => [
                    'name' => 'Penerbitan Surat',
                    'description' => 'Pembuatan dan penandatanganan surat keterangan kematian',
                    'required_documents' => ['Surat keterangan kematian'],
                    'duration_days' => 1
                ]
            ],
            
            'kelahiran' => [
                1 => [
                    'name' => 'Penerimaan Berkas',
                    'description' => 'Berkas permohonan diterima dan dicatat',
                    'required_documents' => ['KTP orangtua', 'KK', 'Surat keterangan lahir RS/bidan', 'Surat nikah orangtua'],
                    'duration_days' => 1
                ],
                2 => [
                    'name' => 'Verifikasi Data Kelahiran',
                    'description' => 'Pengecekan data kelahiran dan identitas orangtua',
                    'required_documents' => ['Verifikasi KTP orangtua', 'Konfirmasi kelahiran'],
                    'duration_days' => 2
                ],
                3 => [
                    'name' => 'Penerbitan Surat',
                    'description' => 'Pembuatan dan penandatanganan surat keterangan kelahiran',
                    'required_documents' => ['Surat keterangan kelahiran'],
                    'duration_days' => 1
                ]
            ]
        ];

        return $stages[$type] ?? [];
    }

    /**
     * Inisialisasi tahapan verifikasi untuk surat baru
     */
    public static function initializeStages($suratType)
    {
        $stages = self::getStagesForSuratType($suratType);
        $initializedStages = [];

        foreach ($stages as $stageNumber => $stage) {
            $initializedStages[$stageNumber] = [
                'name' => $stage['name'],
                'description' => $stage['description'],
                'required_documents' => $stage['required_documents'],
                'duration_days' => $stage['duration_days'],
                'status' => $stageNumber === 1 ? 'in_progress' : 'pending', // First stage starts immediately
                'completed_at' => null,
                'notes' => '',
                'verified_by' => null
            ];
        }

        return $initializedStages;
    }

    /**
     * Update status tahapan verifikasi
     */
    public static function updateStageStatus($stages, $stageNumber, $status, $notes = '', $verifiedBy = null)
    {
        if (isset($stages[$stageNumber])) {
            $stages[$stageNumber]['status'] = $status;
            $stages[$stageNumber]['notes'] = $notes;
            $stages[$stageNumber]['verified_by'] = $verifiedBy;
            
            if ($status === 'completed') {
                $stages[$stageNumber]['completed_at'] = now()->toISOString();
                
                // Start next stage if current is completed
                $nextStage = $stageNumber + 1;
                if (isset($stages[$nextStage]) && $stages[$nextStage]['status'] === 'pending') {
                    $stages[$nextStage]['status'] = 'in_progress';
                }
            }
        }

        return $stages;
    }

    /**
     * Get progress percentage
     */
    public static function getProgressPercentage($stages)
    {
        if (empty($stages)) return 0;

        $totalStages = count($stages);
        $completedStages = 0;

        foreach ($stages as $stage) {
            if ($stage['status'] === 'completed') {
                $completedStages++;
            }
        }

        return round(($completedStages / $totalStages) * 100);
    }

    /**
     * Get current active stage
     */
    public static function getCurrentStage($stages)
    {
        foreach ($stages as $stageNumber => $stage) {
            if ($stage['status'] === 'in_progress') {
                return $stageNumber;
            }
        }
        
        // If no stage is in progress, return the next pending stage
        foreach ($stages as $stageNumber => $stage) {
            if ($stage['status'] === 'pending') {
                return $stageNumber;
            }
        }

        return null;
    }

    /**
     * Get estimated completion date
     */
    public static function getEstimatedCompletion($stages)
    {
        $remainingDays = 0;
        
        foreach ($stages as $stage) {
            if ($stage['status'] !== 'completed') {
                $remainingDays += $stage['duration_days'];
            }
        }

        return now()->addDays($remainingDays);
    }
}
