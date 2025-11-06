<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\DocumentPage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DocumentPage>
 */
class DocumentPageFactory extends Factory
{
    protected $model = DocumentPage::class;

    public function definition(): array
    {
        return [
            'document_id' => Document::factory(),
            'title' => $this->faker->optional()->sentence(3),
            'file_path' => 'documents/' . $this->faker->unique()->uuid() . '.pdf',
            'sort_order' => $this->faker->numberBetween(0, 5),
        ];
    }

    public function forDocument(Document $document): self
    {
        return $this->state(fn () => ['document_id' => $document->id]);
    }
}
