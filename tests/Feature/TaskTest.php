<?php

use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows a user to create a task inside their own project', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->post("/projects/{$project->id}/tasks", [
        'title' => 'New Task',
        'description' => 'Task description',
        'status' => 'todo',
        'priority' => 'medium',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('tasks', [
        'project_id' => $project->id,
        'title' => 'New Task',
    ]);
});

it('allows a user to update a task in their own project', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create(['user_id' => $user->id]);
    $task = Task::factory()->create(['project_id' => $project->id]);

    $response = $this->actingAs($user)->put("/tasks/{$task->id}", [
        'title' => 'Updated Title',
        'description' => 'Updated description',
        'status' => 'in_progress',
        'priority' => 'high',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'title' => 'Updated Title',
        'status' => 'in_progress',
    ]);
});

it('allows a user to delete a task in their own project', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create(['user_id' => $user->id]);
    $task = Task::factory()->create(['project_id' => $project->id]);

    $response = $this->actingAs($user)->delete("/tasks/{$task->id}");

    $response->assertRedirect();
    $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
});

it('allows a user to change a task status', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create(['user_id' => $user->id]);
    $task = Task::factory()->create([
        'project_id' => $project->id,
        'status' => TaskStatus::Todo->value,
    ]);

    $response = $this->actingAs($user)->patch("/tasks/{$task->id}/status", [
        'status' => 'completed',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'status' => 'completed',
    ]);
});

it('prevents a user from modifying a task belonging to another users project', function () {
    $owner = User::factory()->create();
    $intruder = User::factory()->create();
    $project = Project::factory()->create(['user_id' => $owner->id]);
    $task = Task::factory()->create(['project_id' => $project->id]);

    $updateResponse = $this->actingAs($intruder)->put("/tasks/{$task->id}", [
        'title' => 'Hijacked',
        'description' => null,
        'status' => 'todo',
        'priority' => 'low',
    ]);
    $updateResponse->assertForbidden();

    $deleteResponse = $this->actingAs($intruder)->delete("/tasks/{$task->id}");
    $deleteResponse->assertForbidden();

    $this->assertDatabaseHas('tasks', ['id' => $task->id, 'title' => $task->title]);
});

it('rejects an invalid task status', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create(['user_id' => $user->id]);
    $task = Task::factory()->create(['project_id' => $project->id]);

    $response = $this->actingAs($user)->patch("/tasks/{$task->id}/status", [
        'status' => 'not_a_real_status',
    ]);

    $response->assertSessionHasErrors('status');
});

it('rejects an invalid task priority', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->post("/projects/{$project->id}/tasks", [
        'title' => 'Task With Bad Priority',
        'status' => 'todo',
        'priority' => 'urgent',
    ]);

    $response->assertSessionHasErrors('priority');
    $this->assertDatabaseMissing('tasks', ['title' => 'Task With Bad Priority']);
});

it('prevents unauthenticated users from managing tasks', function () {
    $project = Project::factory()->create();
    $task = Task::factory()->create(['project_id' => $project->id]);

    $this->get("/projects/{$project->id}/tasks/create")->assertRedirect('/login');
    $this->post("/projects/{$project->id}/tasks", ['title' => 'x'])->assertRedirect('/login');
    $this->get("/tasks/{$task->id}/edit")->assertRedirect('/login');
    $this->put("/tasks/{$task->id}", ['title' => 'x'])->assertRedirect('/login');
    $this->delete("/tasks/{$task->id}")->assertRedirect('/login');
    $this->patch("/tasks/{$task->id}/status", ['status' => 'todo'])->assertRedirect('/login');
});

it('prevents a user from viewing another users task edit page', function () {
    $owner = User::factory()->create();
    $intruder = User::factory()->create();
    $project = Project::factory()->create(['user_id' => $owner->id]);
    $task = Task::factory()->create(['project_id' => $project->id]);

    $response = $this->actingAs($intruder)->get("/tasks/{$task->id}/edit");

    $response->assertForbidden();
});

it('rejects a non-string task description', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->post("/projects/{$project->id}/tasks", [
        'title' => 'Valid Title',
        'description' => ['not', 'a', 'string'],
        'status' => 'todo',
        'priority' => 'low',
    ]);

    $response->assertSessionHasErrors('description');
});
