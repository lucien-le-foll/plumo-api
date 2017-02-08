<?php

namespace App\Http\Controllers;

use App\Room;
use Illuminate\Http\Request;
use JWTAuth;

class RoomController extends Controller
{
    //

    private $relations = ['tasks', 'house'];

    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $rooms = $user->house->rooms;

        return response()->json($rooms->load($this->relations), 200);
    }

    public function show(Request $request, $id)
    {
        $room = Room::find($id);

        return response()->json($room->load($this->relations), 200);
    }

    public function store(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $house = $user->house;

        $room = Room::create([
            'name' => $request->get('name'),
            'description' => $request->get('description')
        ]);

        $room->house()->associate($house);
        $room->save();

        return response()->json($room, 200);
    }

    public function update(Request $request, $id)
    {
        if (!$room = Room::find($id)) {
            return response()->json(['error' => 'not found'], 404);
        }

        $room->name = $request->get('name');
        $room->description = $request->get('description');

        $room->save();

        return response()->json($room->load($this->relations), 200);
    }

    public function destroy(Request $request, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $house = $user->house;
        $room = Room::find($id);

        if (is_int($house->rooms->search($room))) {
            $room->delete();
            return response()->json(['success' => 'no-content'], 201);
        }

        return response()->json(['error' => 'not-authorized'], 403);
    }
}
