<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport Journalier - {{ $date }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; }
        h1 { text-align: center; }
        .header { text-align: center; margin-bottom: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Rapport Journalier</h1>
        <p>Date : {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</p>
    </div>

    <h3>Statistiques du jour</h3>
    <p><strong>Nombre de collectes :</strong> {{ $collectes }}</p>
    <p><strong>Poids total pesé :</strong> {{ number_format($poids_total, 2) }} Kg</p>
    <p><strong>Quantité triée :</strong> {{ $quantite_triee }}</p>

    @if($pesages->isNotEmpty())
    <h3>Détail des Pesages</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Heure</th>
                <th>Poids (Kg)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pesages as $pesage)
            <tr>
                <td>{{ $pesage->id }}</td>
                <td>{{ $pesage->created_at->format('H:i') }}</td>
                <td>{{ $pesage->poids }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if($tries->isNotEmpty())
    <h3>Détail des Triés</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Quantité</th>
                <th>Heure</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tries as $trie)
            <tr>
                <td>{{ $trie->id }}</td>
                <td>{{ $trie->quantite_trier }}</td>
                <td>{{ $trie->created_at->format('H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</body>
</html>