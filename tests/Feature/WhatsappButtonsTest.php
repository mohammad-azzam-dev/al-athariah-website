<?php

namespace Tests\Feature;

use Tests\TestCase;

class WhatsappButtonsTest extends TestCase
{
    public function test_home_buttons_open_whatsapp_when_a_number_is_set(): void
    {
        config(['athariyah.whatsapp.number' => '966500000000']);

        $html = $this->get('/')->assertOk()->getContent();

        $this->assertSame(2, substr_count($html, 'href="https://wa.me/966500000000?text='));
        $this->assertStringContainsString('ابدأ رحلتك — تواصل معنا عبر واتساب', $html);
        $this->assertStringContainsString('كيف أسجّل؟ — تواصل معنا عبر واتساب', $html);
    }

    public function test_home_buttons_fall_back_to_their_pages_without_a_number(): void
    {
        config(['athariyah.whatsapp.number' => '']);

        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringNotContainsString('wa.me', $html);
        $this->assertStringContainsString('href="'.route('path').'">ابدأ رحلتك</a>', $html);
        $this->assertStringContainsString('href="'.route('register').'">كيف أسجّل؟</a>', $html);
    }

    public function test_inquiry_form_sends_to_whatsapp_when_a_number_is_set(): void
    {
        config(['athariyah.whatsapp.number' => '966500000000']);

        $this->get('/register')->assertOk()
            ->assertSee('data-wa="966500000000"', false)
            ->assertSee('إرسال عبر واتساب')
            ->assertSee('id="inquiryWa"', false);
    }

    public function test_inquiry_form_shows_a_copy_without_a_number(): void
    {
        config(['athariyah.whatsapp.number' => '']);

        $this->get('/register')->assertOk()
            ->assertDontSee('data-wa=', false)
            ->assertSee('تجهيز الاستفسار')
            ->assertDontSee('إرسال عبر واتساب');
    }
}
