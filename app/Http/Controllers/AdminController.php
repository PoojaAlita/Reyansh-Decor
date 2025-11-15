<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{User,AdminRight, AdminPage};


class AdminController extends Controller
{
    public function index()
    {
        $admins = User::latest()->get();
        $pages = AdminPage::all();

        return view('pages.admin', compact('admins', 'pages'));
    }

    public function store(Request $request)
    {
       
        try {

            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:admin,email,' . $request->id,
                'password' => $request->id ? 'nullable|min:6' : 'required|min:6',
                'mobile' => 'nullable|max:11',
                'image' => 'nullable|image|max:2048',
            ]);

            $existinguser = $request->id ? User::find($request->id) : null;


            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password ? bcrypt($request->password) : ($existinguser->password ?? null),
                'mobile' => $request->mobile,
                'type'  => $request->type,
            ];

            // Fetch existing record if updating
            $existinguser = null;
            if (!empty($request->id)) {
                $existinguser = User::find($request->id);
            }

            if ($request->hasFile('imageUpload')) {
                $image = $request->file('imageUpload');
                $imageName = time() . '.' . strtolower($image->getClientOriginalExtension());
                $destinationPath = public_path('uploads/users');

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $imagePath = $destinationPath . '/' . $imageName;
                $ext = $imageName ? pathinfo($imageName, PATHINFO_EXTENSION) : '';

                // Skip resizing for unsupported types
                $supported = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
                if (!in_array($ext, $supported)) {
                    $image->move($destinationPath, $imageName);
                    $data['image'] = 'uploads/users/' . $imageName;
                } else {
                    // Supuser libpng warning
                    ob_start();
                    switch ($ext) {
                        case 'jpg':
                        case 'jpeg':
                            $source = @imagecreatefromjpeg($image->getPathname());
                            break;
                        case 'png':
                            $source = @imagecreatefrompng($image->getPathname());
                            break;
                        case 'gif':
                            $source = @imagecreatefromgif($image->getPathname());
                            break;
                        case 'webp':
                            $source = @imagecreatefromwebp($image->getPathname());
                            break;
                        case 'bmp':
                            $source = @imagecreatefrombmp($image->getPathname());
                            break;
                    }
                    ob_end_clean();

                    if ($source) {
                        $width = 250;
                        $height = 250;

                        $origWidth = imagesx($source);
                        $origHeight = imagesy($source);

                        $resized = imagecreatetruecolor($width, $height);

                        // Preserve transparency for PNG, GIF, WEBP
                        if (in_array($ext, ['png', 'gif', 'webp'])) {
                            imagealphablending($resized, false);
                            imagesavealpha($resized, true);
                            $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
                            imagefilledrectangle($resized, 0, 0, $width, $height, $transparent);
                        }

                        // Resize
                        imagecopyresampled(
                            $resized,
                            $source,
                            0,
                            0,
                            0,
                            0,
                            $width,
                            $height,
                            $origWidth,
                            $origHeight
                        );

                        // Save
                        switch ($ext) {
                            case 'jpg':
                            case 'jpeg':
                                imagejpeg($resized, $imagePath, 80);
                                break;
                            case 'png':
                                imagepng($resized, $imagePath, 6);
                                break;
                            case 'gif':
                                imagegif($resized, $imagePath);
                                break;
                            case 'webp':
                                imagewebp($resized, $imagePath, 80);
                                break;
                            case 'bmp':
                                imagebmp($resized, $imagePath);
                                break;
                        }

                        imagedestroy($source);
                        imagedestroy($resized);
                        $data['image'] = 'uploads/users/' . $imageName;
                    }
                }

                // Delete old image
                if ($existinguser && !empty($existinguser->image) && file_exists(public_path($existinguser->image))) {
                    @unlink(public_path($existinguser->image));
                }
            }


            // Create or update User record
            $UserData = User::updateOrCreate(
                ['id' => $request->id ?? 0], // if no ID, it will create
                $data
            );

            $response = [
                'status' => true,
                'message' => 'User Data ' . ($request->id == 0 ? 'Added' : 'Updated') . ' Successfully',
                'icon' => 'success',
            ];
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
        $admin = User::where('id', $id)->first();
        if (!is_null($admin)) {

            $imageUrl = '';
            if (!empty($admin->image) && file_exists(public_path($admin->image))) {
                $imageUrl = asset($admin->image);
            }
            return response()->json($admin);
        }
    }



    public function destroy(Request $request)
    {
        try {
            $user = User::find($request->id);

            if ($user) {
                // Delete image file if exists
                if (!empty($user->image) && file_exists(public_path($user->image))) {
                    unlink(public_path($user->image));
                }

                $user->delete();

                $response = [
                    'status'  => true,
                    'message' => 'User Deleted successfully',
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
        $user = User::find($request->id);

        if ($user) {
            $user->isblock = $request->status;
            $user->save();

            return response()->json(['success' => true, 'message' => 'Status updated successfully!']);
        }

        return response()->json(['success' => false, 'message' => 'User not found!']);
    }

    public function rightsStore(Request $request)
    {
        try {
         
            $adminId = $request->rightAdminId;
            $pageIds = explode(',', $request->page_id);

            AdminRight::where('admin_id', $adminId)->delete();

            if($pageIds){
                foreach ($pageIds as $pageId) {
                    AdminRight::create([
                        'admin_id' => $adminId,
                        'page_id' => $pageId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }    

            return response()->json([
                'success' => true,
                'message' => 'Page Rights Saved Successfully',
                'icon' => 'success'
            ]);
        } catch (\Throwable $e) {
            dd($e);
            return response()->json([
                'status' => false,
                'message' => 'Something Went Wrong! Please Try Again.',
                'icon' => 'error',
            ]);
        }
    }


    public function getRights($id)
    {
        $pageIds = AdminRight::where('admin_id', $id)
            ->pluck('page_id')
            ->toArray();

        return response()->json([
            'page_ids' => $pageIds
        ]);
    }
}
