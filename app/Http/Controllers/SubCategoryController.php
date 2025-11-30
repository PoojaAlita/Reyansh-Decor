<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{SubCategory,Category};
use Illuminate\Support\Facades\Auth;


class SubCategoryController extends Controller
{
     public function index()
    {
        $categories = Category::where('isshown', 1)->get();
        $subcategories = SubCategory::with('category')->latest()->get();
        return view('pages.subcategory', compact('subcategories','categories'));
    }

    public function store(Request $request)
    {
        try {

            $data = [
                'category_id' => $request->category_id,
                'subcat_name' => $request->name,
                'admin_id' => Auth::id(),
            ];

            if ($request->id) {
                $data['updated_at'] = now();
            } else {
                $data['updated_at'] = null;
            }

            SubCategory::updateOrCreate(
                ['id' => $request->id],
                $data
            );

            return response([
                'status' => true,
                'message' => $request->id ? 'Updated Successfully' : 'Added Successfully',
                'icon' => 'success'
            ]);
        } 
        catch (\Throwable $e) {
            return response([
                'status' => false,
                'message' => 'Something went wrong!',
                'icon' => 'error'
            ]);
        }
    }

    public function edit(Request $request)
    {
        $data = SubCategory::find($request->id);

        return response([
            'status' => true,
            'data' => $data
        ]);
    }

    public function delete(Request $request)
    {
        $find = SubCategory::find($request->id);

        if (!$find)
            return response(['status' => false, 'message' => 'Record not found', 'icon' => 'error']);

        $find->delete();

        return response(['status' => true, 'message' => 'Deleted Successfully', 'icon' => 'success']);
    }

    public function toggleStatus(Request $request)
    {
        $data = SubCategory::find($request->id);

        if ($data) {
            $data->isshown = $request->status;
            $data->save();

            return response()->json(['success' => true, 'message' => 'Status Updated!', 'icon' => 'success']);
        }

        return response()->json(['success' => false, 'message' => 'Not Found!', 'icon' => 'error']);
    }

    public function checkSubCategoryUnique(Request $request)
    {
        $exists = SubCategory::where('id', '!=', $request->id)
            ->where('subcat_name', $request->name)
            ->where('category_id', $request->category_id)
            ->first();

        return $exists ? true : false;
    }
}
