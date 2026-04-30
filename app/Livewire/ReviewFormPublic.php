<?php

namespace App\Livewire;

use App\Mail\ReviewGiftMail;
use App\Models\PageView;
use App\Models\ReviewCampaign;
use App\Models\ReviewSubmission;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ReviewFormPublic extends Component
{
    public ?int $campaignId = null;

    public string $customerName  = '';
    public string $customerEmail = '';
    public int    $rating        = 0;
    public string $comment       = '';

    public bool    $submitted  = false;
    public ?string $giftCode   = null;

    // Estado de error
    public bool   $campaignInvalid  = false;
    public string $campaignErrorMsg = '';

    // El cliente ya dejó reseña en esta campaña (sesión)
    public bool $alreadySubmitted = false;

    public function mount(): void
    {
        $campaign = ReviewCampaign::where('is_active', true)->first();

        if (! $campaign) {
            $this->campaignInvalid  = true;
            $this->campaignErrorMsg = 'No hay ninguna campaña de reseñas activa en este momento. ¡Vuelve pronto!';
            return;
        }

        if (! $campaign->isAvailable()) {
            $this->campaignInvalid  = true;
            $this->campaignErrorMsg = 'Esta campaña ya alcanzó el número máximo de participantes. ¡Gracias a todos!';
            return;
        }

        // Verificar si ya dejó reseña en esta sesión
        $sessionKey = 'review_submitted_campaign_' . $campaign->id;
        if (session()->has($sessionKey)) {
            $this->alreadySubmitted = true;
            $this->campaignId = $campaign->id;
            return;
        }

        $this->campaignId = $campaign->id;

        // Registrar visita
        PageView::record('resena', 'resena-activa:' . $campaign->slug, 'Reseña activa: ' . $campaign->name);
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
        if ($this->campaignInvalid || ! $this->campaignId) return;
        if ($this->alreadySubmitted) return;

        $this->validate();

        $campaign = ReviewCampaign::find($this->campaignId);

        if (! $campaign || ! $campaign->isAvailable()) {
            $this->campaignInvalid  = true;
            $this->campaignErrorMsg = 'Esta campaña ya no está disponible.';
            return;
        }

        // Doble-check sesión (race condition)
        $sessionKey = 'review_submitted_campaign_' . $campaign->id;
        if (session()->has($sessionKey)) {
            $this->alreadySubmitted = true;
            return;
        }

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

        // Marcar en sesión para prevenir duplicados
        session()->put($sessionKey, true);

        // Enviar correo con el regalo
        try {
            Mail::to($this->customerEmail)->send(new ReviewGiftMail(
                ReviewSubmission::where('gift_code', $giftCode)->latest()->first()
            ));
        } catch (\Throwable) {
            // Silent fail — el código igual se muestra en pantalla
        }

        $this->giftCode  = $giftCode;
        $this->submitted = true;
    }

    public function render()
    {
        $campaign = null;

        if ($this->campaignId) {
            $campaign = ReviewCampaign::find($this->campaignId);
        }

        return view('livewire.review-form-public', compact('campaign'))
            ->layout('components.layouts.review');
    }
}
