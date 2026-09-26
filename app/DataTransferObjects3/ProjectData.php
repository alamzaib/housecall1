<?php

namespace App\DataTransferObjects;

use Illuminate\Foundation\Http\FormRequest;

final readonly class ProjectData
{
    public function __construct(
        public string $name,
        public ?string $description,
    ) {
    }

    public static function fromRequest(FormRequest $request): self
    {
        return new self(
            name: $request->validated('name'),
            description: $request->validated('description'),
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
        ];
    }
}