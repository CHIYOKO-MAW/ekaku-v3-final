<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\View\View as ViewView;
use Spatie\ResponseCache\Facades\ResponseCache;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): ViewView
    {
        $user = $request->user();

        if (in_array($user->role, ['admin', 'atasan'], true)) {
            return view('profile.simple', [
                'user' => $user,
            ]);
        }

        $userId = $user->id;

        $biodata = Cache::remember("profile.{$userId}.biodata", 60, fn () => $user->biodata);
        $status = Cache::remember("profile.{$userId}.status", 60, fn () => $user->statusPekerjaan);
        $kartuKuning = Cache::remember("profile.{$userId}.kartu", 60, fn () => $user->kartuKuning);
        $uploads = Cache::remember("profile.{$userId}.uploads", 60, fn () => $user->uploads);

        $notifications = Cache::remember("profile.{$userId}.notifications", 60, function () use ($user) {
            return $user->notificationsE()->latest()->take(5)->get();
        });
        $unreadCount = Cache::remember("profile.{$userId}.unread", 60, function () use ($user) {
            return $user->notificationsE()->where('status_baca', false)->count();
        });

        View::share('unreadNotes', $unreadCount);
        View::share('latestNotes', $notifications);

        $user->load(['kartuKuning', 'uploads']);

        return view('user.profile', [
            'user' => $user,
            'biodata' => $biodata,
            'status' => $status,
            'uploads' => $uploads,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();
        unset($validated['profile_photo']);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $path = $request->file('profile_photo')->store("profile-photos/{$user->id}", 'public');
            $user->profile_photo_path = $path;
        }

        $user->save();

        $userId = $user->id;
        Cache::forget("profile.{$userId}.biodata");
        Cache::forget("profile.{$userId}.status");
        Cache::forget("profile.{$userId}.kartu");
        Cache::forget("profile.{$userId}.uploads");
        Cache::forget("profile.{$userId}.notifications");
        Cache::forget("profile.{$userId}.unread");
        ResponseCache::forget(route('profile.edit'));

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function photo(User $user)
    {
        $currentUser = Auth::user();
        if (! $currentUser) {
            abort(403);
        }

        if ((int) $currentUser->id !== (int) $user->id && ! in_array($currentUser->role, ['admin', 'atasan'], true)) {
            abort(403, 'This action is unauthorized.');
        }

        if (! $user->profile_photo_path) {
            abort(404, 'Foto profil tidak tersedia.');
        }

        if (! Storage::disk('public')->exists($user->profile_photo_path)) {
            abort(404, 'Foto profil tidak ditemukan.');
        }

        return response()->file(Storage::disk('public')->path($user->profile_photo_path), [
            'Cache-Control' => 'private, max-age=86400',
        ]);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        // Use force delete so Breeze default tests and account removal semantics match.
        $user->forceDelete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
