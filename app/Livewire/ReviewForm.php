<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Mail\ReviewGiftMail;
use App\Models\PageView;
use App\Models\ReviewSubmission;
use App\Models\ReviewToken;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

final class ReviewForm extends Component
{
    public ?int $tokenId = null;

    public string $customerName  = '';
    public string $customerEmail = '';
    public int    $rating        = 0;
    public string $comment       = '';

    public bool    $submitted  = false;
    public ?string $giftCode   = null;

    public bool   $tokenInvalid  = false;
    public string $tokenErrorMsg = '';

    public function mount(string $token): void
    {
        $reviewToken = ReviewToken::where('token', $token)
            ->with('campaign')
            ->first();

        if (! $reviewToken) {
            $this->tokenInvalid  = true;
            $this->tokenErrorMsg = 'Este enlace no es válido.';
            return;
        }

        if ($reviewToken->used) {
            $this->tokenInvalid  = true;
            $this->tokenErrorMsg = 'Este enlace ya fue utilizado. Pide uno nuevo a tu mesero.';
            return;
        }

        if ($reviewToken->expires_at->isPast()) {
            $this->tokenInvalid  = true;
            $this->tokenErrorMsg = 'Este enlace ha expirado. Pide uno nuevo a tu mesero.';
            return;
        }

        if (! $reviewToken->campaign->isAvailable()) {
            $this->tokenInvalid  = true;
            $this->tokenErrorMsg = 'Esta campaña ya no está disponible.';
            return;
        }

        $this->tokenId = $reviewToken->id;

        PageView::record('resena', 'resena:' . $reviewToken->campaign->slug, 'Reseña: ' . $reviewToken->campaign->name);
    }

    public function setRating(int $rating): void
    {
        $this->rating = $rating;
    }

    protected function rules(): array
    {
        return [
            'customerName'  => ['required', 'string', 'min:2', 'max:100'],
            'customerEmail' => ['required', 'email', 'max:255'],
            'rating'        => ['required', 'integer', 'min:1', 'max:5'],
            'comment'       => ['nullable', 'string', 'max:1000'],
        ];
    }

    protected function messages(): array
    {
        return [
            'customerName.required'  => 'Por favor escribe tu nombre.',
            'customerEmail.required' => 'El correo es obligatorio para enviarte tu regalo.',
            'customerEmail.email'    => 'Ingresa un correo válido.',
            'rating.min'             => 'Selecciona cuántas estrellas merece ' . siteName() . '.',
        ];
    }

    public function submit(): void
    {
        if ($this->tokenInvalid || ! $this->tokenId) return;

        $this->validate();

        $reviewToken = ReviewToken::with('campaign')->find($this->tokenId);

        if (! $reviewToken || ! $reviewToken->isValid()) {
            $this->tokenInvalid  = true;
            $this->tokenErrorMsg = 'Este enlace ya no es válido. Pide uno nuevo a tu mesero.';
            return;
        }

        $campaign = $reviewToken->campaign;
        $giftCode = $campaign->generateGiftCode();

        ReviewSubmission::create([
            'review_campaign_id' => $campaign->id,
            'customer_name'      => $this->customerName,
            'customer_email'     => $this->customerEmail,
            'rating'             => $this->rating,
            'comment'            => $this->comment ?: null,
            'gift_code'          => $giftCode,
            'ip_address'         => request()->ip(),
        ]);

        $reviewToken->markUsed();

        try {
            Mail::to($this->customerEmail)->send(new ReviewGiftMail(
                ReviewSubmission::where('gift_code', $giftCode)->latest()->first()
            ));
        } catch (\Throwable) {
        }

        $this->giftCode  = $giftCode;
        $this->submitted = true;
    }

    public function render(): \Illuminate\View\View
    {
        $campaign = null;

        if (! $this->tokenInvalid && $this->tokenId) {
            $campaign = ReviewToken::with('campaign')->find($this->tokenId)?->campaign;
        }

        return view('livewire.review-form', compact('campaign'))
            ->layout('components.layouts.review');
    }
}
