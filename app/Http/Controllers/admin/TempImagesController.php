<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;

class TempImagesController extends Controller
{
    public function create(Request $request)
    {

        $image = $request->image;
        $ext = $image->getClientOriginalExtension();
        $newName = time() . '.' . $ext;
        $tempImage = new TempImage();
        $tempImage->name = $newName;
        $tempImage->save();
        $image->move(public_path() . '/temp', $newName);


        $sPath = public_path() . '/temp/' . $newName;
        $dPath = public_path() . '/temp/thumb/' . $newName;
        $image = ImageManager::imagick()->read($sPath);
        $image = $image->resizeDown(300, 275);
        $image->save($dPath);

        return response()->json([
            'status' => true,
            'image_id' => $tempImage->id,
            'image_path' => asset('/temp/thumb/' . $newName),
            'message' => 'Image uploaded successfully!'
        ]);
    }
}
