<?php

use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

it('shows correct statistics scoped only to the authenticated users own data', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    // Data belonging to the authenticated user.
    $projectOne = Project::factory()->create(['user_id' => $user->id]);
    $projectTwo = Project::factory()->create(['user_id' => $user->id]);

    Task::factory()->create(['project_id' => $projectOne->id, 'status' => TaskStatus::Todo->value]);
    Task::factory()->create(['project_id' => $projectOne->id, 'status' => TaskStatus::Todo->value]);
    Task::factory()->create(['project_id' => $projectOne->id, 'status' => TaskStatus::InProgress->value]);
    Task::factory()->create(['project_id' => $projectTwo->id, 'status' => TaskStatus::Completed->value]);
    Task::factory()->create(['project_id' => $projectTwo->id, 'status' => TaskStatus::Completed->value]);

    // Data belonging to a different user — must never be counted.
    $otherProject = Project::factory()->create(['user_id' => $otherUser->id]);
    Task::factory()->create(['project_id' => $otherProject->id, 'status' => TaskStatus::Todo->value]);
    Task::factory()->create(['project_id' => $otherProject->id, 'status' => TaskStatus::Completed->value]);
    Task::factory()->create(['project_id' => $otherProject->id, 'status' => TaskStatus::InProgress->value]);

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Dashboard')
        ->where('statistics.total_projects', 2)
        ->where('statistics.total_tasks', 5)
        ->where('statistics.completed_tasks', 2)
        ->where('statistics.todo_tasks', 2)
        ->where('statistics.in_progress_tasks', 1)
    );
});

it('shows zeroed statistics for a user with no projects or tasks', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Dashboard')
        ->where('statistics.total_projects', 0)
        ->where('statistics.total_tasks', 0)
        ->where('statistics.completed_tasks', 0)
        ->where('statistics.todo_tasks', 0)
        ->where('statistics.in_progress_tasks', 0)
    );
});

it('does not include another users projects in the total project count', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    Project::factory()->count(3)->create(['user_id' => $otherUser->id]);
    Project::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertInertia(fn (Assert $page) => $page
        ->where('statistics.total_projects', 1)
    );
});

it('does not include another users tasks in the task counts', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $project = Project::factory()->create(['user_id' => $user->id]);
    Task::factory()->create(['project_id' => $project->id, 'status' => TaskStatus::Completed->value]);

    $otherProject = Project::factory()->create(['user_id' => $otherUser->id]);
    Task::factory()->count(5)->create(['project_id' => $otherProject->id, 'status' => TaskStatus::Completed->value]);

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertInertia(fn (Assert $page) => $page
        ->where('statistics.total_tasks', 1)
        ->where('statistics.completed_tasks', 1)
    );
});

it('prevents unauthenticated users from accessing the dashboard', function () {
    $response = $this->get('/dashboard');

    $response->assertRedirect('/login');
});
