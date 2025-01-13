# Sélection du premier projet de l'utilisateur
SELECT id_projet, nom FROM PROJET WHERE id_utilisateur = [user_id] LIMIT 1;

# Ajout d'une nouvelle catégorie :
INSERT INTO CATEGORIE (id_categorie, id_projet) VALUES ("[categorie_text]", [id_projet]);

# Suppression d'une catégorie et de ses tâches associées :
DELETE FROM CATEGORIE WHERE id_categorie = "[id_categorie]" AND id_projet = [id_projet];
DELETE FROM TACHE WHERE id_categorie = "[id_categorie]" AND id_projet = [id_projet];

# Création d'un nouveau projet :
SELECT 1 FROM PROJET WHERE id_utilisateur = [id_user] AND nom = "[projet_name]";
INSERT INTO PROJET (id_utilisateur, nom) VALUES ([id_user], "[projet_name]");

# Charger un projet spécifique :
SELECT id_projet, nom FROM PROJET WHERE id_utilisateur = [id_user] AND nom = "[projet_name]";

# Supprimer un projet :
SELECT COUNT(*) FROM PROJET WHERE id_utilisateur = [id_user];
DELETE FROM PROJET WHERE id_utilisateur = [id_user] AND nom = "[projet_name]";

# Ajouter une tâche :
INSERT INTO TACHE (id_projet, id_categorie, titre, description) VALUES ([id_projet], "[id_categorie]", "[task_title]", "[task_description]");

# Mettre à jour une tâche existante :
UPDATE TACHE SET id_categorie = "[id_categorie]", titre = "[task_title]", description = "[task_description]", date_modification = CURRENT_TIMESTAMP WHERE id_tache = [id_task] AND id_projet = [id_projet];

# Supprimer une tâche :
DELETE FROM TACHE WHERE id_tache = [id_task];

# Modification du statut d'une tâche (terminée ou non) :
UPDATE TACHE SET statut = 1 WHERE id_projet = [id_projet] AND id_tache = [id_task]; -- Si terminée
UPDATE TACHE SET statut = 0 WHERE id_projet = [id_projet] AND id_tache = [id_task]; -- Si non terminée

# Modifier le nom d'une catégorie :
UPDATE CATEGORIE SET id_categorie = "[new_categorie]" WHERE id_categorie = "[current_categorie]" AND id_projet = [id_projet];
UPDATE TACHE SET id_categorie = "[new_categorie]" WHERE id_categorie = "[current_categorie]" AND id_projet = [id_projet];

Échanger des catégories :
UPDATE CATEGORIE SET id_categorie = "" WHERE id_projet = [id_projet] AND id_categorie = "[current_categorie]";
UPDATE CATEGORIE SET id_categorie = "[current_categorie]" WHERE id_projet = [id_projet] AND id_categorie = "[new_categorie]";
UPDATE CATEGORIE SET id_categorie = "[new_categorie]" WHERE id_projet = [id_projet] AND id_categorie = "";

# Déplacer une catégorie :
DELETE FROM CATEGORIE WHERE id_categorie = "[current_categorie]" AND id_projet = [id_projet];
UPDATE TACHE SET id_categorie = "[new_categorie]" WHERE id_categorie = "[current_categorie]" AND id_projet = [id_projet];

# Récupérer les tâches pour une catégorie donnée :
SELECT id_tache, titre FROM TACHE WHERE id_projet = [id_projet] AND statut = [statut] AND id_categorie = "[categorie]" ORDER BY date_creation ASC LIMIT 1000;

# Récupérer la liste des projets d'un utilisateur :
SELECT nom FROM PROJET WHERE id_utilisateur = [id_user] LIMIT 30;

# Récupérer une tâche spécifique avec ses détails :
SELECT id_tache, titre, description, date_creation, date_modification, id_categorie FROM TACHE WHERE id_tache = [tid] AND id_projet = [id_projet];

# Récupérer les catégories pour un projet donné :
SELECT DISTINCT id_categorie AS unique_categorie FROM CATEGORIE WHERE id_projet = [id_projet];

# Récupérer la liste des projets d'un utilisateur :
SELECT DISTINCT nom FROM PROJET WHERE id_utilisateur = [id_user];

# Insérer des données par défaut dans la table TACHE :
INSERT INTO TACHE (id_projet, id_categorie, titre, description, statut) VALUES 
([id_projet], "Tâche à faire cette semaine", "Faire les courses", "Acheter des fruits, légumes, produits laitiers et tout ce qui manque à la maison.", 0),
([id_projet], "Tâche à faire cette semaine", "Écouter le dernier épisode de Syntax.fm", "", 0),
([id_projet], "Tâche à faire cette semaine", "Prendre rendez-vous chez le médecin", "Trouver un créneau disponible pour un contrôle général.", 0),
([id_projet], "Tâche à faire cette semaine", "Faire le ménage dans la chambre", "Ranger les affaires, passer l'aspirateur et changer les draps.", 0),
([id_projet], "Tâche à faire cette semaine", "Prendre du temps pour soi", "Lire un livre, regarder un film ou aller se promener pour se détendre.", 0),
([id_projet], "Tâche à faire cette semaine", "Préparer un repas spécial", "Planifier le menu, acheter les ingrédients et cuisiner.", 0),
([id_projet], "Lecture", "Aldous Huxley - Le Meilleur des mondes", "Une vision dystopique d'une société où le contrôle est exercé par le conditionnement psychologique et la manipulation de la technologie.", 0),
([id_projet], "Lecture", "Ray Bradbury - Fahrenheit 451", "Une critique de la censure et de la destruction de la culture dans un monde où les livres sont interdits.", 0),
([id_projet], "Lecture", "Yevgeny Zamyatin - Nous autres", "Un précurseur de la littérature dystopique, décrivant une société totalitaire contrôlée par un État omniprésent.", 0),
([id_projet], "Lecture", "Margaret Atwood - La Servante écarlate", "Une réflexion puissante sur les droits des femmes et les dangers du fanatisme religieux dans une société dystopique.", 0),
([id_projet], "Lecture", "Franz Kafka - Le Procès", "Une exploration de l'absurdité bureaucratique et de la lutte d'un individu contre un système oppressif.", 0),
([id_projet], "Lecture", "Albert Camus - L'Étranger", "Une méditation sur l'absurdité de la vie et l'aliénation dans une société indifférente.", 0),
([id_projet], "Lecture", "John Steinbeck - Les Raisins de la colère", "Une critique sociale poignante sur la pauvreté et l'injustice économique pendant la Grande Dépression.", 0),
([id_projet], "Cuisine", "Bœuf bourguignon", "", 0),
([id_projet], "Cuisine", "Coq au vin", "", 0);

# Insérer des catégories par défaut pour un projet donné :
INSERT INTO CATEGORIE (id_categorie, id_projet) VALUES 
("Tâche à faire cette semaine", [id_projet]),
("Lecture", [id_projet]),
("Cuisine", [id_projet]);

# Vérifier si un utilisateur avec le nom spécifié existe déjà :
SELECT 1 FROM UTILISATEUR WHERE nom = "[username]";

# Vérifier si un utilisateur avec l'email spécifié existe déjà :
SELECT 1 FROM UTILISATEUR WHERE email = "[email]";

# Insérer un nouvel utilisateur dans la base de données :
INSERT INTO UTILISATEUR (nom, mot_de_passe, email) VALUES ("[username]", "[password_hash]", "[email]");

# Insérer un nouveau projet pour un utilisateur donné :
INSERT INTO PROJET (id_utilisateur, nom) VALUES ("[id_user]", "Home");

# Récupérer l'ID du projet d'un utilisateur spécifique :
SELECT id_projet FROM PROJET WHERE id_utilisateur = "[id_user]";

# Vérifier si un utilisateur avec le nom spécifié existe et récupérer son mot de passe et ID utilisateur :
SELECT mot_de_passe, id_utilisateur FROM UTILISATEUR WHERE nom = "[username]";




