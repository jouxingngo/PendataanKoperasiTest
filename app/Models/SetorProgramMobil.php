<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SetorProgramMobil extends Model
{

    protected $fillable = ['admin_id', 'member_id', 'program_mobil_id', 'status', 'transaction_type', 'transaction_date', 'transaction_value','note'];

    
    
    public function member(){
        return $this->belongsTo(Member::class);
    }
    public function admin(){
        return $this->belongsTo(User::class);
    }
    public function programMobil(){
        return $this->belongsTo(ProgramMobil::class);
    }
}
