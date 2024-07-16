<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'result',
        'kikyo_count',
        'kekkyo_count',
        'kitai_count',
        'oketsu_count',
        'suitai_count'
    ];
}
