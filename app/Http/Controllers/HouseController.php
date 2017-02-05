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
        $user = JWTAuth::parseToken()->authenticate();
        if($house = $user->house){
            return response()->json($house->load(['users', 'rooms']), 200);
        }

        return response()->json(['error' => 'not found'], 404);
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

        return response()->json($house->load(['users', 'rooms']), 200);
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $house = $user->house;

        $house->name = $request->get('name');
        $house->description = $request->get('description');

        $house->save();

        return response()->json($house, 200);
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function destroy(Request $request, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $house = $user->house;
        $house->delete();

        return response()->json(['success' => 'no-content'], 201);
    }
}
