<?php

namespace Tests\Feature;

use App\Enums\DocumentType;
use App\Filament\Sadmin\Resources\DocumentResource;
use Filament\Forms\Form;
use Tests\Support\SimpleFormComponent;
use Tests\Support\CreatesMinimalSchema;
use Tests\TestCase;

class SadminDocumentResourceFormTest extends TestCase
{
    use CreatesMinimalSchema;

    public function test_form_contains_expected_fields_and_options(): void
    {
        $livewire = new SimpleFormComponent();
        $form = DocumentResource::form(Form::make($livewire));
        $components = collect($form->getComponents());

        // Recursively collect field names
        $collectNames = function ($component) use (&$collectNames) {
            $names = [];
            if (method_exists($component, 'getName') && ($name = $component->getName())) {
                $names[] = $name;
            }
            if (method_exists($component, 'getChildComponents')) {
                foreach ($component->getChildComponents() as $child) {
                    $names = array_merge($names, $collectNames($child));
                }
            }
            return $names;
        };

        $fields = [];
        foreach ($components as $component) {
            $fields = array_merge($fields, $collectNames($component));
        }
        $fields = array_values(array_unique($fields));

        $this->assertContains('title', $fields);
        $this->assertContains('type', $fields);
        $this->assertContains('status', $fields);
        $this->assertContains('is_published', $fields);
        $this->assertContains('description', $fields);
        $this->assertContains('pages', $fields);
        $this->assertContains('visibility', $fields);

        // Find the 'type' component recursively
        $findByName = function ($component, string $name) use (&$findByName) {
            if (method_exists($component, 'getName') && $component->getName() === $name) {
                return $component;
            }
            if (method_exists($component, 'getChildComponents')) {
                foreach ($component->getChildComponents() as $child) {
                    $found = $findByName($child, $name);
                    if ($found) return $found;
                }
            }
            return null;
        };

        $typeComponent = null;
        foreach ($components as $component) {
            $typeComponent = $findByName($component, 'type');
            if ($typeComponent) break;
        }

        $this->assertNotNull($typeComponent, 'Type select component not found in form.');

        // Filament v3 Select stores options via getOptions()
        if (method_exists($typeComponent, 'getOptions')) {
            $options = $typeComponent->getOptions();
        } elseif (method_exists($typeComponent, 'getCachedOptions')) {
            $options = $typeComponent->getCachedOptions();
        } else {
            $options = [];
        }

        $this->assertNotEmpty($options, 'Type select options are empty.');
        $this->assertArrayHasKey(DocumentType::CERTIFICATE->value, $options);
        $this->assertArrayHasKey(DocumentType::FORM->value, $options);
        $this->assertArrayHasKey(DocumentType::HANDBOOK->value, $options);
    }
}
