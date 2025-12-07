<?php

namespace App\Http\Controllers;

use App\Models\HomeBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class HomeBannerController extends Controller
{
    public function index()
    {
        $home_banners = HomeBanner::latest()->get();
        return view('pages.homebanner', compact('home_banners'));
    }

    public function store(Request $request)
    {
        try {
            $data = [
                'title' => $request->title,
                'link' => $request->link,
                'admin_id' => Auth::id(),
            ];

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
                $destination = public_path('uploads/home_banners');

                if (!File::exists($destination)) {
                    File::makeDirectory($destination, 0755, true);
                }

                $file->move($destination, $filename);
                $data['image'] = $filename;
            }

            if ($request->id) {
                $existing = HomeBanner::find($request->id);
                if ($existing && $request->hasFile('image') && $existing->image) {
                    $old = public_path('uploads/home_banners/') . $existing->image;
                    if (File::exists($old)) File::delete($old);
                }
            }

            if ($request->id) $data['updated_at'] = now();
            else $data['updated_at'] = null;

            HomeBanner::updateOrCreate(['id' => $request->id], $data);

            return response([
                'status' => true,
                'message' => $request->id ? 'Updated Successfully' : 'Added Successfully',
                'icon' => 'success'
            ]);

        } catch (\Throwable $e) {
            return response(['status' => false, 'message' => 'Something went wrong', 'icon' => 'error']);
        }
    }

    public function edit(Request $request)
    {
        $data = HomeBanner::find($request->id);
        return response(['status' => true, 'data' => $data]);
    }

    public function delete(Request $request)
    {
        $find = HomeBanner::find($request->id);

        if (!$find)
            return response(['status' => false, 'message' => 'Record not found', 'icon' => 'error']);

        if ($find->image) {
            $path = public_path('uploads/home_banners/') . $find->image;
            if (file_exists($path)) @unlink($path);
        }

        $find->delete();

        return response(['status' => true, 'message' => 'Deleted Successfully', 'icon' => 'success']);
    }

    public function toggleStatus(Request $request)
    {
        $data = HomeBanner::find($request->id);

        if ($data) {
            $data->isshown = $request->status;
            $data->save();
            return response()->json(['success' => true, 'message' => 'Status Updated!', 'icon' => 'success']);
        }

        return response()->json(['success' => false, 'message' => 'Not Found!', 'icon' => 'error']);
    }

    public function checkUnique(Request $request)
    {
        $exists = HomeBanner::where('id', '!=', $request->id)
            ->where('title', $request->title)
            ->first();

        return $exists ? true : false;
    }

    public function getSorting(Request $request)
    {
        $pages = HomeBanner::orderBy('position')->get();
        $response = [];

        foreach ($pages as $p) {
            $response[] = $p->id . '-' . $p->title;
        }

        return implode('^', $response);
    }

    public function saveSorting(Request $request)
    {
        foreach ($request->order as $order) {
            list($sortorder, $id) = explode('^', $order);
            HomeBanner::where('id', $id)->update(['position' => $sortorder]);
        }

        return response()->json(['success' => true]);
    }
}
