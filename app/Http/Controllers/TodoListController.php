<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddUserToListRequest;
use App\Http\Requests\TodoListStoreRequest;
use App\Http\Requests\TodoListUpdateRequest;
use App\Http\Resources\TodoListResource;
use App\Http\Resources\TodoListShowResource;
use App\Models\Enum\StatusList;
use App\Models\TodoList;
use App\Models\User;

class TodoListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return TodoListResource::collection(TodoList::whereNull('todo_list_id')
            ->with('childrenLists')
            ->filter()
            ->search()
            ->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TodoListStoreRequest $request)
    {
        return TodoListResource::make(TodoList::create($request->validated()));
    }

    /**
     * Display the specified resource.
     */
    public function show(TodoList $list)
    {
        return TodoListResource::make($list->loadMissing('parentList', 'childrenLists'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TodoListUpdateRequest $request, TodoList $list)
    {
        $data = $request->validated();
        if ($data['status'] === StatusList::DONE->value) {
            $data['completed_at'] = now();
            $hasOutstandingSubtasks = $list->childrenLists->contains(function ($value) {
                return $value->status === StatusList::DONE->value;
            });

            if ($hasOutstandingSubtasks) {
                return response()->json(['message' => 'This task has outstanding subtasks'], 403);
            }
        }
        $list->update($data);
        return TodoListResource::make($list->refresh());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TodoList $list)
    {
        $list->delete();
        return response()->json(['message' => 'Ok'], 200);
    }

    public function addUser( TodoList $list, AddUserToListRequest $request)
    {
        $list->user()->associate(User::find($request->validated('user_id')));
        $list->save();
        return TodoListShowResource::make($list->loadMissing('user'));
    }
}
