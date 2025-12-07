<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductVideo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ProductVideoController extends Controller
{
    public function index() {
        return view('pages.productvideo');
    }

    public function store(Request $request) {
        try {
            $data = [
                'product_id' => $request->product_id,
                'title' => $request->title,
                'video_url' => $request->video_url,
                'admin_id' => Auth::id(),
            ];

            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $filename = time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
                $destination = public_path('uploads/product_videos');
                if (!File::exists($destination)) File::makeDirectory($destination, 0755, true);
                $file->move($destination, $filename);
                $data['thumbnail'] = $filename;
            }

            if ($request->id) {
                $existing = ProductVideo::find($request->id);
                if ($existing && $request->hasFile('thumbnail') && $existing->thumbnail) {
                    $old = public_path('uploads/product_videos/') . $existing->thumbnail;
                    if (File::exists($old)) File::delete($old);
                }
            }

            $data['updated_at'] = $request->id ? now() : null;

            ProductVideo::updateOrCreate(['id' => $request->id], $data);

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
        $data = ProductVideo::find($request->id);
        return response(['status' => true, 'data' => $data]);
    }

    public function delete(Request $request) {
        $find = ProductVideo::find($request->id);
        if (!$find) return response(['status' => false, 'message' => 'Record not found', 'icon' => 'error']);

        if ($find->thumbnail) {
            $path = public_path('uploads/product_videos/') . $find->thumbnail;
            if (file_exists($path)) @unlink($path);
        }

        $find->delete();
        return response(['status' => true, 'message' => 'Deleted Successfully', 'icon' => 'success']);
    }

    public function toggleStatus(Request $request) {
        $data = ProductVideo::find($request->id);
        if ($data) {
            $data->ishown = $request->status;
            $data->save();
            return response()->json(['success' => true, 'message' => 'Status Updated!', 'icon' => 'success']);
        }
        return response()->json(['success' => false, 'message' => 'Not Found!', 'icon' => 'error']);
    }

    public function checkProductVideoUnique(Request $request) {
        $exists = ProductVideo::where('id', '!=', $request->id)
            ->where('title', $request->title)
            ->first();
        return $exists ? true : false;
    }
    
}
