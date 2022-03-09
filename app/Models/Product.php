<?php   
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model{
    use HasFactory;
    
    protected $table = "products";

    protected $fillable = [
        'name', 'description', 'image', 'price', 'stock'
    ];

    /**
     * The attributes that should be visible in arrays.
     *
     * @var array
     */
    protected $visible = [
        'id', 'name', 'description', 'image', 'price', 'stock'
    ];

    // public $timestamps = false;
}