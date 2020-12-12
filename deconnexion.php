<?php

require_once 'config/config.inc.php';
require_once 'config/bdd.conf.php';

setcookie('sid', '', -1);

$_SESSION['notification']['result'] = 'danger';
$_SESSION['notification']['message'] = 'Vous êtes déconnecté';

header("Location: index.php");
exit();
