<?php

require_once '../helpers/helpers.php';
require_once '../helpers/helpers_session.php';
require_once '../helpers/helpers_DB.php';

//Besoin d'initialiser les variables car la page est dans GET à la base (pas POST)
$formError = null;
$messagePseudoCo = null;
$messageMdpCo = null;

//S'active quand on appuie sur le bouton valider (soumet le form)
if($_SERVER["REQUEST_METHOD"] == "POST"){

    $formError = false;

    $pseudoCo = htmlspecialchars($_POST['pseudoCo']);
    $mdpCo = htmlspecialchars($_POST['mdpCo']);
    

    if(empty($pseudoCo) || strlen($pseudoCo) < 2 || strlen($pseudoCo) > 255){
        $formError = true;
        $messagePseudoCo = "Pseudo invalide";
    }

    //Si le message est vide ou moins de 10 carac. ou plus...: Affiche erreur
    if(empty($mdpCo) || strlen($mdpCo) < 8 || strlen($mdpCo) > 72){
        $formError = true;
        $messageMdpCo = "Mot de passe invalide";
    }

    if($formError == false) {
        try
        {

            $utilisateur = loadUtilisateurByPseudo($pseudoCo);

            // Si le compte existe
            if($utilisateur){

                // Prendre le mdp de l'utilisateur
                $hashFromDB = $utilisateur['uti_motdepasse'];

                // Vérifier le mdp rentré dans le form (comparer les hash)
                $mdpVerif = password_verify($mdpCo, $hashFromDB);

                if($mdpVerif){
                    $messageMdpCo = "Mdp correct";
                    connecter_utilisateur($utilisateur['uti_id']);
                    redirect('../profil/profil.php');

                }else{
                    $formError = true;
                    $messageMdpCo = "Ce mot de passe n'est pas correct";
                }

            }else{
                $formError = true;
                $messagePseudoCo = "Ce compte n'existe pas";
            }
        }
        
        // Récupère l'erreur s'il y en a
        catch(PDOException $e)
        {
            // Utilise la fonction dans helpers.php pour afficher l'erreur
            gerer_exceptions($e);
        }

    }

}




?>