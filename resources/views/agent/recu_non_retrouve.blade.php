<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Récépissé de déclaration de perte</title>
    <style>
        @page { 
            margin: 1.5cm; 
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11pt;
            color: #222;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .header {
            border-bottom: 3px solid #006A36;
            padding-bottom: 12px;
            margin-bottom: 25px;
            text-align: center;
        }
        .flag { 
            width: 75px; 
            height: 60px;
            margin: 0 auto 10px auto; 
        }
        .title { 
            font-size: 16pt; 
            font-weight: bold; 
            color: #006A36; 
            letter-spacing: 1px;
            margin-bottom: 2px;
        }
        .subtitle { 
            font-size: 13pt; 
            font-weight: bold; 
            color: #D21034; 
            margin-bottom: 4px;
            text-transform: uppercase;
        }
        .devise { 
            font-size: 10pt; 
            color: #555; 
            font-style: italic; 
            margin-bottom: 8px;
        }
        .ministere { 
            font-size: 9pt; 
            color: #444; 
            font-weight: 500; 
            line-height: 1.4;
            border-top: 1px solid #eee; 
            padding-top: 8px; 
            margin-top: 4px;
        }
        .recu-number { 
            text-align: right; 
            font-size: 10pt; 
            margin-bottom: 20px; 
        }
        .recu-number .badge {
            background-color: #f0f0f0;
            padding: 5px 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .content { 
            margin-top: 10px; 
        }
        .info-block { 
            border: 1px solid #ddd; 
            border-radius: 6px; 
            padding: 15px; 
            background: #fafafa; 
            margin-bottom: 20px; 
        }
        .info-block h3 { 
            font-size: 11pt; 
            font-weight: bold; 
            color: #006A36; 
            text-transform: uppercase; 
            margin: 0 0 10px 0; 
            border-bottom: 1px solid #006A36; 
            padding-bottom: 5px; 
        }
        /* Remplacement du flexbox (parfois mal géré par DomPDF) par un tableau propre */
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 5px 0;
            vertical-align: top;
        }
        .info-label { 
            width: 35%; 
            font-weight: bold; 
            color: #444; 
        }
        .info-value { 
            width: 65%;
            color: #222; 
        }
        .instruction-box {
            background: #fff8f8;
            border-left: 5px solid #D21034;
            border-top: 1px solid #f2dede;
            border-right: 1px solid #f2dede;
            border-bottom: 1px solid #f2dede;
            padding: 15px;
            margin: 25px 0;
            border-radius: 0 4px 4px 0;
        }
        .instruction-box .box-title { 
            color: #D21034; 
            font-weight: bold;
            font-size: 11pt;
            margin-bottom: 6px;
        }
        .signature-section {
            margin-top: 35px;
            width: 100%;
        }
        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }
        .signature-table td {
            width: 50%;
            font-size: 9pt;
            color: #444;
            vertical-align: top;
        }
        .footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8pt;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .footer .logo-text { 
            font-weight: bold; 
            color: #006A36; 
        }
        .verification-link {
            margin-top: 5px;
            font-size: 7.5pt;
            color: #888;
            font-family: monospace;
        }
    </style>
</head>
<body>

@php
    // Génération du numéro de récépissé unique
    $recuNumber = 'REC-' . date('Ymd') . '-' . str_pad($perte->id, 6, '0', STR_PAD_LEFT);

    // Détermination du lieu de renouvellement (Structure Switch compatible PHP 7.x)
    $lieuRenouvellement = '';
    switch ($perte->type_piece) {
        case 'Carte d\'identité (CNI)':
        case 'Carte d\'identité nationale':
            $lieuRenouvellement = 'Commissariat de police ou service de la Documentation Nationale le plus proche';
            break;
        case 'Passeport':
            $lieuRenouvellement = 'Direction de l\'Immigration et de l\'Émigration (Lomé) ou bureau régional';
            break;
        case 'Permis de conduire':
            $lieuRenouvellement = 'Direction des Transports Routiers (DTR)';
            break;
        case 'Carte d\'électeur':
            $lieuRenouvellement = 'Commission Électorale Nationale Indépendante (CENI) ou démembrement local';
            break;
        case 'Acte de naissance':
            $lieuRenouvellement = 'Mairie ou Bureau de l\'état civil du lieu de naissance';
            break;
        case 'Certificat de nationalité':
            $lieuRenouvellement = 'Direction de la Sceau et de la Nationalité ou tribunal compétent';
            break;
        default:
            $lieuRenouvellement = 'Service administratif compétent (Police, Mairie ou Préfecture)';
    }
@endphp

