<?php

namespace App\Http\Controllers\admin;

use App\Models\Disease;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;

class DiseaseController extends Controller
{
    public function allDiseasesView(): View
    {
        $diseases = Disease::all();
        return view('pages.admin.diseases.all-diseases', compact('diseases'));
    }
    public function editDiseasesView($id): View
    {
        $disease = Disease::find($id);
        return view('pages.admin.diseases.edit-disease', compact('disease'));
    }
    public function editDiseases(Request $request)
    {
        $disease = Disease::where('id', $request->id)->first();
        if (empty($disease) || !isset($disease))
            return back()->with('msg', 'disease not found.');
        // disease name must be unique .
        $disease_by_new_name = Disease::where('name', '=', $request->name)->whereNot('id', $request->id)->first();
        // if this var is exsists this mean there is already disease with this new name.
        if (!empty($disease_by_new_name) || isset($disease_by_new_name))
            return back()->with('msg', 'The new disease name already taken.');
        $disease->update(['name' => $request->name]);
        return back()->with('msg', 'updated.');
    }
    public function newDiseasesView(): View
    {
        return view('pages.admin.diseases.new-disease');
    }
    public function newDiseasesStore(Request $request)
    {
        // disease name must be unique .
        $disease_by_new_name = Disease::where('name', '=', $request->name)->first();
        // if this var is exsists this mean there is already disease with this new name.
        if (!empty($disease_by_new_name) || isset($disease_by_new_name))
            return back()->with('msg', 'The new disease name already taken.');
        Disease::create(['name' => $request->name]);
        return back()->with('msg', 'Created.');
    }
    public function deleteDisease(Request $request)
    {
        $disease_by_new_id = Disease::where('id', '=', $request->id)->first();
        if (empty($disease_by_new_id) || !isset($disease_by_new_id))
            return back()->with('msg', 'This disease not found.');
        $disease_by_new_id->delete();
        return back()->with('msg', 'Deleted.');
    }
}
