<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport Mensuel</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; }
        h2 { text-align: center; }
        .box { margin-top: 20px; }
    </style>
</head>
<body>

<h2>Rapport Mensuel - {{ $mois ?? '' }}</h2>

<div class="box">
    <p><strong>Poids total :</strong> {{ $stats_mensuelles['poids_total'] ?? 0 }} Kg</p>
    <p><strong>Quantité triée :</strong> {{ $stats_mensuelles['quantite_triee'] ?? 0 }}</p>
    <p><strong>Produits fabriqués :</strong> {{ $stats_mensuelles['produits_fabriqués'] ?? 0 }}</p>
</div>

</body>
</html>