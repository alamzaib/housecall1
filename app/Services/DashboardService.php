<?php

namespace App\Services;

use App\DataTransferObjects\DashboardStatistics;
use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;

class DashboardService
{
    public function getStatistics(User $user): DashboardStatistics
    {
        $totalProjects = $user->projects()->count();

        $taskCounts = Task::query()
            ->join('projects', 'projects.id', '=', 'tasks.project_id')
            ->where('projects.user_id', $user->id)
            ->selectRaw('tasks.status, count(*) as aggregate')
            ->groupBy('tasks.status')
            ->pluck('aggregate', 'tasks.status');

        return new DashboardStatistics(
            totalProjects: $totalProjects,
            totalTasks: $taskCounts->sum(),
            completedTasks: (int) ($taskCounts[TaskStatus::Completed->value] ?? 0),
            todoTasks: (int) ($taskCounts[TaskStatus::Todo->value] ?? 0),
            inProgressTasks: (int) ($taskCounts[TaskStatus::InProgress->value] ?? 0),
        );
    }
}
