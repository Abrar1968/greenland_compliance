<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\AboutSetting;
use App\Models\CaseStudyCategory;
use App\Models\ContactMessage;
use App\Models\HeroSetting;
use App\Models\ServiceCategory;
use App\Models\SiteSetting;
use App\Support\AdminResources;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminCrudTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        Storage::fake('public');

        $this->admin = Admin::create([
            'name' => 'Test Admin',
            'email' => 'admin-crud@example.test',
            'password' => 'password',
        ]);

        $this->actingAs($this->admin, 'admin');
    }

    public function test_all_configured_admin_resources_support_crud(): void
    {
        foreach (AdminResources::all() as $key => $resource) {
            $this->get(route('admin.'.$resource['route'].'.index'))->assertOk();
            $this->get(route('admin.'.$resource['route'].'.create'))->assertOk();

            $createPayload = $this->payloadFor($key, $resource, 'create');

            $this
                ->post(route('admin.'.$resource['route'].'.store'), $createPayload)
                ->assertRedirect(route('admin.'.$resource['route'].'.index'))
                ->assertSessionHasNoErrors();

            $item = $resource['model']::latest('id')->firstOrFail();

            $this->get(route('admin.'.$resource['route'].'.edit', $item->id))->assertOk();
            $this
                ->get(route('admin.'.$resource['route'].'.show', $item->id))
                ->assertRedirect(route('admin.'.$resource['route'].'.edit', $item->id));

            $updatePayload = $this->payloadFor($key, $resource, 'update');

            $this
                ->put(route('admin.'.$resource['route'].'.update', $item->id), $updatePayload)
                ->assertRedirect(route('admin.'.$resource['route'].'.index'))
                ->assertSessionHasNoErrors();

            $item->refresh();
            $this->assertUpdatedValuePersisted($item, $resource, $updatePayload);

            $this
                ->delete(route('admin.'.$resource['route'].'.destroy', $item->id))
                ->assertRedirect(route('admin.'.$resource['route'].'.index'))
                ->assertSessionHasNoErrors();

            $this->assertDatabaseMissing($item->getTable(), ['id' => $item->id]);
        }
    }

    public function test_settings_forms_render_and_update(): void
    {
        $this->get(route('admin.site-settings.edit'))->assertOk();
        $this
            ->put(route('admin.site-settings.update'), [
                'site_name' => 'Greenland Compliance Test',
                'meta_title' => 'Greenland Meta Test',
                'meta_description' => 'Meta description test',
                'primary_phone' => '+8801000000000',
                'primary_email' => 'info@example.test',
                'address' => 'Dhaka, Bangladesh',
                'business_hours' => 'Sun-Thu 9:00-18:00',
                'footer_description' => 'Footer description test',
                'footer_cta_title' => 'Footer CTA',
                'footer_cta_text' => 'Footer CTA text',
                'footer_cta_button_label' => 'Contact',
                'footer_cta_button_href' => '/contact',
                'map_embed_url' => 'https://example.test/map',
                'copyright_text' => 'Copyright test',
                'how_we_work_video_url' => 'https://example.test/video',
                'logo_path' => UploadedFile::fake()->image('logo.jpg', 80, 80),
                'office_image_path' => UploadedFile::fake()->image('office.jpg', 120, 80),
                'company_presentation_file' => UploadedFile::fake()->create('presentation.pdf', 12, 'application/pdf'),
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertSame('Greenland Compliance Test', SiteSetting::firstOrFail()->site_name);
        $this->assertNotNull(SiteSetting::first()->logo_path);
        $this->assertNotNull(SiteSetting::first()->company_presentation_file);

        $this->get(route('admin.hero-settings.edit'))->assertOk();
        $this
            ->put(route('admin.hero-settings.update'), [
                'headline_line1' => 'Dynamic',
                'headline_line2' => 'Hero',
                'paragraph' => 'Hero paragraph',
                'cta1_label' => 'Contact',
                'cta1_href' => '/contact',
                'cta2_label' => 'Services',
                'cta2_href' => '/services',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertSame('Dynamic', HeroSetting::firstOrFail()->headline_line1);

        $this->get(route('admin.about-settings.edit'))->assertOk();
        $this
            ->put(route('admin.about-settings.update'), [
                'banner_label' => 'About Test',
                'hero_heading_line1' => 'About',
                'hero_heading_line2' => 'Hero',
                'hero_paragraph' => 'About paragraph',
                'hero_image_path' => UploadedFile::fake()->image('about.jpg', 120, 80),
                'hero_cta_label' => 'Quote',
                'hero_cta_href' => '/contact',
                'overview_paragraph1' => 'Overview one',
                'overview_paragraph2' => 'Overview two',
                'overview_callout' => 'Callout',
                'mission_heading' => 'Mission',
                'mission_intro' => 'Mission intro',
                'approach_intro1' => 'Approach one',
                'approach_intro2' => 'Approach two',
                'footer_cta_text' => 'About footer',
                'footer_cta_button_label' => 'Contact',
                'footer_cta_button_href' => '/contact',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertSame('About Test', AboutSetting::firstOrFail()->banner_label);
        $this->assertNotNull(AboutSetting::first()->hero_image_path);
    }

    public function test_contact_message_inbox_actions_work(): void
    {
        $message = ContactMessage::create([
            'first_name' => 'Contact',
            'email' => 'contact@example.test',
            'phone' => '+8801000000000',
            'message' => 'Message body',
            'is_read' => false,
        ]);

        $this->get(route('admin.contact-messages.index'))->assertOk();
        $this->get(route('admin.contact-messages.show', $message->id))->assertOk();
        $this->assertTrue($message->fresh()->is_read);

        $message->update(['is_read' => false]);

        $this
            ->patch(route('admin.contact-messages.markRead', $message->id))
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertTrue($message->fresh()->is_read);

        $this
            ->delete(route('admin.contact-messages.destroy', $message->id))
            ->assertRedirect(route('admin.contact-messages.index'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('contact_messages', ['id' => $message->id]);
    }

    public function test_reorder_endpoint_updates_sort_order(): void
    {
        $first = ServiceCategory::create(['label' => 'First', 'slug' => 'first', 'sort_order' => 1]);
        $second = ServiceCategory::create(['label' => 'Second', 'slug' => 'second', 'sort_order' => 2]);

        $this
            ->post(route('admin.reorder', 'service-categories'), [
                'orders' => [
                    $first->id => 20,
                    $second->id => 10,
                ],
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertSame(20, $first->fresh()->sort_order);
        $this->assertSame(10, $second->fresh()->sort_order);
    }

    private function payloadFor(string $key, array $resource, string $suffix): array
    {
        $payload = [];

        foreach ($resource['fields'] as $field) {
            $payload[$field] = $this->valueFor($field, $key, $suffix);
        }

        foreach (array_keys($resource['images'] ?? []) as $field) {
            $payload[$field] = UploadedFile::fake()->image($field.'-'.$suffix.'.jpg', 80, 80);
        }

        foreach (array_keys($resource['files'] ?? []) as $field) {
            $payload[$field] = UploadedFile::fake()->create($field.'-'.$suffix.'.pdf', 10, 'application/pdf');
        }

        return $payload;
    }

    private function valueFor(string $field, string $key, string $suffix): mixed
    {
        $base = Str::slug($key.'-'.$field.'-'.$suffix);

        return match ($field) {
            'service_category_id' => $this->serviceCategory()->id,
            'case_study_category_id' => $this->caseStudyCategory()->id,
            'email' => $base.'@example.test',
            'href', 'profile_slug' => '/'.$base,
            'url', 'external_url' => 'https://example.test/'.$base,
            'slug' => $base,
            'year' => '2026',
            'sort_order' => $suffix === 'create' ? 10 : 20,
            'is_active' => '1',
            'published_at' => '2026-05-17',
            'price' => '$100',
            'page' => 'about',
            'format' => 'PDF',
            'language' => 'English',
            'location' => 'header',
            'platform' => 'LinkedIn',
            'icon' => 'Building2',
            default => Str::title(str_replace('_', ' ', $field)).' '.$suffix,
        };
    }

    private function serviceCategory(): ServiceCategory
    {
        return ServiceCategory::firstOrCreate(
            ['slug' => 'dependency-service-category'],
            ['label' => 'Dependency Service Category', 'sort_order' => 1]
        );
    }

    private function caseStudyCategory(): CaseStudyCategory
    {
        return CaseStudyCategory::firstOrCreate(
            ['slug' => 'dependency-case-study-category'],
            ['name' => 'Dependency Case Study Category', 'sort_order' => 1]
        );
    }

    private function assertUpdatedValuePersisted($item, array $resource, array $payload): void
    {
        $field = collect($resource['fields'])->first(fn (string $field) => ! str_ends_with($field, '_id') && $field !== 'is_active');

        if ($field) {
            $this->assertSame((string) $payload[$field], (string) $item->{$field});
        }
    }
}
