<?php

namespace Tests\Feature;

use App\Enums\DocumentType;
use App\Models\Document;
use App\Models\User;
use Database\Factories\DocumentPageFactory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Models\Role;
use Tests\Support\CreatesMinimalSchema;
use Tests\TestCase;

class DocumentPrintAndZipTest extends TestCase
{
    use CreatesMinimalSchema;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpMinimalSchema();

        // Seed required roles
        Role::query()->firstOrCreate(['name' => 'Студент', 'guard_name' => 'web']);
        Role::query()->firstOrCreate(['name' => 'Преподаватель', 'guard_name' => 'web']);
        Role::query()->firstOrCreate(['name' => 'Администратор', 'guard_name' => 'web']);
    }

    public function test_print_requires_auth_and_respects_visibility(): void
    {
        $doc = Document::query()->create([
            'title' => 'Print Test',
            'type' => DocumentType::FORM->value,
            'visibility' => 'Студент',
        ]);

        // Add one page so print has content
        $pagePath = 'documents/test-print.pdf';
        // Ensure physical file exists for potential rendering (not strictly required for view)
        $abs = storage_path('app/public/' . $pagePath);
        if (!is_dir(dirname($abs))) {
            mkdir(dirname($abs), 0777, true);
        }
        file_put_contents($abs, 'dummy');

        $doc->pages()->create([
            'title' => 'Лист 1',
            'file_path' => $pagePath,
            'sort_order' => 0,
        ]);

        // Guest should be 302 to login or 403 depending on middleware; simulate direct call
        $response = $this->get(route('documents.print', $doc));
        $response->assertStatus(302); // redirected to login due to auth middleware

        // Authed user without role should get 403
        $user = User::factory()->create();
        $this->actingAs($user);
        $this->get(route('documents.print', $doc))->assertStatus(403);

        // Assign role and try again
        $user->assignRole('Студент');
        $this->get(route('documents.print', $doc))
            ->assertOk()
            ->assertSee('Лист 1');
    }

    public function test_download_all_builds_zip_with_all_pages_in_order(): void
    {
        $doc = Document::query()->create([
            'title' => 'ZIP Test',
            'type' => DocumentType::HANDBOOK->value,
            'visibility' => 'all',
        ]);

        // Create three pages with physical files (controller reads from storage_path('app/public/...'))
        $paths = [
            'documents/03_last.pdf',
            'documents/01_first.pdf',
            'documents/02_middle.pdf',
        ];

        foreach ($paths as $rel) {
            $abs = storage_path('app/public/' . $rel);
            if (!is_dir(dirname($abs))) {
                mkdir(dirname($abs), 0777, true);
            }
            file_put_contents($abs, 'content-' . basename($rel));
        }

        // Unordered insert; order should be by sort_order then id
        $doc->pages()->create(['title' => 'C', 'file_path' => $paths[0], 'sort_order' => 2]);
        $doc->pages()->create(['title' => 'A', 'file_path' => $paths[1], 'sort_order' => 0]);
        $doc->pages()->create(['title' => 'B', 'file_path' => $paths[2], 'sort_order' => 1]);

        // Auth as any user
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('documents.downloadAll', $doc));
        $response->assertOk();
        $response->assertHeader('content-disposition');

        // Save output to temp to inspect zip structure (optional lightweight check)
        $tmp = tempnam(sys_get_temp_dir(), 'ziptest_');
        file_put_contents($tmp, $response->streamedContent());

        $zip = new \ZipArchive();
        $this->assertTrue($zip->open($tmp) === true, 'Zip cannot be opened');
        $this->assertSame(3, $zip->numFiles, 'ZIP should contain 3 files');
        // File names are prefixed with 01_, 02_, 03_
        $this->assertSame('01_' . basename($paths[1]), $zip->getNameIndex(0));
        $this->assertSame('02_' . basename($paths[2]), $zip->getNameIndex(1));
        $this->assertSame('03_' . basename($paths[0]), $zip->getNameIndex(2));
        $zip->close();

        @unlink($tmp);
    }
}
