<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport des déclarations</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Rapport des déclarations de perte</h1>
    <p>Généré le {{ now()->format('d/m/Y à H:i') }}</p>
    <table>
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
</body>
</html>