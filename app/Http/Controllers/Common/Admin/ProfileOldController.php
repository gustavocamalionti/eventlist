<?php

namespace App\Http\Controllers\Common\Admin;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Common\Controller;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Http\Requests\Systems\Master\Modules\Admin\ProfileUpdateRequest;

class ProfileOldController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        $system = $this->isTenant() ? "tenant" : "master";
        return Inertia::render("systems/{$system}/modules/admin/pages/profile/Edit", [
            "mustVerifyEmail" => $request->user() instanceof MustVerifyEmail,
            "status" => session("status"),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty("email")) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        $path = $this->isTenant() ? "tenant.auth." : "master.auth";
        return Redirect::route($path . "." . "profile.edit");
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            "password" => ["required", "current_password"],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to("/");
    }
}
