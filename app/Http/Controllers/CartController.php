<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::where('admin_id', Auth::id())->with(['product', 'variant'])->get();
        return view('frontend.cart', compact('carts'));
    }

    public function store(Request $request)
    {
        try {
            $data = [
                'product_id' => $request->product_id,
                'variant_id' => $request->variant_id ?: null,
                'quantity' => $request->quantity ?? 1,
                'isshown' => $request->isshown ?? 1,
                'admin_id' => Auth::id(),
            ];

            if ($request->id) $data['updated_at'] = now();
            else $data['updated_at'] = null;

            Cart::updateOrCreate(['id' => $request->id], $data);

            return response([
                'status' => true,
                'message' => $request->id ? 'Updated Successfully' : 'Added Successfully',
                'icon' => 'success'
            ]);
        } catch (\Throwable $e) {
            // remove dd in production — keeping same pattern as your example but return error
            // dd($e);
            return response([
                'status' => false,
                'message' => 'Something went wrong!',
                'icon' => 'error'
            ]);
        }
    }

    public function edit(Request $request)
    {
        $data = Cart::find($request->id);
        return response(['status' => true, 'data' => $data]);
    }

    public function delete(Request $request)
    {
        $find = Cart::find($request->id);

        if (!$find)
            return response(['status' => false, 'message' => 'Record not found', 'icon' => 'error']);

        $find->delete();
        return response(['status' => true, 'message' => 'Deleted Successfully', 'icon' => 'success']);
    }

    public function toggleStatus(Request $request)
    {
        $data = Cart::find($request->id);

        if ($data) {
            $data->isshown = $request->status;
            $data->save();

            return response()->json(['success' => true, 'message' => 'Status Updated!', 'icon' => 'success']);
        }
        return response()->json(['success' => false, 'message' => 'Not Found!', 'icon' => 'error']);
    }

    /**
     * Check uniqueness (optional): returns true if exists (to match HomeVideo JS logic)
     * We will check product_id + variant_id uniqueness excluding current id
     */
    public function checkCartUnique(Request $request)
    {
        $exists = Cart::where('id', '!=', $request->id)
            ->where('product_id', $request->product_id)
            ->where(function ($q) use ($request) {
                if ($request->variant_id) {
                    $q->where('variant_id', $request->variant_id);
                } else {
                    $q->whereNull('variant_id');
                }
            })
            ->first();

        return $exists ? true : false;
    }

    /**
     * Return variants for a product (AJAX)
     * Responds with JSON array of variants
     */
    public function getVariants(Request $request)
    {
        $product_id = $request->product_id;
        $variants = ProductVariant::where('product_id', $product_id)->orderBy('material')->get();
        return response()->json(['success' => true, 'variants' => $variants]);
    }
}
