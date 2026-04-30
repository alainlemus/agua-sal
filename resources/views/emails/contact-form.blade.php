<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo mensaje — {{ siteName() }}</title>
    <style>
        body { background: #0f0f0f; margin: 0; padding: 0; font-family: 'Inter', Arial, sans-serif; color: #e5e7eb; }
        .container { max-width: 600px; margin: 32px auto; background: #1c1c1c; border-radius: 12px; overflow: hidden; border: 1px solid #2a2a2a; }
        .header { background: #E52B2B; padding: 28px 32px; }
        .header h1 { margin: 0; color: #fff; font-size: 22px; font-weight: 800; letter-spacing: 2px; text-transform: uppercase; }
        .header p { margin: 6px 0 0; color: rgba(255,255,255,0.8); font-size: 13px; }
        .body { padding: 32px; }
        .badge { display: inline-block; background: #E52B2B20; border: 1px solid #E52B2B40; color: #E52B2B; font-size: 11px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; padding: 4px 12px; border-radius: 999px; margin-bottom: 20px; }
        .field { margin-bottom: 20px; padding: 16px; background: #111; border-radius: 8px; border-left: 3px solid #E52B2B; }
        .field-label { font-size: 11px; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 6px; }
        .field-value { font-size: 15px; color: #f3f4f6; line-height: 1.6; white-space: pre-wrap; }
        .meta { margin-top: 28px; padding-top: 20px; border-top: 1px solid #2a2a2a; font-size: 12px; color: #6b7280; }
        .meta span { color: #9ca3af; }
        .footer { background: #111; padding: 18px 32px; text-align: center; font-size: 11px; color: #4b5563; border-top: 1px solid #1a1a1a; }
        .btn { display: inline-block; margin-top: 20px; background: #E52B2B; color: #fff; padding: 12px 28px; border-radius: 6px; font-weight: 700; font-size: 13px; letter-spacing: 1px; text-transform: uppercase; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header" style="text-align:center;">
            <img src="{{ asset('storage/images/logo.png') }}" alt="{{ siteName() }}" width="100" style="display:block;margin:0 auto 10px;height:auto;">
            <h1>📬 Nuevo Mensaje Recibido</h1>
            <p>{{ $submission->form_title ?? 'Formulario de Contacto' }} — {{ siteName() }}</p>
        </div>
        <div class="body">
            <div class="badge">Formulario: {{ $submission->form_page_slug ?? 'sitio web' }}</div>

            @foreach($submission->fields_data as $field)
                <div class="field">
                    <div class="field-label">{{ $field['label'] ?? 'Campo' }}</div>
                    <div class="field-value">{{ $field['value'] ?? '—' }}</div>
                </div>
            @endforeach

            <div class="meta">
                <p>📅 Recibido el: <span>{{ $submission->created_at->format('d/m/Y \a \l\a\s H:i') }}</span></p>
                @if($submission->sender_email)
                    <p>✉️ Email del remitente: <span>{{ $submission->sender_email }}</span></p>
                @endif
                <a href="{{ config('app.url') }}/admin/contact-submissions/{{ $submission->id }}" class="btn">
                    Ver en el Admin
                </a>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ siteName() }} &mdash; Este mensaje fue enviado desde el sitio web.
        </div>
    </div>
</body>
</html>
