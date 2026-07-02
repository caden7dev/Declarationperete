@extends('layouts.agent')

@section('title', 'Rapports des déclarations')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>Rapport des déclarations de perte</h3>
            <a href="{{ route('agent.rapport-pdf') }}" class="btn btn-primary">📄 Télécharger PDF</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>N° déclaration</th>
                            <th>Déclarant</th>
                            <th>Type</th>
                            <th>Date perte</th>
                            <th>Statut</th>
                         </tr>
                    </thead>
                    <tbody>
                        @foreach($pertes as $perte)
                        <tr>
                            <td>{{ $perte->numero_declaration ?? 'DL-'.$perte->id }}</td>
                            <td>{{ $perte->first_name }} {{ $perte->last_name }}</td>
                            <td>{{ $perte->type_piece }}</td>
                            <td>{{ $perte->date_perte->format('d/m/Y') }}</td>
                            <td>
                                @if($perte->statut == 'en_attente') En attente
                                @elseif($perte->statut == 'validee') Validée
                                @elseif($perte->statut == 'restitue') Restituée
                                @elseif($perte->statut == 'non_retrouve') Non retrouvée
                                @else Rejetée
                                @endif
                             </td>
                         </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection