<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Models\EventMember;
use App\Models\EventRule;
use App\Models\EventScore;
use App\Models\Member;
use App\Models\Team;
use App\Services\ImageService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        return view('events.index', [
            'events' => Event::get()->map->format(),
        ]);
    }

    public function create()
    {
        return view('events.form', [
            'members' => Member::get()->map->format()
            ]);
    }

    public function store(StoreEventRequest $request)
    {
        $event = new Event([
            'name' => $request->name,
            'description' => $request->description,
            'regulation' => $request->regulation,
            'instructions' => $request->instructions,
            'dolphins' => $request->dolphins,
            'date_for_partecipate' => $request->date_for_partecipate,
            'date_phase_1' => $request->date_phase_1,
            'date_phase_2' => $request->date_phase_2
        ]);
        if ($request->hasFile('image')) {
            $event->image = ImageService::upload($request->file('image'), 'events');
        }
        if ($event->save()) {
            $this->syncMembers($event, $request->members);
            $this->handleRules($request->input('bonus', []), $event);
            $this->handleRules($request->input('malus', []), $event);
            return redirect()->route('events.index')->with('success', 'Event created successfully.');
        } else {
            return redirect()->route('events.create')->with('error', 'Failed to create event. Please try again.');
        }
    }

    public function show(string $id)
    {
        try {
            $event = Event::findOrFail($id)->formatDashboard();
            $teams = Team::where('event_id', $id)->get()->map->format();
            $members = EventMember::where('event_id', $id)->where('active', 1)->get()->map->formatWithScore($id);

            return view('events.show', [
                'event' => $event,
                'teams' => $teams,
                'members' => $members
            ]);
        } catch (ModelNotFoundException $e) {
            return redirect()->route('events.index')->with('error', 'Event not found.');
        } catch (\Exception $e) {
            return redirect()->route('events.index')->with('error', $e->getMessage());
        }
    }

    public function edit(string $id)
    {
        try {
            $event = Event::findOrFail($id)->format();
            return view('events.form', [
                'event' => $event,
                'members' => Member::orderBy("name")->get()->map->format()
            ]);
        } catch (ModelNotFoundException $e) {
            return redirect()->route('events.index')->with('error', 'Event not found.');
        } catch (\Exception $e) {
            return redirect()->route('events.index')->with('error', $e->getMessage());
        }
    }

    public function update(UpdateEventRequest $request, string $id)
    {
        try {
            $event = Event::findOrFail($id);
            $event->name = $request->name;
            $event->description = $request->description;
            $event->regulation = $request->regulation;
            $event->instructions = $request->instructions;
            $event->dolphins = $request->dolphins;
            $event->date_for_partecipate = $request->date_for_partecipate;
            $event->date_phase_1 = $request->date_phase_1;
            $event->date_phase_2 = $request->date_phase_2;

            if(request()->input('image_deleted') == "1"){
                ImageService::delete($event->image, 'events');
                $event->image = null;
            }
            if ($request->hasFile('image')) {
                $event->image = ImageService::upload($request->file('image'), 'events');
            }

            if ($event->save()) {
                $this->syncMembers($event, $request->members);
                $this->handleRules($request->input('bonus', []), $event);
                $this->handleRules($request->input('malus', []), $event);
                return redirect()->route('events.index')->with('success', 'Event updated successfully.');
            } else {
                return redirect()->route('events.edit', $id)->with('error', 'Failed to update event. Please try again.');
            }
        } catch (ModelNotFoundException $e) {
            return redirect()->route('events.index')->with('error', 'Event not found.');
        } catch (\Exception $e) {
            return redirect()->route('events.index')->with('error', $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            $event = Event::findOrFail($id);
            ImageService::delete($event->image, 'events');

            if ($event->delete()) {
                return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
            } else {
                return redirect()->route('events.index')->with('error', 'Failed to delete event. Please try again.');
            }
        } catch (ModelNotFoundException $e) {
            return redirect()->route('events.index')->with('error', 'Event not found.');
        } catch (\Exception $e) {
            return redirect()->route('events.index')->with('error', $e->getMessage());
        }
    }

    public function createTeam(string $id)
    {
        try {
            $event = Event::findOrFail($id)->format();
            $maxCost = $event['members']->max('cost');

            return view('events.team', [
                'event' => $event,
                'members' => $event['members'],
                'maxCost' => $maxCost
            ]);

        } catch (ModelNotFoundException $e) {
            return redirect()->route('events.index')->with('error', 'Event not found.');
        } catch (\Exception $e) {
            return redirect()->route('events.index')->with('error', $e->getMessage());
        }
    }

    function handleRules($rules, $event)
    {
        foreach ($rules as $ruleData) {
            if (isset($ruleData['id'])) {
                if (isset($ruleData['_delete']) && $ruleData['_delete'] == 1) {
                    EventRule::where('id', $ruleData['id'])->delete();
                } else {
                    unset($ruleData['_delete']);
                    EventRule::where('id', $ruleData['id'])->update($ruleData);
                }
            } else {
                if (!isset($ruleData['_delete']) || $ruleData['_delete'] != 1) {
                    unset($ruleData['_delete']);
                    $event->rules()->create($ruleData);
                }
            }
        }
    }

    protected function syncMembers(Event $event, $members)
    {
        $syncData = [];
        foreach ($members as $memberId => $memberData) {
            $syncData[$memberId] = [
                'active' => isset($memberData['active']) ? 1 : 0,
                'cost' => $memberData['cost'],
                'extra' => $memberData['extra'],
                'extra_message' => $memberData['extra_message']
            ];
        }
        $event->members()->sync($syncData);
    }

}
