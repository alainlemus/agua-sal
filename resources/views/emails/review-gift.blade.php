<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tu regalo — {{ siteName() }}</title>
</head>
<body style="margin:0;padding:0;background:#1c1c1c;font-family:'Inter',Arial,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#1c1c1c;padding:32px 16px;">
        <tr>
            <td align="center">
                <table width="100%" style="max-width:560px;background:#242424;border-radius:12px;overflow:hidden;">

                    {{-- Header --}}
                    <tr>
                        <td style="background:#E52B2B;padding:28px 32px;text-align:center;">
                            <img src="{{ asset('storage/images/logo.png') }}" alt="{{ siteName() }}" width="120" style="display:block;margin:0 auto 8px;height:auto;">
                            <h1 style="margin:8px 0 0;color:#fff;font-size:26px;font-weight:900;letter-spacing:1px;">
                                {{ strtoupper(siteName()) }}
                            </h1>
                            <p style="margin:4px 0 0;color:#ffdddd;font-size:13px;letter-spacing:2px;text-transform:uppercase;">
                                {{ siteInfo()?->address ?? '' }}
                            </p>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding:32px;">
                            <p style="margin:0 0 8px;color:#aaa;font-size:14px;">
                                Hola, <strong style="color:#fff;">{{ $submission->customer_name }}</strong> 👋
                            </p>
                            <h2 style="margin:0 0 16px;color:#f97316;font-size:22px;font-weight:800;">
                                ¡Gracias por tu reseña!
                            </h2>
                            <p style="margin:0 0 24px;color:#ccc;font-size:15px;line-height:1.6;">
                                Valoramos muchísimo tu opinión. Como agradecimiento, tienes un regalo
                                esperándote en tu próxima visita:
                            </p>

                            {{-- Gift box --}}
                            <table width="100%" cellpadding="0" cellspacing="0" style="background:#1c1c1c;border:2px solid #E52B2B;border-radius:10px;margin-bottom:24px;">
                                <tr>
                                    <td style="padding:20px 24px;text-align:center;">
                                        <p style="margin:0 0 6px;font-size:28px;">🎁</p>
                                        <p style="margin:0 0 4px;color:#f97316;font-size:18px;font-weight:800;">
                                            {{ $submission->campaign->gift_title }}
                                        </p>
                                        @if($submission->campaign->gift_description)
                                        <p style="margin:0 0 12px;color:#aaa;font-size:13px;">
                                            {{ $submission->campaign->gift_description }}
                                        </p>
                                        @endif
                                        <p style="margin:12px 0 4px;color:#888;font-size:11px;letter-spacing:2px;text-transform:uppercase;">
                                            Tu código único
                                        </p>
                                        <p style="margin:0;font-size:32px;font-weight:900;letter-spacing:6px;color:#fff;font-family:'Courier New',monospace;">
                                            {{ $submission->gift_code }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0 0 24px;color:#ccc;font-size:14px;line-height:1.6;">
                                Muestra este correo con tu código al llegar al restaurante y nuestro equipo
                                registrará que tu regalo ha sido cobrado.
                                <br><br>
                                <strong style="color:#f97316;">¡Solo válido una vez!</strong> Asegúrate de presentarlo antes de pedir tu orden.
                            </p>

                            {{-- Stars --}}
                            <p style="margin:0;text-align:center;font-size:24px;">
                                @for($i = 1; $i <= 5; $i++)
                                    {{ $i <= $submission->rating ? '⭐' : '☆' }}
                                @endfor
                            </p>
                            <p style="margin:4px 0 0;text-align:center;color:#888;font-size:12px;">
                                Tu calificación: {{ $submission->rating }}/5
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background:#1c1c1c;padding:16px 32px;border-top:1px solid #333;text-align:center;">
                            <p style="margin:0;color:#555;font-size:12px;">
                                {{ siteName() }}<br>
                                Este código fue generado el {{ now()->format('d/m/Y') }} y es válido solo para <strong style="color:#888;">{{ $submission->customer_email }}</strong>
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
