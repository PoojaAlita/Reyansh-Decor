<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminIcon;


class AdminIconController extends Controller
{
    public function index()
    {
        // Fetch all admin icons, ordered by the most recent
        $icons = AdminIcon::orderBy('id', 'desc')->get();
        return view('pages.admin_icons', compact('icons'));
    }

    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'txtName' => 'required|string|max:255',
            'txtClass' => 'required|string|max:255',
        ]);

        // Save new or update existing record
        if ($request->hId == 0) {
            // For save new record
            AdminIcon::create([
                'title' => $request->txtName,
                'class' => $request->txtClass,
                'isshown' => 1,
            ]);
            return redirect()->route('admin.icons.index')->with('success', 'Icon record saved successfully!');
        } else {
            // For update record
            $icon = AdminIcon::findOrFail($request->hId);
            $icon->update([
                'title' => $request->txtName,
                'class' => $request->txtClass,
            ]);
            return redirect()->route('admin.icons.index')->with('success', 'Icon record updated successfully!');
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
