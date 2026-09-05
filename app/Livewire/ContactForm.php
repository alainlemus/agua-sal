<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\ContactSubmission;
use Livewire\Component;

final class ContactForm extends Component
{
    public array $blockData = [];
    public string $pageSlug = 'home';

    public array $formValues = [];
    public bool $submitted = false;
    public string $successMessage = '';

    public int $captchaA = 0;
    public int $captchaB = 0;
    public ?int $captchaAnswer = null;

    public function mount(array $blockData = [], string $pageSlug = 'home'): void
    {
        $this->blockData = $blockData;
        $this->pageSlug = $pageSlug;

        $this->captchaA = random_int(1, 9);
        $this->captchaB = random_int(1, 9);

        $fields = $blockData['fields'] ?? [
            ['type' => 'text', 'name' => 'name', 'label' => 'Nombre', 'required' => true],
            ['type' => 'email', 'name' => 'email', 'label' => 'Correo electrónico', 'required' => true],
            ['type' => 'tel', 'name' => 'phone', 'label' => 'Teléfono', 'required' => false],
            ['type' => 'textarea', 'name' => 'message', 'label' => 'Mensaje', 'required' => true],
        ];

        foreach ($fields as $i => $field) {
            $this->formValues[$i] = '';
        }
    }

    public function submit(): void
    {
        if ($this->blockData['show_captcha'] ?? true) {
            if ($this->captchaAnswer !== $this->captchaA + $this->captchaB) {
                $this->addError('captchaAnswer', 'Respuesta incorrecta');
                $this->regenerateCaptcha();
                return;
            }
        }

        $fields = $this->blockData['fields'] ?? [];

        $rules = [];
        $attributes = [];

        foreach ($fields as $i => $field) {
            $type = $field['type'] ?? 'text';
            $fieldRules = [($field['required'] ?? true) ? 'required' : 'nullable', 'string'];

            if ($type === 'email') {
                $fieldRules[] = 'email';
            }

            $fieldRules[] = $type === 'textarea' ? 'max:5000' : 'max:255';

            $rules["formValues.{$i}"] = $fieldRules;
            $attributes["formValues.{$i}"] = $field['label'] ?? 'Campo';
        }

        $this->validate($rules, attributes: $attributes);

        $data = [];

        foreach ($fields as $i => $field) {
            $data[$field['name']] = $this->formValues[$i] ?? '';
        }

        ContactSubmission::create([
            'fields_data' => $data,
        ]);

        $this->submitted = true;
        $this->successMessage = $this->blockData['success_message'] ?? '¡Mensaje enviado! Te responderemos pronto. 🔥';

        $this->formValues = [];
        $this->regenerateCaptcha();
    }

    public function regenerateCaptcha(): void
    {
        $this->captchaA = random_int(1, 9);
        $this->captchaB = random_int(1, 9);
        $this->captchaAnswer = null;
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.contact-form');
    }
}