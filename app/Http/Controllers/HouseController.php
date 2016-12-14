<?php

namespace App\Http\Controllers;

use App\House;
use Illuminate\Http\Request;

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
        try {
            $house = House::create(['name' => $request->get('name'), 'description' => $request->get('description')]);
        } catch (\Exception $e){
            return response()->json(['error' => 'House already exists']);
        }

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

    public function update(Request $request, $id)
    {
        $house = House::find($id);
        $house->name = $request->get('name');
        $house->description = $request->get('description');
        $house->save();

        return response()->json($house);
    }

    public function destroy(Request $request, $id)
    {
        House::destroy($id);
        $houses = House::all();

        return response()->json($houses);
    }
}
