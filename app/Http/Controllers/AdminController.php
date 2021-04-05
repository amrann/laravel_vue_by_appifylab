<?php

namespace App\Http\Controllers;

use App\Categori;
use App\Tag;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function tambahTag(Request $request)
    {
        $this->validate($request, [
            'tagName' => 'required'
        ]);
        return Tag::create([
            'tagName' => $request->tagName
        ]);
    }

    public function editTag(Request $request)
    {
        $this->validate($request, [
            'tagName' => 'required',
            'id' => 'required',

        ]);
        return Tag::where('id', $request->id)->update([
            'tagName' => $request->tagName
        ]);
    }

    public function hapusTag(Request $request)
    {
        $this->validate($request, [
            'id' => 'required'
        ]);
        return Tag::where('id', $request->id)->delete();
    }

    public function getTag()
    {
        // desc = descending
        return Tag::orderBy('id', 'desc')->get();
    }


    // Categori
    public function uploadImgCategori(Request $request)
    {
        // $this->validate($request, [
        //     'file' => 'required|mimes:jpeg,jpg,png'
        // ]);
        $pictName = time().'.'.$request->file->extension();
        $request->file->move(public_path('mygambar'), $pictName);
        return $pictName;
    }

    public function hapusGambar(Request $request)
    {
        $fileName = $request->namaGmbr;
        $filePath = public_path().'/mygambar/'.$fileName;
        if (file_exists($filePath)) {
            @unlink($filePath);
        }
        return 'done';
    }

    public function getCategori()
    {
        // desc = descending
        return Categori::orderBy('id', 'desc')->get();
    }

    public function tambahCategori(Request $request)
    {
        $this->validate($request, [
            'categoriName' => 'required',
            'iconImage' => 'required',
        ]);
        return Categori::create([
            'categoriName' => $request->categoriName,
            'iconImage' => $request->iconImage
        ]);
    }

    public function editCategori(Request $request)
    {
        $this->validate($request, [
            'categoriName' => 'required',
            'iconImage' => 'required',
        ]);
        return Categori::where('id', $request->id)->update([
            'categoriName' => $request->categoriName,
            'iconImage' => $request->iconImage
        ]);
    }
}
