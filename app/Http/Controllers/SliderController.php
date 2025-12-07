<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slider;
use Illuminate\Support\Facades\Auth;

class SliderController extends Controller
{
    public function index() {
        $sliders = Slider::latest()->get();
        return view('pages.sliders', compact('sliders'));
    }

    public function store(Request $request) {
        try {

            $data = [
                'title' => $request->title,
                'sub_title' => $request->sub_title,
                'link' => $request->link,
                'admin_id' => Auth::id()
            ];

            if ($request->hasFile('image')) {
                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(public_path('uploads/sliders'), $imageName);
                $data['image'] = $imageName;
            }

            if ($request->id) {
                $data['updated_at'] = now();
                Slider::where('id', $request->id)->update($data);
            } else {
                $data['updated_at'] = null;
                Slider::create($data);
            }

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
        $data = Slider::find($request->id);
        if (!is_null($data)) {
                $imageUrl = '';
                    if (!empty($data->image) && file_exists(public_path('uploads/sliders/'.$data->image))) {
                        $imageUrl = asset('uploads/sliders/'.$data->image);
                    }
            return response(['status' => true, 'data' => $data]);
        }
    }

    public function delete(Request $request) {
        $data = Slider::find($request->id);

        if (!$data)
            return response(['status' => false, 'message' => 'Record not found', 'icon' => 'error']);

        if ($data->image && file_exists(public_path('uploads/sliders/' . $data->image))) {
            unlink(public_path('uploads/sliders/' . $data->image));
        }

        $data->delete();
        return response(['status' => true, 'message' => 'Deleted Successfully', 'icon' => 'success']);
    }

    public function toggleStatus(Request $request) {
        $data = Slider::find($request->id);

        if ($data) {
            $data->isshown = $request->status;
            $data->save();

            return response()->json(['success' => true, 'message' => 'Status Updated!', 'icon' => 'success']);
        }

        return response()->json(['success' => false, 'message' => 'Not Found!', 'icon' => 'error']);
    }

    public function checkSliderUnique(Request $request) {
        $exists = Slider::where('id', '!=', $request->id)
            ->where('title', $request->title)
            ->first();

        return $exists ? true : false;
    }
}
