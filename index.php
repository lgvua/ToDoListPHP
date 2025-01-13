<?php 
# Démarrage de la session
session_start();
include_once("config.php");
include_once("display.php");

# Si l'utilisateur est connecter, nous le redirigons vers la dashboard
if (isset($_SESSION['connected']) &&  $_SESSION['connected'] == True)
{
	header("Location: dashboard.php");
}

$error="";

if(isset($_GET['register']))
{
	# Verification des champs
	if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email']))
	{
		$username =htmlspecialchars($_POST['username']);
		$email = htmlspecialchars($_POST['email']);
		$password = htmlspecialchars($_POST['password']);
		# Les tailles sont configurables dans config.php
		# la taille du nom d'utilisateur doit être compris entre $maxlen_username et $minlen_username
		if(mb_strlen($username)>=$minlen_username && mb_strlen($username) <=$maxlen_username)
		{
			# la taille du mot de passe doit être supérieur à $minlen_password et inférieur à $maxlen_password
			if (mb_strlen($password)>=$minlen_password && mb_strlen($password)<=$maxlen_password)
			{
				#la taille de l'email est supérieur à 5 inférieur à 1000
				if (mb_strlen($email)>=$minlen_email && mb_strlen($email)<=$maxlen_email)
				{
					# Verifions que le nom d'utilisateur et l'email ne sont pas des doublons
					$query_user = "SELECT 1 FROM UTILISATEUR where nom = \"$username\"" ;
					$result = $mysqli->query($query_user);
					if($result->fetch_array(MYSQLI_NUM)[0] == 0)
					{
						$query_email = "SELECT 1 FROM UTILISATEUR where email = \"$email\"" ;
						$result = $mysqli->query($query_email);
						if($result->fetch_array(MYSQLI_NUM)[0] == 0)
						{
							$password_hash=password_hash($password, PASSWORD_BCRYPT);

							$query_INSERT = "INSERT INTO UTILISATEUR (nom,mot_de_passe,email) VALUES (\"$username\",\"$password_hash\", \"$email\")";

							$result = $mysqli->query($query_INSERT);
							$id_user=$mysqli->insert_id;

							# Ce projet est créé pour un nouvel utilisateur
							$insert_query="INSERT INTO PROJET (id_utilisateur, nom) VALUES ( \"$id_user\", \"Home\")";
							$insert_result = $mysqli->query($insert_query);

							$id_query="SELECT id_projet FROM PROJET WHERE id_utilisateur = \"$id_user\"";
							$id_result=$mysqli->query($id_query);
							$_SESSION['id_projet']=$id_result->fetch_array()[0];
							$_SESSION['project_name']="Home";

							$id_projet=$_SESSION['id_projet'];

							set_default_data($mysqli,$id_projet);

							$page = $_SERVER['PHP_SELF'];
							header("Refresh: 0; url=$page");
						}
						else	
							$error .="<li>L'email est déjà pris.</li>";
					}
					
					else
						$error .="<li>Le nom d'utilisateur est déjà pris.</li>";
				}

				else
					$error .= "<li>L'email doit contenir entre $minlen_email et $maxlen_email caractère.</li>";
			}

			else
				$error .= "<li>Le mot de passe doit contenir entre $minlen_password et $maxlen_password caractère.</li>";
		}

		else
		{
			$error .= "<li>Le nom d'utilisateur doit contenir entre $minlen_username et $maxlen_username caractère.</li>";
		}
	}
}
else
{
	if(isset($_POST['username']) && isset($_POST['password']))
	{
		$username=htmlspecialchars($_POST['username']);
		$password=htmlspecialchars($_POST['password']);

		if(!empty($username) && !empty($password))
		{
			$query = "SELECT 1 FROM UTILISATEUR where nom = \"$username\"";
			$result = $mysqli->query($query);

			if($result->fetch_array(MYSQLI_NUM)[0] != 0)
			{
				$query = "SELECT mot_de_passe,id_utilisateur FROM UTILISATEUR where nom = \"$username\"";
				$result = $mysqli->query($query);
				$arr=$result->fetch_array(MYSQLI_NUM);

				if(password_verify($password,$arr[0]))
				{
					$_SESSION['name']=$username;
					$_SESSION['connected']=1;
					$_SESSION['id_user']=$arr[1];
					$_SESSION['statut']=0;
					header("Location: dashboard.php");
				}
			}
		}


		$error = "Identifiant ou mot de passe incorrect";

	}
}


# Si la variable register est définie, nous monttons l'écran d'enregistrement, sinon de connection
if(isset($_GET['register']))
{
	print_html(1);
}
else
{
	print_html(0);
}

?>
