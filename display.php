<?php 

# Fonction qui affiche la page html dépendamment d'un paramètre entier qui correspnd à authentification pour 0 et enregistrement pour 1
function print_html($page=0)
{
global $error;

if($page==0)
{

echo "
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Authentification</title>
    <link href='style.css' rel='stylesheet'>
	<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11.15.0/dist/sweetalert2.all.min.js\"></script>
    <script type='text/javascript' src='script.js'></script>
</head>
<body>";

# Message confirmant la création du compte
if (isset($_SESSION['created']))
{
	echo "<script type='text/javascript'>
	Swal.fire({
	icon: \"success\",
    title: \"Compte crée\",
    showConfirmButton: false,
    timer: 2000
	});
</script>";
	unset($_SESSION['created']);

}

# Fonction qui traite les erreurs et affiche l'erreur correspondante dans une boîte de dialogue
if(!empty($error))
{
	echo "<script type='text/javascript'>
	Swal.fire({
	icon: \"error\",
    title: \"$error\"
	});
</script>";
}

echo "
    <section class='container'>
        <div class='login-container'>
            <div class='circle circle-one'></div>
            <div class='form-container'>
                <h1 class='opacity'>Authentification</h1>
                <form method='post'>
                    <input type='text' name='username' placeholder=\"Nom d'utilisateur\"/>
                    <input type='password' name='password' placeholder='Mot de passe' />
                    <button class='opacity'>Envoyer</button>
                </form>
                <div class='register-forget opacity'>
                    <a href='index.php?register=1'>S'inscrire</a>
                </div>
            </div>
            <div class='circle circle-two'></div>
        </div>
    </section>
</body>
</html>
";
}
else
{
echo "
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Inscription</title>
    <link href='style.css' rel='stylesheet'>
	<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11.15.0/dist/sweetalert2.all.min.js\"></script>
</head>
<body>";

# Fonction qui traite les erreurs et affiche l'erreur correspondante dans une boîte de dialogue
if(!empty($error))
{
	echo "<script type='text/javascript'>
	Swal.fire({
	icon: \"error\",
    title: \"Quelque chose s'est mal passé\",
    showConfirmButton: false,
    footer: \" <ul> $error </ul> \"
	});
</script>";
}

echo "
    <section class='container'>
        <div class='login-container'>
            <div class='circle circle-one'></div>
            <div class='form-container'>
                <h1 class='opacity'>Inscription</h1>
                <form method='post'>
                    <input type='text' name='username' placeholder=\"Nom d'utilisateur\" />
                    <input type='password' name='password' placeholder='Mot de passe' />
                    <input type='email' name='email' placeholder='e-mail' />
                    <button class='opacity'>Envoyer</button>
                </form>
                <div class='register-forget opacity'>
                    <a href='index.php'>S'authentifier</a>
                </div>
            </div>
            <div class='circle circle-two'></div>
        </div>
    </section>
</body>
</html>";
}
}

# Affichage de l'entête de l'application'
function print_project_head($board_title="",$statut=0)
{

# Prise en compte du statut dans l' affichage du bouton permettant de changer le statut
$str_statut="Voir les tâches finies";
$i=1;
if($statut==1) {
    $str_statut="Voir les tâches non finies";
    $i=0;
}
    echo "
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Dashboard</title>
    <script type='text/javascript' src='dashboard.js'></script>
    <script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11.15.0/dist/sweetalert2.all.min.js\"></script>
    <link href='dashboard.css' rel='stylesheet'>
</head>

<body>

<div class='board-bar'>
<form method='post'>
    <ul>
      <li>
      <a><i class='fa fa-user'></i>".$_SESSION['name']."</a>
      </li>

      <li>
      <a><i class='fa fa-bars'></i>$board_title</a>
      </li>

      <li class='post  max_b lic' style='margin-left:60px;'>
          <input class='fo-browser' list='projet' value=''  oninvalid=\"this.setCustomValidity('Veuillez renseigner un projet déjà existant.')\" onchange=\"this.setCustomValidity('')\" name='browser' placeholder=\"Sélectionner un projet\" autocomplete='off' />
      </li>

      <li class='max_a lic'>
        <button name='action' class='post fo-btn' value='load_project'>Charger</button>
      </li>

      <li class='max_b lic'>
        <button name='action' class='post fo-btn' value='delete_project'>Supprimer</button>
      </li>

      <li class='post max_a lic' style='margin-left:60px;'>
          <input  type='text' name='new_project' value='' placeholder=\"Saisir un projet\" autocomplete='off' />
      </li>

      <li class='max_a lic'>
        <button name='action' class='post fo-btn' value='create_project'>Créer</button>
      </li>
      </ul>
</form>
<form method='post'>
    <div class='dropdown lic'>
            <a href='javascript:void(0)' class='bar-btn menu-btn'> <i class=\"fas fa-ellipsis-h menu-btn-icon\"></i>Menu</a>
            <div class='dropdown-content'>
              <div class='min_b'>
               <input class='so-browser' list='projet'  name='browser' placeholder=\"Sélectionner un projet\" autocomplete='off' />
              </div>
              <div class='min_b'>
                <button name='action' class='so-btn' value='load_project'>Charger</button>
              </div>
              <div class='min_b '>
                <button name='action' class='so-btn' value='delete_project'>Supprimer</button>
              </div>
              <div class='min_a' style='margin-top:4px;' >
                <input  type='text' name='new_project' value='' placeholder=\"Saisir un nouveau projet\" autocomplete='off' />
                </div>
              <div class='min_a'>
                <button name='action' class='so-btn' value='create_project'>Créer</button>
              </div>
              <button name='action' class='so-btn' value='change_vision'>$str_statut</button>
              <button name='action' class='so-btn' value='logout'><i class='fa fa-sign-out-alt'></i>Déconnection</button>
            </div>
      </div>
</form>
</div>


<section class=\"lists-container\">\n";
}

function print_project_tail($datalist="",$arr=NULL)
{
$display='none';
$id_tache="";
$titre="";
$description="";
$date_creation="";
$date_modification="";
$categorie="";

if(!is_null($arr))
{
    $display='block';
    $id_tache=$arr[0];
    $titre=$arr[1];
    $description=$arr[2];
    $date_creation=$arr[3];
    $date_modification=$arr[4];
    $categorie=$arr[5];
}

    echo "
    <div class=\"add-list\">
    <form method='post'>
        <input type='text' class='categorie_text' name='categorie_text' placeholder=\"Ajouter une liste...\" autocomplete='off' />
        <button class='categorie_submit' value='add_list' name='action'>Soumettre</button>
    </form>
    </div>
</section>
<div id='overlay' class='overlay' style='display:$display;' onclick=\"hide('overlay');hide('centered');removetidfromurl();\"></div>
<div class='centered' id='centered' style='display:$display;'>
        <div class='head-btn'>
            <button class='del-btn' name='remove_categorie' onclick=\"hide('overlay');hide('centered');removetidfromurl();\"><i class='fas fa-window-close'></i></button>
        </div>
         <form method='post'>
                 <label>Tâche :</label>
                 <input type='text' name='task_text' placeholder=\"Modifier la tâche\" value=\"$titre\" autocomplete='off' />
                 <label>Description :</label>
                 <textarea id='browser_' name='task_textarea' value=\"$description\" placeholder=\"Modifier la description...\">$description</textarea>
                 <input type='hidden' value='$id_tache' name='id_task' />
                 <input type='hidden' name='current_categorie' value=\"$categorie\"/>
                 <p class='created'>Date de création : $date_creation</p>
                 <p class='modified'>Date de modification : $date_modification</p>
                 <input class='browser' list='browsers' value=''  name='browser_categorie' placeholder='Modifier la catégorie' id='brow'  autocomplete='off'/>
                 <button name='action' value='change_task' onclick='removetidfromurl();' class='mod-btn'>Modifier</button>
         </form>
</div>

$datalist
</body>
</html>";
}

