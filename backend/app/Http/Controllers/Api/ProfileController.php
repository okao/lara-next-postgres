<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Services\SpacesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function __construct(private SpacesService $spacesService) {}

    public function show(Request $request)
    {
        try {
            $profile = $request->user()->profile;

            return response()->json([
                'status' => true,
                'data' => [
                    'profile' => $profile
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'bio' => 'nullable|string|max:255',
                'location' => 'nullable|string|max:255',
                'website' => 'nullable|url|max:255',
                'social_links' => 'nullable|array',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240' // 10MB max
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation Error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $profile = $request->user()->profile;
            $data = $request->only(['bio', 'location', 'website', 'social_links']);

            if ($request->hasFile('avatar')) {
                // // Delete old avatar if exists
                // if ($profile->avatar_url) {
                //     $this->spacesService->deleteFile($profile->avatar_url);
                // }

                // // Upload new avatar
                // $data['avatar_url'] = $this->spacesService->uploadFile(
                //     $request->file('avatar'),
                //     'avatars'
                // );

                // $extension  = request()->file('avatar')->getClientOriginalExtension(); //This is to get the extension of the image file just uploaded
                // $image_name = time() . '_' . $request->user()->id . '.' . $extension;
                // $path = $request->file('avatar')->storeAs(
                //     'avatars',
                //     $image_name,
                //     's3_spaces'
                // );

                // Delete old avatar if exists
                if ($profile->avatar_url) {
                    $this->spacesService->deleteFile($profile->avatar_url);
                }

                // Upload new avatar
                $path = $this->spacesService->uploadFile(
                    $request->file('avatar'),
                    $request,
                    'avatars',
                    's3_spaces'
                );

                $profile->avatar_url = $path;
            }

            $profile->update($data);

            return response()->json([
                'status' => true,
                'message' => 'Profile updated successfully',
                'data' => [
                    'profile' => $profile->fresh()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
