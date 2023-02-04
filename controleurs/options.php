<?php

require_once './modeles/options.php';

class ControlleurOption{
    

    function afficherJSON() {
        $options = modele_option::ObtenirTous();
        echo json_encode($options);
    }


}