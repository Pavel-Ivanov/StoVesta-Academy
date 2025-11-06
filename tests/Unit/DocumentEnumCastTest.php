<?php

namespace Tests\Unit;

use App\Enums\DocumentStatus;
use App\Enums\DocumentType;
use App\Models\Document;
use Tests\Support\CreatesMinimalSchema;
use Tests\TestCase;

class DocumentEnumCastTest extends TestCase
{
    use CreatesMinimalSchema;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpMinimalSchema();
    }

    public function test_document_type_is_cast_to_enum_on_retrieval(): void
    {
        $doc = Document::query()->create([
            'title' => 'Test Doc',
            'type' => DocumentType::FORM->value,
            'description' => 'Desc',
            'visibility' => 'all',
        ]);

        $doc->refresh();

        $this->assertInstanceOf(DocumentType::class, $doc->type);
        $this->assertTrue($doc->type === DocumentType::FORM);
        $this->assertSame('form', $doc->getAttribute('type')->value);
    }

    public function test_setting_enum_value_persists_string_in_db(): void
    {
        $doc = new Document();
        $doc->title = 'Another';
        $doc->type = DocumentType::HANDBOOK; // set via enum
        $doc->visibility = 'all';
        $doc->save();

        $this->assertDatabaseHas('documents', [
            'id' => $doc->id,
            'type' => 'handbook',
        ]);
    }

    public function test_document_status_is_cast_and_defaults_to_draft(): void
    {
        $doc = Document::query()->create([
            'title' => 'Status Test',
            'type' => DocumentType::CERTIFICATE->value,
            'visibility' => 'all',
        ]);
        $doc->refresh();
        $this->assertInstanceOf(DocumentStatus::class, $doc->status);
        $this->assertTrue($doc->status === DocumentStatus::DRAFT);
        $this->assertSame('draft', $doc->getAttribute('status')->value);
    }

    public function test_can_set_status_enum_and_is_published_boolean(): void
    {
        $doc = new Document();
        $doc->title = 'Publish Test';
        $doc->type = DocumentType::FORM;
        $doc->status = DocumentStatus::PUBLISHED;
        $doc->is_published = true;
        $doc->visibility = 'all';
        $doc->save();

        $doc->refresh();
        $this->assertTrue($doc->status === DocumentStatus::PUBLISHED);
        $this->assertTrue($doc->is_published === true);

        $this->assertDatabaseHas('documents', [
            'id' => $doc->id,
            'type' => 'form',
            'status' => 'published',
            'is_published' => 1,
        ]);
    }
}
