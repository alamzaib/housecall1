<?php

namespace App\DataTransferObjects;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;

final readonly class TaskData
{
    public function __construct(
        public string $title,
        public ?string $description,
        public TaskStatus $status,
        public TaskPriority $priority,
    ) {
    }

    public static function fromRequest(FormRequest $request): self
    {
        return new self(
            title: $request->validated('title'),
            description: $request->validated('description'),
            status: TaskStatus::from($request->validated('status')),
            priority: TaskPriority::from($request->validated('priority')),
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status->value,
            'priority' => $this->priority->value,
        ];
    }
}