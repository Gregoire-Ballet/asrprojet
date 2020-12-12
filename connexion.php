<?php
require_once 'config/config.inc.php';
require_once 'config/bdd.conf.php';
//print_r2($_session);

if (isset($_POST['submit'])) {
    echo 'Utilisateur ajouté';
    //print_r2($_POST);
    //print_r2($_FILES);

    $utilisateur = new utilisateurs();
    $utilisateur->hydrate($_POST);


    //print_r2($utilisateur);
//recherche de l'utilisateur en bdd
    $utilisateurManager = new utilisateurManager($bdd);
    $utilisateurenbdd = $utilisateurManager->getByEmail($utilisateur->getEmail());
    //print_r2($utilisateurenbdd);
    //exit();

    $isconnect = password_verify($utilisateur->getMdp(), $utilisateurenbdd->getMdp());
    //  var_dump($isconnect);
//exit();
    if ($isconnect == true) {
        $sid = md5($utilisateur->getEmail() . time());
        //echo $sid
        setcookie('sid', $sid, time() + 86400);
        //Mise en bdd du sid 
        $utilisateur->setSid($sid);
        $utilisateurManager->updateByEmail($utilisateur);
        //var_dump($utilisateurManager->get_result());
    }



//créer une session
    if ($isconnect == true) {
        $_SESSION['notification']['result'] = 'success';
        $_SESSION['notification']['message'] = 'Vous êtes connecté(e)';
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['notification']['result'] = 'danger';
        $_SESSION['notification']['message'] = 'Une erreur est survenue pendant la connexion';
        header("Location: connexion.php");
        exit();
    }
} else {
//echo 'aucun formulaire est posté';
    include_once 'includes/header.inc.php';
    ?>

    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1 class="mt-5">Connexion utilisateur</h1>
                <p class="lead">Complete with pre-defined file paths and responsive navigation!</p>
                <ul class="list-unstyled">
                    <li>Bootstrap 4.5.0</li>
                    <li>jQuery 3.5.1</li>
                </ul>
            </div>
        </div>
        <?php if (isset($_SESSION['notification'])) { ?>

            <!-- verifie la session -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-<?= $_SESSION['notification']['result'] ?>" role="alert">
                        <?= $_SESSION['notification']['message'] ?>
                    </div>
                </div>
            </div>
            <!-- tuer la session -->
            <?php
            unset($_SESSION['notification']);
        }
        ?>

        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <form id="articleForm" method="POST" action="connexion.php" enctype="multipart/form-data">


                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="email">Email :</label>
                            <input type="mail" id="email" name="email" class="form-control" value="" placeholder="" required>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="mdp">Mot de passe :</label>
                            <input type="password" id="mdp" name="mdp" class="form-control" value="" placeholder="" required>
                        </div>
                    </div>
                    <button type="submit" id="submit" name="submit" class="btn btn-primary">Se connecter</button>   
                </form>
            </div>
        </div>
    </div>


    <?php
    include_once 'includes/footer.inc.php';
}
?>
