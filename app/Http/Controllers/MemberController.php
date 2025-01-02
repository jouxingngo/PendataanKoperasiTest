<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Remaining_debt;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $members = Member::with('admin')->get();
        return view('member.index', compact('members'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('member.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $member = Member::create($request->all());
        $debt = Remaining_debt::create([
            'member_id' => $member->id,
            'program_bos' => 0,
            'program_bop' => 0,
            'program_mobil' => 0,
            'program_motor' => 0,
        ]);
        if($member){
            session()->flash('status', 'success');
            session()->flash('message', 'Member ' . $member->name . " Successfully added");
        }
        return redirect()->route('member.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $member = Member::with('Debt')->findOrFail($id);
        return view('member.show', compact('member'));   
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return redirect()->route('member.index');
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $member = Member::findOrFail($id);
        $member->update($request->all());
        if($member){
            session()->flash('status', 'success');
            session()->flash('message', 'Member ' . $member->name . " Successfully edited");
        }
        return redirect()->route('member.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $member = Member::findOrFail($id);
        $member->delete();
        if($member){
            session()->flash('status', 'success');
            session()->flash('message', 'Member ' . $member->name . " Successfully deleted");
        }
        return redirect()->route('member.index');

    }
}
