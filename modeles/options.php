<?php

require_once "./include/config.php";


class modele_option{
    public $id;
    public $accessible_aux_personnes_handicapes;
    public $menu_sans_allergene;
    public $animaux_acceptes;
    
    
    

    public function __construct($id, $accessible_aux_personnes_handicapes, $menu_sans_allergene, $animaux_acceptes) {
        $this->id;
        $this->accessible_aux_personnes_handicapes = $accessible_aux_personnes_handicapes;
        $this->menu_sans_allergene = $menu_sans_allergene;
        $this->animaux_acceptes = $animaux_acceptes;
        
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

        $resultatRequete = $mysqli->query("SELECT * FROM options ORDER BY id");

        foreach ($resultatRequete as $enregistrement) {
            $liste[] = new modele_option($enregistrement['id'], $enregistrement['accessible_aux_personnes_handicapes'], $enregistrement['menu_sans_allergene'], $enregistrement['animaux_acceptes']);
        }

        return $liste;
    }

    

    

}