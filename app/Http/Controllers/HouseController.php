<?php

namespace App\Http\Controllers;

use App\House;
use App\Room;
use Illuminate\Http\Request;
use JWTAuth;

class HouseController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $houses = House::all();

        return response()->json($houses);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        // creating and saving the house object
        $house = House::create([
            'name' => $request->get('name'),
            'description' => $request->get('description')
        ]);

        // defining the first user of the house
        $user = JWTAuth::parseToken()->authenticate();
        $user->house()->associate($house);
        $user->save();

        // parsing the rooms so they can be added to the house
        foreach ($request->get('rooms') as $room) {
            $newRoom = Room::create([
                'name' => $room['name'],
                'description' => $room['description']
            ]);
            $newRoom->house()->associate($house);
            $newRoom->save();
        }

        return response()->json($house->load(['users', 'rooms']));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function show(Request $request, $id)
    {
        $house = House::find($id);

        return response()->json($house->load(['users', 'rooms']));
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        $house = House::find($id);
        $house->name = $request->get('name');
        $house->description = $request->get('description');
        $house->save();

        return response()->json($house);
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function destroy(Request $request, $id)
    {
        House::destroy($id);
        $houses = House::all();

        return response()->json($houses);
    }

    public function currentHouse(Request $request)
    {
        if($user = JWTAuth::parseToken()->authenticate()){
            if($user->house){
                return response()->json($user->house->load('users', 'rooms'));
            }
            $house = new House();
            return response()->json($house);
        }

        return response()->json(['error' => 'not_allowed'], 401);
    }
}
