<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{AdminRight, User, AdminPage};


class AdminRightController extends Controller
{
    public function index($assign_id)
    {
        $admins = User::where('id', $assign_id)->first();
        $rights = AdminRight::where('admin_id',$admins->id)->with(['admin', 'page'])->get();

        if ($rights->isNotEmpty()) {
            $rights = $rights->filter(function ($right) use ($rights) {
                if (!$right->page) return false;

                if ($right->page->parent_id != 0) {
                    return true;
                }

                $hasChild = $rights->contains(function ($r) use ($right) {
                    return $r->admin_id == $right->admin_id &&
                        $r->page &&
                        $r->page->parent_id == $right->page->id;
                });

                return !$hasChild;
            });
        }

       
        $pages = AdminPage::all();

        return view('pages.admin_right', compact('rights', 'admins', 'pages'));
    }


    public function store(Request $request)
    {
        
        try { 
            $request->validate([
                'page_id'  => 'required|string',
            ]);

            $adminId = $request->admin_id;
            $pageIds = explode(',', $request->page_id);

            foreach ($pageIds as $pageId) {
                AdminRight::updateOrCreate(
                    ['admin_id' => $adminId, 'page_id' => $pageId],
                    ['updated_at' => now()]
                );
            }

            return response()->json(['success' => true, 'message' => 'Admin Rights ' . ($request->id == 0 ? 'Added' : 'Updated') . ' Successfully', 'icon' => 'success']);
         } catch (\Throwable $e) {
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
            ];
        }

        return response($response);
    }



    public function edit($id)
    {
        $right = AdminRight::with(['admin', 'page'])->findOrFail($id);

        return response()->json([
            'id' => $right->id,
            'admin_id' => $right->admin_id,
            'page_ids' => [$right->page_id] // ek hi page ke liye array
        ]);
    }


    public function update(Request $request, $id)
    {
        $right = AdminRight::findOrFail($id);
        $right->update($request->only('admin_id', 'page_id'));

        return response()->json(['success' => true, 'message' => 'Right updated successfully']);
    }


    public function destroy(Request $request)
    {
        try {
            $rights = AdminRight::find($request->id);

            if ($rights) {

                $rights->delete();

                $response = [
                    'status'  => true,
                    'message' => 'Admin rights deleted successfully',
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
}
