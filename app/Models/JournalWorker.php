<?php

namespace App\Models;

use App\Helpers\StringHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalWorker extends Model
{
    use HasFactory;

    protected $table = 'journal_workers';

    protected $fillable = [
        'nama_pekerjaan',
        'spesifikasi_or_kualifikasi',
        'negara_asal',
        'satuan_harga',
        'tkdn',
        'classification_tkdn',
        'keterangan'
    ];
}
