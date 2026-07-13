<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Récépissé de déclaration de perte</title>
    <style>
        @page { 
            margin: 1.5cm; 
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10pt;
            color: #222;
            line-height: 1.5;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }
        
        /* Structure de l'En-tête */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .header-table td {
            padding: 0;
            vertical-align: middle;
        }
        
        .republique-title {
            text-align: center;
            font-size: 18pt;
            font-weight: bold;
            color: #006A36;
            text-transform: uppercase;
            padding-bottom: 12px;
            letter-spacing: 2px;
        }
        
        .devise-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .devise-table td {
            vertical-align: middle;
        }
        .flag-cell {
            width: 85px;
            text-align: left;
        }
        .devise-cell {
            text-align: center;
            font-size: 11pt;
            color: #555;
            font-style: italic;
            font-weight: bold;
            padding-right: 85px; /* Compense la largeur du drapeau pour un centrage parfait de la devise */
        }
        
        .document-title {
            text-align: center;
            font-size: 15pt;
            font-weight: bold;
            color: #D21034;
            text-transform: uppercase;
            margin-bottom: 25px;
            letter-spacing: 0.5px;
        }
        
        /* Bloc Ministère & Numéro */
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            border-top: 2px solid #006A36;
            border-bottom: 2px solid #006A36;
            padding: 8px 0;
            margin-bottom: 30px;
        }
        .meta-table td {
            vertical-align: middle;
            padding: 6px 0;
        }
        .ministere-cell {
            width: 65%;
            font-size: 8.5pt;
            color: #333;
            line-height: 1.4;
            text-align: left;
        }
        .ministere-cell strong {
            color: #006A36;
        }
        .recu-cell {
            width: 35%;
            text-align: right;
        }
        .badge-recu {
            background-color: #f8f9fa;
            border: 1px solid #006A36;
            padding: 6px 12px;
            font-size: 9.5pt;
            font-weight: bold;
            color: #222;
            display: inline-block;
            border-radius: 3px;
        }

        /* Contenu global */
        .content { 
            margin-top: 10px; 
        }
        .info-block { 
            border: 1px solid #e0e0e0; 
            border-radius: 4px; 
            padding: 15px; 
            background: #fafafa; 
            margin-bottom: 20px; 
        }
        .info-block h3 { 
            font-size: 10.5pt; 
            font-weight: bold; 
            color: #006A36; 
            text-transform: uppercase; 
            margin: 0 0 12px 0; 
            border-bottom: 1px solid #006A36; 
            padding-bottom: 6px; 
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 5px 0;
            vertical-align: top;
        }
        .info-label { 
            width: 30%; 
            font-weight: bold; 
            color: #555; 
        }
        .info-value { 
            width: 70%;
            color: #111; 
        }
        
        /* Boite d'instructions */
        .instruction-box {
            background: #fff9f9;
            border-left: 4px solid #D21034;
            border-top: 1px solid #f9e1e1;
            border-right: 1px solid #f9e1e1;
            border-bottom: 1px solid #f9e1e1;
            padding: 15px;
            margin: 25px 0;
            border-radius: 0 4px 4px 0;
        }
        .instruction-box .box-title { 
            color: #D21034; 
            font-weight: bold;
            font-size: 10.5pt;
            margin-bottom: 8px;
        }
        
        /* Section Signatures */
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
        .signature-table .sig-line {
            display: inline-block;
            width: 180px;
            border-top: 1px dashed #999;
            margin-top: 40px;
            float: right;
        }
        
        /* Pied de page */
        .footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 7.5pt;
            color: #666;
            border-top: 1px solid #e0e0e0;
            padding-top: 12px;
            line-height: 1.4;
        }
        .footer .logo-text { 
            font-weight: bold; 
            color: #006A36; 
        }
        .footer .official-text {
            font-weight: bold;
            color: #D21034;
        }
        .verification-link {
            margin-top: 6px;
            font-size: 7pt;
            color: #888;
            font-family: monospace;
        }
    </style>
