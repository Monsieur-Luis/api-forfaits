<?php

require_once "./include/config.php";

class modele_etablissement{

    
    public $nomEtablissement;
    public $adresse;
    public $ville;
    public $telephone;
    public $courriel;
    public $siteWeb;
    public $description;

    public function __construct($nom_etablissement, $adresse, $ville, $telephone, $courriel, $site_web, $description) {
        $this-> nomEtablissement = $nom_etablissement;
        $this-> adresse = $adresse;
        $this-> ville = $ville;
        $this-> telephone = $telephone;
        $this-> courriel = $courriel;
        $this-> siteWeb = $site_web;
        $this-> description = $description;
    }
}

class modele_forfait{

    public $id;  
    public $nom;
    public $description;
    public $code;
    public $categories;
    public $dateDebut;
    public $dateFin ;
    public $prix;
    public $nouveauPrix;
    public $premium;
    public $etablissement;
    
    
    

    public function __construct($id, $nom, $description_forfait, $code, $categories, 
    $date_debut, $date_fin, $prix, $nouveau_prix, $premium, $nom_etablissement, $adresse,
    $ville, $telephone, $courriel, $site_web, $description) {

        $this->id = $id;
        $this->nom = $nom;
        $this->description = $description_forfait;
        $this->code = $code;
        $this->categories = $categories;
        $this->dateDebut = $date_debut;
        $this->dateFin = $date_fin;
        $this->prix = $prix;
        $this->nouveauPrix = $nouveau_prix;
        $this->premium  = $premium;
        $this->description = $description_forfait;
        $this->etablissement = new modele_etablissement($nom_etablissement, $adresse, $ville, $telephone, $courriel, $site_web, $description);
        
    }

    static function connecter() {
        
        $mysqli = new mysqli(Db::$host, Db::$username, Db::$password, Db::$database);

        // Vérifier la connexion
        if ($mysqli -> connect_errno) {
            http_response_code(500); // Envoi un code 500 au serveur
            $erreur = new stdClass();
            $erreur->message = "DEBOGAGE : Échec de connexion à la base de données MySQL: ";
            $erreur->error = $mysqli -> connect_error;
            echo json_encode($erreur);
            exit();
        } 

        return $mysqli;
    }

    public static function ObtenirTous() {
        $liste = [];
        $mysqli = self::connecter();

        $resultatRequete = $mysqli->query("SELECT * FROM forfait ORDER BY nom");

        foreach ($resultatRequete as $enregistrement) {
            $liste[] = new modele_forfait($enregistrement['id'], $enregistrement['nom'], $enregistrement['description_forfait'], $enregistrement['code'], $enregistrement['categories'], $enregistrement['date_debut'],
            $enregistrement['date_fin'], $enregistrement['prix'], $enregistrement['nouveau_prix'], $enregistrement['premium'], $enregistrement['nom_etablissement'], $enregistrement['adresse'], $enregistrement['ville'], $enregistrement['telephone'],
            $enregistrement['courriel'], $enregistrement['site_web'], $enregistrement['description']);
        }

        return $liste;
    }

    public static function ObtenirUn($id) {
        $resultat = new stdClass();

        $mysqli = self::connecter();

        if ($requete = $mysqli->prepare("SELECT * FROM forfait WHERE id=?")) {  // Création d'une requête préparée 
            $requete->bind_param("i", $id); // Envoi des paramètres à la requête

            $requete->execute(); // Exécution de la requête

            $resultat_requete = $requete->get_result(); // Récupération de résultats de la requête¸
            
            if($enregistrement = $resultat_requete->fetch_assoc()) { // Récupération de l'enregistrement
                $forfait = new modele_forfait($enregistrement['id'], $enregistrement['nom'], $enregistrement['description_forfait'], $enregistrement['code'], $enregistrement['categories'], $enregistrement['date_debut'],
                $enregistrement['date_fin'], $enregistrement['prix'], $enregistrement['nouveau_prix'], $enregistrement['premium'], $enregistrement['nom_etablissement'], $enregistrement['adresse'], $enregistrement['ville'], $enregistrement['telephone'],
                $enregistrement['courriel'], $enregistrement['site_web'], $enregistrement['description']);
            } else {
                http_response_code(404); // Envoi un code 404 au serveur
                $resultat->message = "Erreur: Aucun forfait trouvé";
                return $resultat;
            }   
            
            $requete->close(); // Fermeture du traitement
            return $forfait; 
        } else {
            http_response_code(500); // Envoi un code 500 au serveur
            $resultat->message = "Une erreur a été détectée dans la requête utilisée : ";
            $resultat->erreur = $mysqli->error;
            return $resultat;
        }        
    }
   
