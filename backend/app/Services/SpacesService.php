<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class SpacesService
{
    // public function uploadFile(UploadedFile $file, string $directory = ''): string
    // {
    //     $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
    //     $path = trim($directory, '/') . '/' . $filename;

    //     Storage::disk('spaces')->put($path, file_get_contents($file), 'public');

    //     return '/' . $path;
    // }

    // public function deleteFile(string $url): bool
    // {
    //     $path = ltrim($url, '/');

    //     if (Storage::disk('spaces')->exists($path)) {
    //         return Storage::disk('spaces')->delete($path);
    //     }

    //     return false;
    // }

    // public function getUrl(string $path): string
    // {
    //     return config('filesystems.disks.spaces.url') . '/' . $path;
    // }

    public function getUrl(string $path): string
    {
        return config('filesystems.disks.s3_spaces.url') . '/' . $path;
    }

    public function deleteFile(string $path): bool
    {
        try {
            Storage::disk('s3_spaces')->delete($path);
        } catch (\Exception $e) {
            throw $e;
        }

        return false;
    }

    public function uploadFile(
        UploadedFile $file,
        Request $request,
        string $directory,
        string $disk = 's3_spaces',
        string $filename = null
    ): string {
        try {
            $extension  = request()->file('avatar')->getClientOriginalExtension(); //This is to get the extension of the image file just uploaded
            $filename = $filename ?? Str::uuid() . '_' . $request->user()->id . '.' . $extension;
            $path = $request->file('avatar')->storeAs(
                $directory,
                $filename,
                $disk
            );

            return $path;
        } catch (\Exception $e) {
            throw $e;
        }

        return '';
    }
}
