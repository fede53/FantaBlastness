<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $id = Auth::id();
        $user = User::findOrFail($id)->format();

        return view('profile.edit', [
            'user' => $user,
            'roles' => Role::get()->map->format(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {

        $id = Auth::id();
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->email = $request->email;

        if(request()->input('image_deleted') == "1"){
            ImageService::delete($user->image, 'users');
            $user->image = null;
        }

        if ($request->hasFile('image')) {
            $user->image = ImageService::upload($request->file('image'), 'users');
        }
        $user->save();
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }


    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
