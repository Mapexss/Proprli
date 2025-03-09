<?php

namespace App\Http\Controllers;

use App\Core\Data\Services\StoreCommentService;
use App\Http\Requests\CommentStoreRequest;
use App\Models\Task;
use Illuminate\Http\Response;

class CommentController extends Controller
{
    /**
     * CommentController contructor
     *
     * @param StoreCommentService $storeCommentService
     */
    public function __construct(
        protected readonly StoreCommentService $storeCommentService,
    ) {
    }

    /**
     * Stores a comment to the requested task
     *
     * @param Task $task
     * @param CommentStoreRequest $request
     * @return Response
     */
    public function store(Task $task, CommentStoreRequest $request): Response
    {
        $validated = $request->safe()->only(['content', 'creator_user_id']);
        $validated['task_id'] = $task->id;

        $this->storeCommentService->storeComment($validated);

        return response()->noContent(Response::HTTP_CREATED);
    }
}