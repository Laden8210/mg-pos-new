<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File; // Import the File facade
use App\Models\Employee; // or Employee, depending on your model

class AvatarController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|max:10240',
        ]);

        $user = Auth::user();

        // Store the file
        if ($request->hasFile('avatar')) {
            // Get the original filename
            $filename = uniqid() . '.' . $request->file('avatar')->getClientOriginalExtension(); // Use a unique filename
            $path = public_path('assets/img/avatars'); // Define the path directly to public directory

            // Delete the existing avatar if it exists
            if ($user->avatar) {
                // Delete the old file from public directory
                $oldAvatarPath = $path . '/' . $user->avatar;
                if (File::exists($oldAvatarPath)) {
                    File::delete($oldAvatarPath);
                }
            }

            // Store the file with the new unique name
            $request->file('avatar')->move($path, $filename); // Move the uploaded file
            // Update the user's avatar filename
            $user->avatar = $filename; // Store only the new filename
            $user->save();
        }

        session()->flash('success', 'Image uploaded successfully!');
        return redirect()->route('user_account');
    }
}
