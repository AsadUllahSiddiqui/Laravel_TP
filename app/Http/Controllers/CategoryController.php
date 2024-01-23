<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\ImageManager;

class CategoryController extends Controller
{
  public function index(Request $request)
  {
    $categories = Category::latest();
    if (!empty($request->get('keyword'))) {
      $categories = $categories->where('name', 'like', '%' . $request->get('keyword') . '%');
    }

    $categories = $categories->paginate(10);

    // $data['categories'] = $categories;
    return view('admin.category.list', compact('categories'));
  }

  public function create()
  {
    return view('admin.category.create');
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'slug' => 'required|unique:categories',

    ]);
    if ($validator->fails()) {
      $request->session()->flash("error", $validator->errors());
      return response()->json([
        'status' => false,
        'errors' => $validator->errors()
      ]);
    } else {

      $category = new Category;
      $category->name = $request->name;
      $category->slug = $request->slug;
      $category->status = $request->status;
      $category->save();

      //save image here
      if (!empty($request->image_id)) {
        $tempImage = TempImage::find($request->image_id);
        $extArray = explode('.', $tempImage->name);
        $ext = last($extArray);
        $newImageName = $category->id . '.' . $ext;
        $sPath = public_path() . '/temp/' . $tempImage->name;
        $dPath = public_path() . '/uploads/category/' . $newImageName;
        File::copy($sPath, $dPath);

        //create thumbails
        $dPath = public_path() . '/uploads/category/thumb/' . $newImageName;
        $image = ImageManager::imagick()->read($sPath);
        $image = $image->resizeDown(450, 600);
        $image->save($dPath);


        $category->image = $newImageName;
        $category->save();

      }

      $request->session()->flash("success", "Category added successfully");
      return response()->json([
        'status' => true,
        'message' => "Category created successfully"
      ]);
    }
  }

  public function edit($CategoryId, Request $request)
  {
    $category = Category::find($CategoryId);
    if (!empty($category)) {
      return view('admin/category/edit', compact('category'));
    } else {
      return redirect()->route('categories.index');
    }

  }

  public function update($CategoryId, Request $request)
  {
    $category = Category::find($CategoryId);
    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'slug' => 'required|unique:categories,slug,' . $category->id . ',id',

    ]);
    if ($validator->fails()) {
      $request->session()->flash("error", $validator->errors());
      return response()->json([
        'status' => false,
        'errors' => $validator->errors()
      ]);
    } else {
      $category->name = $request->name;
      $category->slug = $request->slug;
      $category->status = $request->status;
      $category->save();

      //save image here
      if (!empty($request->image_id)) {
        $tempImage = TempImage::find($request->image_id);
        $extArray = explode('.', $tempImage->name);
        $ext = last($extArray);
        $newImageName = $category->id . '.' . $ext;
        $sPath = public_path() . '/temp/' . $tempImage->name;
        $dPath = public_path() . '/uploads/category/' . $newImageName;
        File::copy($sPath, $dPath);

        //create thumbails
        $dPath = public_path() . '/uploads/category/thumb/' . $newImageName;
        $image = ImageManager::imagick()->read($sPath);
        $image = $image->resizeDown(450, 600);
        $image->save($dPath);


        $category->image = $newImageName;
        $category->save();

      }

      $request->session()->flash("success", "Category updated successfully");
      return response()->json([
        'status' => true,
        'message' => "Category updated successfully"
      ]);
    }
  }
}
