<?php

namespace App\Http\Controllers;

use App\Models\ProductImage;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductImageController extends Controller
{
    public function index() {
        $products = Product::where('isshown', 1)->get();
        $product_images = ProductImage::with('product')->latest()->get();
        return view('pages.productimages', compact('product_images','products'));
    }

    public function store(Request $request) {
        try {
            $data = [
                'product_id' => $request->product_id,
                'admin_id' => Auth::id(),
            ];

            /* Image Upload */
            if($request->hasFile('image')){
                $img = $request->file('image');
                $name = time().rand(1000,9999).".".$img->getClientOriginalExtension();
                $img->move(public_path('uploads/product_images'), $name);
                $data['image'] = $name;
            }

            /* Update old image delete */
            if ($request->id) {
                $find = ProductImage::find($request->id);
                if ($request->hasFile('image') && $find->image && file_exists(public_path('uploads/product_images/'.$find->image))) {
                    unlink(public_path('uploads/product_images/'.$find->image));
                }
                $data['updated_at'] = now();
            }
            else {
                $data['updated_at'] = null;
            }

            ProductImage::updateOrCreate(['id' => $request->id], $data);

            return response([
                'status' => true,
                'message' => $request->id ? 'Updated Successfully' : 'Added Successfully',
                'icon' => 'success'
            ]);

        } catch (\Throwable $e) {
            return response([
                'status' => false,
                'message' => 'Something went wrong!',
                'icon' => 'error'
            ]);
        }
    }

    public function edit(Request $request) {
        $data = ProductImage::find($request->id);
         if (!is_null($data)) {
            $imageUrl = '';
                if (!empty($data->image) && file_exists(public_path('uploads/product_images//'.$data->image))) {
                    $imageUrl = asset('uploads/product_images//'.$data->image);
                }
                 return response(['status' => true, 'data' => $data]);
            }
    }

    public function delete(Request $request) {
        $find = ProductImage::find($request->id);

        if(!$find)
            return response(['status' => false, 'message' => 'Record not found', 'icon' => 'error']);

        if ($find->image && file_exists(public_path('uploads/product_images/'.$find->image))) {
            unlink(public_path('uploads/product_images/'.$find->image));
        }

        $find->delete();

        return response(['status' => true, 'message' => 'Deleted Successfully', 'icon' => 'success']);
    }

    public function toggleStatus(Request $request) {
        $data = ProductImage::find($request->id);

        if ($data) {
            $data->isshown = $request->status;
            $data->save();
            return response()->json(['success' => true, 'message' => 'Status Updated!', 'icon' => 'success']);
        }
        return response()->json(['success' => false, 'message' => 'Not Found!', 'icon' => 'error']);
    }
}
