<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'log_laporan';

    protected $fillable = [
        'laporan_id',
        'status',
        'catatan',
        'diperbarui_oleh',
    ];

    public function laporan()
    {
        return $this->belongsTo(Report::class, 'laporan_id');
    }

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'diperbarui_oleh');
    }
}
