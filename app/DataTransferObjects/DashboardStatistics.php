<?php

namespace App\DataTransferObjects;

final readonly class DashboardStatistics
{
    public function __construct(
        public int $totalProjects,
        public int $totalTasks,
        public int $completedTasks,
        public int $todoTasks,
        public int $inProgressTasks,
    ) {
    }

    public function toArray(): array
    {
        return [
            'total_projects' => $this->totalProjects,
            'total_tasks' => $this->totalTasks,
            'completed_tasks' => $this->completedTasks,
            'todo_tasks' => $this->todoTasks,
            'in_progress_tasks' => $this->inProgressTasks,
        ];
    }
}