<div class="header">
    <div class="flag">
        <svg viewBox="0 0 5 4" xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">
            <rect width="5" height="0.8" y="0" fill="#006A36"/>
            <rect width="5" height="0.8" y="0.8" fill="#FFCB00"/>
            <rect width="5" height="0.8" y="1.6" fill="#006A36"/>
            <rect width="5" height="0.8" y="2.4" fill="#FFCB00"/>
            <rect width="5" height="0.8" y="3.2" fill="#006A36"/>
            <rect width="1.9" height="2.4" fill="#D21034"/>
            <polygon points="0.95,0.38 1.07,0.76 1.47,0.76 1.16,0.99 1.28,1.37 0.95,1.14 0.62,1.37 0.74,0.99 0.43,0.76 0.83,0.76" fill="#FFFFFF"/>
        </svg>
    </div>
    <div class="title">RÉPUBLIQUE TOGOLAISE</div>
    <div class="devise">« Travail – Liberté – Patrie »</div>
    <div class="subtitle">Récépissé de déclaration de perte</div>
    
    <div class="ministere">
        Ministère de l'Administration Territoriale, de la Décentralisation et du Développement des Territoires<br>
        <strong>Direction Générale de la Documentation Nationale (DGDN)</strong>
    </div>
</div>

<div class="recu-number">
    <span class="badge"><strong>N° Récépissé :</strong> {{ $recuNumber }}</span>
</div>

<div class="content">
    <div class="info-block">
        <h3>📌 Informations du déclarant</h3>
        <table class="info-table">
            <tr>
                <td class="info-label">Nom & Prénoms :</td>
                <td class="info-value"><strong>{{ strtoupper($perte->last_name) }} {{ $perte->first_name }}</strong></td>
            </tr>
            <tr>
                <td class="info-label">Téléphone :</td>
                <td class="info-value">{{ $perte->contact }}</td>
            </tr>
            <tr>
                <td class="info-label">Adresse Email :</td>
                <td class="info-value">{{ $perte->email }}</td>
            </tr>
        </table>
    </div>

    <div class="info-block">
        <h3>📄 Détails de la pièce déclarée perdue</h3>
        <table class="info-table">
            <tr>
                <td class="info-label">Type de document :</td>
                <td class="info-value"><strong>{{ $perte->type_piece }}</strong></td>
            </tr>
            <tr>
                <td class="info-label">Numéro de la pièce :</td>
                <td class="info-value">{{ $perte->numero_piece ?? 'Non renseigné' }}</td>
            </tr>
            <tr>
                <td class="info-label">Date de la perte :</td>
                <td class="info-value">{{ \Carbon\Carbon::parse($perte->date_perte)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td class="info-label">Lieu de la perte :</td>
                <td class="info-value">{{ $perte->lieu_perte }}</td>
            </tr>
            <tr>
                <td class="info-label">Circonstances :</td>
                <td class="info-value"><em>{{ $perte->circonstances ?? 'Non renseignées' }}</em></td>
            </tr>
        </table>
    </div>

    <div class="instruction-box">
        <div class="box-title">🔴 Instructions importantes pour le renouvellement</div>
        <p style="margin: 5px 0 10px 0; font-size: 10.5pt;">
            Après vérification croisée dans nos bases de données, votre document <strong>{{ $perte->type_piece }}</strong> n'a pas été retrouvé à ce jour. 
            Ce récépissé vaut attestation de déclaration officielle de perte.
        </p>
        <table class="info-table" style="font-size: 10pt;">
            <tr>
                <td style="width: 30%; font-weight: bold; color: #006A36;">📍 Lieu recommandé :</td>
                <td style="width: 70%;">{{ $lieuRenouvellement }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold; color: #006A36;">📅 Durée de validité :</td>
                <td><strong>30 jours</strong> à compter de la date d'émission ci-dessous.</td>
            </tr>
        </table>
        <p style="margin: 10px 0 0 0; font-size: 9pt; color: #666; font-style: italic;">
            Note : Présentez-vous auprès du service recommandé muni de ce document et de toute autre pièce justificative disponible afin d'engager la procédure de renouvellement.
        </p>
    </div>

    <div class="signature-section">
        <table class="signature-table">
            <tr>
                <td>
                    <strong>Fait le :</strong> {{ date('d/m/Y à H:i') }}<br>
                    <strong>Généré via :</strong> e-Déclaration Togo
                </td>
                <td style="text-align: right; padding-right: 20px;">
                    <strong>Cachet et Signature de l'Autorité :</strong>
                    <div style="margin-top: 40px; border-bottom: 1px dashed #aaa; width: 200px; float: right;"></div>
                </td>
            </tr>
        </table>
    </div>
</div>

<div class="footer">
    <span class="logo-text">e-Déclaration TG</span> – Plateforme Nationale Numérique des Déclarations de Perte<br>
    <span style="font-weight: bold; color: #D21034;">Document Officiel</span> – Toute falsification ou usage de faux expose son auteur à des poursuites judiciaires (Code Pénal Togolais).
    <div class="verification-link">
        Code de vérification en ligne : https://e-declaration.tg/verifier/{{ $recuNumber }}
    </div>
</div>

</body>
</html>