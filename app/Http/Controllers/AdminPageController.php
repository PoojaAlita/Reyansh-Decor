<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{AdminPage, AdminIcon};

class AdminPageController extends Controller
{
    public function index()
    {

        $pages = AdminPage::orderByDesc('id')->get();
        $icons = AdminIcon::where('isshown', 1)->orderBy('title')->get();
        return view('pages.admin_page', compact('pages', 'icons'));
    }


    public function store(Request $request)
    {
        try {
            $id = $request->hId;
            $parentId = $request->ddlMenu;

            // Get max sort order under the parent
            $maxSort = AdminPage::where('parent_id', $parentId)->max('sortorder') ?? 0;

            // Prepare data
            $data = [
                'title'      => $request->txtName,
                'url'        => $request->txtUrl,
                'icon'       => $request->ddlIcon,
                'parent_id'  => $parentId,
                'sortorder'  => $maxSort + 1,
                'isshown'    => 1,
            ];

            // Add timestamp depending on create/update
            $data['updated_at'] = $id ? now() : null;

            // Save or Update
            AdminPage::updateOrCreate(
                ['id' => $id],
                $data
            );

            return response([
                'status'  => true,
                'message' => $id ? 'Record Updated Successfully!' : 'Record Saved Successfully!',
                'icon'    => 'success'
            ]);

        } catch (\Throwable $e) {

            return response([
                'status'  => false,
                'message' => 'Something went wrong!',
                'icon'    => 'error'
            ]);
        }
    }


    public function edit(Request $request)
    {
        $page = AdminPage::findOrFail($request->id);
        return response()->json($page);
    }

    public function destroy(Request $request)
    {
        try {
            $page = AdminPage::find($request->id);

            if ($page) {

                $page->delete();

                $response = [
                    'status'  => true,
                    'message' => 'Admin Page deleted successfully',
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

    public function toggleStatus(Request $request)
    {
        $adminPage = AdminPage::find($request->id);

        if ($adminPage) {
            $adminPage->isshown = $request->status;
            $adminPage->save();

            return response()->json(['success' => true, 'message' => 'Status updated successfully!','icon' => 'success']);
        }

        return response()->json(['success' => false, 'message' => 'Admin Page not found!']);
    }

    public function getAdminPagesForSorting(Request $request)
    {
        $pages = AdminPage::where('parent_id', $request->parentid)->orderBy('sortorder')->get();
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
            AdminPage::where('id', $id)->update(['sortorder' => $sortorder]);
        }
        return response()->json(['success' => true]);
    }

    public function menuHtml()
    {

         $pages = AdminPage::where('isshown',1)->orderBy('sortorder')->get();
    return view('layouts.sidebar', compact('pages'));
    }

    public function delete(Request $request)
    {
        $page = AdminPage::find($request->id);
        if($page) {
            $page->delete();
            return response()->json(['status' => true, 'message' => 'Deleted successfully', 'icon' => 'success']);
        }
        return response()->json(['status' => false, 'message' => 'Not found', 'icon' => 'error']);
    }

}
