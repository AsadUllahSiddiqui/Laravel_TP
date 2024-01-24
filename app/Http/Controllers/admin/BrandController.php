<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    public function create()
    {
        return view('admin.brand.create');
    }
    public function index(Request $request)
    {
        $brands = Brand::latest();
        if (!empty($request->get('keyword'))) {
            $brands = $brands->where('name', 'like', '%' . $request->get('keyword') . '%');
        }
        $brands = $brands->paginate(10);
        return view('admin.brand.list', compact('brands'));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:sub_categories',
            'status' => 'required',
        ]);

        if ($validator->passes()) {

            $brand = new Brand();
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();
            $request->session()->flash("success", "Brand added successfully");
            return response([
                'status' => true,
                'success' => "Brand created successfully"
            ]);


        } else {
            $request->session()->flash("error", $validator->errors());
            return response([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function edit($brandID, Request $request)
    {
        $brand = Brand::find($brandID);
        if (!empty($brand)) {
            return view('admin/brand/edit', compact('brand'));
        } else {
            return redirect()->route('brands.index');
        }

    }
    public function update($brandId, Request $request)
    {

        $brand = Brand::find($brandId);

        if (empty($brand)) {
            return response([
                'status' => false,
                'nofound' => true,
                'errors' => 'Record not found'
            ]);

        } else {


            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'slug' => 'required|unique:sub_categories,slug,' . $brand->id . ',id',
                'status' => 'required',
            ]);

            if ($validator->passes()) {

                $brand->name = $request->name;
                $brand->slug = $request->slug;
                $brand->status = $request->status;
                $brand->save();
                $request->session()->flash("success", "Brand updated successfully");
                return response([
                    'status' => true,
                    'success' => "Brand updated successfully"
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
    public function destroy($brandId, Request $request)
    {
        $brand = Brand::find($brandId);
        if (empty($brand)) {
            $request->session()->flash("error", "Brand not found!");
            return response()->json([
                'status' => false,
                'message' => "Brand not found!"
            ]);
        }
        $brand->delete();
        $request->session()->flash("success", "brand deleted successfully");
        return response()->json([
            'status' => true,
            'message' => "Brand deleted successfully"
        ]);

    }
}
