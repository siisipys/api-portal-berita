<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    use HasFactory;

    protected $table = 'berita';

    protected $fillable = [
        'user_id',
        'judul',
        'konten',
        'gambar',
        'kategori',
        'status',
    ];

    /**
     * Get the user that owns the berita.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all komentar for the berita.
     */
    public function komentar()
    {
        return $this->hasMany(Komentar::class);
    }
}
