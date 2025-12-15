<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\EventRepositoryInterface;

class EventController extends Controller
{

    protected $event;

    public function __construct(EventRepositoryInterface $event)
    {
        $this->event = $event;
    }

    public function index(){
       return response()->json( $this->event->getAll());
    }

    public function show($id){
        $event = $this->event->getById((int) $id);

        if (! $event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        return response()->json($event);
    }

    public function store(Request $request){
        $fields = $request->validate([
            'category_id' => 'nullable|integer|exists:event_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'location' => 'required|string|max:255',
            'event_time' => 'nullable|string|max:255',
            'event_date' => 'required|date',
            'image' => 'nullable|image|max:2048',
        ]);

        $imageUrl = null;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $imageUrl = "/storage/$path";
        }

        $payload = [
            'category_id' => $fields['category_id'] ?? null,
            'title' => $fields['title'],
            'description' => $fields['description'] ?? null,
            'content' => $fields['content'] ?? null,
            'location' => $fields['location'],
            'event_time' => $fields['event_time'] ?? null,
            'event_date' => $fields['event_date'],
            'image' => $imageUrl,
        ];

        $id = $this->event->store($payload);

        return response()->json([
            'message' => 'Event created successfully',
            'id' => $id,
        ], 201);
    }

    public function update(Request $request, $id){
        $fields = $request->validate([
            'category_id' => 'nullable|integer|exists:event_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'location' => 'required|string|max:255',
            'event_time' => 'nullable|string|max:255',
            'event_date' => 'required|date',
            'image' => 'nullable|image|max:2048',
        ]);

        $existing = $this->event->getById((int) $id);

        if (! $existing) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        $imageUrl = $existing->image ?? null;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $imageUrl = "/storage/$path";
        }

        $payload = [
            'category_id' => $fields['category_id'] ?? null,
            'title' => $fields['title'],
            'description' => $fields['description'] ?? null,
            'content' => $fields['content'] ?? null,
            'location' => $fields['location'],
            'event_time' => $fields['event_time'] ?? null,
            'event_date' => $fields['event_date'],
            'image' => $imageUrl,
        ];

        $updated = $this->event->update($payload, (int) $id);

        if ($updated) {
            return response()->json(['message' => 'Event updated successfully']);
        }

        return response()->json(['message' => 'No changes applied'], 400);
    }

    public function destroy(Request $request, $id){
        $user = $request->user();
        $userId = $user ? $user->id : null;
        
        $deleted = $this->event->destroy((int) $id, $userId);

        if ($deleted) {
            return response()->json(['message' => 'Event deleted successfully']);
        }

        return response()->json(['message' => 'Event not found'], 404);
    }
}
