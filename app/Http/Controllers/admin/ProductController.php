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
            'slug' => 'required|unique:products',
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

            $product = new Product();
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->barcode = $request->barcode;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->brand_id = $request->brand;
            $product->sku = $request->sku;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->price = $request->price;
            $product->is_featured = $request->is_featured;
            $product->compare_price = $request->compare_price;
            $product->save();
            $request->session()->flash("success", "Product created successfully");
            return response([
                'status' => true,
                'success' => "Product created successfully"
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
