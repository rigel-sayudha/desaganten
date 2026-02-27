<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domisili extends Model
{
	use HasFactory;
	protected $table = 'domisili';
	protected $fillable = [
		'user_id',
		'nik',
		'nama',
		'tempat_lahir',
		'tanggal_lahir',
		'jenis_kelamin',
		'kewarganegaraan',
		'agama',
		'status',
		'pekerjaan',
		'alamat',
		'keperluan',
		'file_ktp',
		'file_kk',
		'file_pengantar_rt',
		'file_dokumen_tambahan',
		'status_pengajuan',
		'tahapan_verifikasi',
		'catatan_verifikasi',
		'tanggal_verifikasi_terakhir'
	];

	protected $casts = [
		'tanggal_lahir' => 'date',
		'tahapan_verifikasi' => 'array',
		'tanggal_verifikasi_terakhir' => 'datetime',
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
