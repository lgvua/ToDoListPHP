<?php 

# Démarrage de la session
session_start();
include_once("config.php");
include_once("display.php");

# Si l'utilisateur n'est pas passé par l'étape d'authentification
if( !isset($_SESSION['id_user']))
{ 
	session_unset(); # Supression de la session
	header("Location: index.php"); # Retour à index.php
}


# Si aucun projet n'est actuellement en cours d'utilisation, nous considérons le premier projet associé à l'utilisateur
if( !isset($_SESSION['id_projet']) || !isset($_SESSION['project_name']) )
{
	$id_query="SELECT id_projet,nom FROM PROJET WHERE id_utilisateur = ".$_SESSION['id_user']." LIMIT 1";
	$id_result=$mysqli->query($id_query);

	$arr = $id_result->fetch_array(MYSQLI_NUM);

	$_SESSION['id_projet']=$arr[0];
	$_SESSION['project_name']=$arr[1];
	$_SESSION['statut']=0;
}

# Si une action est détectée, nous identifions son type, puis traitons la fonctionnalité correspondante
if(isset($_POST['action']))
{
	# Initialisation de variables pour insérer des données dans des chaînes de caractères
	$id_user=$_SESSION['id_user'];
	$id_projet=$_SESSION['id_projet'];

	switch ($_POST['action'])
	{
		# Ajout d'une catégorie dans la table CATEGORIE
		case 'add_list':
			if(isset($_POST['categorie_text']))
			{
				$categorie_text=htmlspecialchars($_POST['categorie_text']);
				if(empty($categorie_text) || mb_strlen($categorie_text) > $maxlen_categorie)
					break;

				$categorie_arr=get_categorie($mysqli); # Récupération des catégories dans la base de données

				#Nous insérons une nouvelle catégorie que si elle n'existe pas dans la base de données pour l'utilisateur et le projet
				if(!in_array($categorie_text,$categorie_arr))
				{
					$insert_categorie_query="INSERT INTO CATEGORIE (id_categorie,id_projet) VALUES (\"$categorie_text\",$id_projet)";

					$result=$mysqli->query($insert_categorie_query);
				}
			}
			break;

		# Supprime la catégorie ciblée par l'action ainsi que toutes les tâches qui y sont associées
		case 'remove_categorie':
			if(isset($_POST['remove_categorie']))
			{
				$id_categorie=htmlspecialchars($_POST['remove_categorie']);
				if(empty($id_categorie))
					break;

				$remove_CATEGORIE_query="DELETE FROM CATEGORIE WHERE id_categorie = \"$id_categorie\" AND id_projet=$id_projet";
				$remove_TACHE_query="DELETE FROM TACHE WHERE id_categorie = \"$id_categorie\" AND id_projet=$id_projet";

				$resut_rm_CATEGORIE=$mysqli->query($remove_CATEGORIE_query);
				$resut_rm_TACHE=$mysqli->query($remove_TACHE_query);
			}
			break;

		case 'create_project':
			if(isset($_POST['new_project']))
			{
				$projet=htmlspecialchars($_POST['new_project']);

				if(empty($projet) || mb_strlen($projet) > $maxlen_project)
					break;


				$projet_query="SELECT 1 FROM PROJET WHERE id_utilisateur = $id_user AND nom=\"$projet\"";
				$projet_result=$mysqli->query($projet_query);
				$projet_in_db = $projet_result->fetch_array(MYSQLI_NUM);

				if(mysqli_num_rows($projet_result)!=0 )
					break;

				$insert_query="INSERT INTO PROJET (id_utilisateur, nom) VALUES ( \"$id_user\", \"$projet\")";
				$insert_result = $mysqli->query($insert_query);

				$_SESSION['id_projet']=$mysqli->insert_id;;
				$_SESSION['project_name']=$projet;
				$_SESSION['statut']=0;
			}
			break;

		case 'load_project':
			if(isset($_POST['browser']))
			{
				$projet=htmlspecialchars($_POST['browser']);

				if(empty($projet)  || mb_strlen($projet) > $maxlen_project)
					break;

				$projet_query="SELECT id_projet,nom FROM PROJET WHERE id_utilisateur = $id_user AND nom=\"$projet\"";
				$projet_result=$mysqli->query($projet_query);

				if(mysqli_num_rows($projet_result)==0)
					break;

				$projet_arr = $projet_result->fetch_array(MYSQLI_NUM);
				$_SESSION['id_projet']=$projet_arr[0];
				$_SESSION['project_name']=$projet_arr[1];
				$_SESSION['statut']=0;
			}
			break;
		
		case 'delete_project':
			if(isset($_POST['browser']))
			{
				$projet=htmlspecialchars($_POST['browser']);

				if(empty($projet)  || mb_strlen($projet) > $maxlen_project)
					break;

				$count_projet_query="SELECT COUNT(*) FROM PROJET WHERE id_utilisateur = $id_user";
				$count_projet_result=$mysqli->query($count_projet_query);
 
				if($count_projet_result->fetch_array(MYSQLI_NUM)[0] <=1)
					break;

				$delete_query ="DELETE FROM PROJET WHERE id_utilisateur=$id_user AND nom=\"$projet\"";
				$delete_result=$mysqli->query($delete_query);

				unset($_SESSION['id_projet']);
			}
			break;

		case 'change_vision':
			if($_SESSION['statut']==0)
				$_SESSION['statut']=1;
			else
				$_SESSION['statut']=0;
			break;

		case 'logout':
				session_unset(); # Supression de la session
				header("Location: index.php"); # Retour à index.php
			break;

		## Ajoute une tâche et sa description
		case 'add_task':
			if(isset($_POST['task_text']) && isset($_POST['categorie']) && isset($_POST['task_textarea']))
			{
				$task_text=htmlspecialchars($_POST['task_text']);
				$id_categorie=htmlspecialchars($_POST['categorie']);
				$description=htmlspecialchars($_POST['task_textarea']);



				# Vérification que le nom de la tâche n'est pas vide
				$categorie_arr=get_categorie($mysqli); # Récupération des catégories dans la base de données
				if(empty($task_text) || !in_array($id_categorie,$categorie_arr))
					break;

				if(mb_strlen($description) > $maxlen_description || mb_strlen($id_categorie)>$maxlen_categorie || mb_strlen($task_text)>$maxlen_task)
					break;

				# Insertion de la tâche dans la base de donnée
				$add_task_query="INSERT INTO TACHE (id_projet,id_categorie,titre,description) VALUES (\"$id_projet\",\"$id_categorie\",\"$task_text\",\"$description\")";
				$result=$mysqli->query($add_task_query);
			}
			break;

		# Changement du contenue d'une tâche d'une catégorie
		case 'change_task':
			if(isset($_POST['task_text']) && isset($_POST['current_categorie']) && isset($_POST['task_textarea']) && isset($_POST['id_task']) && isset($_POST['browser_categorie']))
			{
				$task_text=htmlspecialchars($_POST['task_text']);
				$current_categorie=htmlspecialchars($_POST['current_categorie']);
				$task_textarea=htmlspecialchars($_POST['task_textarea']);
				$id_task=(int)htmlspecialchars($_POST['id_task']);
				$description=htmlspecialchars($_POST['task_textarea']);
				$browser_categorie=htmlspecialchars($_POST['browser_categorie']);
				$id_categorie=$current_categorie;

				# Si une catégorie a été sélectionnée, alors nous la considérons pour la requête SQL qui suit
				if(!empty($browser_categorie))
					$id_categorie=$browser_categorie;

				# Vérifions qu'elle existe et que le nom de la tâche n'est pas vide
				$categorie_arr=get_categorie($mysqli);
				if(empty($id_categorie) || !in_array($id_categorie,$categorie_arr) || empty($task_text))
					break;

				# Vérifions la longueur des champs
				if(mb_strlen($description) > $maxlen_description || mb_strlen($task_text)>$maxlen_task)
					break;

				# Insertion de la tâche dans la base de donnée
				$UPDATE_task_query="UPDATE TACHE SET id_categorie = \"$id_categorie\",titre = \"$task_text\",description = \"$description\",date_modification = CURRENT_TIMESTAMP WHERE id_tache=$id_task AND id_projet=$id_projet";
				$result=$mysqli->query($UPDATE_task_query);
			}
			break;

		case 'delete_task':
			if(isset($_POST['deleted_task']))
			{
				$id_task=(int)$_POST['deleted_task'];
				$delete_task_query="DELETE FROM TACHE WHERE id_tache=$id_task";
				$result=$mysqli->query($delete_task_query);
			}
			break;

		case 'modify_categorie':
			if(isset($_POST['current_categorie']) && isset($_POST['text_categorie']))
			{
				$current_categorie=htmlspecialchars($_POST['current_categorie']);
				$new_categorie=htmlspecialchars($_POST['text_categorie']);

				if(mb_strlen($new_categorie)>$maxlen_categorie)
					break;

				$categorie_arr=get_categorie($mysqli); # Récupération des catégories dans la base de données
				if(in_array($current_categorie,$categorie_arr) && !in_array($new_categorie,$categorie_arr))
				{
					$CATEGORIE_update_query="UPDATE CATEGORIE SET id_categorie=\"$new_categorie\" WHERE id_categorie=\"$current_categorie\" AND id_projet = $id_projet";
					$TACHE_update_query="UPDATE TACHE SET id_categorie=\"$new_categorie\" WHERE id_categorie=\"$current_categorie\" AND id_projet=$id_projet";
					$CATEGORIE_result=$mysqli->query($CATEGORIE_update_query);
					$TACHE_result=$mysqli->query($TACHE_update_query);
				}
			}	
			break;

		case 'swap_categorie':
			if(isset($_POST['current_categorie']) && isset($_POST['into_browser_categorie']))
			{
				$current_categorie=htmlspecialchars($_POST['current_categorie']);
				$new_categorie=htmlspecialchars($_POST['into_browser_categorie']);

				$categorie_arr=get_categorie($mysqli); # Récupération des catégories dans la base de données
				if(in_array($current_categorie,$categorie_arr)  && in_array($new_categorie,$categorie_arr))
				{
					$UPDATE_tmp_query="UPDATE CATEGORIE SET id_categorie=\"\" WHERE id_projet=$id_projet AND id_categorie=\"$current_categorie\"";
					$UPDATE_new_query="UPDATE CATEGORIE SET id_categorie=\"$current_categorie\" WHERE id_projet=$id_projet AND id_categorie=\"$new_categorie\"";
					$UPDATE_current_query="UPDATE CATEGORIE SET id_categorie=\"$new_categorie\" WHERE id_projet=$id_projet AND id_categorie=\"\"";
					$UPDATE_tmp_result=$mysqli->query($UPDATE_tmp_query);
					$UPDATE_new_result=$mysqli->query($UPDATE_new_query);
					$UPDATE_current_result=$mysqli->query($UPDATE_current_query);
				}
			}
			break;

		case 'move_categorie':
			if(isset($_POST['current_categorie']) && isset($_POST['into_browser_categorie']))
			{
				$current_categorie=htmlspecialchars($_POST['current_categorie']);
				$new_categorie=htmlspecialchars($_POST['into_browser_categorie']);

				$categorie_arr=get_categorie($mysqli); # Récupération des catégories dans la base de données
				if(in_array($current_categorie,$categorie_arr)  && in_array($new_categorie,$categorie_arr))
				{
					$CATEGORIE_remove_query="DELETE FROM CATEGORIE WHERE id_categorie=\"$current_categorie\" AND id_projet = $id_projet";
					$TACHE_update_query="UPDATE TACHE SET id_categorie=\"$new_categorie\" WHERE id_categorie=\"$current_categorie\" AND id_projet=$id_projet";
					$CATEGORIE_result=$mysqli->query($CATEGORIE_remove_query);
					$TACHE_result=$mysqli->query($TACHE_update_query);
				}
			}	
			break;

	# Modification du statut d'une tâche
		# Si la tâche est terminée
		case 'finished_task':
			if(isset($_POST['finished_task']))
			{
				$id_task=(int)$_POST['finished_task'];

				$query="UPDATE TACHE SET statut = 1 WHERE id_projet=$id_projet AND id_tache=$id_task";

				$result= $mysqli->query($query);
			}
			break;

		# Si nous décidons de rétablir la tâche terminée dans un état où elle ne l'est pas
		case 'unfinished_task':
			if(isset($_POST['unfinished_task']))
			{
				$id_task=(int)$_POST['unfinished_task'];

				$query="UPDATE TACHE SET statut = 0 WHERE id_projet=$id_projet AND id_tache=$id_task";

				$result= $mysqli->query($query);
			}
			break;

		default:
			//
			break;
	}

	# Supression du contenue de la varaible $_POST
	header('Location: ' . $_SERVER['HTTP_REFERER']);
}

