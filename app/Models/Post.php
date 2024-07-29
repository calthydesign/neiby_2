<?php
//Post.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'condition',
        'memo',
        'selected_tags',
        'weather',
        // 他の fillable なフィールドがあれば追加
    ];
    
}