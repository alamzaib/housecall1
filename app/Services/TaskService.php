<?php

namespace App\Services;

use App\DataTransferObjects\TaskData;
use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;

class TaskService
{
    public function __construct(
        private readonly TaskRepositoryInterface $repository,
    ) {
    }

    public function create(Project $project, TaskData $data): Task
    {
        return $this->repository->create($project, $data);
    }

    public function update(Task $task, TaskData $data): Task
    {
        return $this->repository->update($task, $data);
    }

    public function delete(Task $task): bool
    {
        return $this->repository->delete($task);
    }

    public function changeStatus(Task $task, TaskStatus $status): Task
    {
        $data = new TaskData(
            title: $task->title,
            description: $task->description,
            status: $status,
            priority: $task->priority,
        );

        return $this->repository->update($task, $data);
    }
}