<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductVariantController extends Controller
{
     public function index() {
        $products = Product::where('isshown', 1)->get();
        $product_variants = ProductVariant::with('product')->latest()->get();
        return view('pages.productvariants', compact('product_variants','products'));
    }

    public function store(Request $request) {
        try {
            $data = [
                'product_id' => $request->product_id,
                'size' => $request->size,
                'color' => $request->color,
                'material' => $request->material,
                'price' => $request->price,
                'stock' => $request->stock,
                'admin_id' => Auth::id(),
            ];

            if ($request->id) $data['updated_at'] = now();
            else $data['updated_at'] = null;

            ProductVariant::updateOrCreate(['id' => $request->id], $data);

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
        $data = ProductVariant::find($request->id);
        return response(['status' => true, 'data' => $data]);
    }

    public function delete(Request $request) {
        $find = ProductVariant::find($request->id);

        if (!$find)
            return response(['status' => false, 'message' => 'Record not found', 'icon' => 'error']);

        $find->delete();
        return response(['status' => true, 'message' => 'Deleted Successfully', 'icon' => 'success']);
    }

    public function toggleStatus(Request $request) {
        $data = ProductVariant::find($request->id);

        if ($data) {
            $data->isshown = $request->status;
            $data->save();

            return response()->json(['success' => true, 'message' => 'Status Updated!', 'icon' => 'success']);
        }
        return response()->json(['success' => false, 'message' => 'Not Found!', 'icon' => 'error']);
    }

    public function checkProductVariantUnique(Request $request) {
        // Option A: unique on product_id + size + color + material
        // since size/color/material can be nullable, compare with COALESCE to empty string
        $exists = ProductVariant::where('id', '!=', $request->id)
            ->where('product_id', $request->product_id)
            ->whereRaw("COALESCE(size,'') = ?", [ $request->size ?? '' ])
            ->whereRaw("COALESCE(color,'') = ?", [ $request->color ?? '' ])
            ->whereRaw("COALESCE(material,'') = ?", [ $request->material ?? '' ])
            ->first();

        return $exists ? true : false;
    }
}
