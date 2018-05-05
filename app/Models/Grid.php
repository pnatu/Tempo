<?php
namespace App\Models;

use \Illuminate\Database\Eloquent\Model;


class Grid extends Model
{
    protected $table = "users";
    public $primaryKey = "user_id";
    public $timestamps = false;
}
