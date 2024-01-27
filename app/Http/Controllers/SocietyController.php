<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Society;

class SocietyController extends Controller
{
    public function index()
    {
        $academicSocieties = Society::where('societyType', 'Academic')->where('approved', 1)->get();
        $socialSocieties = Society::where('societyType', 'Social')->where('approved', 1)->get();

        return view('societies', compact('academicSocieties', 'socialSocieties'));
    }

    public function viewSociety($id)
    {
        $society = Society::findOrFail($id);

        return view('view-society', compact('society'));
    }


    public function createSociety(Request $request)
    {
        $validatedData = $request->validate([
            'societyType' => 'required',
            'societyName' => $request->societyType === 'academic' ? 'required' : '',
            'subjectList' => $request->societyType === 'academic' ? 'required' : '',
            'societyDescription' => 'required',
        ]);

        $society = new Society();

        $society->ownerId = auth()->user()->id;
        $society->societyType = $validatedData['societyType'];
        $society->societyName = $request->societyType === 'Academic' ? $validatedData['subjectList'] : $validatedData['societyName'];
        $society->societyDescription = $validatedData['societyDescription'];
        $society->approved = false;

        $society->save();

        $societyTypeName = $request->societyType === 'Academic' ? 'Academic' : 'Social';

        session()->flash('success', "Thank you for your submission! Your " . strtolower($societyTypeName) . " society will be reviewed by an administrator.");

        return redirect()->route('societies');
    } 
}
