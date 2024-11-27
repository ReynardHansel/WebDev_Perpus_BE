<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    /** @use HasFactory<\Database\Factories\BukuFactory> */
    use HasFactory;

    // Specify the table name
    protected $table = 'buku';

    // Specify the primary key if it's not 'id'
    protected $primaryKey = 'isbn';

    // Disable auto-incrementing if the primary key is not an integer
    public $incrementing = false;

    // Specify the data type of the primary key
    protected $keyType = 'string';

    // Disable timestamps if your table does not have 'created_at' and 'updated_at' columns
    public $timestamps = false;
}
