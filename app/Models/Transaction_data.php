<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction_data extends Model
{
    protected $fillable = ['program_name', 'program_id', 'admin_id', 'member_id', 'transaction_date', 'transaction_type', 'transaction_value', 'status'];

    public function member(){
        return $this->belongsTo(Member::class);
    }
    public function admin(){
        return $this->belongsTo(User::class);
    }
}
