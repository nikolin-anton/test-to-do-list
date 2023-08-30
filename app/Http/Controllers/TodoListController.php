<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoListStoreRequest;
use App\Http\Requests\TodoListUpdateRequest;
use App\Http\Resources\TodoListResource;
use App\Models\TodoList;

class TodoListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return TodoListResource::collection(TodoList::whereNull('list_id')
            ->with('childrenLists')
            ->filter()
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
        $list->update($request->validated());
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
}
