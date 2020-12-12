<?php
require_once 'config/config.inc.php';
require_once 'config/bdd.conf.php';

if ($utilisateurConnecte->isConnect == false) {
    $_SESSION['notification']['result'] = 'danger';
    $_SESSION['notification']['message'] = 'Vous devez être connecté';
    header("Location: connexion.php");
    exit();
}

//print_r2($_session);

if (isset($_POST['submit'])) {
    echo 'le formulaire est posté';
    //print_r2($_POST);
    //print_r2($_FILES);

    $article = new article();
    $article->hydrate($_POST);
    $article->setDate(date('Y-m-d'));

    $publie = $article->getPublie() === 'on' ? 1 : 0;

    $article->setPublie($publie);
    print_r2($article);

    //insersion de l'article
    $articleManager = new articleManager($bdd);
    $articleManager->add($article);
    //var_dump($articleManager);
    //Traité l'image
    if ($_FILES['image']['error'] == 0) {
        //rename l'image uploded
        $fileInfos = pathinfo($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], 'img/' . 'image' . $articleManager->get_getLastInsertId() . '.' . $fileInfos['extension']);
    }



//créer une session
    if ($articleManager->get_result() == true) {
        $_SESSION['notification']['result'] = 'success';
        $_SESSION['notification']['message'] = 'votre article a ete ajouté !';
    } else {
        $_SESSION['notification']['result'] = 'danger';
        $_SESSION['notification']['message'] = 'Une erreur est survenue pendant la création de votre article';
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
                <h1 class="mt-5">Créer un article</h1>
                <p class="lead">Complete with pre-defined file paths and responsive navigation!</p>
                <ul class="list-unstyled">
                    <li>Bootstrap 4.5.0</li>
                    <li>jQuery 3.5.1</li>
                </ul>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <form id="articleForm" method="POST" action="article.php" enctype="multipart/form-data">

                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="titre">Titre</label>
                            <input type="text" id="tite" name="titre" class="form-control" value="" placeholder="" required>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="texte">Le texte de mon article</label>
                            <textarea class="form-control" id="texte" name="texte" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="image">L'image de mon article</label>
                            <input type="file" class="form-control-file" id="image" name="image">
                        </div>
                    </div>    
                    <div class="col-lg-12">
                        <div class="form-group form-check">
                            <label class="form-check-label" id="publie" name="publie" for="publie">Article publié ?</label>
                            <input type="checkbox" checked class="form-check-input" id="publie">
                        </div>
                    </div>    
                    <button type="submit" id="submit" name="submit" class="btn btn-primary">Créer mon article</button>   
                </form>
            </div>
        </div>
    </div>


    <?php
    include_once 'includes/footer.inc.php';
}
?>
