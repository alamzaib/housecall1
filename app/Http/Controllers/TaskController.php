<?php

namespace App\Http\Controllers;

use App\DataTransferObjects\TaskData;
use App\Enums\TaskStatus;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Requests\UpdateTaskStatusRequest;
use App\Models\Project;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class TaskController extends Controller
{
    public function __construct(
        private readonly TaskService $service,
    ) {
    }

    public function create(Project $project): Response
    {
        $this->authorize('create', [Task::class, $project]);

        return Inertia::render('Tasks/Create', [
            'project' => $project,
        ]);
    }

    public function store(StoreTaskRequest $request, Project $project): RedirectResponse
    {
        $data = TaskData::fromRequest($request);

        $this->service->create($project, $data);

        return redirect()
            ->route('projects.show', $project)
            ->with('success', 'Task created.');
    }

    public function edit(Task $task): Response
    {
        $this->authorize('update', $task);

        return Inertia::render('Tasks/Edit', [
            'task' => $task,
        ]);
    }

    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        $data = TaskData::fromRequest($request);

        $this->service->update($task, $data);

        return redirect()
            ->route('projects.show', $task->project)
            ->with('success', 'Task updated.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $this->authorize('delete', $task);

        $project = $task->project;

        $this->service->delete($task);

        return redirect()
            ->route('projects.show', $project)
            ->with('success', 'Task deleted.');
    }

    public function updateStatus(UpdateTaskStatusRequest $request, Task $task): RedirectResponse
    {
        $this->service->changeStatus(
            $task,
            TaskStatus::from($request->validated('status')),
        );

        return redirect()
            ->route('projects.show', $task->project)
            ->with('success', 'Task status updated.');
    }
}