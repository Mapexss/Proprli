<?php

namespace App\Http\Controllers;

use App\Core\Data\Services\FetchTasksService;
use App\Core\Data\Services\StoreTaskService;
use App\Http\Requests\TaskIndexRequest;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Collections\TaskCollection;
use App\Models\Building;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    /**
     * TaskController contructor
     *
     * @param FetchTasksService $FetchTasksService
     * @param StoreTaskService $storeTaskService
     */
    public function __construct(
        protected readonly FetchTasksService $FetchTasksService,
        protected readonly StoreTaskService $storeTaskService,
    ) {
    }

    /**
     * Gets the filtered tasks along with their comments
     *
     * @param TaskIndexRequest $request
     * @return TaskCollection
     */
    public function index(TaskIndexRequest $request): TaskCollection
    {
        $fetchedTasks = $this->FetchTasksService->fetchTasks($request->validated());
        return new TaskCollection(collect($fetchedTasks));
    }

    /**
     * Stores a task
     *
     * @param Building $building
     * @param TaskStoreRequest $request
     * @return Response
     */
    public function store(Building $building, TaskStoreRequest $request): Response
    {
        $validated = $request->safe()->only([
            'name',
            'description',
            'status',
            'assigned_user_id',
            'creator_user_id',
        ]);
        $validated['building_id'] = $building->id;
        $this->storeTaskService->storeTask($validated);

        return response()->noContent(Response::HTTP_CREATED);
    }
}
