<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostMetaModel extends Model
{
    use HasFactory;

    protected $table = "wp_postmeta";
}
