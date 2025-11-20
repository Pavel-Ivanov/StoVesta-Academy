<?php

namespace Tests\Feature;

use App\Enums\DocumentType;
use App\Filament\Intern\Resources\CertificatesResource;
use App\Filament\Intern\Resources\FormsResource;
use App\Filament\Intern\Resources\HandbookResource;
use App\Models\User;
use Database\Factories\DocumentFactory;
use Tests\Support\CreatesMinimalSchema;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class InternDocumentsVisibilityTest extends TestCase
{
    use CreatesMinimalSchema;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpMinimalSchema();
        // Ensure roles table contains expected roles
        Role::create(['name' => 'Студент']);
        Role::create(['name' => 'Преподаватель']);
        Role::create(['name' => 'Администратор']);
    }

    public function test_certificates_visible_for_all_and_matching_role_only(): void
    {
        $user = User::factory()->create();
        $user->assignRole('Студент');
        $this->actingAs($user);

        // Create docs
        DocumentFactory::new()->certificate()->visibleTo('all')->create(['title' => 'Public Certificate']);
        DocumentFactory::new()->certificate()->visibleTo('Студент')->create(['title' => 'Student Certificate']);
        DocumentFactory::new()->certificate()->visibleTo('Преподаватель')->create(['title' => 'Teacher Certificate']);
        DocumentFactory::new()->form()->visibleTo('Студент')->create(); // other type should not be included

        $records = CertificatesResource::getEloquentQuery()->pluck('title')->all();

        $this->assertContains('Public Certificate', $records);
        $this->assertContains('Student Certificate', $records);
        $this->assertNotContains('Teacher Certificate', $records);
        $this->assertCount(2, $records);
    }

    public function test_forms_visibility_for_user_without_roles_sees_only_all(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user); // no roles

        DocumentFactory::new()->form()->visibleTo('all')->create(['title' => 'Public Form']);
        DocumentFactory::new()->form()->visibleTo('Студент')->create(['title' => 'Student Form']);

        $records = FormsResource::getEloquentQuery()->pluck('title')->all();

        $this->assertContains('Public Form', $records);
        $this->assertNotContains('Student Form', $records);
        $this->assertCount(1, $records);
    }

    public function test_handbook_visibility_with_multiple_roles(): void
    {
        $user = User::factory()->create();
        $user->assignRole('Преподаватель');
        $user->assignRole('Администратор');
        $this->actingAs($user);

        DocumentFactory::new()->handbook()->visibleTo('Администратор')->create(['title' => 'Admin HB']);
        DocumentFactory::new()->handbook()->visibleTo('Преподаватель')->create(['title' => 'Teacher HB']);
        DocumentFactory::new()->handbook()->visibleTo('Студент')->create(['title' => 'Student HB']);
        DocumentFactory::new()->handbook()->visibleTo('all')->create(['title' => 'Public HB']);

        $records = HandbookResource::getEloquentQuery()->pluck('title')->all();

        $this->assertContains('Admin HB', $records);
        $this->assertContains('Teacher HB', $records);
        $this->assertContains('Public HB', $records);
        $this->assertNotContains('Student HB', $records);
        $this->assertCount(3, $records);
    }
}
