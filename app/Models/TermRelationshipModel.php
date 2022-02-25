<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TermRelationshipModel extends Model
{
    use HasFactory;

    protected $table = "wp_term_relationships";
}
