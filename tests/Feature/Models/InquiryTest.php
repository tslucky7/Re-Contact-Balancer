<?php

namespace Tests\Feature\Models;

use App\Enums\InquiryStatus;
use App\Models\Inquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class InquiryTest extends TestCase
{
    use RefreshDatabase;

    public function test_factory_creates_an_inquiry(): void
    {
        $inquiry = Inquiry::factory()->create();

        $this->assertDatabaseHas('inquiries', [
            'id' => $inquiry->id,
            'request_id' => $inquiry->request_id,
            'status' => 'pending',
        ]);
    }

    public function test_status_is_cast_to_the_enum(): void
    {
        $inquiry = Inquiry::factory()->create();
        $fresh = $inquiry->fresh();

        $this->assertSame(InquiryStatus::Pending, $fresh->status);
    }

    public function test_all_writable_columns_are_mass_assignable(): void
    {
        $requestId = (string) Str::ulid();

        $inquiry = Inquiry::create([
            'request_id' => $requestId,
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'subject' => 'テスト件名',
            'message' => 'テスト本文です。',
            'status' => InquiryStatus::Pending,
            'backlog_issue_id' => 12345,
            'backlog_issue_key' => 'TEST-1',
            'error_message' => 'テストエラー',
        ]);
        $fresh = $inquiry->fresh();

        $this->assertSame($requestId, $fresh->request_id);
        $this->assertSame('テスト太郎', $fresh->name);
        $this->assertSame('test@example.com', $fresh->email);
        $this->assertSame('テスト件名', $fresh->subject);
        $this->assertSame('テスト本文です。', $fresh->message);
        $this->assertSame(InquiryStatus::Pending, $fresh->status);
        $this->assertSame(12345, $fresh->backlog_issue_id);
        $this->assertSame('TEST-1', $fresh->backlog_issue_key);
        $this->assertSame('テストエラー', $fresh->error_message);
    }
}
