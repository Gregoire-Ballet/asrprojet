<?php

require_once 'bdd.conf.php';

if (isset($_COOKIE['sid'])) {
    $sid = $_COOKIE['sid'];
    $utilisateurManager = new utilisateurManager($bdd);
    $utilisateurConnecte = $utilisateurManager->getBySid($sid);
    if ($utilisateurConnecte->getEmail() != '') {
        $utilisateurConnecte->isConnect = true;
    } else {
        $utilisateurConnecte->isConnect = false;
    }
    //print_r2($utilisateurConnecte);
} else {
    $utilisateurConnecte = new utilisateurs();
    $utilisateurConnecte->isConnect = false;
    print_r2($utilisateurConnecte);
}
