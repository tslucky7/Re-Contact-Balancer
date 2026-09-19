<?php

namespace Tests\Feature\Inquiries;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreInquiryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * request_id と id はサーバ側で採番されるためテストからは検証しない。
     */
    public function test_valid_input_is_stored_as_a_pending_inquiry(): void
    {
        $inquiry = $this->validInquiry();

        $response = $this
            ->from('/')
            ->post(
                route('inquiries.store'),
                $inquiry
            );

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('inquiries.complete'));

        $this->assertDatabaseHas('inquiries', [
            'name' => $inquiry['name'],
            'email' => $inquiry['email'],
            'subject' => $inquiry['subject'],
            'message' => $inquiry['message'],
            'status' => 'pending',
        ]);
    }

    /**
     * name が空の場合は保存されず、直前の画面へ差し戻される。
     */
    public function test_invalid_input_is_not_stored(): void
    {
        $invalidInquiry = [...$this->validInquiry(), 'name' => ''];

        $response = $this
            ->from('/')
            ->post(
                route('inquiries.store'),
                $invalidInquiry
            );

        $response
            ->assertSessionHasErrors('name')
            ->assertRedirect('/');

        $this->assertDatabaseEmpty('inquiries');
    }

    /**
     * 送信後のリダイレクト先で完了画面が表示される。
     *
     * complete() の入場条件はフラッシュのため、
     * 送信とリダイレクト先の取得を続けて行わないと検証できない。
     */
    public function test_complete_page_is_shown_after_store(): void
    {
        $this->post(route('inquiries.store'), $this->validInquiry())
            ->assertRedirect(route('inquiries.complete'));

        $this->get(route('inquiries.complete'))
            ->assertOk();
    }

    /**
     * @return array{name: string, email: string, subject: string, message: string}
     */
    private function validInquiry(): array
    {
        return [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'subject' => 'テスト件名',
            'message' => 'テスト本文です。',
        ];
    }
}
