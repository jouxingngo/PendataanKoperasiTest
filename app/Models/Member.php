<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = ['name', 'admin_id'];

    public function admin()
    {
        return $this->belongsTo(User::class);
    }
    public function Debt()
    {
        return $this->hasOne(Remaining_debt::class);
    }
    public function Program_bos_datas()
    {
        return $this->hasMany(Program_BOS_Data::class);
    }
    public function Program_bop_datas()
    {
        return $this->hasMany(Program_BOP_Data::class);
    }

    // Motor Mobil
    public function ActiveProgramMotors()
    {
        return $this->hasMany(ProgramMotor::class)->where('paid_off', false);
    }

    public function LunasProgramMotors()
    {
        return $this->hasMany(ProgramMotor::class)->where('paid_off', true);

    }
    public function ActiveProgramMobils()
    {
        return $this->hasMany(ProgramMobil::class)->where('paid_off', false);
    }

    public function LunasProgramMobils()
    {
        return $this->hasMany(ProgramMobil::class)->where('paid_off', true);

    }    



}
