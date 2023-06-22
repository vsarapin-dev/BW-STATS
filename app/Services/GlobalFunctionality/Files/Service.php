<?php


namespace App\Services\GlobalFunctionality\Files;


use App\Http\Resources\User\UserResource;
use App\Models\File;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Service
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $userFiles = File::where('user_id', $user->id)->get();
        $sharedFiles = File::whereJsonContains('shared_with', $user->id)->get();

        return response()->json([
            'user_files' => $userFiles,
            'shared_files' => $sharedFiles,
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

    public function download(Request $request): StreamedResponse
    {
        $file = File::findOrFail($request->fileId);
        $filePath = $file->path;

        return Storage::disk('users')->download($filePath);
    }

    public function delete(Request $request): JsonResponse
    {
        $user = $request->user();
        $filesToDelete = File::where('user_id', $user->id)->whereIn('id', $request->fileIds)->pluck('path')->toArray();
        Storage::disk('users')->delete($filesToDelete);
        File::where('user_id', $user->id)->whereIn('id', $request->fileIds)->delete();

        $message = 'Files deleted successfully';
        if (count($filesToDelete) !== count($request->fileIds)) {
            $message = 'Some files do not belong to you.';
        }

        return response()->json([
            'message' => $message,
        ]);
    }

    public function share(Request $request): JsonResponse
    {
        $user = $request->user();
        $filesToShare = File::where('user_id', $user->id)->whereIn('id', $request->fileIds)->get();
        $filesToShare->each(function ($file) use ($request) {
            $file->update([
                'shared_with' => $request->userIds,
            ]);
        });

        $message = 'File shared successfully';
        if (count($filesToShare) !== count($request->fileIds)) {
            $message = 'Not all shared, some files do not belong to you.';
        }

        return response()->json([
            'message' => $message,
        ]);
    }

    public function getUsers(Request $request): JsonResponse
    {
        $user = $request->user();
        $users = User::where('id', '!=', $user->id)->get();

        return response()->json(UserResource::collection($users));
    }
}