</head>
<body>

@php
    $recuNumber = 'REC-' . date('Ymd') . '-' . str_pad($perte->id, 6, '0', STR_PAD_LEFT);
    
    // Détermination du lieu de renouvellement (PHP 7 compatible - sans match())
    $type_piece = $perte->type_piece;
    $lieuRenouvellement = '';
    
    if ($type_piece == 'Carte d\'identité (CNI)' || $type_piece == 'Carte d\'identité nationale') {
        $lieuRenouvellement = 'Commissariat de police ou service de la Documentation Nationale le plus proche';
    } elseif ($type_piece == 'Passeport') {
        $lieuRenouvellement = 'Direction de l\'Immigration et de l\'Émigration (Lomé) ou bureau régional';
    } elseif ($type_piece == 'Permis de conduire') {
        $lieuRenouvellement = 'Direction des Transports Routiers (DTR)';
    } elseif ($type_piece == 'Carte d\'électeur') {
        $lieuRenouvellement = 'Commission Électorale Nationale Indépendante (CENI) ou démembrement local';
    } elseif ($type_piece == 'Acte de naissance') {
        $lieuRenouvellement = 'Mairie ou Bureau de l\'état civil du lieu de naissance';
    } elseif ($type_piece == 'Certificat de nationalité') {
        $lieuRenouvellement = 'Direction du Sceau et de la Nationalité ou tribunal compétent';
    } else {
        $lieuRenouvellement = 'Service administratif compétent (Police, Mairie ou Préfecture)';
    }
@endphp

<!-- ===== EN-TÊTE ===== -->
<table class="header-table">
    <tr>
        <td class="republique-title">RÉPUBLIQUE TOGOLAISE</td>
    </tr>
</table>

<table class="devise-table">
    <tr>
        <td class="flag-cell">
            <svg xmlns="http://www.w3.org/2000/svg" width="80" height="56" viewBox="0 0 500 350">
                <rect width="500" height="70" y="0" fill="#006A36"/>
                <rect width="500" height="70" y="70" fill="#FFCB00"/>
                <rect width="500" height="70" y="140" fill="#006A36"/>
                <rect width="500" height="70" y="210" fill="#FFCB00"/>
                <rect width="500" height="70" y="280" fill="#006A36"/>
                <rect width="210" height="210" y="0" fill="#D21034"/>
                <polygon points="105,35 119,77 163,77 127,103 141,145 105,119 69,145 83,103 47,77 91,77" fill="#FFFFFF"/>
            </svg>
        </td>
        <td class="devise-cell">
            « Travail – Liberté – Patrie »
        </td>
    </tr>
</table>

<div class="document-title">
    RÉCÉPISSÉ DE DÉCLARATION DE PERTE
</div>

<table class="meta-table">
    <tr>
        <td class="ministere-cell">
            <strong>Ministère de l'Administration Territoriale, de la Décentralisation et du Développement des Territoires</strong><br>
            Direction Générale de la Documentation Nationale (DGDN)
        </td>
        <td class="recu-cell">
            <div class="badge-recu">
                <strong>N°</strong> {{ $recuNumber }}
            </div>
        </td>
    </tr>
</table>

