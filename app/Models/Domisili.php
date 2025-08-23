<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domisili extends Model
{
	use HasFactory;
	protected $table = 'domisili';
	protected $fillable = [
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
		'status_pengajuan'
	];
}
