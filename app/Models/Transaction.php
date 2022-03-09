<?php   
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model{
    protected $table = "transactions";

    protected $fillable = ['user_id', 'order_code', 'status', 'amount'];

    protected $visible = ['id', 'user_id', 'order_code', 'status', 'amount'];

    // public $timestamps = false;
}