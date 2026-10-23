@extends('emails.layouts.base')

@php
    $couleurs = [
        'success' => '#046d38',
        'danger'  => '#c0392b',
        'warning' => '#d68910',
        'info'    => '#1f78b4',
        'primary' => '#033f1f',
    ];
    $accent = $couleurs[$couleur ?? 'primary'] ?? '#033f1f';
@endphp

@section('content')
    <h2 style="margin:0 0 18px; font-size:18px; color:{{ $accent }};">
        {{ $titre }}
    </h2>

    <p style="margin:0 0 14px; font-size:14px; color:#333;">{{ $salutation }}</p>

    <p style="margin:0 0 16px; font-size:14px; color:#333; line-height:1.6;">
        {{ $introduction }}
    </p>

    @if (!empty($details))
        <table width="100%" cellpadding="0" cellspacing="0"
               style="border-collapse:collapse; margin:20px 0; background:#f8faf9; border-left:4px solid {{ $accent }};">
            @foreach ($details as $libelle => $valeur)
                <tr>
                    <td style="padding:8px 14px; font-size:13px; font-weight:bold; color:#033f1f; width:40%; vertical-align:top;">
                        {{ $libelle }}
                    </td>
                    <td style="padding:8px 14px; font-size:13px; color:#333; white-space:pre-line;">{{ $valeur }}</td>
                </tr>
            @endforeach
        </table>
    @endif

    @foreach ($lignes ?? [] as $ligne)
        <p style="margin:0 0 12px; font-size:14px; color:#333; line-height:1.6;">{{ $ligne }}</p>
    @endforeach

    @if (!empty($actionUrl))
        <div style="text-align:center; margin:30px 0 10px;">
            <a href="{{ $actionUrl }}"
               style="background:#033f1f; color:#ffffff; padding:13px 28px; text-decoration:none;
                      border-radius:6px; font-weight:bold; font-size:14px; display:inline-block;">
                {{ $actionTexte ?? 'Consulter sur le SI-RH' }}
            </a>
        </div>
        <p style="margin:8px 0 0; font-size:11px; color:#999; text-align:center; word-break:break-all;">
            Si le bouton ne fonctionne pas : {{ $actionUrl }}
        </p>
    @endif

    <p style="margin:28px 0 0; font-size:14px; color:#333;">
        Cordialement,<br>
        <strong style="color:#033f1f;">La Direction des Ressources Humaines</strong><br>
        <span style="color:#666; font-size:12px;">YAKO AFRICA ASSURANCES VIE</span>
    </p>
@endsection
