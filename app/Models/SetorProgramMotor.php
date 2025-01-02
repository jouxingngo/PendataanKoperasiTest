<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SetorProgramMotor extends Model
{
    protected $fillable = ['admin_id', 'member_id', 'program_motor_id', 'status', 'transaction_type', 'transaction_date', 'transaction_value','note'];


    public function member(){
        return $this->belongsTo(Member::class);
    }
    public function admin(){
        return $this->belongsTo(User::class);
    }
    public function programMotor(){
        return $this->belongsTo(ProgramMotor::class);
    }
    
}
