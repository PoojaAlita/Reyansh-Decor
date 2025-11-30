<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{ChildCategory, SubCategory};
use Illuminate\Support\Facades\Auth;

class ChildCategoryController extends Controller
{
     public function index() {
        $subcategories = SubCategory::where('isshown', 1)->get();
        $child_categories = ChildCategory::with('subcategory')->latest()->get();
        return view('pages.childcategory', compact('child_categories','subcategories'));
    }

    public function store(Request $request) {
        try {
            $data = [
                'subcategory_id' => $request->subcategory_id,
                'name' => $request->name,
                'admin_id' => Auth::id(),
            ];

            if ($request->id) $data['updated_at'] = now();
            else $data['updated_at'] = null;

            ChildCategory::updateOrCreate(['id' => $request->id], $data);

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
        $data = ChildCategory::find($request->id);
        return response(['status' => true, 'data' => $data]);
    }

    public function delete(Request $request) {
        $find = ChildCategory::find($request->id);

        if (!$find)
            return response(['status' => false, 'message' => 'Record not found', 'icon' => 'error']);

        $find->delete();
        return response(['status' => true, 'message' => 'Deleted Successfully', 'icon' => 'success']);
    }

    public function toggleStatus(Request $request) {
        $data = ChildCategory::find($request->id);

        if ($data) {
            $data->isshown = $request->status;
            $data->save();

            return response()->json(['success' => true, 'message' => 'Status Updated!', 'icon' => 'success']);
        }
        return response()->json(['success' => false, 'message' => 'Not Found!', 'icon' => 'error']);
    }

    public function checkChildCategoryUnique(Request $request) {
        $exists = ChildCategory::where('id', '!=', $request->id)
            ->where('name', $request->name)
            ->where('subcategory_id', $request->subcategory_id)
            ->first();

        return $exists ? true : false;
    }
}