# Récupération des catégories 
$unique_categorie=get_categorie($mysqli);

# Vérification si l'utilisateur a cliqué pour modifier une tâche avec l'identifiant unique $tid
$tid=NULL;

# Si c'est le cas, nous enregistrons l'identifiant de la tâche concernée
if(isset($_GET['tid']))
{
	$tid=(int)$_GET['tid'];
}

$statut = $_SESSION['statut'];

# Affichage de l'en-tête HTML de l'application
print_project_head($_SESSION['project_name'],$statut);


# Permet de convertir un entier en un "hash" simple, afin de l'utiliser comme identifiant unique en HTML.
# Les chiffres sont remplacés par des lettres : 0 devient 'a', 1 devient 'b', 2 devient 'c', 3 devient 'd', 
# 4 devient 'e', 5 devient 'f', 6 devient 'g', 7 devient 'h', 8 devient 'i', et 9 devient 'j'.
function int_to_unique_str($i) {
	$i=(string)$i;


    $char_arr = ['0' => 'a','1' => 'b','2' => 'c','3' => 'd','4' => 'e','5' => 'f','6' => 'g','7' => 'h','8' => 'i','9' => 'j'];

    $unique_str = "";
    $len = mb_strlen($i);
    
    for ($j = 0; $j < $len; $j++) {
        $unique_str .= $char_arr[$i[$j]];
    }

    return $unique_str;
}

