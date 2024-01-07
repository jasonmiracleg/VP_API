<?php

namespace App\Http\Controllers\Api;

use DateTime;
use Exception;
use DateInterval;
use App\Models\Group;
use App\Models\Reminder;
use App\Models\ToDoList;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\ToDoListResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ToDoListController extends Controller
{
    public function allToDoList($userId): array
    {
        $toDoLists = ToDoList::with('categories')->where('id', $userId)->get();
        return [
            "status" => Response::HTTP_OK,
            "message" => "Success",
            "data" => ToDoListResource::collection($toDoLists)
        ];
    }

    public function todayToDoList($userId)
    {
        $today_tdl = ToDoList::with('categories')
            ->where('user_id', $userId)
            ->whereDate('date', today('Asia/Jakarta'))
            ->get();

        return [
            "status" => Response::HTTP_OK,
            "message" => "Successfully get Today's To Do List",
            "data" => $today_tdl
        ];
    }

    public function getAllGroupTasks($groupId)
    {
        try {
            $group = Group::findOrFail($groupId);

            // Get all tasks associated with the group
            $tasks = $group->toDoLists;

            return [
                "status" => Response::HTTP_OK,
                "message" => "Successfully retrieved all tasks for the group",
                "data" => $tasks
            ];
        } catch (Exception $e) {
            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage(),
                'data' => []
            ];
        }
    }

    public function getTasksByDayName(Request $request)
    {
        try {
            $request->validate([
                'day' => 'required|string',
                'user_id' => 'required|numeric',
            ]);

            // Assuming 'day_name' and 'user_id' are columns in your to_do_lists table
            $tasks = ToDoList::with('categories')
                ->where('day', $request->day)
                ->where('user_id', $request->user_id)
                ->get();

            return [
                "status" => Response::HTTP_OK,
                "message" => "Successfully retrieved tasks for the specified day and user",
                "data" => $tasks
            ];
        } catch (Exception $e) {
            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage(),
                'data' => []
            ];
        }
    }

    public function createToDoListGroup(Request $request)
    {
        try {
            $tdl =  ToDoList::create([
                'title' => $request->title,
                'is_group' => '1',
                'is_complete' => '0',
                'description' => $request->description,
                'date' => $request->date,
                'user_id' => $request->user_id,
                'group_id' => $request->group_id,
                'day' => $this->getDayNameFromDate($request->date)
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

    public function createToDoList(Request $request, $userId)
    {
        try {
            $tdl = ToDoList::create([
                'title' => $request->title,
                'is_group' => $request->is_group,
                'is_complete' => '0',
                'description' => $request->description,
                'timer' => $request->timer,
                'total_seconds' => $request->total_seconds,
                'user_id' => $userId,
                'date' => $request->date,
                'day' => $this->getDayNameFromDate($request->date)
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

    private function getDayNameFromDate($date)
    {
        $dateTime = new DateTime($date);
        return $dateTime->format('l');
    }

    public function editToDoList(Request $request, ToDoList $toDoList)
    {
        try {
            $toDoList->fill($request->all())->save();
            if ($request->has('date')) {
                $toDoList->update([
                    'day' => $this->getDayNameFromDate($toDoList->date),
                ]);
            }

            if ($toDoList->reminder) {
                // Check if the user is updating the Reminder values
                if ($request->has('time_hours') || $request->has('time_minutes')) {
                    $toDoList->reminder->update([
                        'time_hours' => $request->time_hours,
                        'time_minutes' => $request->time_minutes
                    ]);
                }
            }

            return [
                'status' => Response::HTTP_OK,
                'message' => 'Successfully updated a to do list!',
                'data' => $toDoList->title
            ];
        } catch (Exception $e) {
            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage(),
                'data' => []
            ];
        }
    }

    public function editToDoListGroup(Request $request, Group $group, ToDoList $toDoList)
    {
        $data = $request->all();


        // Ensure the task belongs to the correct group
        if ($toDoList->group_id != $group->id) {
            return [
                "status" => Response::HTTP_BAD_REQUEST,
                "message" => "The provided to-do list does not belong to the specified group",
                "data" => null
            ];
        }

        // Update the task details
        $toDoList->fill($data)->save();
        if ($request->has('date')) {
            $toDoList->update([
                'day' => $this->getDayNameFromDate($toDoList->date),
            ]);
        }
        return [
            "status" => Response::HTTP_OK,
            "message" => "Successfully updated the to-do list in the group",
            "data" => $toDoList // Optionally, you can return the updated to-do list
        ];
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
        $toDoList = ToDoList::where('id', $request->id)->first();

        if (!$toDoList) {
            return [
                "status" => Response::HTTP_NOT_FOUND,
                "message" => "To-do list not found",
                "data" => null
            ];
        }

        $toDoList->timer_started = '1';
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
            $toDoList = ToDoList::findOrFail($request->id);

            // Update the to-do list
            $toDoList->timer_started = '0'; // Assuming timer_started is a boolean field
            $toDoList->update([
                'total_seconds' => $request->total_seconds
            ]);
            $toDoList->save();

            // Update user's productive time
            $user = $toDoList->tasks; // Assuming tasks() is the relationship method in ToDoList model

            if (is_string($request->elapsed)) {
                // If $request->elapsed is a string (time duration)
                // Extract hours, minutes, and seconds from the elapsed time string
                list($hours, $minutes, $seconds) = explode(':', $request->elapsed);

                // Convert hours, minutes, and seconds to total seconds
                $totalSecondsToAdd = ($hours * 3600) + ($minutes * 60) + $seconds;

                // Add the elapsed time directly to the productive_time column
                $user->productive_time = Carbon::parse($user->productive_time)
                    ->addSeconds($totalSecondsToAdd)
                    ->format('H:i:s'); // Format to store only the time
            } else {
                // If $request->elapsed is already in time data type
                $user->productive_time = Carbon::parse($user->productive_time)
                    ->addSeconds(Carbon::parse($request->elapsed)->diffInSeconds());
            }

            $user->save();
            return [
                "status" => Response::HTTP_OK,
                "message" => "Timer stopped successfully",
                "data" => [$toDoList]
            ];
        } catch (Exception $e) {
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
            $toDoList = ToDoList::where('id', $request->id)->first();

            return [
                "status" => Response::HTTP_OK,
                "message" => "Success",
                "data" => $toDoList->timer
            ];
        } catch (Exception $e) {
            return [
                "status" => Response::HTTP_NOT_FOUND,
                "message" => "To Do List not found",
                "data" => []
            ];
        }
    }
}
