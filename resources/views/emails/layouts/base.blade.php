<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'YAKO AFRICA' }}</title>
</head>
<body style="margin:0; padding:0; background:#f4f6f8; font-family: Arial, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f8; padding: 24px 0;">
        <tr>
            <td align="center">

                <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 620px; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">

                    {{-- HEADER --}}
                    <tr>
                        <td style="background: linear-gradient(135deg, #033f1f 0%, #046d38 100%); padding: 24px; text-align: center;">
                            <h1 style="color: #fff; margin: 0; font-size: 20px; font-weight: 700;">
                                {{ $headerTitle ?? 'YAKO AFRICA ASSURANCES VIE' }}
                            </h1>
                            @if (!empty($headerSubtitle))
                                <p style="color: #b5e48c; margin: 6px 0 0; font-size: 13px;">
                                    {{ $headerSubtitle }}
                                </p>
                            @endif
                        </td>
                    </tr>

                    {{-- BODY --}}
                    <tr>
                        <td style="padding: 30px;">
                            {{-- Compatible @extends/@section ET GenericMail (contentView) --}}
                            @if (!empty($contentView))
                                @include($contentView, ['contentView' => null])
                            @else
                                @yield('content')
                            @endif
                        </td>
                    </tr>

                    {{-- FOOTER --}}
                    <tr>
                        <td style="background: #f8f9fa; padding: 16px; text-align: center; border-top: 1px solid #e0e0e0;">
                            <p style="margin: 0; font-size: 11px; color: #666;">
                                © {{ date('Y') }} YAKO AFRICA ASSURANCES VIE — Tous droits réservés.<br>
                                <span style="color: #999;">
                                    Cet email a été envoyé automatiquement, merci de ne pas y répondre.
                                </span>
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>
