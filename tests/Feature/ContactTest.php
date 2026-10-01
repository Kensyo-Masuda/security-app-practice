<?php

namespace Tests\Feature;

use Tests\TestCase;

class ContactTest extends TestCase
{
    public function test_contact_form_is_displayed(): void
    {
        $this->get('/contact')
            ->assertOk()
            ->assertSee('お問い合わせ');
    }

    public function test_valid_submission_displays_thanks_with_escaped_input(): void
    {
        $this->post('/contact', [
            'name' => '<script>alert(1)</script>',
            'email' => 'test@example.com',
            'message' => 'お問い合わせ内容です。',
        ])
            ->assertOk()
            ->assertViewIs('contact.thanks')
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertSee('test@example.com')
            ->assertSee('お問い合わせ内容です。');
    }

    public function test_invalid_submission_returns_validation_errors(): void
    {
        $this->post('/contact', [
            'name' => '',
            'email' => 'invalid-email',
            'message' => '',
        ])->assertSessionHasErrors(['name', 'email', 'message']);
    }
}