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

        if(isset($data['nom']) && isset($data['description']) && isset($data['code']) && isset($data['dateDebut'])
        && isset($data['dateFin']) && isset($data['prix']) && isset($data['etablissement']['nomEtablissement'])
        && isset($data['etablissement']['adresse']) && isset($data['etablissement']['ville']) && isset($data['etablissement']['telephone']) && isset($data['etablissement']['courriel']) 
        && isset($data['etablissement']['siteWeb']) && isset($data['etablissement']['description'])) {
            $resultat = modele_forfait::ajouter( $data['nom'], $data['description'], $data['code'], '', $data['dateDebut'], $data['dateFin']
            , $data['prix'], 0, false, $data['etablissement']['nomEtablissement'], $data['etablissement']['adresse'], $data['etablissement']['ville'], 
            $data['etablissement']['telephone'], $data['etablissement']['courriel'], $data['etablissement']['siteWeb']
            , $data['etablissement']['description']);
        } else {
            http_response_code(500); // Envoi un code 500 au serveur
            $resultat->message = "Impossible d'ajouter un forfait. Des informations sont manquantes";
        }
        echo json_encode($resultat);
    }

    function modifierJSON($data) {
        $resultat = new stdClass();
        if(isset($_GET['id'])) {
            if(isset($data['nom']) && isset($data['description']) && isset($data['code']) && isset($data['dateDebut'])
            && isset($data['dateFin']) && isset($data['prix']) && isset($data['etablissement']['nomEtablissement'])
            && isset($data['etablissement']['adresse']) && isset($data['etablissement']['ville']) && isset($data['etablissement']['telephone']) && isset($data['etablissement']['courriel']) 
            && isset($data['etablissement']['siteWeb']) && isset($data['etablissement']['description'])) {
                $resultat = modele_forfait::modifier($_GET['id'], $data['nom'], $data['description'], $data['code'], '', $data['dateDebut'], $data['dateFin']
                , $data['prix'], 0, false, $data['etablissement']['nomEtablissement'], $data['etablissement']['adresse'], $data['etablissement']['ville'], 
                $data['etablissement']['telephone'], $data['etablissement']['courriel'], $data['etablissement']['siteWeb']
                , $data['etablissement']['description']); 
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