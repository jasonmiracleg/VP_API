<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use App\Models\Reminder;
use App\Models\ToDoList;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Resources\ToDoListResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ToDoListController extends Controller
{
    public function allToDoList()
    {
        $toDoLists = ToDoList::all();
        return [
            "status" => Response::HTTP_OK,
            "message" => "Success",
            "data" => ToDoListResource::collection($toDoLists)
        ];
    }

    public function todayToDoList()
    {
        $today_tdl = [];
        foreach (ToDoList::all() as $tdl) {
            if (Carbon::today('Asia/Jakarta')->isSameDay($tdl->date)) {
                $todayt_tdl[] = $tdl;
            }
        }

        return [
            "status" => Response::HTTP_OK,
            "message" => "Successfully get Today's To Do List",
            "data" => $today_tdl
        ];
    }

    public function createToDoList(Request $request)
    {
        try {
            $tdl = ToDoList::create([
                'title' => $request->title,
                'is_group' => $request->is_group,
                'is_complete' => '0',
                'description' => $request->description,
                'timer' => $request->timer,
                'total_seconds' => $request->total_seconds,
                'user_id' => $request->user_id,
                'date' => $request->date
            ]);
            Reminder::create([
                'time_hours' => $request->time_hours,
                'time_minutes' => $request->time_minutes,
                'to_do_list_id' => $tdl->id
            ]);
            return [
                "status" => Response::HTTP_OK,
                "message" => "Successfully create To Do List",
                "data" => $tdl
            ];
        } catch (Exception $e) {
            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage(),
                'data' => []
            ];
        }
    }

    public function editToDoList(Request $request)
    {
        try {
            $update_tdl = ToDoList::findOrFail('id', $request->id);
            $update_tdl->update([
                'title' => $request->title,
                'is_group' => $request->is_group,
                'is_complete' => $request->is_complete,
                'description' => $request->description,
                'timer' => $request->timer,
                'total_seconds' => $request->total_seconds,
                'date' => $request->date
            ]);

            if ($update_tdl->reminder) {
                // Check if the user is updating the Reminder values
                if ($request->has('time_hours') || $request->has('time_minutes')) {
                    $update_tdl->reminder->update([
                        'time_hours' => $request->time_hours,
                        'time_minutes' => $request->time_minutes
                    ]);
                }
            }

            return [
                'status' => Response::HTTP_OK,
                'message' => 'Successfully updated a to do list!',
                'data' => $update_tdl->title
            ];
        } catch (Exception $e) {
            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage(),
                'data' => []
            ];
        }
    }

    public function deleteToDoList(Request $request)
    {
        try {
            ToDoList::find($request->id)->delete();
            return [
                'status' => Response::HTTP_OK,
                'message' => 'Successfully delete!',
                'data' => []
            ];
        } catch (Exception $e) {
            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage(),
                'data' => []
            ];
        }
    }

    public function startTimer(Request $request)
    {
        $toDoList = ToDoList::where('id', $request->id);
        $toDoList->timer_started = true;
        $toDoList->save();

        return [
            "status" => Response::HTTP_OK,
            "message" => "Success",
            "data" => $toDoList
        ];
    }

    public function stopTimer(Request $request)
    {
        try {
            $toDoList = ToDoList::where('id', $request->id);

            $toDoList->timer_started = false;
            $toDoList->save();

            $user = $toDoList->user();

            $user->productive_time += $toDoList->elapsed;
            $user->save();

            return [
                "status" => Response::HTTP_OK,
                "message" => "Timer stopped successfully",
                "data" => [$toDoList, $user]
            ];
        } catch (ModelNotFoundException $e) {
            return [
                "status" => Response::HTTP_NOT_FOUND,
                "message" => "To Do List not found",
                "data" => []
            ];
        }
    }

    public function getTimerState(Request $request)
    {
        try {
            $toDoList = ToDoList::where('id', $request->id);

            return [
                "status" => Response::HTTP_OK,
                "message" => "Success",
                "data" => $toDoList
            ];
        } catch (ModelNotFoundException $e) {
            return [
                "status" => Response::HTTP_NOT_FOUND,
                "message" => "To Do List not found",
                "data" => []
            ];
        }
    }
}