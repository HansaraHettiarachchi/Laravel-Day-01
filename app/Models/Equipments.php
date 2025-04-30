<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipments extends Model
{
    use HasFactory;
    protected $table = "equipments";
    protected $fillable = [
        "name",
        "code",
        "price",
        "tQty",
        "sub_tot",
        "cat_id",
        "status",
        "created_user"
    ];
}
