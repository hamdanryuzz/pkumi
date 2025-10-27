<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KhazanahSubmission extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     * Sesuaikan jika nama tabel Anda berbeda.
     * @var string
     */
    // protected $table = 'khazanah_submissions'; // Hapus komentar jika perlu

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'title',
        'summary',
        'content',
        'tags',
        'cover_image_path',
        'status', // Pastikan kolom 'status' ada di migrasi Anda
    ];

    /**
     * Mendapatkan data mahasiswa yang mengirim submission ini.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
        // Pastikan Anda punya model 'Student' di App\Models\Student
    }
}
