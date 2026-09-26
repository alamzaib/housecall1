<?php

namespace App\Http\Controllers;

use App\DataTransferObjects\ProjectData;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
    public function __construct(
        private readonly ProjectService $service,
    ) {
    }

    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Project::class);

        return Inertia::render('Projects/Index', [
            'projects' => $this->service->list($request->user()),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Project::class);

        return Inertia::render('Projects/Create');
    }

    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $data = ProjectData::fromRequest($request);

        $project = $this->service->create($request->user(), $data);

        return redirect()
            ->route('projects.show', $project)
            ->with('success', 'Project created.');
    }

    public function show(Project $project): Response
    {
        $this->authorize('view', $project);

        return Inertia::render('Projects/Show', [
            'project' => $project->load('tasks'),
        ]);
    }

    public function edit(Project $project): Response
    {
        $this->authorize('update', $project);

        return Inertia::render('Projects/Edit', [
            'project' => $project,
        ]);
    }

    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        $data = ProjectData::fromRequest($request);

        $this->service->update($project, $data);

        return redirect()
            ->route('projects.show', $project)
            ->with('success', 'Project updated.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->authorize('delete', $project);

        $this->service->delete($project);

        return redirect()
            ->route('projects.index')
            ->with('success', 'Project deleted.');
    }
}