    public static function ajouter($nom, $description_forfait, $code, $categories, $date_debut, $date_fin, $prix, $nouveau_prix, $premium,
    $nom_etablissement, $adresse, $ville, $telephone, $courriel, $site_web, $description) {
        $resultat = new stdClass();

        $mysqli = self::connecter();
        
        // Création d'une requête préparée
        if ($requete = $mysqli->prepare("INSERT INTO forfait(nom, description_forfait, code, categories, date_debut, date_fin, prix, nouveau_prix, premium,
        nom_etablissement, adresse, ville, telephone, courriel, site_web, description) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
        ?, ?, ?, ?, ?)")) {      

        /************************* ATTENTION **************************/
        /* On ne fait présentement peu de validation des données.     */
        /* On revient sur cette partie dans les prochaines semaines!! */
        /**************************************************************/

        $requete->bind_param("ssssssddisssssss", $nom, $description_forfait, $code, $categories, $date_debut, $date_fin, $prix, $nouveau_prix, $premium,
        $nom_etablissement, $adresse, $ville, $telephone, $courriel, $site_web, $description);

        if($requete->execute()) { // Exécution de la requête
            $resultat->message = "Forfait ajouté";  // Message ajouté dans la page en cas d'ajout réussi
        } else {
            http_response_code(500); // Envoi un code 500 au serveur
            $resultat->message =  "Une erreur est survenue lors de l'ajout";  // Message ajouté dans la page en cas d’échec
            $resultat->erreur = $requete->error;
        }

        $requete->close(); // Fermeture du traitement

        } else {
            http_response_code(500); // Envoi un code 500 au serveur
            $resultat->message = "Une erreur a été détectée dans la requête utilisée : ";
            $resultat->erreur = $mysqli->error;
        }

        return $resultat;
    }



    public static function modifier( $id, $nom, $description_forfait, $code, $categories, $date_debut, $date_fin, $prix, $nouveau_prix, $premium,
    $nom_etablissement, $adresse, $ville, $telephone, $courriel, $site_web, $description) {
        $resultat = new stdClass();

        $mysqli = self::connecter();
        
        // Création d'une requête préparée
        if ($requete = $mysqli->prepare("UPDATE forfait SET nom=?, description_forfait=?, code=?, categories=?, date_debut=?, date_fin=?, prix=?, nouveau_prix=?, premium=?
        , nom_etablissement=?, adresse=?, ville=?, telephone=?, courriel=?, site_web=?, description=? WHERE id=?")) {      

        /************************* ATTENTION **************************/
        /* On ne fait présentement peu de validation des données.     */
        /* On revient sur cette partie dans les prochaines semaines!! */
        /**************************************************************/

        $requete->bind_param("ssssssddisssssssi", $nom, $description_forfait, $code, $categories, $date_debut, $date_fin, $prix, $nouveau_prix, $premium,
        $nom_etablissement, $adresse, $ville, $telephone, $courriel, $site_web, $description, $id);

        if($requete->execute()) { // Exécution de la requête
            $resultat->message = "Forfait modifié";  // Message ajouté dans la page en cas d'ajout réussi
        } else {
            http_response_code(500); // Envoi un code 500 au serveur
            $resultat->message =  "Une erreur est survenue lors de l'édition: ";  // Message ajouté dans la page en cas d’échec
            $resultat->erreur = $requete->error;
        }

        $requete->close(); // Fermeture du traitement

        } else  {
            http_response_code(500); // Envoi un code 500 au serveur
            $resultat->message = "Une erreur a été détectée dans la requête utilisée : ";
            $resultat->erreur = $mysqli->error;
        }

        return $resultat;
    }



    public static function supprimer($id) {
        $resultat = new stdClass();

        $mysqli = self::connecter();
        
        // Création d'une requête préparée
        if ($requete = $mysqli->prepare("DELETE FROM forfait WHERE id=?")) {      

        /************************* ATTENTION **************************/
        /* On ne fait présentement peu de validation des données.     */
        /* On revient sur cette partie dans les prochaines semaines!! */
        /**************************************************************/

        $requete->bind_param("i", $id);

        if($requete->execute()) { // Exécution de la requête
            $resultat->message = "Forfait supprimé";  // Message ajouté dans la page en cas d'ajout réussi
        } else {
            http_response_code(500); // Envoi un code 500 au serveur
            $resultat->message = "Une erreur est survenue lors de la suppression: ";  // Message ajouté dans la page en cas d’échec
            $resultat->erreur = $requete->error;
        }

        $requete->close(); // Fermeture du traitement

        } else  {
            http_response_code(500); // Envoi un code 500 au serveur
            $resultat->message = "Une erreur a été détectée dans la requête utilisée : ";
            $resultat->erreur = $mysqli->error;
        }

        return $resultat;
    }
    

    

    

}