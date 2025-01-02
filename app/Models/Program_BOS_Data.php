<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program_BOS_Data extends Model
{
    protected $fillable = ['admin_id','member_id','status','transaction_date','transaction_type','transaction_value','note'];
    
    public function member(){
        return $this->belongsTo(Member::class);
    }
    public function admin(){
        return $this->belongsTo(User::class);
    }
}
