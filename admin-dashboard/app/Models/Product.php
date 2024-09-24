<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products_table'; // Define the table explicitly

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'name',
        'category',
        'description',
        'date_time'
    ];
}
