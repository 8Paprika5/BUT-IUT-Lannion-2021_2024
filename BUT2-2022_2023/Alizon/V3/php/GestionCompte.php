<?php

    include("fonctions/Session.php");
    include("fonctions/fonctions.php");
    
    $_SESSION['id_admin'] = "1";

    if(!isset($_GET['client_terme'])) {
        // affichage par défaut
        $liste_Client = liste_Client();
        /**Tableau $liste_Client contenant des clients :
         * [ID_Client]
         * [nom_client]
         * [prenom_client]
         * [adresse_livraison]
         * [adresse_facturation]
         * [date_de_naissance]
         * [email]
         * [mdp] 
         * [QuestionReponse]
         * [active]
         */
    } else {
        // en cas de recherche de client
        $liste_Client = liste_Client_2($_GET['client_terme']);
    }

    if((isset($_POST['numList']) || isset($_GET['numList']))  && isset($_GET['id'])){
        //recupère le client selectionné dans la barre de navigation de gauche (liste des clients)
        if(isset($_POST['numList'])){
            $active = $_POST['numList'] ;
        }else{
            $active = $_GET['numList'] ;
        }
        $infoClient = infos_Client($_GET['id']);
        /**Tableau $infoClient
         * [ID_Client]
         * [nom_client]
         * [prenom_client]
         * [adresse_livraison]
         * [adresse_facturation]
         * [date_de_naissance]
         * [email]
         * [mdp] 
         * [QuestionReponse]
         * [active]
         * [ID_Panier]
         * [Prix_total_HT]
         * [Prix_total_TTC]
         * [derniere_modif] 
         */
    
    }else{            
        $active = 0;
        if(sizeof($liste_Client) > 0){
            $infoClient = infos_Client($liste_Client[0]['ID_Client']);
        }
    }

    if(isset($_POST['coordonnees'])) {
        // Envoie des nouvelles information en cas de modification des informations du client
            update_information($_GET['id'],$_POST['prenom'],$_POST['nom'],$_POST['DateDeNaissance'], $_POST['adresse'],$_POST['adresse']);
            header("Location: GestionCompte.php");
    }

    if(isset($_POST['supp'])){
        // désactivation du compte en cas de click sur le boutton
        desactiver_compte($_GET['ID_Client'], $_GET['ID_Panier']);
        header("Location: GestionCompte.php");
    }
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Gestion des Comptes</title>
    </head>
    <body>

        <header>
            <?php include('header_admin.php'); ?>
        </header>

        <main class="main-GestionCompte">    
            <aside class="ListeClients">
                <div>  
                    <div class="comptes_clients">
                        <?php 
                            if(sizeof($liste_Client)==0) {
                                echo "<p class='trouve_cli'>Aucun résultat trouvé";
                            }
                            else if(sizeof($liste_Client) == 1) {
                                echo "<p class='trouve_cli'>".sizeof($liste_Client)." résultat trouvé";
                            }
                            else {
                                echo "<p class='trouve_cli'>".sizeof($liste_Client)." résultats trouvés";
                            }
                        ?>
                        <?php for ($i=0; $i < sizeof($liste_Client); $i++): ?>
                            <?php if($liste_Client[$i]['active'] == 1):?>
                                <?php if($active == $i):?>
                                    <form action="GestionCompte.php?<?php echo 'id='.$liste_Client[$i]['ID_Client']?>" method="POST">
                                        <button class="ButtonGestionCompte active" name="numList" value="<?php echo $i;?>" type="submit">
                                            <?php echo strtoupper($liste_Client[$i]['nom_client']) ." ". $liste_Client[$i]['prenom_client']; ?>
                                        </button>
                                    </form>
                                    <hr>
                                <?php else:?>
                                    <form action="GestionCompte.php?<?php echo 'id='.$liste_Client[$i]['ID_Client']?>" method="POST">
                                        <button class="ButtonGestionCompte" name="numList" value="<?php echo $i;?>" type="submit">
                                            <?php echo strtoupper($liste_Client[$i]['nom_client']) ." ". $liste_Client[$i]['prenom_client']; ?>
                                        </button>
                                    </form>
                                    <hr>
                                <?php endif;?>
                            <?php endif;?>
                        <?php endfor; ?>
                    </div>
                </div>

            </aside>

            <div class="bloc_infos_client">

            <form action="GestionCompte.php" method="GET" class="search-box-cli">
                <input type="search" name="client_terme" class="search-txt-cli" placeholder="Rechercher un client..." required>
                <button class="search-btn-cli" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </form>

                <div class="client_non_trouve">
                    <p>Il n'y aucun client qui correspond à ce critère.</p>
                </div>

                <div class="bloc_infos_persos_client_gestion">

                    <h1>Coordonnees de <?php echo strtoupper($infoClient['nom_client'])." ".$infoClient['prenom_client'];?></h1>

                    <div class="coordonnees">

                        <form method="POST" action="GestionCompte.php?<?php echo 'id='.$infoClient['ID_Client'].'&numList='.$active ;?>">
                            <div class="formulaire">    

                                <div class="champ_infos_persos">
                                    <label for="fname">Nom</label>
                                    <input id="fname" type="text" name="nom" placeholder=<?php echo str_replace(" ","-",$infoClient['nom_client']);?>>
                                </div>

                                <div class="champ_infos_persos">
                                    <label for="fname">Prénom</label>
                                    <input id="fname" type="text" name="prenom" placeholder=<?php echo str_replace(" ","-",$infoClient['prenom_client']);?>>
                                </div>    

                                <div class="champ_infos_persos">
                                    <label for="fname">Email <i class="fa-solid fa-triangle-exclamation" style="color: red;" title="Impossible de modifier l'adresse mail"></i></label>
                                    <input type="email" name="email" readonly="readonly" title="Impossible de modifier l'adresse mail" value=<?php echo str_replace(" ","-",$infoClient['email']); ?>>
                                        
                                </div>

                                <div class="champ_infos_persos">
                                    <label for="fname">Date de naissance</label>
                                    <input id="fname" type="date" name="DateDeNaissance" value=<?php echo str_replace(" ","-",$infoClient['date_de_naissance']);?>>
                                </div>

                                <div class="champ_infos_persos">
                                    <label for="fname">Adresse</label>
                                    <input id="fname" type="text" name="adresse" placeholder=<?php echo str_replace(" ","-",$infoClient['adresse_facturation']);?>>
                                </div>
                                
                            </div>

                            <article class="btn-confirmer_gestion">
                                <input type="submit" id="confirmer" name="coordonnees" value="Confirmer"/>
                            </article>
                                
                        </form>

                        <form action="GestionCompte.php?<?php echo 'ID_Client='.$infoClient['ID_Client'].'&ID_Panier='.$infoClient['ID_Panier'] ;?>" method="POST" class="btn-suppr" name="formSupp">
                            <input type="submit" class="supprimer" name="supp" id="supp" onClick="verifDeco()" value="Désactiver le compte">
                        </form>
                            
                    </div>

                </div>
            </div>

            <script>
                // recherche du client dans la barre de recherche
                let client_trouve = document.querySelector(".bloc_infos_persos_client_gestion");
                let client_non_trouve = document.querySelector(".client_non_trouve");

                <?php if(sizeof($liste_Client)==0):?>
                    client_non_trouve.style.display = "block";
                    client_trouve.style.display = "none";
                <?php else:?>
                    client_trouve.style.display = "block";
                    client_non_trouve.style.display = "none";
                <?php endif;?>
            </script>

        </main>

        <footer>
            <?php include('footer.php'); ?>
        </footer>

        <script>
            function verifDeco(){
                let text = "Voulez-vous vraiment désactiver le compte de <?php echo $infoClient['nom_client']." ".$infoClient['prenom_client']; ?>";
                if (confirm(text) == true) {
                    formSupp.submit();
                } 
            }
        </script>   

    </body>
</html>