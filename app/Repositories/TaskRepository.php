<?php

namespace App\Repositories;

use App\DataTransferObjects\TaskData;
use App\Models\Project;
use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TaskRepository implements TaskRepositoryInterface
{
    public function create(Project $project, TaskData $data): Task
    {
        return $project->tasks()->create($data->toArray());
    }

    public function find(int $id): ?Task
    {
        return Task::find($id);
    }

    public function update(Task $task, TaskData $data): Task
    {
        $task->update($data->toArray());

        return $task->refresh();
    }

    public function delete(Task $task): bool
    {
        return (bool) $task->delete();
    }

    public function getForProject(Project $project): Collection
    {
        return $project->tasks()->latest()->get();
    }
}