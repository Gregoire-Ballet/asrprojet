<?php

require_once 'config/config.inc.php';
require_once 'config/bdd.conf.php';
require_once('components/smarty/libs/Smarty.class.php');

//$articleManager = new articleManager($bdd);
//$newArticle = $articleManager->get(1);
//print_r2($newArticle);
//print_r2($_SESSION);

$page = !empty($_GET['p']) ? $_GET['p'] : 1;

$articleManager = new articleManager($bdd);

$nbArticlesTotalAPublie = $articleManager->countArticlesPublie();
//print_r2($nbArticlesTotalAPublie);

$indexDepart = ($page - 1) * nb_articles_par_page;

$nbPages = ceil($nbArticlesTotalAPublie / nb_articles_par_page);

$listArticles = $articleManager->getListArticlesAAfficher($indexDepart, nb_articles_par_page);

//print_r2($listArticles);

$smarty = new Smarty();

$smarty->setTemplateDir('templates/');
$smarty->setCompileDir('templates_c/');

$smarty->assign('listArticles', $listArticles);
$smarty->assign('UtilisateurConnecte', $utilisateurConnecte);
$smarty->assign('nbPages', $nbPages);
$smarty->assign('page', $page);

include_once 'includes/header.inc.php';

$smarty->display('index.tpl');

include_once 'includes/footer.inc.php';

unset($_SESSION['notification']);
?>
 
