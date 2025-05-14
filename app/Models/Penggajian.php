<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Penggajian extends Model
{
    use HasFactory;

    protected $table = 'penggajians';

    protected $fillable = [
        'nomor_penggajian',
        'tanggal_penggajian',
        'periode_awal',
        'periode_akhir',
        'total_gaji',
        'status',
        'keterangan',
        'user_id'
    ];

    protected $casts = [
        'tanggal_penggajian' => 'date',
        'periode_awal' => 'date',
        'periode_akhir' => 'date',
        'total_gaji' => 'decimal:2'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->user_id = auth()->id();
            $model->total_gaji = 0;

            if (empty($model->nomor_penggajian)) {
                $model->nomor_penggajian = static::generateNomorPenggajian();
            }
        });

        static::created(function ($model) {
            $model->updateTotalGajiQuietly();
        });

        static::updated(function ($model) {
            $model->updateTotalGajiQuietly();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function detailPenggajians(): HasMany
    {
        return $this->hasMany(DetailPenggajian::class, 'penggajian_id');
    }

    public function updateTotalGaji()
    {
        $total = $this->detailPenggajians()->sum('total_gaji');
        $this->total_gaji = $total;
        $this->save();
    }

    public function updateTotalGajiQuietly()
    {
        $total = $this->detailPenggajians()->sum('total_gaji');
        $this->total_gaji = $total;
        $this->saveQuietly();
    }

    public static function generateNomorPenggajian(): string
    {
        $today = now()->format('Ymd');
        $lastNumber = static::query()
            ->whereDate('created_at', today())
            ->where('nomor_penggajian', 'like', "PG-{$today}-%")
            ->orderBy('nomor_penggajian', 'desc')
            ->value('nomor_penggajian');

        if ($lastNumber) {
            $lastRunningNumber = (int) substr($lastNumber, -4);
            $runningNumber = $lastRunningNumber + 1;
        } else {
            $runningNumber = 1;
        }

        return sprintf("PG-%s-%04d", $today, $runningNumber);
    }
}