# Création d'une liste de données pour les champs de saisie (input) à inclure à la fin du fichier HTML
$datalist="<datalist id='browsers'>\n";
$c_index=100;

# Nous parcourons toutes les catégories du projet et affichons ensuite les tâches associées à chaque catégorie
foreach($unique_categorie as $categorie)
{
	# Requête SQL pour accéder aux tâches
		$query="SELECT id_tache, titre FROM TACHE WHERE id_projet=".$_SESSION['id_projet']." AND statut=$statut AND id_categorie=\"$categorie\" ORDER BY date_creation ASC LIMIT 1000";

		$result= $mysqli->query($query);
		$list=$result->fetch_all(MYSQLI_ASSOC);
	
	# Récupération des tâches et de leurs identifiants uniques
	$title=array_column($list, 'titre');
	$id_tache=array_column($list,'id_tache');

	# Transformation d l'entier correspondant aux nombres d'occurence en un identifiant unique en chaîne de caractère
	$unique_str=int_to_unique_str($c_index);

	# Affichage de l'entête HTML de la liste
	print_list_head($categorie,$unique_str);

	# Affichage des tâches dans la liste
	$index = 0;
	foreach($title as $key)
	{
		print_button_html($id_tache[$index],$key, $statut);
		$index++;
	}

	# Affichage de la fin HTML de la liste
	print_list_tail($categorie,$unique_str);
	$c_index++;

	# Ajout d'un champ de saisie pour la liste des catégories (listes)
	$datalist.="	<option value=\"$categorie\">\n";
}

