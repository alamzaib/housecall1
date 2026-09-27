<?php

namespace App\Repositories\Contracts;

use App\DataTransferObjects\TaskData;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

interface TaskRepositoryInterface
{
    public function create(Project $project, TaskData $data): Task;

    public function find(int $id): ?Task;

    public function update(Task $task, TaskData $data): Task;

    public function delete(Task $task): bool;

    public function getForProject(Project $project): Collection;
}