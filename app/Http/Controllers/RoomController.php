<?php

namespace App\Http\Controllers;

use App\Room;
use Illuminate\Http\Request;
use JWTAuth;

class RoomController extends Controller
{
    //

    public function index()
    {
        $rooms = Room::all();

        return response()->json($rooms);
    }

    public function show(Request $request, $id)
    {
        $room = Room::find($id);

        return response()->json($room);
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

        return response()->json($room);
    }

    public function update(Request $request, $id)
    {
        
    }

    public function destroy(Request $request, $id)
    {

    }
}
