<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class DetailPenggajian extends Model
{
    use HasFactory;

    protected $table = 'detail_penggajians';

    protected $fillable = [
        'penggajian_id',
        'pegawai_id',
        'total_hadir',
        'gaji_pokok',
        'tunjangan',
        'potongan',
        'total_gaji'
    ];

    protected $attributes = [
        'total_hadir' => 0,
        'gaji_pokok' => 100000,
        'tunjangan' => 0,
        'potongan' => 0,
        'total_gaji' => 0,
    ];

    protected $casts = [
        'total_hadir' => 'integer',
        'gaji_pokok' => 'decimal:2',
        'tunjangan' => 'decimal:2',
        'potongan' => 'decimal:2',
        'total_gaji' => 'decimal:2'
    ];

    public function penggajian(): BelongsTo
    {
        return $this->belongsTo(Penggajian::class, 'penggajian_id');
    }

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->total_gaji = ($model->gaji_pokok * $model->total_hadir) +
                (float)($model->tunjangan ?? 0) -
                (float)($model->potongan ?? 0);
        });

        static::saved(function ($model) {
            if ($model->penggajian) {
                $model->penggajian->updateTotalGajiQuietly();
            }
        });
    }

    public static function mutateRelationshipData(array $data): array
    {
        return array_map(function ($item) {
            $item['total_gaji'] = ($item['gaji_pokok'] * $item['total_hadir']) +
                (int)($item['tunjangan'] ?? 0) -
                (int)($item['potongan'] ?? 0);
            return $item;
        }, $data);
    }
}
