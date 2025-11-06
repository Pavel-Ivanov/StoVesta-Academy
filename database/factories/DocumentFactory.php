<?php

namespace Database\Factories;

use App\Enums\DocumentType;
use App\Models\Document;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Document>
 */
class DocumentFactory extends Factory
{
    protected $model = Document::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement([
            DocumentType::CERTIFICATE->value,
            DocumentType::FORM->value,
            DocumentType::HANDBOOK->value,
        ]);

        return [
            'title' => $this->faker->sentence(3),
            'type' => $type,
            'status' => 'draft',
            'is_published' => false,
            'description' => $this->faker->optional()->paragraph(),
            'visibility' => $this->faker->randomElement(['all', 'Студент', 'Преподаватель', 'Администратор']),
        ];
    }

    public function certificate(): self
    {
        return $this->state(fn () => ['type' => DocumentType::CERTIFICATE->value]);
    }

    public function form(): self
    {
        return $this->state(fn () => ['type' => DocumentType::FORM->value]);
    }

    public function handbook(): self
    {
        return $this->state(fn () => ['type' => DocumentType::HANDBOOK->value]);
    }

    public function visibleTo(string $visibility): self
    {
        return $this->state(fn () => ['visibility' => $visibility]);
    }
}
