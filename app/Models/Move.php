<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Move extends Model
{
    use HasFactory;
    protected $fillable = ['storage_id','name', 'series','number', 'sender_fio', 'reciver_fio', 'send_date', 'accept_date', 'status', 'address'];
}
