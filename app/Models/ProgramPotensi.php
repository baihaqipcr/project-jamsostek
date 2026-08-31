<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramPotensi extends Model
{
    use HasFactory;

    protected $table = 'program_potensi';

    protected $fillable = [
        'potensi_id',
        'jenis_program',
    ];

    public function potensi(): BelongsTo
    {
        return $this->belongsTo(Potensi::class);
    }
}
