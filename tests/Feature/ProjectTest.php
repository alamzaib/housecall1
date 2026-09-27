<?php

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows an authenticated user to create a project', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/projects', [
        'name' => 'New Project',
        'description' => 'A test project',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('projects', [
        'user_id' => $user->id,
        'name' => 'New Project',
    ]);
});

it('allows an authenticated user to view their own project', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->get("/projects/{$project->id}");

    $response->assertOk();
});

it('allows an authenticated user to update their own project', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->put("/projects/{$project->id}", [
        'name' => 'Updated Name',
        'description' => 'Updated description',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('projects', [
        'id' => $project->id,
        'name' => 'Updated Name',
    ]);
});

it('allows an authenticated user to delete their own project', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->delete("/projects/{$project->id}");

    $response->assertRedirect();
    $this->assertDatabaseMissing('projects', ['id' => $project->id]);
});

it('prevents an unauthenticated user from accessing project pages', function () {
    $project = Project::factory()->create();

    $this->get('/projects')->assertRedirect('/login');
    $this->get("/projects/{$project->id}")->assertRedirect('/login');
    $this->get('/projects/create')->assertRedirect('/login');
});

it('prevents a user from updating another users project', function () {
    $owner = User::factory()->create();
    $intruder = User::factory()->create();
    $project = Project::factory()->create(['user_id' => $owner->id]);

    $response = $this->actingAs($intruder)->put("/projects/{$project->id}", [
        'name' => 'Hijacked Name',
    ]);

    $response->assertForbidden();
    $this->assertDatabaseMissing('projects', ['name' => 'Hijacked Name']);
});

it('prevents a user from deleting another users project', function () {
    $owner = User::factory()->create();
    $intruder = User::factory()->create();
    $project = Project::factory()->create(['user_id' => $owner->id]);

    $response = $this->actingAs($intruder)->delete("/projects/{$project->id}");

    $response->assertForbidden();
    $this->assertDatabaseHas('projects', ['id' => $project->id]);
});

it('rejects invalid project data', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/projects', [
        'name' => '',
    ]);

    $response->assertSessionHasErrors('name');
    $this->assertDatabaseCount('projects', 0);
});

it('prevents a user from viewing another users project', function () {
    $owner = User::factory()->create();
    $intruder = User::factory()->create();
    $project = Project::factory()->create(['user_id' => $owner->id]);

    $response = $this->actingAs($intruder)->get("/projects/{$project->id}");

    $response->assertForbidden();
});

it('rejects a non-string project description', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/projects', [
        'name' => 'Valid Name',
        'description' => ['not', 'a', 'string'],
    ]);

    $response->assertSessionHasErrors('description');
});
