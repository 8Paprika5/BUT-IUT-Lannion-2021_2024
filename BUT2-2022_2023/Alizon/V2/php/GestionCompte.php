<?php
    
    include("fonctions/Session.php");
    include("fonctions/fonctions.php");
    
    
    $liste_Client = liste_Client();

    if((isset($_POST['numList']) || isset($_GET['numList']))  && isset($_GET['id'])){
        if(isset($_POST['numList'])){
            $active = $_POST['numList'] ;
        }else{
            $active = $_GET['numList'] ;
        }
        $infoClient = infos_Client($_GET['id']);
    }else{            
        $active = 0;
        
        $infoClient = infos_Client($liste_Client[0]['ID_Client']);
    }
    //print_r($infoClient);

    if(isset($_POST['coordonnees'])) {
        $email = $_POST['email'];
        $sth = $dbh -> prepare("SELECT ID_Client FROM Alizon._Client WHERE email = '$email'");
        $sth->execute();
        $tabverif = $sth->fetchAll();
        $verif = 0;
        foreach ($tabverif as $row){
            $verif+=1;
        }
        if ($verif >= 1) {
            echo "Erreur : Cet email est déjà existante.";
        }else{
            update_information($_GET['id'],$_POST['prenom'],$_POST['nom'],$_POST['DateDeNaissance'], $_POST['email'], $_POST['adresse']);
            header("Location: GestionCompte.php");
            //header("Location: GestionCompte.php?id=".$_GET['id']);
        }
        
    }

    if(isset($_POST['supp'])){
        supprimer_compte($_GET['ID_Client'], $_GET['ID_Panier']);
        header("Location: GestionCompte.php");
    }
    
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <meta charset="utf-8" />
        <title>Gestion des Comptes</title>
        <meta name="description" content="" />
        <meta name="keywords" content="" />
        
    </head>
    <body>

        <header>
            <?php include('header.php'); ?>
        </header>

        <main class="main-GestionCompte">    
            <aside class="ListeClients">
                <section>  
                    <div class="comptes_clients">
                        <?php for ($i=0; $i < sizeof($liste_Client); $i++): ?>
                            <?php if($active == $i):?>
                                <form action="GestionCompte.php?<?php echo 'id='.$liste_Client[$i]['ID_Client']?>" method="POST">
                                    <button class="ButtonGestionCompte active" name="numList" value="<?php echo $i;?>" type="submit">
                                        <?php echo $liste_Client[$i]['nom_client'] ." ". $liste_Client[$i]['prenom_client']; ?>
                                    </button>
                                </form>
                            <?php else:?>
                                <form action="GestionCompte.php?<?php echo 'id='.$liste_Client[$i]['ID_Client']?>" method="POST">
                                    <button class="ButtonGestionCompte" name="numList" value="<?php echo $i;?>" type="submit">
                                        <?php echo $liste_Client[$i]['nom_client'] ." ". $liste_Client[$i]['prenom_client']; ?>
                                    </button>
                                </form>
                            <?php endif;?>
                        <?php endfor; ?>
                    </div>
                </section>

            </aside>

            <div class="bloc_infos_client">

                <div class="bloc_infos_persos_client_gestion">

                    <h1>Coordonnees de <?php echo $infoClient['nom_client']." ".$infoClient['prenom_client'];?></h1>

                    <article class ="coordonnees">

                        <form method="POST" action="GestionCompte.php?<?php echo 'id='.$infoClient['ID_Client'].'&numList='.$active ;?>">
                            <section class="formulaire">    

                                <article class="champ_infos_persos">
                                    <label for="fname">Nom</label><br>
                                    <input type="text" name="nom" placeholder=<?php echo str_replace(" ","-",$infoClient['nom_client']);?>>
                                </article>

                                <article class="champ_infos_persos">
                                    <label for="fname">Prénom</label><br>
                                    <input type="text" name="prenom" placeholder=<?php echo str_replace(" ","-",$infoClient['prenom_client']);?>>
                                </article>    

                                <article class="champ_infos_persos">
                                    <label for="fname">Email</label><br>
                                    <input type="email" name="email" placeholder=<?php echo str_replace(" ","-",$infoClient['email']);?>>
                                </article>

                                <article class="champ_infos_persos">
                                    <label for="fname">Date de naissance</label><br>
                                    <input type="date" name="DateDeNaissance" value=<?php echo str_replace(" ","-",$infoClient['date_de_naissance']);?>>
                                </article>

                                <article class="champ_infos_persos">
                                    <label for="fname">Adresse</label><br>
                                    <input type="text" name="adresse" placeholder=<?php echo str_replace(" ","-",$infoClient['adresse_facturation']);?>>
                                </article>
                                
                            </section>

                            <article class="btn-confirmer_gestion">
                                <input type="submit" id="confirmer" name="coordonnees" value="Confirmer"/>
                            </article>
                                
                        </form>

                        <form action="GestionCompte.php?<?php echo 'ID_Client='.$infoClient['ID_Client'].'&ID_Panier='.$infoClient['ID_Panier'] ;?>" method="POST" class="btn-suppr" name="formSupp">
                            <input class="supprimer" name="supp" id="supp" onClick="verifDeco()" value="Supprimer le compte">
                        </form>
                            
                    </article>

                </div>
            </div>

        </main>

        <footer>
            <?php include('footer.php'); ?>
        </footer>

        <script>
            function verifDeco(){
                let text = "Voulez-vous vraiment supprimer le compte de <?php echo $infoClient['nom_client']." ".$infoClient['prenom_client']; ?>";
                if (confirm(text) == true) {
                    formSupp.submit();
                } 
            }

            var client_selectionne = document.queryselector()
        </script>   

    </body>
</html>