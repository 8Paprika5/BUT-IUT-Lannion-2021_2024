<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

<?php
    if(isset($_POST["deco_vendeur"])) {
        $_SESSION["vendeur_deconnecte"] = true;
        header("Location: ./index.php");
        exit();
    }

    $donneesVendeur = recupDonneeVendeur($_SESSION["vendeur"][1]);
?>
<header class="header-vendeur">
            <!-- TITRE ALIZON -->
        <a href="index.php" title="Accueil" class="logo">
            <img src="../img/logo2.0.png" alt="Logo Alizon" title="Logo Alizon" width='200' class="img_logo">
        </a>

        <nav class="nav-vendeur">

            <div>
                <i class="fa-solid fa-table-columns fa-xl"></i>
                <a href="vendeur.php"><p>Dashboard</p></a>
            </div>
            <hr>
            <div class="commandes-vendeur">
                <i class="fa-solid fa-bag-shopping fa-xl"></i>
                <a href="vendeur.php"><p>Commandes</p></a>
                
            </div>

            <div class="catalogue-vendeur active">
            <i class="fa-solid fa-folder-open fa-xl"></i>
                <a href="vendeur.php"><p>Catalogue</p></a>
            </div>

            <div class="compte-vendeur">
                    <i class="fa-solid fa-user fa-xl"></i>
                    <a href="vendeur.php"><p>Mon compte</p></a>
            </div>

            <form action="header_vendeur.php" method="post">
                <div class="deconnexion-vendeur">
                    <i class="fa-solid fa-right-from-bracket fa-xl"></i>
                    <input type="submit" value="Se déconnecter" name="deco_vendeur">
                </div>
            </form> 
        </nav>
        <div class="row_vendeur">
            <?php
                    
                    echo "<img class='lien_vendeur' src='../img/vendeur/".$donneesVendeur[0]['ID_Vendeur']."/img1.webp' alt='marche stp'>";
                    echo "<h4 class='nom_vendeur'>" .$donneesVendeur[0]['Nom_vendeur']. "</h4>";
                  
                ?>
            </div>
</header> 

