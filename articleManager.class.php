<?php

class articleManager {

    //DECLARATIONS ET INSTANCIATIONS
    private $bdd; //Instance de PDO
    private $_result;
    private $_message;
    private $_article;
    private $_getLastInsertId;

    public function __construct(PDO $bdd) {
        $this->setBdd($bdd);
    }

    function getBdd() {
        return $this->bdd;
    }

    function get_result() {
        return $this->_result;
    }

    function get_message() {
        return $this->_message;
    }

    function get_article() {
        return $this->_article;
    }

    function setBdd($bdd): void {
        $this->bdd = $bdd;
    }

    function set_result($_result): void {
        $this->_result = $_result;
    }

    function set_message($_message): void {
        $this->_message = $_message;
    }

    function set_article($_article): void {
        $this->_article = $_article;
    }

    public function get($id) {
        $sql = 'SELECT * FROM articles WHERE id= :id';
        $req = $this->bdd->prepare($sql);

        //securisé la requête
        $req->bindValue(':id', PDO::PARAM_INT);
        $req->execute();

        $donnees = $req->fetch(PDO::FETCH_ASSOC);

        $article = new article();
        $article->hydrate($donnees);

        return $article;
    }

    function get_getLastInsertId() {
        return $this->_getLastInsertId;
    }

    function set_getLastInsertId($_getLastInsertId): void {
        $this->_getLastInsertId = $_getLastInsertId;
    }

    public function getList() {
        $listArticle = [];

        //Prepare requête sql sans limite
        $sql = 'SELECT id, '
                . 'titre, '
                . 'texte, '
                . 'publie, '
                . 'DATE_FORMAT(date, "%d/%m/%Y") as date '
                . 'FROM articles';
        $req = $this->bdd->prepare($sql);

        //execute la requête
        $req->execute();


        //tant que les resultats sont reçu on fait un tableau
        while ($donnees = $req->fetch(PDO::FETCH_ASSOC)) {
            $article = new article();
            $article->hydrate($donnees);
            $listArticle[] = $article;
        }
        //print_r2($listeArticle);
        return $listArticle;
    }

    public function add(article $article) {
        $sql = "INSERT INTO articles "
                . "(titre, texte, publie, date) "
                . "VALUES (:titre, :texte, :publie, :date)";

        $req = $this->bdd->prepare($sql);
        //secur variable
        $req->bindValue(':titre', $article->getTitre(), PDO::PARAM_STR);
        $req->bindValue(':texte', $article->getTexte(), PDO::PARAM_STR);
        $req->bindValue(':publie', $article->getPublie(), PDO::PARAM_INT);
        $req->bindValue(':date', $article->getDate(), PDO::PARAM_STR);
        //exec sql
        $req->execute();

        //retour erreur
        if ($req->errorCode() == 00000) {
            $this->_result = true;
            $this->_getLastInsertId = $this->bdd->lastInsertId();
        } else {
            $this->_result = false;
        }
        return $this;
    }

    public function countArticlesPublie() {
        $sql = "SELECT COUNT(*) as total FROM articles "
                . "WHERE publie = 1";
        $req = $this->bdd->prepare($sql);
        $req->execute();
        $count = $req->fetch(PDO::FETCH_ASSOC);
        $total = $count['total'];
        return $total;
    }

    public function getListArticlesAAfficher($depart, $limit) {
        $listArticle = [];

        //Prepare requête sql sans limite
        $sql = 'SELECT id, '
                . 'titre, '
                . 'texte, '
                . 'publie, '
                . 'DATE_FORMAT(date, "%d/%m/%Y") as date '
                . 'FROM articles '
                . 'WHERE publie = 1 '
                . 'LIMIT :depart, :limit';
        $req = $this->bdd->prepare($sql);

        $req->bindValue(':depart', $depart, PDO::PARAM_INT);
        $req->bindValue(':limit', $limit, PDO::PARAM_INT);

        //execute la requête
        $req->execute();


        //tant que les resulta son recu on fait un tableau
        while ($donnees = $req->fetch(PDO::FETCH_ASSOC)) {
            $article = new article();
            $article->hydrate($donnees);
            $listArticle[] = $article;
        }
        //print_r2($listeArticle);
        return $listArticle;
    }

    public function getListArticlesFromRecherche($recherche) {
        $listArticle = [];

        // Prépare une requête de type SELECT avec une clause WHERE selon l'id.
        $sql = 'SELECT id, '
                . 'titre, '
                . 'texte, '
                . 'publie, '
                . 'DATE_FORMAT(date, "%d/%m/%Y") as date '
                . 'FROM articles '
                . 'WHERE publie = 1 '
                . 'AND (titre LIKE :recherche '
                . 'OR texte LIKE :recherche)';
        $req = $this->bdd->prepare($sql);

        $req->bindValue(':recherche', "%" . $recherche . "%", PDO::PARAM_STR);

        // Exécution de la requête avec attribution des valeurs aux marqueurs nominatifs.
        $req->execute();

        // On stocke les données obtenues dans un tableau.
        while ($donnees = $req->fetch(PDO::FETCH_ASSOC)) {
            //On créé des objets avec les données issues de la table
            $article = new article();
            $article->hydrate($donnees);
            $listArticle[] = $article;
        }

        //print_r2($listArticle);
        return $listArticle;
    }

}