<!-- ===== CONTENU ===== -->
<div class="content">
    
    <!-- Informations du déclarant -->
    <div class="info-block">
        <h3>👤 Informations du déclarant</h3>
        <table class="info-table">
            <tr>
                <td class="info-label">Nom &amp; Prénoms</td>
                <td class="info-value"><strong>{{ strtoupper($perte->last_name) }} {{ $perte->first_name }}</strong></td>
            </tr>
            <tr>
                <td class="info-label">Téléphone</td>
                <td class="info-value">{{ $perte->contact }}</td>
            </tr>
            <tr>
                <td class="info-label">Adresse Email</td>
                <td class="info-value">{{ $perte->email }}</td>
            </tr>
        </table>
    </div>

    <!-- Détails du document -->
    <div class="info-block">
        <h3>📄 Détails du document déclaré perdu</h3>
        <table class="info-table">
            <tr>
                <td class="info-label">Type de document</td>
                <td class="info-value"><strong>{{ $perte->type_piece }}</strong></td>
            </tr>
            <tr>
                <td class="info-label">Numéro de la pièce</td>
                <td class="info-value">{{ $perte->numero_piece ?? 'Non renseigné' }}</td>
            </tr>
            <tr>
                <td class="info-label">Date de la perte</td>
                <td class="info-value">{{ \Carbon\Carbon::parse($perte->date_perte)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td class="info-label">Lieu de la perte</td>
                <td class="info-value">{{ $perte->lieu_perte }}</td>
            </tr>
            @if($perte->date_delivrance)
            <tr>
                <td class="info-label">Date de délivrance</td>
                <td class="info-value">{{ \Carbon\Carbon::parse($perte->date_delivrance)->format('d/m/Y') }}</td>
            </tr>
            @endif
            @if($perte->autorite_delivrance)
            <tr>
                <td class="info-label">Autorité de délivrance</td>
                <td class="info-value">{{ $perte->autorite_delivrance }}</td>
            </tr>
            @endif
            <tr>
                <td class="info-label">Circonstances</td>
                <td class="info-value"><em>{{ $perte->circonstances ?? 'Non renseignées' }}</em></td>
            </tr>
        </table>
    </div>

    <!-- Instructions -->
    <div class="instruction-box">
        <div class="box-title">🔴 Instructions pour le renouvellement</div>
        <p style="margin: 5px 0 12px 0; font-size: 10pt;">
            Après vérification dans nos bases de données, votre document <strong>{{ $perte->type_piece }}</strong> 
            n'a pas été retrouvé. Ce récépissé vaut attestation officielle de déclaration de perte.
        </p>
        <table class="info-table" style="font-size: 9.5pt;">
            <tr>
                <td style="width: 25%; font-weight: bold; color: #006A36; padding-bottom: 5px;">📍 Lieu recommandé</td>
                <td style="width: 75%; padding-bottom: 5px;"><strong>{{ $lieuRenouvellement }}</strong></td>
            </tr>
            <tr>
                <td style="font-weight: bold; color: #006A36;">📅 Validité</td>
                <td><strong>30 jours</strong> à compter de la date d'émission</td>
            </tr>
        </table>
        <p style="margin: 10px 0 0 0; font-size: 8.5pt; color: #666; font-style: italic;">
            <i class="fas fa-info-circle"></i> Présentez-vous au service recommandé avec ce document et toute pièce justificative disponible pour engager la procédure de renouvellement.
        </p>
    </div>

    <!-- Signature -->
    <div class="signature-section">
        <table class="signature-table">
            <tr>
                <td>
                    <strong>Fait le :</strong> {{ date('d/m/Y à H:i') }}<br>
                    <strong>Généré via :</strong> e-Déclaration Togo
                </td>
                <td style="text-align: right; padding-right: 5px;">
                    <strong>Cachet et Signature de l'Autorité</strong>
                    <div class="sig-line"></div>
                    <div style="font-size: 7pt; color: #999; margin-top: 2px; text-align: right;">
                        Signature électronique certifiée
                    </div>
                </td>
            </tr>
        </table>
    </div>

</div>

<!-- ===== FOOTER ===== -->
<div class="footer">
    <span class="logo-text">e-Déclaration TG</span> – Plateforme Nationale Numérique des Déclarations de Perte
    <br>
    <span class="official-text">Document Officiel</span> – Toute falsification ou usage de faux expose son auteur à des poursuites judiciaires (Code Pénal Togolais)
    <div class="verification-link">
        🔍 Vérification en ligne : https://e-declaration.tg/verifier/{{ $recuNumber }}
    </div>
</div>

</body>
</html>