<?php

namespace App\Repositories\Contracts;

use App\DataTransferObjects\ProjectData;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface ProjectRepositoryInterface
{
    public function create(User $user, ProjectData $data): Project;

    public function find(int $id): ?Project;

    public function update(Project $project, ProjectData $data): Project;

    public function delete(Project $project): bool;

    public function getForUser(User $user): Collection;
}