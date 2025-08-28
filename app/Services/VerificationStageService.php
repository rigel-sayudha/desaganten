<?php

namespace App\Services;

class VerificationStageService
{
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
                    'required_documents' => ['KTP', 'KK', 'Surat Permohonan'],
                    'duration_days' => 1
                ],
                2 => [
                    'name' => 'Verifikasi Dokumen',
                    'description' => 'Pengecekan kelengkapan dokumen dan identitas pemohon',
                    'required_documents' => ['Verifikasi KTP', 'Verifikasi KK', 'Cek status pernikahan'],
                    'duration_days' => 2
                ],
                3 => [
                    'name' => 'Verifikasi Status',
                    'description' => 'Konfirmasi status belum menikah di database kependudukan',
                    'required_documents' => ['Cek database Dukcapil', 'Konfirmasi KUA'],
                    'duration_days' => 3
                ],
                4 => [
                    'name' => 'Konfirmasi Sosial',
                    'description' => 'Verifikasi status dari lingkungan sekitar',
                    'required_documents' => ['Keterangan RT/RW', 'Konfirmasi tetangga'],
                    'duration_days' => 2
                ],
                5 => [
                    'name' => 'Persetujuan Kepala Desa',
                    'description' => 'Review dan persetujuan akhir dari Kepala Desa',
                    'required_documents' => ['Laporan verifikasi lengkap'],
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
                    'name' => 'Verifikasi Dokumen',
                    'description' => 'Pengecekan kelengkapan dokumen pendukung',
                    'required_documents' => ['Verifikasi KTP', 'Verifikasi KK'],
                    'duration_days' => 1
                ],
                3 => [
                    'name' => 'Survey Kelakuan',
                    'description' => 'Verifikasi kelakuan baik dari lingkungan',
                    'required_documents' => ['Keterangan RT/RW', 'Konfirmasi tetangga'],
                    'duration_days' => 3
                ],
                4 => [
                    'name' => 'Penandatanganan Pengantar',
                    'description' => 'Pembuatan surat pengantar ke Polres',
                    'required_documents' => ['Surat pengantar'],
                    'duration_days' => 1
                ]
            ],
            
            'kematian' => [
                1 => [
                    'name' => 'Penerimaan Berkas',
                    'description' => 'Berkas permohonan diterima dan dicatat dalam sistem',
                    'required_documents' => ['KTP almarhum', 'KK', 'Surat keterangan dari rumah sakit/dokter'],
                    'duration_days' => 1
                ],
                2 => [
                    'name' => 'Verifikasi Dokumen',
                    'description' => 'Pengecekan kelengkapan dokumen kematian',
                    'required_documents' => ['Verifikasi identitas almarhum', 'Verifikasi surat keterangan medis'],
                    'duration_days' => 1
                ],
                3 => [
                    'name' => 'Verifikasi Kematian',
                    'description' => 'Konfirmasi kematian dari saksi dan keluarga',
                    'required_documents' => ['Keterangan saksi', 'Konfirmasi keluarga'],
                    'duration_days' => 1
                ],
                4 => [
                    'name' => 'Persetujuan Kepala Desa',
                    'description' => 'Review dan persetujuan dari Kepala Desa',
                    'required_documents' => ['Laporan verifikasi'],
                    'duration_days' => 1
                ],
                5 => [
                    'name' => 'Penerbitan Surat',
                    'description' => 'Pembuatan dan penandatanganan surat keterangan kematian',
                    'required_documents' => ['Surat yang sudah ditandatangani'],
                    'duration_days' => 1
                ]
            ],
            
            'kelahiran' => [
                1 => [
                    'name' => 'Penerimaan Berkas',
                    'description' => 'Berkas permohonan diterima dan dicatat dalam sistem',
                    'required_documents' => ['KTP orang tua', 'KK', 'Surat keterangan lahir dari bidan/dokter'],
                    'duration_days' => 1
                ],
                2 => [
                    'name' => 'Verifikasi Dokumen',
                    'description' => 'Pengecekan kelengkapan dokumen kelahiran',
                    'required_documents' => ['Verifikasi identitas orang tua', 'Verifikasi surat keterangan lahir'],
                    'duration_days' => 1
                ],
                3 => [
                    'name' => 'Verifikasi Kelahiran',
                    'description' => 'Konfirmasi kelahiran dari saksi dan keluarga',
                    'required_documents' => ['Keterangan saksi', 'Konfirmasi keluarga'],
                    'duration_days' => 1
                ],
                4 => [
                    'name' => 'Persetujuan Kepala Desa',
                    'description' => 'Review dan persetujuan dari Kepala Desa',
                    'required_documents' => ['Laporan verifikasi'],
                    'duration_days' => 1
                ],
                5 => [
                    'name' => 'Penerbitan Surat',
                    'description' => 'Pembuatan dan penandatanganan surat keterangan kelahiran',
                    'required_documents' => ['Surat yang sudah ditandatangani'],
                    'duration_days' => 1
                ]
            ],
            
            'kehilangan' => [
                1 => [
                    'name' => 'Penerimaan Berkas',
                    'description' => 'Berkas permohonan diterima dan dicatat dalam sistem',
                    'required_documents' => ['KTP', 'KK', 'Surat permohonan', 'Kronologi kehilangan'],
                    'duration_days' => 1
                ],
                2 => [
                    'name' => 'Verifikasi Dokumen',
                    'description' => 'Pengecekan kelengkapan dokumen dan identitas pemohon',
                    'required_documents' => ['Verifikasi KTP', 'Verifikasi KK', 'Cek validitas kronologi'],
                    'duration_days' => 1
                ],
                3 => [
                    'name' => 'Verifikasi Laporan Polisi',
                    'description' => 'Konfirmasi laporan kehilangan ke pihak kepolisian',
                    'required_documents' => ['Surat laporan polisi', 'Verifikasi nomor laporan'],
                    'duration_days' => 2
                ],
                4 => [
                    'name' => 'Verifikasi Lokasi Kehilangan',
                    'description' => 'Konfirmasi lokasi dan waktu kehilangan',
                    'required_documents' => ['Keterangan saksi', 'Konfirmasi RT/RW'],
                    'duration_days' => 2
                ],
                5 => [
                    'name' => 'Persetujuan Kepala Desa',
                    'description' => 'Review dan persetujuan dari Kepala Desa',
                    'required_documents' => ['Laporan verifikasi lengkap'],
                    'duration_days' => 1
                ],
                6 => [
                    'name' => 'Penerbitan Surat',
                    'description' => 'Pembuatan dan penandatanganan surat keterangan kehilangan',
                    'required_documents' => ['Surat yang sudah ditandatangani'],
                    'duration_days' => 1
                ]
            ],
            
            'usaha' => [
                1 => [
                    'name' => 'Penerimaan Berkas',
                    'description' => 'Berkas permohonan diterima dan dicatat dalam sistem',
                    'required_documents' => ['KTP', 'KK', 'Surat permohonan', 'Foto usaha'],
                    'duration_days' => 1
                ],
                2 => [
                    'name' => 'Verifikasi Dokumen',
                    'description' => 'Pengecekan kelengkapan dokumen dan identitas pemohon',
                    'required_documents' => ['Verifikasi KTP', 'Verifikasi KK', 'Cek data pemohon'],
                    'duration_days' => 1
                ],
                3 => [
                    'name' => 'Survey Lokasi Usaha',
                    'description' => 'Peninjauan langsung ke lokasi usaha',
                    'required_documents' => ['Foto lokasi usaha', 'Dokumentasi kegiatan usaha', 'Wawancara dengan pelaku usaha'],
                    'duration_days' => 3
                ],
                4 => [
                    'name' => 'Verifikasi Aktivitas Usaha',
                    'description' => 'Konfirmasi aktivitas usaha dari lingkungan sekitar',
                    'required_documents' => ['Keterangan RT/RW', 'Konfirmasi tetangga', 'Bukti aktivitas usaha'],
                    'duration_days' => 2
                ],
                5 => [
                    'name' => 'Verifikasi Legalitas',
                    'description' => 'Pengecekan aspek legalitas dan perizinan usaha',
                    'required_documents' => ['Cek izin yang diperlukan', 'Verifikasi tidak melanggar aturan'],
                    'duration_days' => 2
                ],
                6 => [
                    'name' => 'Persetujuan Kepala Desa',
                    'description' => 'Review dan persetujuan dari Kepala Desa',
                    'required_documents' => ['Laporan verifikasi lengkap', 'Rekomendasi tim survey'],
                    'duration_days' => 2
                ],
                7 => [
                    'name' => 'Penerbitan Surat',
                    'description' => 'Pembuatan dan penandatanganan surat keterangan usaha',
                    'required_documents' => ['Surat yang sudah ditandatangani'],
                    'duration_days' => 1
                ]
            ]
        ];

        return $stages[$type] ?? [];
    }

    public static function getTotalStages($type)
    {
        $stages = self::getStagesForSuratType($type);
        return count($stages);
    }

    public static function getStageInfo($type, $stage)
    {
        $stages = self::getStagesForSuratType($type);
        return $stages[$stage] ?? null;
    }

    public static function getTotalEstimatedDays($type)
    {
        $stages = self::getStagesForSuratType($type);
        $totalDays = 0;

        foreach ($stages as $stage) {
            $totalDays += $stage['duration_days'] ?? 0;
        }

        return $totalDays;
    }

    public static function initializeStages($type)
    {
        $stages = self::getStagesForSuratType($type);
        $initializedStages = [];

        foreach ($stages as $stageNumber => $stageInfo) {
            $initializedStages[$stageNumber] = [
                'stage_number' => $stageNumber,
                'name' => $stageInfo['name'],
                'description' => $stageInfo['description'],
                'status' => $stageNumber == 1 ? 'in_progress' : 'pending',
                'started_at' => $stageNumber == 1 ? now() : null,
                'completed_at' => null,
                'notes' => null,
                'required_documents' => $stageInfo['required_documents'],
                'estimated_duration_days' => $stageInfo['duration_days']
            ];
        }

        return $initializedStages;
    }

    /**
     * Menghitung persentase progress verifikasi
     */
    public static function getProgressPercentage($stages)
    {
        // Convert JSON string to array if needed
        if (is_string($stages)) {
            $stages = json_decode($stages, true);
        }

        if (empty($stages) || !is_array($stages)) {
            return 0;
        }

        $totalStages = count($stages);
        $completedStages = 0;

        foreach ($stages as $stage) {
            if (isset($stage['status']) && $stage['status'] === 'completed') {
                $completedStages++;
            }
        }

        return $totalStages > 0 ? round(($completedStages / $totalStages) * 100) : 0;
    }

    /**
     * Mendapatkan tahapan yang sedang aktif
     */
    public static function getCurrentStage($stages)
    {
        // Convert JSON string to array if needed
        if (is_string($stages)) {
            $stages = json_decode($stages, true);
        }

        if (empty($stages) || !is_array($stages)) {
            return null;
        }

        foreach ($stages as $stageNumber => $stage) {
            if (isset($stage['status']) && $stage['status'] === 'in_progress') {
                return $stage;
            }
        }

        // Jika tidak ada yang in_progress, cari yang pertama dengan status pending
        foreach ($stages as $stageNumber => $stage) {
            if (isset($stage['status']) && $stage['status'] === 'pending') {
                return $stage;
            }
        }

        return null;
    }

    /**
     * Mendapatkan estimasi waktu penyelesaian
     */
    public static function getEstimatedCompletion($stages)
    {
        // Convert JSON string to array if needed
        if (is_string($stages)) {
            $stages = json_decode($stages, true);
        }

        if (empty($stages) || !is_array($stages)) {
            return null;
        }

        $remainingDays = 0;
        foreach ($stages as $stage) {
            if (isset($stage['status']) && in_array($stage['status'], ['pending', 'in_progress'])) {
                $remainingDays += $stage['estimated_duration_days'] ?? 1;
            }
        }

        return $remainingDays > 0 ? now()->addDays($remainingDays) : now();
    }

    /**
     * Update status tahapan verifikasi
     */
    public static function updateStageStatus($stages, $stageNumber, $status, $notes = null, $updatedBy = null)
    {
        // Convert JSON string to array if needed
        if (is_string($stages)) {
            $stages = json_decode($stages, true);
        }

        if (!is_array($stages) || !isset($stages[$stageNumber])) {
            return $stages;
        }

        $stages[$stageNumber]['status'] = $status;
        $stages[$stageNumber]['notes'] = $notes;
        $stages[$stageNumber]['updated_by'] = $updatedBy;
        $stages[$stageNumber]['updated_at'] = now();

        if ($status === 'completed') {
            $stages[$stageNumber]['completed_at'] = now();
            
            // Activate next stage if available
            $nextStageNumber = $stageNumber + 1;
            if (isset($stages[$nextStageNumber]) && $stages[$nextStageNumber]['status'] === 'pending') {
                $stages[$nextStageNumber]['status'] = 'in_progress';
                $stages[$nextStageNumber]['started_at'] = now();
            }
        } elseif ($status === 'in_progress') {
            $stages[$stageNumber]['started_at'] = now();
        }

        return $stages;
    }
}
