<?php

namespace App\Http\Controllers;

use App\House;
use App\User;
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
        $user = JWTAuth::parseToken()->authenticate();
        $house = House::create([
            'name' => $request->get('name'),
            'description' => $request->get('description')
        ]);
        $user->house()->associate($house);
        $user->save();

        return response()->json($house);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function show(Request $request, $id)
    {
        $house = House::find($id);

        return response()->json($house);
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
            return response()->json($user->house);
        }

        return response()->json(['error' => 'not_allowed'], 403);
    }
}
