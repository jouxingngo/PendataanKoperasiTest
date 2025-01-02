<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Remaining_debt extends Model
{
    protected $fillable = ['member_id', 'program_bos', 'program_bop','program_motor','program_mobil'];

}