function print_button_html($index,$key, $state)
{
    switch ($state) {
        case 0:
            echo "          <li onclick=\"window.location.href='dashboard.php?tid=$index';\">$key
                <div class='m-btn'>
                     <form method='post'>
                        <input type='button' class='NEWCLASS r btn' value=\"&#xf00d;\"  onClick='confSubmite(event,this.form);'/>
                        <input type='hidden' name='deleted_task' value=\"$index\"/>
                        <input type='hidden' name='action' value='delete_task'/>
                    </form>
                    <form method='post'>
                        <button class='g fas fa-check-square' name='finished_task' value=\"$index\"></button>
                        <input type='hidden' name='action' value='finished_task'/>
                    </form>
                </div>
            </li>\n";
            break;
        
        case 1:
            echo "          <li onclick=\"window.location.href='dashboard.php?tid=$index';\";\">$key
                <div class='m-btn'>
                    <form method='post'>
                        <input type='button' class='NEWCLASS r btn' value=\"&#xf00d;\"  onClick='confSubmite(event,this.form);'/>
                        <input type='hidden' name='deleted_task' value=\"$index\"/>
                        <input type='hidden' name='action' value='delete_task'/>
                    </form>
                    <form method='post'>
                        <button class='y fas fa-list' name='unfinished_task' value=\"$index\"></button>
                        <input type='hidden' name='action' value='unfinished_task' />
                    </form>
                </div>
            </li>\n";
            break;

        default:
            break;
    }

}

function print_list_head($categorie, $unique_str)
{
    echo "  <div class='list'>
        <div class='head-btn'>
            <button class='mod-btn' name='modify_categorie' onclick=\"headarea('$unique_str')\"><i class='fas fa-edit'></i></button>
            <form method='post' style='display:inline!important;'>
                <input type='button' class='NEWCLASS del-btn r' value=\"&#xf00d;\"  onClick='confSubmit(this.form);'/>
                <input  type='hidden' name='remove_categorie'  value=\"$categorie\" />
                <input type='hidden' name='action' value='remove_categorie'/>
            </form>
        </div>
        <h3 class='list-title' id='heado-$unique_str'>$categorie</h3>
        <div id='headt-$unique_str' style='display:none;' >
            <div class='headarea'>
                <h3 class='list-title' >$categorie</h3>
                <div  style='padding: 1rem;'>
                    <form method='post'>
                        <input type='text' class='browser' value='' name='text_categorie' placeholder='Modifier le nom de la liste' autocomplete='off'/>
                        <input type='hidden' name='current_categorie' value=\"$categorie\"/>
                        <button name='action' value='modify_categorie'>Modifier</button>
                    </form>
                    <form method='post'>
                         <input type='hidden' name='current_categorie' value=\"$categorie\"/>
                         <input class='browser' list='browsers' value='' name='into_browser_categorie' placeholder='Intervertir avec la sélection' autocomplete='off'/>
                         <button name='action'  value='swap_categorie'>Intervertir</button> 
                    </form>
                    <form method='post'>
                        <input type='hidden' name='current_categorie' value=\"$categorie\"/>
                        <input class='browser' list='browsers' value='' name='into_browser_categorie' placeholder='Fusionner dans la sélection' autocomplete='off'/>
                        <button name='action'  value='move_categorie'>Fusionner</button> 
                    </form>
                </div>
            </div>
        </div>
        <ul class='list-items'>\n";
}

function print_list_tail($categorie,$unique_str)
{
    echo "
            </ul>
            <hr class='solid'>
            <div class='add-card'>
                <form method='post'>
                    <input type='text' name='task_text' placeholder=\"Saisir une tâche...\" autocomplete='off' />
                    <i class='fa fa-plus' id='plus-$unique_str' style='display:inline;' onclick=\"textarea('$unique_str')\" ></i>
                    <i class='fa fa-minus' id='minus-$unique_str' style='display:none;' onclick=\"textarea('$unique_str')\" ></i>
                    <button value='add_task' name='action'>Soumettre</button>
                    <textarea id='$unique_str' style='display:none;' name='task_textarea' value='' placeholder=\"Ajouter une description à la tâche...\"></textarea>
                    <input type='hidden' value=\"$categorie\" name='categorie' />
                </form>
            </div>
        </div>\n";
}



?>