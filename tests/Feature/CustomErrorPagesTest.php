<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomErrorPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_404_error_page_renders_custom_stukka_template(): void
    {
        $response = $this->get('/halaman-yang-jelas-tidak-ada-random-xyz');

        $response->assertStatus(404);
        $response->assertSee('404');
        $response->assertSee('Halaman Tidak Ditemukan');
        $response->assertSee('STUKKA');
        $response->assertSee('Kembali ke Beranda');
    }

    public function test_custom_error_views_can_be_rendered_directly(): void
    {
        $views = ['errors.404', 'errors.403', 'errors.419', 'errors.500', 'errors.503', 'errors.429'];

        foreach ($views as $view) {
            $rendered = view($view)->render();
            $this->assertStringContainsString('STUKKA', $rendered);
            $this->assertStringContainsString('Kembali ke Beranda', $rendered);
        }
    }
}
