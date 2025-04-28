<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipments_Has_Product extends Model
{
    use HasFactory;
    protected $table = "equipments_has_product";
    protected $fillable = [
        "qty",
        "cost",
        "sub_total",
        "product_id",
        "equipments_id"
    ];
}
