<?php

class commentaireManager {

    private $bdd;
    private $_result;
    private $_message;
    private $_commentaire;
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

    function get_commentaire() {
        return $this->_commentaire;
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

    function set_commentaire($_commentaire): void {
        $this->_commentaire = $_commentaire;
    }

    function get_getLastInsertId() {
        return $this->_getLastInsertId;
    }

    function set_getLastInsertId($_getLastInsertId): void {
        $this->_getLastInsertId = $_getLastInsertId;
    }

    public function add(commentaire $commentaire) {
        $sql = "INSERT INTO commentaire "
                . "(username, email, commentaire, id_article) "
                . "VALUES (:username, :email, :commentaire, :id_article)";

        $req = $this->bdd->prepare($sql);
        $req->bindValue(':username', $commentaire->getUsername(), PDO::PARAM_STR);
        $req->bindValue(':email', $commentaire->getEmail(), PDO::PARAM_STR);
        $req->bindValue(':commentaire', $commentaire->getCommentaire(), PDO::PARAM_STR);
        $req->bindValue(':id_article', $commentaire->getId_article(), PDO::PARAM_INT);
        $req->execute();


        if ($req->errorCode() == 00000) {
            $this->_result = true;
            $this->_getLastInsertId = $this->bdd->lastInsertId();
        } else {
            $this->_result = false;
        }
        return $this;
    }

    public function getList($id_article) {
        $listCommentaire = [];

        $sql = 'SELECT username, commentaire FROM commentaires INNER JOIN articles ON commentaires.id_article = articles.id WHERE articles.id = :id'; // On prépare la requête : on demande pseudo et commentaire dans la table commentaires quand l'id_article de la table commentaires correspond à l'id d'un article, avec une jointure donc entre la table commentaires et article.
        $req = $this->bdd->prepare($sql);

        $req->bindValue(':id', $id_article, PDO::PARAM_INT);
        $req->execute();


        while ($donnees = $req->fetch(PDO::FETCH_ASSOC)) {
            $commentaire = new commentaires();
            $commentaire->hydrate($donnees);
            $listCommentaire[] = $commentaire;
        }

        return $listCommentaire;
    }

}
