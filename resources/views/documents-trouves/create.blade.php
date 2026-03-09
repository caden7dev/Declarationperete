<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Déclarer un Document Trouvé - e-Déclaration TG</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 2rem 1rem;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .header h1 {
            font-size: 2.5rem;
            color: #1e3a5f;
            margin-bottom: 0.5rem;
            font-weight: 900;
        }

        .header .subtitle {
            font-size: 1.1rem;
            color: #64748b;
        }

        .alert-info {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            display: flex;
            align-items: start;
            gap: 1rem;
        }

        .alert-info-icon {
            font-size: 2rem;
            flex-shrink: 0;
        }

        .form-card {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }

        .section-title {
            font-size: 1.5rem;
            color: #1e3a5f;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e2e8f0;
            font-weight: 800;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .required { color: #ef4444; }

        .form-control {
            width: 100%;
            padding: 0.9rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
            font-family: 'Inter', sans-serif;
        }

        .form-control:focus {
            outline: none;
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        .file-upload {
            border: 2px dashed #cbd5e1;
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .file-upload:hover {
            border-color: #10b981;
            background: #f0fdf4;
        }

        .file-upload input[type="file"] { display: none; }

        .btn-submit {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 1.2rem 3rem;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            width: 100%;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }

        /* ===== BOUTON RETOUR ===== */
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #64748b;
            color: white;
            padding: 0.85rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            margin-bottom: 2rem;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s;
        }

        .btn-back:hover {
            background: #475569;
            color: white;
            transform: translateY(-1px);
        }

        .help-text {
            font-size: 0.85rem;
            color: #64748b;
            margin-top: 0.3rem;
        }

        @media (max-width: 768px) {
            .form-card { padding: 2rem 1.5rem; }
            .header h1 { font-size: 2rem; }
            .form-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="container">

        {{-- ===== BOUTON RETOUR : dashboard si connecté, accueil sinon ===== --}}
        @auth
            <a href="{{ route('dashboard') }}" class="btn-back">
                ← Retour au dashboard
            </a>
        @else
            <a href="{{ route('home') }}" class="btn-back">
                ← Retour à l'accueil
            </a>
        @endauth

        <div class="header">
            <h1>📦 Déclarer un Document Trouvé</h1>
            <p class="subtitle">Aidez quelqu'un à retrouver son document perdu</p>
        </div>

        <div class="alert-info">
            <div class="alert-info-icon">ℹ️</div>
            <div>
                <strong>Merci pour votre geste citoyen !</strong><br>
                En déclarant ce document trouvé, vous aidez son propriétaire à le récupérer. 
                Nous ferons le lien automatiquement avec les déclarations de perte enregistrées.
            </div>
        </div>

        <div class="form-card">
            <form action="{{ route('documents-trouves.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Section 1 : Vos informations -->
                <div class="section-title">👤 Vos Informations</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Nom <span class="required">*</span></label>
                        <input type="text" name="nom_declarant" class="form-control" 
                               value="{{ old('nom_declarant', auth()->user()->last_name ?? auth()->user()->name ?? '') }}" required>
                        @error('nom_declarant')
                            <span class="help-text" style="color: #ef4444;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Prénom <span class="required">*</span></label>
                        <input type="text" name="prenom_declarant" class="form-control" 
                               value="{{ old('prenom_declarant', auth()->user()->first_name ?? '') }}" required>
                        @error('prenom_declarant')
                            <span class="help-text" style="color: #ef4444;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Email <span class="required">*</span></label>
                        <input type="email" name="email_declarant" class="form-control" 
                               value="{{ old('email_declarant', auth()->user()->email ?? '') }}" required>
                        <span class="help-text">Pour vous contacter si nous trouvons le propriétaire</span>
                        @error('email_declarant')
                            <span class="help-text" style="color: #ef4444;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Téléphone <span class="required">*</span></label>
                        <input type="tel" name="telephone_declarant" class="form-control" 
                               value="{{ old('telephone_declarant', auth()->user()->contact ?? auth()->user()->phone ?? '') }}" required>
                        @error('telephone_declarant')
                            <span class="help-text" style="color: #ef4444;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Section 2 : Informations du document -->
                <div class="section-title">📄 Informations du Document Trouvé</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Type de document <span class="required">*</span></label>
                        <select name="type_document" class="form-control" required>
                            <option value="">-- Sélectionner --</option>
                            <option value="Carte d'identité (CNI)" {{ old('type_document') == "Carte d'identité (CNI)" ? 'selected' : '' }}>Carte d'identité (CNI)</option>
                            <option value="Passeport" {{ old('type_document') == 'Passeport' ? 'selected' : '' }}>Passeport</option>
                            <option value="Permis de conduire" {{ old('type_document') == 'Permis de conduire' ? 'selected' : '' }}>Permis de conduire</option>
                            <option value="Carte d'électeur" {{ old('type_document') == "Carte d'électeur" ? 'selected' : '' }}>Carte d'électeur</option>
                            <option value="Acte de naissance" {{ old('type_document') == 'Acte de naissance' ? 'selected' : '' }}>Acte de naissance</option>
                            <option value="Certificat de nationalité" {{ old('type_document') == 'Certificat de nationalité' ? 'selected' : '' }}>Certificat de nationalité</option>
                            <option value="Autre" {{ old('type_document') == 'Autre' ? 'selected' : '' }}>Autre document officiel</option>
                        </select>
                        @error('type_document')
                            <span class="help-text" style="color: #ef4444;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Numéro du document (si visible)</label>
                        <input type="text" name="numero_document" class="form-control" 
                               value="{{ old('numero_document') }}">
                        <span class="help-text">Aide à identifier le propriétaire</span>
                    </div>

                    <div class="form-group">
                        <label>Nom sur le document (si lisible)</label>
                        <input type="text" name="nom_sur_document" class="form-control" 
                               value="{{ old('nom_sur_document') }}">
                    </div>

                    <div class="form-group">
                        <label>Prénom sur le document (si lisible)</label>
                        <input type="text" name="prenom_sur_document" class="form-control" 
                               value="{{ old('prenom_sur_document') }}">
                    </div>
                </div>

                <!-- Section 3 : Circonstances de la découverte -->
                <div class="section-title">📍 Où et Quand avez-vous trouvé ce document ?</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Date de découverte <span class="required">*</span></label>
                        <input type="date" name="date_decouverte" class="form-control" 
                               value="{{ old('date_decouverte', date('Y-m-d')) }}" 
                               max="{{ date('Y-m-d') }}" required>
                        @error('date_decouverte')
                            <span class="help-text" style="color: #ef4444;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Lieu de découverte <span class="required">*</span></label>
                        <input type="text" name="lieu_decouverte" class="form-control" 
                               value="{{ old('lieu_decouverte') }}" 
                               placeholder="Ex: Marché de Lomé, Avenue de la Paix" required>
                        @error('lieu_decouverte')
                            <span class="help-text" style="color: #ef4444;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label>Description du document</label>
                    <textarea name="description" class="form-control" 
                              placeholder="État du document, particularités visibles...">{{ old('description') }}</textarea>
                    <span class="help-text">Décrivez l'état et les particularités du document</span>
                </div>

                <div class="form-group">
                    <label>Circonstances de la découverte</label>
                    <textarea name="circonstances" class="form-control" 
                              placeholder="Comment avez-vous trouvé ce document ?">{{ old('circonstances') }}</textarea>
                    <span class="help-text">Ex: Trouvé sur un banc, dans un taxi, etc.</span>
                </div>

                <!-- Section 4 : Photo (optionnelle) -->
                <div class="section-title">📷 Photo du Document (Optionnel)</div>
                <div class="form-group">
                    <div class="file-upload" onclick="document.getElementById('photo').click()">
                        <input type="file" id="photo" name="photo_document" accept="image/*,.pdf">
                        <div style="font-size: 3rem; margin-bottom: 1rem;">📸</div>
                        <div style="font-weight: 600; margin-bottom: 0.5rem;">Cliquez pour ajouter une photo</div>
                        <div style="font-size: 0.85rem; color: #64748b;">
                            Photo du document (pour vérification).<br>
                            Formats acceptés : JPG, PNG, PDF (max 2 Mo)
                        </div>
                    </div>
                    <span class="help-text">⚠️ N'envoyez qu'une photo si nécessaire pour l'identification</span>
                </div>

                <button type="submit" class="btn-submit">
                    ✅ Déclarer ce Document Trouvé
                </button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('photo').addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                const fileName = e.target.files[0].name;
                const fileUpload = document.querySelector('.file-upload');
                fileUpload.innerHTML = `
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">✅</div>
                    <div style="font-weight: 600; color: #10b981;">${fileName}</div>
                    <div style="font-size: 0.85rem; color: #64748b; margin-top: 0.5rem;">
                        Cliquez pour changer de fichier
                    </div>
                `;
            }
        });
    </script>
</body>
</html>