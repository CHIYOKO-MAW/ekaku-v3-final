<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Intervention\Image\Laravel\Facades\Image;

class UploadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $uploads = Auth::user()->uploads()->latest()->get();

        return view('upload.index', compact('uploads'));
    }

    public function create()
    {
        return redirect()->route('upload.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jenis_dokumen' => ['required', Rule::in(['ktp', 'ijazah', 'foto', 'kk', 'sertifikat'])],
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        if ($validator->fails()) {
            if ($request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json(['message' => 'Validation error', 'errors' => $validator->errors()], 422);
            }

            return redirect()
                ->to(route('profile.edit') . '#berkas')
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();

        if (! $user) {
            abort(403, 'Unauthorized');
        }

        $file = $request->file('file');

        if (! $file) {
            return redirect()
                ->to(route('profile.edit') . '#berkas')
                ->with('error', 'File tidak terbaca. Ukuran file mungkin melebihi batas server.');
        }

        $originalName = pathinfo(
            $file->getClientOriginalName(),
            PATHINFO_FILENAME
        );

        $extension = $file->getClientOriginalExtension();

        $filename = Str::slug($originalName)
            .'-'.time()
            .'.'.$extension;

        $path = $file->storeAs(
            "uploads/{$user->id}",
            $filename,
            'public'
        );

        Upload::create([
            'user_id' => $user->id,
            'jenis_dokumen' => $request->jenis_dokumen,
            'file_path' => $path,
        ]);

        $mime = $file->getMimeType();
        if (str_starts_with($mime, 'image/') && $extension !== 'pdf') {
            try {
                $thumbDir = "uploads/{$user->id}/thumb";
                if (! Storage::disk('public')->exists($thumbDir)) {
                    Storage::disk('public')->makeDirectory($thumbDir);
                }
                $thumbFilename = 'thumb-' . pathinfo($filename, PATHINFO_FILENAME) . '.webp';
                $img = Image::read(Storage::disk('public')->path("uploads/{$user->id}/{$filename}"));
                $img->coverDown(100, 100);
                $img->encodeByMediaType('image/webp', quality: 80);
                $img->save(Storage::disk('public')->path("{$thumbDir}/{$thumbFilename}"));
            } catch (\Throwable $e) {
                Log::warning('Thumbnail generation failed', ['error' => $e->getMessage()]);
            }
        }

        if ($request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['message' => 'File berhasil diupload.', 'redirect' => route('profile.edit') . '#berkas']);
        }

        session()->flash('success', 'File berhasil diupload.');
        session()->flash('active_tab', 'berkas');

        return redirect()->to(route('profile.edit') . '#berkas');
    }

    public function preview(Upload $upload)
    {
        $user = Auth::user();
        if (! $user) {
            abort(403, 'Unauthorized');
        }
        if (! in_array($user->role, ['admin', 'atasan'], true) && (int) $upload->user_id !== (int) $user->id) {
            abort(403, 'This action is unauthorized.');
        }

        $relativePath = $upload->file_path;
        $cleanPath = str_replace('public/', '', $relativePath);

        if (Storage::disk('public')->exists($cleanPath)) {
            return response()->file(Storage::disk('public')->path($cleanPath), [
                'Cache-Control' => 'private, max-age=86400',
            ]);
        }

        if (Storage::exists($relativePath)) {
            return Storage::response($relativePath, null, [
                'Cache-Control' => 'private, max-age=86400',
            ]);
        }

        return redirect()->to(route('profile.edit') . '#berkas')->with('error', 'File tidak ditemukan di server. Upload ulang dokumen Anda.');
    }

    public function destroy(Upload $upload)
    {
        $user = Auth::user();
        if (! $user) {
            abort(403, 'Unauthorized');
        }
        if (! in_array($user->role, ['admin', 'atasan'], true) && (int) $upload->user_id !== (int) $user->id) {
            abort(403, 'This action is unauthorized.');
        }

        if (Storage::disk('public')->exists($upload->file_path)) {
            Storage::disk('public')->delete($upload->file_path);
        } elseif (Storage::exists($upload->file_path)) {
            Storage::delete($upload->file_path);
        } elseif (Storage::disk('public')->exists(str_replace('public/', '', $upload->file_path))) {
            Storage::disk('public')->delete(str_replace('public/', '', $upload->file_path));
        }

        $upload->delete();

        session()->flash('success', 'File berhasil dihapus.');
        session()->flash('active_tab', 'berkas');

        return redirect()->to(route('profile.edit') . '#berkas');
    }

    public function thumbnail(Upload $upload)
    {
        $user = Auth::user();
        if (! $user) {
            abort(403, 'Unauthorized');
        }
        if (! in_array($user->role, ['admin', 'atasan'], true) && (int) $upload->user_id !== (int) $user->id) {
            abort(403, 'This action is unauthorized.');
        }

        $originalPath = str_replace('public/', '', $upload->file_path);
        $filename = basename($originalPath);
        $dir = dirname($originalPath);
        $thumbPath = "{$dir}/thumb/thumb-{$filename}";

        $thumbPathWebp = preg_replace('/\.\w+$/', '.webp', $thumbPath);
        if (Storage::disk('public')->exists($thumbPathWebp)) {
            return response()->file(Storage::disk('public')->path($thumbPathWebp), [
                'Cache-Control' => 'private, max-age=86400',
            ]);
        }

        return $this->preview($upload);
    }
}
