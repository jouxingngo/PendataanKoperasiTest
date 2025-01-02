<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramMobil extends Model
{
    protected $fillable = ['nomor_polisi','sisa_bayar', 'admin_id', 'member_id', 'status', 'transaction_date', 'cicilan', 'total_installment', 'paid_off'];

    public function member(){
        return $this->belongsTo(Member::class);
    }
    public function admin(){
        return $this->belongsTo(User::class);
    }
    public function setors()
    {
        return $this->hasMany(SetorProgramMobil::class);
    }
}
