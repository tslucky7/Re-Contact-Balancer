<?php

namespace App\Http\Controllers;

use App\Enums\InquiryStatus;
use App\Http\Requests\StoreInquiryRequest;
use App\Models\Inquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

/**
 * 問い合わせフォームの送信を受け付けるコントローラ。
 */
class InquiryController extends Controller
{
    /**
     * # 問い合わせを pending 状態で保存する。
     *
     * - request_id は Backlog 課題・Slack 通知と問い合わせを突き合わせるための受付 ID。
     * - Backlog / Slack への連携は未実装のため、現時点では保存のみを行う。
     */
    public function store(StoreInquiryRequest $request): RedirectResponse
    {
        Inquiry::create([
            ...$request->validated(),
            'request_id' => (string) Str::ulid(),
            'status' => InquiryStatus::Pending,
        ]);

        return back();
    }
}
