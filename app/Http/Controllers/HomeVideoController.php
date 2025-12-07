<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomeVideo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class HomeVideoController extends Controller
{
    public function index() {
        $home_videos = HomeVideo::latest()->get();
        return view('pages.homevideo', compact('home_videos'));
    }

    public function store(Request $request) {
        try {
            $data = [
                'title' => $request->title,
                'video_url' => $request->video_url,
                // 'position' => $request->position ?? 0,
                'admin_id' => Auth::id(),
            ];

            // handle thumbnail upload if present
            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $filename = time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
                $destination = public_path('uploads/home_videos');
                if (!File::exists($destination)) {
                    File::makeDirectory($destination, 0755, true);
                }
                $file->move($destination, $filename);
                $data['thumbnail'] = $filename;
            }

            if ($request->id) {
                $existing = HomeVideo::find($request->id);
                if ($existing && $request->hasFile('thumbnail') && $existing->thumbnail) {
                    $old = public_path('uploads/home_videos/') . $existing->thumbnail;
                    if (File::exists($old)) File::delete($old);
                }
            }

            if ($request->id) $data['updated_at'] = now();
            else $data['updated_at'] = null;

            HomeVideo::updateOrCreate(['id' => $request->id], $data);

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
        $data = HomeVideo::find($request->id);
        return response(['status' => true, 'data' => $data]);
    }

    public function delete(Request $request) {
        $find = HomeVideo::find($request->id);

        if (!$find)
            return response(['status' => false, 'message' => 'Record not found', 'icon' => 'error']);

        // delete thumbnail file if exists
        if ($find->thumbnail) {
            $path = public_path('uploads/home_videos/') . $find->thumbnail;
            if (file_exists($path)) @unlink($path);
        }

        $find->delete();
        return response(['status' => true, 'message' => 'Deleted Successfully', 'icon' => 'success']);
    }

    public function toggleStatus(Request $request) {
        $data = HomeVideo::find($request->id);

        if ($data) {
            $data->ishown = $request->status;
            $data->save();

            return response()->json(['success' => true, 'message' => 'Status Updated!', 'icon' => 'success']);
        }
        return response()->json(['success' => false, 'message' => 'Not Found!', 'icon' => 'error']);
    }

    public function checkHomeVideoUnique(Request $request) {
        $exists = HomeVideo::where('id', '!=', $request->id)
            ->where('title', $request->title)
            ->first();

        return $exists ? true : false;
    }

    public function getAdminPagesForSorting(Request $request)
    {
        $pages = HomeVideo::where('position', $request->parentid)->orderBy('position')->get();
        $response = [];
        foreach ($pages as $p) {
            $response[] = $p->id . '-' . $p->title;
        }
        return implode('^', $response);
    }

    public function saveAdminPagesPosition(Request $request)
    {
        foreach ($request->order as $order) {
            list($sortorder, $id) = explode('^', $order);
            HomeVideo::where('id', $id)->update(['position' => $sortorder]);
        }
        return response()->json(['success' => true]);
    }
}
