<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Potensi extends Model
{
    use HasFactory;

    protected $table = 'potensi';

    protected $fillable = [
        'user_id',
        'tanggal_input',
        'nama_usaha',
        'npwp',
        'segmen',
        'uraian',
        'alamat',
        'latitude',
        'longitude',
        'estimasi_tk',
        'estimasi_upah',
        'estimasi_iuran',
        'status_sp1',
        'tanggal_cetak_sp1',
        'status_tindak_lanjut',
        'catatan',
    ];

    protected $casts = [
        'tanggal_input' => 'date',
        'status_sp1' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'estimasi_tk' => 'integer',
        'estimasi_upah' => 'decimal:2',
        'estimasi_iuran' => 'decimal:2',
        'tanggal_cetak_sp1' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function programPotensi(): HasMany
    {
        return $this->hasMany(ProgramPotensi::class);
    }
}