$datalist.="</datalist>\n";

 #Ajout d'une liste de noms de projets sous forme de datalist
	$projet_query="SELECT nom FROM PROJET WHERE id_utilisateur = ".$_SESSION['id_user']." LIMIT 30";
	$projet_result=$mysqli->query($projet_query);
	$projet_arr = $projet_result->fetch_all(MYSQLI_NUM);
	$projet_arr=array_column($projet_arr,0);

	$datalist.="<datalist id='projet'>\n";
	foreach($projet_arr as $nom)
	{
		$datalist.="	<option value=\"$nom\">\n";
	}

	$datalist.="</datalist>";



# Si l'utilisateur n'a pas cliqué sur une tâche
if(is_null($tid))
{
	print_project_tail($datalist);
}
# Sinon
else
{
	# Nous envoyons une requette à la base de donnée pour récuperer la description associé à la tâche
    $task_query="SELECT id_tache,titre,description,date_creation,date_modification,id_categorie FROM TACHE WHERE id_tache=$tid AND id_projet=".$_SESSION['id_projet'];
    $task_result=$mysqli->query($task_query);
    
    # Vérification pour garantir que la tâche envoyée par le client existe et ne cause pas de perturbation dans la suite du processus
    $task_arr = NULL;
    if(mysqli_num_rows($task_result)!=0)
    	$task_arr = $task_result->fetch_array(MYSQLI_NUM);

    # Affichage de la fin dans lequel on prend en compte que l'utilisateur veut modifier la tâche
    print_project_tail($datalist,$task_arr);

}

?>