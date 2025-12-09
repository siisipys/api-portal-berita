<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komentar extends Model
{
    use HasFactory;

    protected $table = 'komentar';

    protected $fillable = [
        'user_id',
        'berita_id',
        'isi',
    ];

    /**
     * Get the user that owns the komentar.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the berita that owns the komentar.
     */
    public function berita()
    {
        return $this->belongsTo(Berita::class);
    }
}
