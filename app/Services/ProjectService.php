<?php

namespace App\Services;

use App\DataTransferObjects\ProjectData;
use App\Models\Project;
use App\Models\User;
use App\Repositories\Contracts\ProjectRepositoryInterface;

class ProjectService
{
    public function __construct(
        private readonly ProjectRepositoryInterface $repository,
    ) {
    }

    public function create(User $user, ProjectData $data): Project
    {
        return $this->repository->create($user, $data);
    }

    public function update(Project $project, ProjectData $data): Project
    {
        return $this->repository->update($project, $data);
    }

    public function delete(Project $project): bool
    {
        return $this->repository->delete($project);
    }
}