<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Room\StoreRoomRequest;
use App\Http\Requests\Room\UpdateRoomStatusRequest;
use App\Models\Room;
use Illuminate\Http\JsonResponse;

class RoomController extends Controller
{
    public function index(int $hostelId): JsonResponse
    {
        $rooms = Room::query()->where('hostel_id', $hostelId)->orderBy('room_number')->get();

        return response()->json($rooms);
    }

    public function store(StoreRoomRequest $request, int $hostelId): JsonResponse
    {
        $payload = $request->validated();
        $payload['hostel_id'] = $hostelId;
        $payload['status'] = $payload['status'] ?? 'available';

        $room = Room::query()->create($payload);

        return response()->json($room, 201);
    }

    public function show(int $id): JsonResponse
    {
        $room = Room::query()->findOrFail($id);

        return response()->json($room);
    }

    public function update(StoreRoomRequest $request, int $id): JsonResponse
    {
        $room = Room::query()->findOrFail($id);
        $room->update($request->validated());

        return response()->json($room->refresh());
    }

    public function destroy(int $id): JsonResponse
    {
        Room::query()->findOrFail($id)->delete();

        return response()->json(['message' => 'Room deleted']);
    }

    public function updateStatus(UpdateRoomStatusRequest $request, int $id): JsonResponse
    {
        $room = Room::query()->findOrFail($id);
        $room->update(['status' => $request->validated('status')]);

        return response()->json($room->refresh());
    }
}
