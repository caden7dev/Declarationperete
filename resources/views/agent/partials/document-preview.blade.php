<div class="document-preview">
    <div class="row">
        <div class="col-md-6">
            <p><strong>Type :</strong> {{ $document->type_document }}</p>
            <p><strong>Nom :</strong> {{ $document->nom_sur_document }}</p>
            <p><strong>Prénom :</strong> {{ $document->prenom_sur_document ?? 'Non renseigné' }}</p>
            <p><strong>Numéro :</strong> {{ $document->numero_document ?? 'Non renseigné' }}</p>
        </div>
        <div class="col-md-6">
            <p><strong>Date découverte :</strong> {{ $document->date_decouverte->format('d/m/Y') }}</p>
            <p><strong>Lieu :</strong> {{ $document->lieu_decouverte }}</p>
            <p><strong>Déclarant :</strong> {{ $document->nom_declarant }} {{ $document->prenom_declarant }}</p>
            <p><strong>Statut :</strong> 
                <span class="badge bg-{{ $document->statut == 'en_attente' ? 'warning' : ($document->statut == 'matche' ? 'success' : 'secondary') }}">
                    {{ $document->statut }}
                </span>
            </p>
        </div>
    </div>
    @if($document->photo_document)
        <div class="mt-2">
            <img src="{{ Storage::url($document->photo_document) }}" alt="Photo du document" class="img-fluid rounded" style="max-height: 200px;">
        </div>
    @endif
</div>