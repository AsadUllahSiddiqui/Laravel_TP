<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{

    public function index(Request $request)
    {
        $subCategories = SubCategory::select('sub_categories.*', 'categories.name as categoryName')
            ->latest('sub_categories.id')
            ->leftjoin('categories', 'categories.id', 'sub_categories.category_id');

        if (!empty($request->get('keyword'))) {
            $subCategories = $subCategories->where('sub_categories.name', 'like', '%' . $request->get('keyword') . '%');
            $subCategories = $subCategories->orWhere('categories.name', 'like', '%' . $request->get('keyword') . '%');
        }

        $subCategories = $subCategories->paginate(10);

        // $data['categories'] = $categories;
        return view('admin.sub_category.list', compact('subCategories'));
    }

    public function create()
    {
        $categories = Category::orderby('name', 'ASC')->get();
        return view('admin.sub_category.create', compact('categories'));
    }


    public function edit($SubCategoryId, Request $request)
    {
        $subCategory = SubCategory::find($SubCategoryId);
        $categories = Category::orderby('name', 'ASC')->get();
        if (!empty($subCategory)) {
            $data['categories'] = $categories;
            $data['subCategory'] = $subCategory;
            return view('admin.sub_category.edit', $data);
        } else {
            $request->session()->flash("error", "Sub Category not found");
            return redirect()->route('sub-categories.index');
        }


    }


    public function update($subCategoryId, Request $request)
    {

        $subCategory = SubCategory::find($subCategoryId);

        if (empty($subCategory)) {
            return response([
                'status' => false,
                'nofound' => true,
                'errors' => 'Record not found'
            ]);

        } else {


            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'slug' => 'required|unique:sub_categories,slug,' . $subCategory->id . ',id',
                'category_id' => 'required',
                'status' => 'required',
            ]);

            if ($validator->passes()) {

                // $subCategory = new SubCategory();    
                $subCategory->name = $request->name;
                $subCategory->slug = $request->slug;
                $subCategory->category_id = $request->category_id;
                $subCategory->status = $request->status;
                $subCategory->save();
                $request->session()->flash("success", "Sub Category added successfully");
                return response([
                    'status' => true,
                    'success' => "Sub category created successfully"
                ]);


            } else {
                $request->session()->flash("error", $validator->errors());
                return response([
                    'status' => false,
                    'errors' => $validator->errors()
                ]);
            }
        }
    }



    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:sub_categories',
            'category_id' => 'required',
            'status' => 'required',
        ]);

        if ($validator->passes()) {

            $subCategory = new SubCategory();
            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->category_id = $request->category_id;
            $subCategory->status = $request->status;
            $subCategory->save();
            $request->session()->flash("success", "Sub Category added successfully");
            return response([
                'status' => true,
                'success' => "Sub category created successfully"
            ]);


        } else {
            $request->session()->flash("error", $validator->errors());
            return response([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($subCategoryId, Request $request)
    {
        $subCategory = SubCategory::find($subCategoryId);
        if (empty($subCategory)) {
            $request->session()->flash("error", "Sub Category not found!");
            return response()->json([
                'status' => false,
                'message' => "Sub Category not found!"
            ]);
        }
        $subCategory->delete();
        $request->session()->flash("success", "Sub category deleted successfully");
        return response()->json([
            'status' => true,
            'message' => "Sub category deleted successfully"
        ]);

    }
}
