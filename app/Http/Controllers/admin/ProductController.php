<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;

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
        // dd($request);
        // exit;

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

            //save images here
            if (!empty($request->image_array)) {
                foreach ($request->image_array as $temp_image_id) {
                    $tempImageInfo = TempImage::find($temp_image_id);
                    $infoArray = explode('.', $tempImageInfo->name);
                    $ext = last($infoArray);

                    // dd($infoArray);
                    // exit;

                    $productImage = new ProductImage();
                    $productImage->product_id = $product->id;
                    $productImage->image = Null;
                    $productImage->save();

                    $imageName = $product->id . '-' . $productImage->id . '-' . time() . '.' . $ext;      // 1-2-13123.jpg
                    $productImage->image = $imageName;
                    $productImage->save();

                    // generate thumbnail


                    // largeImages
                    $sPath = public_path() . '/temp/' . $tempImageInfo->name;
                    $dPath = public_path() . '/uploads/product/large/' . $tempImageInfo->name;
                    $image = ImageManager::imagick()->read($sPath);
                    $image = $image->resize(height: 1400);
                    $image->save($dPath);

                    // smallImages

                    $sPath = public_path() . '/temp/' . $tempImageInfo->name;
                    $dPath = public_path() . '/uploads/product/small/' . $tempImageInfo->name;
                    $image = ImageManager::imagick()->read($sPath);
                    $image = $image->resize(300, 300);
                    $image->save($dPath);
                }
            }


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