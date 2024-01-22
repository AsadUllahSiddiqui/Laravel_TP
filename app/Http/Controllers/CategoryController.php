<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
  public function index()
  {
     $categories= Category::latest()->paginate(10);
    // $data['categories'] = $categories;
     return view('admin.category.list',compact('categories'));
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

      $request->session()->flash("success","Category added successfully");
      return response()->json([
        'status' => true,
        'message' => "Category added successfully"
      ]);
    }
  }

  public function edit()
  {

  }

  public function update()
  {

  }
}