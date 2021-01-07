<?php

class commentaires {

    public $id;
    public $username;
    public $email;
    public $commentaire;
    public $id_article;

    public function __construct() {
        
    }

    function getId() {
        return $this->id;
    }

    function getUsername() {
        return $this->username;
    }

    function getEmail() {
        return $this->email;
    }

    function getCommentaire() {
        return $this->commentaire;
    }

    function getId_article() {
        return $this->id_article;
    }

    function setId($id): void {
        $this->id = $id;
    }

    function setUsername($username): void {
        $this->username = $username;
    }

    function setEmail($email): void {
        $this->email = $email;
    }

    function setCommentaire($commentaire): void {
        $this->commentaire = $commentaire;
    }

    function setId_article($id_article): void {
        $this->id_article = $id_article;
    }

    public function hydrate($donnees) {
        if (isset($donnees['id'])) {
            $this->id = $donnees['id'];
        } else {
            $this->id = '';
        }

        if (isset($donnees['username'])) {
            $this->username = $donnees['username'];
        } else {
            $this->username = '';
        }

        if (isset($donnees['email'])) {
            $this->email = $donnees['email'];
        } else {
            $this->email = '';
        }

        if (isset($donnees['commentaire'])) {
            $this->commentaire = $donnees['commentaire'];
        } else {
            $this->commentaire = '';
        }

        if (isset($donnees['id_article'])) {
            $this->id_article = $donnees['id_article'];
        } else {
            $this->id_article = '';
        }
    }

}
