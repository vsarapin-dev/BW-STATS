<?php


namespace App\Services\GlobalFunctionality\Files;


use App\Models\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Storage;

class Service
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        return response()->json([
            'user_files' => File::where('user_id', $user->id)->get(),
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $files = $request->file('files');

        foreach ($files as $file) {
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . sha1_file($file) . '.' . $extension;
            $filePath = $file->storeAs("users/$user->id", $fileName, 'users');

            File::create([
                'name' => $file->getClientOriginalName(),
                'path' => $filePath,
                'user_id' => $user->id,
            ]);
        }
    }

    public function download(Request $request)
    {
        $file = File::findOrFail($request->fileId);
        $filePath = $file->path;

        return Storage::disk('users')->download($filePath);
    }

    public function delete(Request $request)
    {
        $user = $request->user();
        $filesToDelete = File::where('user_id', $user->id)->whereIn('id', $request->fileIds)->pluck('path')->toArray();
        Storage::disk('users')->delete($filesToDelete);
        File::where('user_id', $user->id)->whereIn('id', $request->fileIds)->delete();

        return response()->json([
            'message' => 'File deleted successfully',
        ]);
    }
}
