<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    // Tentukan primary key
    protected $primaryKey = 'id_artikel';

    // Primary key auto-increment
    public $incrementing = true;

    // Kolom yang bisa diisi
    protected $fillable = [
        'title',
        'content',
        'role',
        'author',
        'image',
    ];
}
