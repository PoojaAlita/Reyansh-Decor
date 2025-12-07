<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminIcon;


class AdminIconController extends Controller
{
    public function index()
    {
        return view('pages.admin_icons');
    }

    public function store(Request $request)
    {
        try {
            // Validation
            $request->validate([
                'txtName' => 'required|string',
                'txtClass' => 'required|string',
            ]);

            // Prepare data
            $data = [
                'title' => $request->txtName,
                'class' => $request->txtClass,
                'isshown' => 1,
            ];

            // If updating, no need to overwrite isshown
            if ($request->hId) {
                $data['updated_at'] = now();
            } else {
                $data['updated_at'] = null;
            }

            // Save or Update
            AdminIcon::updateOrCreate(['id' => $request->hId], $data);

            return response([
                'status'  => true,
                'message' => $request->hId ? 'Icon updated successfully!' : 'Icon added successfully!',
                'icon'    => 'success'
            ]);

        } catch (\Throwable $e) {
            dd($e);
            return response([
                'status'  => false,
                'message' => 'Something went wrong!',
                'icon'    => 'error'
            ]);
        }
    }


    public function toggleStatus(Request $request)
    {
        $adminIcon = AdminIcon::find($request->id);

        if ($adminIcon) {
            $adminIcon->isshown = $request->status;
            $adminIcon->save();

            return response()->json(['success' => true, 'message' => 'Status updated successfully!', 'icon' => 'success']);
        }

        return response()->json(['success' => false, 'message' => 'Admin Icon not found!']);
    }
   

    public function destroy(Request $request)
    {
        try {
            $icon = AdminIcon::find($request->id);

            if ($icon) {

                $icon->delete();

                $response = [
                    'status'  => true,
                    'message' => 'Icon deleted successfully',
                    'icon'    => 'success',
                ];
            } else {
                $response = [
                    'status'  => false,
                    'message' => 'Record not found',
                    'icon'    => 'error',
                ];
            }
        } catch (\Throwable $e) {
            $response = [
                'status'  => false,
                'message' => 'Something Went Wrong! Please Try Again.',
                'icon'    => 'error',
                'error'   => $e->getMessage(),
            ];
        }

        return response()->json($response);
    }

  
    public function edit(Request $request)
    {
        $icon = AdminIcon::findOrFail($request->id);  
        return response()->json($icon); 
    }
}
