<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Enquiry;
use App\Models\AdminPage;

class EnquiryController extends Controller
{
    public function index()
    {
        $enquiries = Enquiry::with('product')->orderBy('created_at', 'desc')->get();
        return view('pages.enquiry', compact('enquiries'));
    }

    public function delete(Request $request)
    {
        $enquiry = Enquiry::find($request->id);
        if ($enquiry) {
            $enquiry->delete();
            return response()->json(['status' => 'success', 'message' => 'Enquiry deleted successfully']);
        }
        return response()->json(['status' => 'error', 'message' => 'Enquiry not found']);
    }

    public function toggleStatus(Request $request)
    {
        $enquiry = Enquiry::find($request->id);
        if ($enquiry) {
            $enquiry->status = $request->status;
            $enquiry->save();
            return response()->json(['status' => 'success', 'message' => 'Status updated successfully']);
        }
        return response()->json(['status' => 'error', 'message' => 'Enquiry not found']);
    }
}
