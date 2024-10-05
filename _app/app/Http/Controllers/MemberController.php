<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Models\Member;
use App\Services\ImageService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MemberController extends Controller
{
    public function index()
    {
        return view('members.index', [
            'members' => Member::get()->map->format(),
        ]);
    }

    public function create()
    {
        return view('members.form');
    }

    public function store(StoreMemberRequest $request)
    {
        $member = new Member([
            'name' => $request->name,
            'fantaname' => $request->fantaname,
            'characteristics' => $request->characteristics
        ]);

        if ($request->hasFile('image')) {
            $member->image = ImageService::upload($request->file('image'), 'members');
        }

        if ($member->save()) {
            return redirect()->route('members.index')->with('success', 'Member created successfully.');
        } else {
            return redirect()->route('members.create')->with('error', 'Failed to create member. Please try again.');
        }
    }

    public function show(string $id)
    {
        try {
            $member = Member::findOrFail($id)->format();

            return view('members.form', [
                'member' => $member
            ]);

        } catch (ModelNotFoundException $e) {
            return redirect()->route('members.index')->with('error', 'Member not found.');
        } catch (\Exception $e) {
            return redirect()->route('members.index')->with('error', $e->getMessage());
        }
    }

    public function edit(string $id)
    {
        try {
            $member = Member::findOrFail($id)->format();

            return view('members.form', [
                'member' => $member
            ]);
        } catch (ModelNotFoundException $e) {
            return redirect()->route('members.index')->with('error', 'Member not found.');
        } catch (\Exception $e) {
            return redirect()->route('members.index')->with('error', $e->getMessage());
        }
    }

    public function update(UpdateMemberRequest $request, string $id)
    {
        try {
            $member = Member::findOrFail($id);
            $member->name = $request->name;
            $member->fantaname = $request->fantaname;
            $member->characteristics = $request->characteristics;

            if(request()->input('image_deleted') == "1"){
                ImageService::delete($member->image, 'members');
                $member->image = null;
            }
            if ($request->hasFile('image')) {
                $member->image = ImageService::upload($request->file('image'), 'members');
            }

            if ($member->save()) {
                return redirect()->route('members.index')->with('success', 'Member updated successfully.');
            } else {
                return redirect()->route('members.edit', $id)->with('error', 'Failed to update member. Please try again.');
            }
        } catch (ModelNotFoundException $e) {
            return redirect()->route('members.index')->with('error', 'Member not found.');
        } catch (\Exception $e) {
            return redirect()->route('members.index')->with('error', $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            $member = Member::findOrFail($id);
            ImageService::delete($member->image, 'members');

            if ($member->delete()) {
                return redirect()->route('members.index')->with('success', 'Member deleted successfully.');
            } else {
                return redirect()->route('members.index')->with('error', 'Failed to delete member. Please try again.');
            }
        } catch (ModelNotFoundException $e) {
            return redirect()->route('members.index')->with('error', 'Member not found.');
        } catch (\Exception $e) {
            return redirect()->route('members.index')->with('error', $e->getMessage());
        }
    }
}
