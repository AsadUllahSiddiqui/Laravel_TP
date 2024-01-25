<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function create()
    {
        $data = [];
        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();
        $data['categories'] = $categories;
        $data['brands'] = $brands;
        return view("admin.product.create", $data);
    }

    public function store(Request $request)
    {
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:sub_categories',
            'sku' => 'required',
            'price' => 'required|numeric',
            'status' => 'required',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'sub_category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
        ];

        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {

            // $product = new Product();
            // $product->title = $request->title;
            // $product->slug = $request->slug;
            // $product->category_id = $request->category_id;
            // $product->sub_category_id = $request->sub_category_id;
            // $product->brand_id = $request->brand_id;
            // $product->status = $request->status;
            // $product->save();
            // $request->session()->flash("success", "Sub Category added successfully");
            // return response([
            //     'status' => true,
            //     'success' => "Sub category created successfully"
            // ]);


        } else {
            $request->session()->flash("error", $validator->errors());
            return response([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

}
