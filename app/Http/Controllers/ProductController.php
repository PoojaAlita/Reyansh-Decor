<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Product,Category,SubCategory,ChildCategory};
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{

    private function generateSKU($category = null, $subcategory = null, $childcat = null, $productName)
        {
            $c1 = $category ? strtoupper(substr($category, 0, 3)) : null;
            $c2 = $subcategory ? strtoupper(substr($subcategory, 0, 3)) : null;
            $c3 = $childcat ? strtoupper(substr($childcat, 0, 3)) : null;
            $p  = strtoupper(substr($productName, 0, 3));

            // Unique incremental number
            $last = Product::orderBy('id', 'DESC')->first();
            $nextId = $last ? $last->id + 1 : 1;
            $num = str_pad($nextId, 3, '0', STR_PAD_LEFT);

            // CASE 1: Only Category + Product
            if ($c1 && !$c2 && !$c3) {
                return $c1 . '-' . $p . '-' . $num;
            }

            // CASE 2: Category + Subcategory + Product (No child)
            if ($c1 && $c2 && !$c3) {
                return $c1 . '-' . $c2 . '-' . $p . '-' . $num;
            }

            // CASE 3: Category + Subcategory + Child + Product
            if ($c1 && $c2 && $c3) {
                return $c1 . '-' . $c2 . '-' . $c3 . '-' . $p . '-' . $num;
            }

            // Fallback
            return 'GEN-' . $p . '-' . $num;
        }


    public function index() {
        $categories = Category::where('isshown', 1)->get();
        $subcategories = SubCategory::where('isshown', 1)->get();
        $child_categories = ChildCategory::where('isshown', 1)->get();

        $products = Product::with(['category','subcategory','childCategory'])->latest()->get();

        return view('pages.products', compact('products','categories','subcategories','child_categories'));
    }

    // public function store(Request $request) {
    //     try {

    //         $data = [
    //             'category_id' => $request->category_id,
    //             'subcategory_id' => $request->subcategory_id,
    //             'child_category_id' => $request->child_category_id,
    //             'name' => $request->name,
    //             'sku' => $request->sku,
    //             'description' => $request->description,
    //             'price' => $request->price,
    //             'sale_price' => $request->sale_price,
    //             'stock' => $request->stock,
    //             'admin_id' => Auth::id(),
    //         ];

    //         /* Image Upload */
    //         if ($request->hasFile('main_image')) {

    //             if ($request->id) {
    //                 $old = Product::find($request->id);
    //                 if ($old && $old->main_image && file_exists(public_path('products/'.$old->main_image))) {
    //                     unlink(public_path('products/'.$old->main_image));
    //                 }
    //             }

    //             $image_name = time() . '_' . rand(1000,9999) . '.' . $request->main_image->extension();
    //             $request->main_image->move(public_path('products'), $image_name);
    //             $data['main_image'] = $image_name;
    //         }

    //         if ($request->id) $data['updated_at'] = now();
    //         else $data['updated_at'] = null;

    //         Product::updateOrCreate(['id' => $request->id], $data);

    //         return response([
    //             'status' => true,
    //             'message' => $request->id ? 'Updated Successfully' : 'Added Successfully',
    //             'icon' => 'success'
    //         ]);

    //     } catch (\Throwable $e) {
    //         return response([
    //             'status' => false,
    //             'message' => 'Something went wrong!',
    //             'icon' => 'error'
    //         ]);
    //     }
    // }

    public function store(Request $request) {
        try {

            // GET CATEGORY / SUB / CHILD NAMES FOR SKU
            $categoryName = $request->category_id ? Category::find($request->category_id)->name : null;
            $subcategoryName = $request->subcategory_id ? SubCategory::find($request->subcategory_id)->subcat_name : null;
            $childcatName = $request->child_category_id ? ChildCategory::find($request->child_category_id)->name : null;

            $data = [
                'category_id' => $request->category_id,
                'subcategory_id' => $request->subcategory_id,
                'child_category_id' => $request->child_category_id,
                'name' => $request->name,

                // AUTO SKU
                'sku' => $this->generateSKU(
                    $categoryName,
                    $subcategoryName,
                    $childcatName,
                    $request->name
                ),

                'description' => $request->description,
                'price' => $request->price,
                'sale_price' => $request->sale_price,
                'stock' => $request->stock,
                'admin_id' => Auth::id(),
            ];

            /* Image Upload */
            if ($request->hasFile('main_image')) {

                if ($request->id) {
                    $old = Product::find($request->id);
                    if ($old && $old->main_image && file_exists(public_path('products/'.$old->main_image))) {
                        unlink(public_path('products/'.$old->main_image));
                    }
                }

                $image_name = time() . '_' . rand(1000,9999) . '.' . $request->main_image->extension();
                $request->main_image->move(public_path('products'), $image_name);
                $data['main_image'] = $image_name;
            }

            if ($request->id) $data['updated_at'] = now();
            else $data['updated_at'] = null;

            Product::updateOrCreate(['id' => $request->id], $data);

            return response([
                'status' => true,
                'message' => $request->id ? 'Updated Successfully' : 'Added Successfully',
                'icon' => 'success'
            ]);

        } catch (\Throwable $e) {
            dd($e);
            return response([
                'status' => false,
                'message' => 'Something went wrong!',
                'icon' => 'error'
            ]);
        }
    }


    public function edit(Request $request) {
        $data = Product::find($request->id);
        if (!is_null($data)) {
            $imageUrl = '';
                if (!empty($data->main_image) && file_exists(public_path('products/'.$data->main_image))) {
                    $imageUrl = asset('products/'.$data->main_image);
                }
            return response(['status' => true, 'data' => $data]);
        }
    }

    public function delete(Request $request) {
        $find = Product::find($request->id);

        if (!$find)
            return response(['status' => false, 'message' => 'Record not found', 'icon' => 'error']);

        if ($find->main_image && file_exists(public_path('products/'.$find->main_image))) {
            unlink(public_path('products/'.$find->main_image));
        }

        $find->delete();

        return response(['status' => true, 'message' => 'Deleted Successfully', 'icon' => 'success']);
    }

    public function toggleStatus(Request $request) {
        $data = Product::find($request->id);

        if ($data) {
            $data->isshown = $request->status;
            $data->save();
            return response()->json(['success' => true, 'message' => 'Status Updated!', 'icon' => 'success']);
        }
        return response()->json(['success' => false, 'message' => 'Not Found!', 'icon' => 'error']);
    }

    public function checkNameUnique(Request $request) {
        $exists = Product::where('id', '!=', $request->id)
            ->where('name', $request->name)
            ->first();

        return $exists ? true : false;
    }

    public function getSubcategories(Request $request) {
        return SubCategory::where('category_id', $request->category_id)
            ->where('isshown', 1)
            ->get();
    }

    public function getChildcategories(Request $request) {
        return ChildCategory::where('subcategory_id', $request->subcategory_id)
            ->where('isshown', 1)
            ->get();
    }

}
