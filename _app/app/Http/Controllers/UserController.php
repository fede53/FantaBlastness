<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use App\Services\ImageService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index', [
            'users' => User::get()->map->format(),
        ]);
    }

    public function create()
    {
        return view('users.form', [
            'roles' => Role::get()->map->format(),
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        $user = new User([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        if ($request->hasFile('image')) {
            $user->image = ImageService::upload($request->file('image'), 'users');
        }

        // Check if the user is successfully saved
        if ($user->save()) {
            return redirect()->route('users.index')->with('success', 'User created successfully.');
        } else {
            return redirect()->route('users.create')->with('error', 'Failed to create user. Please try again.');
        }
    }

    public function show(string $id)
    {
        try {
            $user = User::findOrFail($id)->format();

            return view('users.form', [
                'user' => $user,
                'roles' => Role::get()->map->format(),
            ]);
        } catch (ModelNotFoundException $e) {
            return redirect()->route('events.index')->with('error', 'User not found.');
        } catch (\Exception $e) {
            return redirect()->route('events.index')->with('error', $e->getMessage());
        }
    }

    public function edit(string $id)
    {
        try {
            $user = User::findOrFail($id)->format();

            return view('users.form', [
                'user' => $user,
                'roles' => Role::get()->map->format(),
            ]);
        } catch (ModelNotFoundException $e) {
            return redirect()->route('events.index')->with('error', 'User not found.');
        } catch (\Exception $e) {
            return redirect()->route('events.index')->with('error', $e->getMessage());
        }
    }

    public function update(UpdateUserRequest $request, string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->name = $request->name;
            $user->surname = $request->surname;
            $user->email = $request->email;
            $user->role_id = $request->role_id;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            if(request()->input('image_deleted') == "1"){
                ImageService::delete($user->image, 'users');
                $user->image = null;
            }

            if ($request->hasFile('image')) {
                $user->image = ImageService::upload($request->file('image'), 'users');
            }

            if ($user->save()) {
                return redirect()->route('users.index')->with('success', 'User updated successfully.');
            } else {
                return redirect()->route('users.edit', $id)->with('error', 'Failed to update user. Please try again.');
            }
        } catch (ModelNotFoundException $e) {
            return redirect()->route('events.index')->with('error', 'User not found.');
        } catch (\Exception $e) {
            return redirect()->route('events.index')->with('error', $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);

            ImageService::delete($user->image, 'users');

            if ($user->delete()) {
                return redirect()->route('users.index')->with('success', 'User deleted successfully.');
            } else {
                return redirect()->route('users.index')->with('error', 'Failed to delete user. Please try again.');
            }
        } catch (ModelNotFoundException $e) {
            return redirect()->route('events.index')->with('error', 'User not found.');
        } catch (\Exception $e) {
            return redirect()->route('events.index')->with('error', $e->getMessage());
        }
    }
}
