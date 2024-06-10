<?php
require_once 'helpers.php';
startSession()
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

    <?php
    $pageTitre = "Profil";
    $metaDescription = "Ceci est la page de profil";
    require_once 'header.php';
    require_once 'gestion-forms-connex.php';
    if(est_connecte() == false){
        redirect('/connexion.php');
    }else{
        $utilisateur = loadUtilisateur($_SESSION['utilisateurPseudo']);
    }
    ?>
    
    <h1>Profil</h1>
    <p>Pseudo: </p>
    <p><?=$utilisateur['uti_pseudo']?></p>
    <p>Email: </p>
    <p><?=$utilisateur['uti_email']?></p>

    <button>Déconnexion</button>

    <?php require_once 'footer.php'; ?>
</body>
</html>