<?php

$databaseHost = '************';
$databaseUsername = '***************';
$databasePassword = '**************';
$databaseName = '******************';

$maxlen_description=10000;
$maxlen_task=100;
$maxlen_project=50;
$maxlen_categorie=100;

$maxlen_password=100;
$minlen_password=4;
$maxlen_email=100;
$minlen_email=3;
$maxlen_username=100;
$minlen_username=3;



$mysqli = mysqli_connect($databaseHost, $databaseUsername, $databasePassword, $databaseName); 

function get_categorie($mysqli)
{
	$unique_query="SELECT DISTINCT id_categorie AS unique_categorie FROM CATEGORIE WHERE id_projet=".$_SESSION['id_projet'];
	$unique_result=$mysqli->query($unique_query);
	$unique_categorie=array_column($unique_result->fetch_all(MYSQLI_NUM),0);

	return $unique_categorie;
}

function get_projet($mysqli)
{
	$unique_query="SELECT DISTINCT nom FROM PROJET WHERE id_utilisateur=".$_SESSION['id_user'];
	$unique_result=$mysqli->query($unique_query);

	return array_column($unique_result->fetch_all(MYSQLI_NUM),0);
}


function set_default_data($mysqli,$id_projet) {
$TACHE_query = "INSERT INTO TACHE (id_projet,id_categorie,titre, description,statut) VALUES 
							($id_projet,\"Tâche à faire cette semaine\",\"Faire les courses\",\"Acheter des fruits, légumes, produits laitiers et tout ce qui manque à la maison.\",0)
							,($id_projet,\"Tâche à faire cette semaine\",\"Écouter le dernier épisode de Syntax.fm\",\"\",0)
							,($id_projet,\"Tâche à faire cette semaine\",\"Prendre rendez-vous chez le médecin\",\"Trouver un créneau disponible pour un contrôle général.\",0)
							,($id_projet,\"Tâche à faire cette semaine\",\"Faire le ménage dans la chambre\",\"Ranger les affaires, passer l'aspirateur et changer les draps.\",0)
							,($id_projet,\"Tâche à faire cette semaine\",\"Prendre du temps pour soi\",\"Lire un livre, regarder un film ou aller se promener pour se détendre.\",0)
							,($id_projet,\"Tâche à faire cette semaine\",\"Préparer un repas spécial\",\"Planifier le menu, acheter les ingrédients et cuisiner.\",0)
							,($id_projet,\"Lecture\",\"Aldous Huxley - Le Meilleur des mondes\",\"Une vision dystopique d'une société où le contrôle est exercé par le conditionnement psychologique et la manipulation de la technologie.\",0)
							,($id_projet,\"Lecture\",\"Ray Bradbury - Fahrenheit 451\",\"Une critique de la censure et de la destruction de la culture dans un monde où les livres sont interdits.\",0)
							,($id_projet,\"Lecture\",\"Yevgeny Zamyatin - Nous autres\",\"Un précurseur de la littérature dystopique, décrivant une société totalitaire contrôlée par un État omniprésent.\",0)
							,($id_projet,\"Lecture\",\"Margaret Atwood - La Servante écarlate\",\"Une réflexion puissante sur les droits des femmes et les dangers du fanatisme religieux dans une société dystopique.\",0)
							,($id_projet,\"Lecture\",\"Franz Kafka - Le Procès\",\"Une exploration de l'absurdité bureaucratique et de la lutte d'un individu contre un système oppressif.\",0)
							,($id_projet,\"Lecture\",\"Albert Camus - L'Étranger\",\"Une méditation sur l'absurdité de la vie et l'aliénation dans une société indifférente.\",0)
							,($id_projet,\"Lecture\",\"John Steinbeck - Les Raisins de la colère\",\"Une critique sociale poignante sur la pauvreté et l'injustice économique pendant la Grande Dépression.\",0),($id_projet,\"Cuisine\",\"Bœuf bourguignon\",\" \",0),($id_projet,\"Cuisine\",\"Coq au vin\",\" \",0)";

$TACHE_result=$mysqli->query($TACHE_query);

$CATEGORIE_query="INSERT INTO CATEGORIE (id_categorie, id_projet) VALUES (\"Tâche à faire cette semaine\",$id_projet),(\"Lecture\",$id_projet),(\"Cuisine\",$id_projet)";

$CATEGORIE_result=$mysqli->query($CATEGORIE_query);
}

?>