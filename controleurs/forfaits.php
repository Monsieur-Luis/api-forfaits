<?php

require_once './modeles/forfaits.php';

class ControlleurForfait{
    


    function afficherJSON() {
        $resultat = modele_forfait::ObtenirTous();
        echo json_encode($resultat);
    }

    function afficherFicheJSON($id) {
        $resultat = modele_forfait::ObtenirUn($id);
        echo json_encode($resultat);
    }

    function ajouterJSON($data) {
        $resultat = new stdClass();

        if(isset($data['nom']) && isset($data['description_forfait']) && isset($data['code']) && isset($data['categories']) && isset($data['date_debut'])
        && isset($data['date_fin']) && isset($data['prix']) && isset($data['nouveau_prix']) && isset($data['premium']) && isset($data['nom_etablissement'])
        && isset($data['adresse']) && isset($data['ville']) && isset($data['telephone']) && isset($data['courriel']) && isset($data['site_web'])
        && isset($data['description'])) {
            $resultat = modele_forfait::ajouter( $data['nom'], $data['description_forfait'], $data['code'], $data['categories'], $data['date_debut'], $data['date_fin']
            , $data['prix'], $data['nouveau_prix'], $data['premium'], $data['nom_etablissement'], $data['adresse'], $data['ville'], $data['telephone'], $data['courriel'], $data['site_web']
            , $data['description']);
        } else {
            http_response_code(500); // Envoi un code 500 au serveur
            $resultat->message = "Impossible d'ajouter un forfait. Des informations sont manquantes";
        }
        echo json_encode($resultat);
    }

    function modifierJSON($data) {
        $resultat = new stdClass();
        if(isset($_GET['id'])) {
            if(isset($data['nom']) && isset($data['description_forfait']) && isset($data['code']) && isset($data['categories']) && isset($data['date_debut']) && isset($data['date_fin'])
            && isset($data['prix'])&& isset($data['nouveau_prix'])&& isset($data['premium'])&& isset($data['nom_etablissement'])&& isset($data['adresse'])
            && isset($data['ville'])&& isset($data['telephone'])&& isset($data['courriel'])&& isset($data['site_web'])&& isset($data['description'])) {
                $resultat = modele_forfait::modifier($_GET['id'], $data['nom'], $data['description_forfait'], $data['code'], $data['categories'], $data['date_debut'], $data['date_fin']
                , $data['prix'], $data['nouveau_prix'], $data['premium'], $data['nom_etablissement'], $data['adresse'], $data['ville'], $data['telephone'], $data['courriel'], $data['site_web']
                , $data['description']); 
            } else {
                http_response_code(500); // Envoi un code 500 au serveur
                $resultat = "Impossible de modifier le forfait. Des informations sont manquantes";
            }
            
        } else {
            http_response_code(500); // Envoi un code 500 au serveur
            $resultat->message = "ID manquant";
        }  
        echo json_encode($resultat);     
    }

    function supprimerJSON() {
        $resultat = new stdClass();
        if(isset($_GET['id'])) {
            $resultat = modele_forfait::supprimer($_GET['id']);
        } else {
            http_response_code(500); // Envoi un code 500 au serveur
            $resultat->message = "ID manquant";
        }  
        echo json_encode($resultat);
    }

}