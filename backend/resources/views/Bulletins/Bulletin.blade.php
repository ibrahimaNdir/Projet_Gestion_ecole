<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bulletin de notes - {{ $eleve->prenom }} {{ $eleve->nom }}</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
        }
        .container {
            width: 100%;
            margin: auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        h1 {
            color: #4CAF50;
            margin: 0;
            font-size: 24px;
        }
        h2 {
            font-size: 18px;
            color: #555;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 5px;
            margin-top: 20px;
        }
        .info-block {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 20px;
        }
        .info-block p {
            margin: 5px 0;
        }
        .notes-table table {
            width: 100%;
            border-collapse: collapse;
        }
        .notes-table th, .notes-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .notes-table th {
            background-color: #f2f2f2;
            color: #555;
            font-weight: bold;
        }
        .notes-table td:last-child, .notes-table th:last-child {
            text-align: right;
        }
        .summary {
            margin-top: 20px;
            font-size: 14px;
        }
        .summary p {
            margin: 5px 0;
        }
        .summary .highlight {
            font-weight: bold;
            color: #d9534f;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Bulletin de notes</h1>
        <p><strong>Année Académique :</strong> {{ $annee->nom_annee_academique }}</p>
        <p><strong>Période d'évaluation :</strong> {{ $periode->nom_periode }}</p>
    </div>

    <h2>Informations sur l'élève</h2>
    <div class="info-block">
        <p><strong>Nom et Prénom :</strong> {{ $eleve->prenom }} {{ $eleve->nom }}</p>
        <p><strong>Classe :</strong> {{ $eleve->classe->nom_classe }}</p>
        <p><strong>Date de naissance :</strong> {{ \Carbon\Carbon::parse($eleve->date_naissance)->format('d/m/Y') }}</p>
    </div>

    <h2>Détail des notes</h2>
    <div class="notes-table">
        <table>
            <thead>
            <tr>
                <th>Matière</th>
                <th>Moyenne de la matière</th>
                <th>Coefficient</th>
                <th>Note Pondérée</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($matieres as $matiere)
                <tr>
                    <td>{{ $matiere['nom'] }}</td>
                    <td>{{ $matiere['moyenne'] }}</td>
                    <td>{{ $matiere['coefficient'] }}</td>
                    <td>{{ $matiere['note_ponderee'] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="summary">
        <p><strong>Moyenne Générale :</strong> <span class="highlight">{{ number_format($moyenneGenerale, 2) }} / 20</span></p>
        <p><strong>Mention :</strong> <span class="highlight">{{ $mention }}</span></p>
        <p><strong>Rang :</strong> Non calculé</p>
    </div>
</div>
</body>
</html>
