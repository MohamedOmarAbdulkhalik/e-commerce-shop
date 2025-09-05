<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Product extends Model
{
    //
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'name', 
        'description', 
        'price', 
        'on_sale',
        'image_path'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'on_sale' => 'boolean'
    ];
}
