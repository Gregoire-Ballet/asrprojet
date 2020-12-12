<?php

class utilisateurManager {

//DECLARATIONS ET INSTANCIATIONS
    private $bdd; //Instance de PDO
    private $_result;
    private $_message;
    private $_utilisateur;
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

    function get_utilisateur() {
        return $this->_utilisateur;
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

    function set_utilisateur($_utilisateur): void {
        $this->_utilisateur = $_utilisateur;
    }

    public function get($id) {
        $sql = 'SELECT * FROM utilisateurs WHERE id= :id';
        $req = $this->bdd->prepare($sql);

//securisé la requête
        $req->bindValue(':id', PDO::PARAM_INT);
        $req->execute();

        $donnees = $req->fetch(PDO::FETCH_ASSOC);

        $utilisateur = new utilisateur();
        $utilisateur->hydrate($donnees);

        return $utilisateur;
    }

    public function getByEmail($email) {
        $sql = 'SELECT * FROM utilisateurs WHERE email= :email';
        $req = $this->bdd->prepare($sql);

//securisé la requête
        $req->bindValue(':email', $email, PDO::PARAM_STR);
        $req->execute();

        $donnees = $req->fetch(PDO::FETCH_ASSOC);

        $utilisateur = new utilisateurs();
        $utilisateur->hydrate($donnees);

        return $utilisateur;
    }

    function get_getLastInsertId() {
        return $this->_getLastInsertId;
    }

    function set_getLastInsertId($_getLastInsertId): void {
        $this->_getLastInsertId = $_getLastInsertId;
    }

    public function getList() {
        $listUtilisateurs = [];

//Prepare requête sql sans limite
        $sql = 'SELECT id, '
                . 'nom, '
                . 'prenom, '
                . 'email, '
                . 'mdp '
                . 'sid '
                . 'FROM utilisateurs ';
        $req = $this->bdd->prepare($sql);

//execute la requête
        $req->execute();


//tant que les resultats son recu on fait un tableau
        while ($donnees = $req->fetch(PDO::FETCH_ASSOC)) {
            $utilisateur = new utilisateurs();
            $utilisateur->hydrate($donnees);
            $listUtilisateurs[] = $utilisateur;
        }
//print_r2($listeutilisateur);
        return $listUtilisateurs;
    }

    public function getBySid($sid) {
        $sql = 'SELECT * FROM utilisateurs WHERE sid = :sid';
        $req = $this->bdd->prepare($sql);

//securisé la requête
        $req->bindValue(':sid', $sid, PDO::PARAM_STR);
        $req->execute();

        $donnees = $req->fetch(PDO::FETCH_ASSOC);

        $utilisateur = new utilisateurs();
        $utilisateur->hydrate($donnees);

        return $utilisateur;
    }

    public function add(utilisateurs $utilisateur) {
        $sql = "INSERT INTO utilisateurs "
                . "(nom, prenom, email, mdp, sid) "
                . "VALUES (:nom, :prenom, :email, :mdp, :sid)";

        $req = $this->bdd->prepare($sql);
//secur variable
        $req->bindValue(':nom', $utilisateur->getNom(), PDO::PARAM_STR);
        $req->bindValue(':prenom', $utilisateur->getPrenom(), PDO::PARAM_STR);
        $req->bindValue(':email', $utilisateur->getEmail(), PDO::PARAM_STR);
        $req->bindValue(':mdp', password_hash($utilisateur->getMdp(), PASSWORD_DEFAULT), PDO::PARAM_STR);
        $req->bindValue(':sid', $utilisateur->getSid(), PDO::PARAM_STR);
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

    public function updateByEmail(utilisateurs $utilisateur) {
        $sql = "UPDATE utilisateurs SET sid = :sid WHERE email = :email";
        $req = $this->bdd->prepare($sql);
//secur variable
        $req->bindValue(':email', $utilisateur->getEmail(), PDO::PARAM_STR);
        $req->bindValue(':sid', $utilisateur->getSid(), PDO::PARAM_STR);
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

}
