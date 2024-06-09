<?php

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
            // Instancier la connexion à la base de données.
            $pdo = connexion_bdd();
            
            // Récupérer utilisateur en DB grâce à son pseudo
            $requete = "SELECT * FROM t_utilisateur_uti WHERE uti_pseudo = '$pseudoCo'";

            // Exécute la requête
            $stmt = $pdo->query($requete);
    
            // Récupérer le résultat sous le format de tableau associatif
            $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);


            // Si le compte existe
            if($utilisateur){

                // Prendre le mdp de l'utilisateur
                $hashFromDB = $utilisateur['uti_motdepasse'];

                // Vérifier le mdp rentré dans le form (comparer les hash)
                $mdpVerif = password_verify($mdpCo, $hashFromDB);

                if($mdpVerif){
                    $messageMdpCo = "Mdp correct";
                }else{
                    $messageMdpCo = "Ce mdp n'est pas correct";
                }

            }else{
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