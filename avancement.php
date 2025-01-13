<?php 
include '../base.php';

$style="
table, td, th {  
  border: 1px solid #ddd;
  text-align: left;
}

table {
  border-collapse: collapse;
  width: 100%;
}

th, td {
  padding: 15px;
}
";

ex_head("Avancement",$style,"../index.php","Retour à l'accueil");

echo "

<table>
  <tr>
    <th>Fonctionnalité</th>
    <th>Menu</th>
    <th>Fichier</th>
    <th>Date de réalisation</th>
  </tr>
  <tr>
    <td>Creation d'une page d'inscription et d'identification</td>
    <td></td>
    <td>index.php</td>
    <td>15/12/2024</td>
  </tr>
  <tr>
    <td>Mise en place d'une interface graphique pour le tableau de bord des tâches</td>
  	<td></td>
    <td>dashboard.php</td>
    <td>15/12/2024</td>
  </tr>
  <tr>
    <td>Créer des données de base pour chaque nouveau utilisateur après authentification</td>
  	<td></td>
    <td></td>
    <td>à faire</td>
  </tr>
  <tr>
    <td>Afficher les données du projet Home (par défaut et personel) dans chaque utillisateur</td>
  	<td></td>
    <td></td>
    <td>à faire</td>
  </tr>
  <tr>
    <td>Ajout de catégorie par l'utilisateur dans Home</td>
  	<td></td>
    <td></td>
    <td>à faire</td>
  </tr>
  <tr>
    <td>Ajout de tâche par l'utilisateur</td>
  	<td></td>
    <td></td>
    <td>à faire</td>
  </tr>

  <tr>
    <td>Modification de tâche par l'utlisateur</td>
  	<td></td>
    <td></td>
    <td>à faire</td>
  </tr>

  <tr>
    <td>Création de projet (projet personalisé, toujours sans l'aspet collaboratif)</td>
  	<td></td>
    <td></td>
    <td>à faire</td>
  </tr>
</table>
";

ex_tail();

?>