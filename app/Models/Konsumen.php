<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konsumen extends Model
{
    use HasFactory;

    protected $table = 'konsumen';

    protected $fillable = [
        'user_id',
        'nama_konsumen',
        'jenis_kelamin',
        'alamat',
        'no_telp',
        'email',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
