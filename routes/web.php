<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProgramBOPDataController;
use App\Http\Controllers\ProgramBOSDataController;
use App\Http\Controllers\ProgramMobilController;
use App\Http\Controllers\ProgramMotorController;
use App\Http\Controllers\SetorProgramMobilController;
use App\Http\Controllers\SetorProgramMotorController;
use App\Http\Controllers\TransactionDataController;
use App\Http\Middleware\SuperAdmin;
use App\Models\Program_BOS_Data;
use App\Models\ProgramMobil;
use App\Models\SetorProgramMotor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('admin.index');
});

Route::middleware(['guest'])->group(function () {

    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticating'])->name('auth.authenticating');
});

Route::middleware('auth')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::resource('admin', AdminController::class);
    Route::resource('member', MemberController::class);
    Route::get('transaction/setor', [TransactionDataController::class, 'setor'])->name('transaction.setor');
    // program bos pake resource
    Route::resource('program_bos', ProgramBOSDataController::class);
    Route::post('member/program_bos/{id}/store', [ProgramBOSDataController::class, 'stored'])->name('program_bos.stored');
    Route::get('member/program_bos/{id}', [ProgramBOSDataController::class, 'memberProgramBos'])->name('member.program_bos');
    // program bop pake resource
    Route::resource('program_bop', ProgramBOPDataController::class);
    Route::get('member/program_bop/{id}', [ProgramBOPDataController::class, 'memberProgramBop'])->name('member.program_bop');
    Route::post('member/program_bop/{id}', [ProgramBOPDataController::class, 'stored'])->name('program_bop.stored');

    // program motor tidak pakai resource
    Route::get('/member/{member}/program_motor/{program}', [ProgramMotorController::class, 'show'])->name('program_motor.show');
    Route::get('member/program_motor/{id}', [ProgramMotorController::class, 'index'])->name('member.program_motor');
    Route::post('member/program_motor/{id}', [ProgramMotorController::class, 'store'])->name('program_motor.stored');
    Route::delete('member/program_motor/{id}',[ProgramMotorController::class,'destroy'])->name('program_motor.destroy');
    Route::post('setor/program_motor/{id}',[SetorProgramMotorController::class, 'store'])->name('setor_program_motor.store');
    Route::delete('setor/program_motor/{id}/delete',[SetorProgramMotorController::class,'destroy'])->name('setor_program_motor.destroy');
    Route::get('program_motor/{id}/lunas',[ProgramMotorController::class,'lunas'])->name('program_motor.lunas');
    Route::get('program_motor/{member}/lunas/{program}', [ProgramMotorController::class, 'showLunas'])->name('program_motor_lunas.show');


    // program mobil tidak pakai resoure
    Route::get('member/program_mobil/{id}', [ProgramMobilController::class, 'index'])->name('member.program_mobil');
    Route::post('member/program_mobil/{id}', [ProgramMobilController::class, 'store'])->name('program_mobil.stored');
    Route::delete('member/program_mobil/{id}',[ProgramMobilController::class,'destroy'])->name('program_mobil.destroy');
    Route::post('setor/program_mobil/{id}',[SetorProgramMobilController::class, 'store'])->name('setor_program_mobil.store');
    Route::delete('setor/program_mobil/{id}/delete',[SetorProgramMobilController::class,'destroy'])->name('setor_program_mobil.destroy');
    Route::get('program_mobil/{id}/lunas',[ProgramMobilController::class,'lunas'])->name('program_mobil.lunas');
    Route::get('program_mobil/{member}/lunas/{program}', [ProgramMobilController::class, 'showLunas'])->name('program_mobil_lunas.show');



    Route::resource('transaction', TransactionDataController::class);
    Route::put('transaction/acc/{id}', [TransactionDataController::class, 'acc'])->name('transaction.acc');
});
