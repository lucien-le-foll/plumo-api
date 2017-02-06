<?php

namespace App\Http\Controllers;

use App\Room;
use App\Task;
use App\User;
use Illuminate\Http\Request;
use JWTAuth;

class TaskController extends Controller
{
    //

    /**
     * @return mixed
     */
    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();

        return response()->json($user->tasks, 200);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $room = Room::find($request->get('room_id'));
        $targetUser = User::find($request->get('user_id'));

        $task = new Task([
            'name' => $request->get('name'),
            'description' => $request->get('description')
        ]);

        if (is_int($user->house->rooms->search($room)) && is_int($user->house->users->search($targetUser))) {
            $task->user()->associate($targetUser);
            $task->room()->associate($room);
            $task->save();

            return response()->json($task, 200);
        }

        return response()->json(['error' => 'unauthorized'], 403);
    }

    public function show(Request $request, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $task = Task::find($id);

        if (is_int($user->house->tasks->search($task))) {
            return response()->json($task, 200);
        }

        return response()->json(['error' => 'unauthorized'], 403);
    }

    public function update(Request $request, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $task = Task::find($id);

        if (is_int($user->house->tasks->search($task))) {
            $room = Room::find($request->get('room_id'));
            $targetUser = User::find($request->get('user_id'));

            if (is_int($user->house->rooms->search($room)) && is_int($user->house->users->search($targetUser))) {
                $task->name = $request->get('name');
                $task->description = $request->get('description');
                $task->user()->associate($targetUser);
                $task->room()->associate($room);
                $task->save();

                return response()->json($task, 200);
            }
            return response()->json(['error' => 'unauthorized'], 403);
        }

        return response()->json(['error' => 'unauthorized'], 403);
    }

    public function destroy(Request $request, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $task = Task::find($id);

        if(is_int($user->tasks->search($task))){
            $task->delete();

            return response()->json(['success' => 'no-content'], 203);
        }

        return response()->json(['error' => 'unauthorized'], 403);
    }
}
