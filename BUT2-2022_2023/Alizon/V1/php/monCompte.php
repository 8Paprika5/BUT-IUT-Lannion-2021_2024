<?php

    // function update_information($id, $prenom, $nom, $date, $mail, $adresse_f) {
    //     // Mettre a jour les information du profil Client
    //     if(!empty($prenom)) {
    //         $this->db->update('_Client', 'prenom_client = $prenom', "ID_Client = $id");
    //     }
    //     if(!empty($nom)) {
    //         $this->db->update('_Client', $nom, "ID_Client = $id");
    //     }
    //     if(!empty($date) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
    //         $this->db->update('_Client', $date, "ID_Client = $id");
    //     }
    //     if(!empty($prenom)) {
    //         $this->db->update('_Client', $prenom, "ID_Client = $id");
    //     }
    //     if(!empty($adresse_f)) {
    //         $this->db->update('_Client', $adresse_f, "ID_Client = $id");
    //     }
    // }

    // public function update_mdp($mdp) {
    //     // Mettre a jour le mot de passe du Client

    // }

    // public function details_commande() {
    //     // afficher la liste des commandes en cours et effectuées
    // }
    
    // public function get_client() {
    //     return $this->db->where("email", $_POST['email']) && $this->db->where("mdp", $_POST['pwd']);
    // }

    // public function verif_infos() {
    //     $_SESSION['id_client'] = $this->db->select('(select ID_client from Alizon._Client where email = $_POST['email'] and mot_de_passe = $_POST['pwd']');
    //     $this->db->get("select ID_client from Alizon._Client where email = $_POST['email'] and mdp = $_POST['pwd']");
    // }
?>