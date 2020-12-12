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


    print_r2($utilisateur);

    //insersion de l'article
    $utilisateurManager = new utilisateurManager($bdd);
    $utilisateurManager->add($utilisateur);
    //var_dump($utilisateurManager);
    //Traité l'image
    //if ($_FILES['image']['error'] == 0) {
    //rename l'image uploded
    //$fileInfos = pathinfo($_FILES['image']['name']);
    //move_uploaded_file($_FILES['image']['tmp_name'], 'img/' . 'image' . $articleManager->get_getLastInsertId() . '.' . $fileInfos['extension']);
//créer une session
    if ($utilisateurManager->get_result() == true) {
        $_SESSION['notification']['result'] = 'success';
        $_SESSION['notification']['message'] = 'votre utilisateur a ete ajouté';
    } else {
        $_SESSION['notification']['result'] = 'danger';
        $_SESSION['notification']['message'] = 'Une erreur est survenue pendant la création de votre utilisateur';
    }

    header("Location: index.php");
    exit();
} else {
//echo 'aucun formulaire est posté';
    include_once 'includes/header.inc.php';
    ?>

    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1 class="mt-5">Ajouter un utilisateur</h1>
                <p class="lead">Complete with pre-defined file paths and responsive navigation!</p>
                <ul class="list-unstyled">
                    <li>Bootstrap 4.5.0</li>
                    <li>jQuery 3.5.1</li>
                </ul>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <form id="articleForm" method="POST" action="utilisateur.php" enctype="multipart/form-data">

                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="nom">Nom :</label>
                            <input type="text" id="nom" name="nom" class="form-control" value="" placeholder="" required>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="prenom">Prénom :</label>
                            <input type="text" id="prenom" name="prenom" class="form-control" value="" placeholder="" required>
                        </div>
                    </div>
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
                    <button type="submit" id="submit" name="submit" class="btn btn-primary">Enregistrer</button>   
                </form>
            </div>
        </div>
    </div>


    <?php
    include_once 'includes/footer.inc.php';
}
?>
