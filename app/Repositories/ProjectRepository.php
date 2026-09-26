<?php

namespace App\Repositories;

use App\DataTransferObjects\ProjectData;
use App\Models\Project;
use App\Models\User;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProjectRepository implements ProjectRepositoryInterface
{
    public function create(User $user, ProjectData $data): Project
    {
        return $user->projects()->create($data->toArray());
    }

    public function find(int $id): ?Project
    {
        return Project::find($id);
    }

    public function update(Project $project, ProjectData $data): Project
    {
        $project->update($data->toArray());

        return $project->refresh();
    }

    public function delete(Project $project): bool
    {
        return (bool) $project->delete();
    }

    public function getForUser(User $user): Collection
    {
        return $user->projects()
            ->withCount('tasks')
            ->latest()
            ->get();
    }
}