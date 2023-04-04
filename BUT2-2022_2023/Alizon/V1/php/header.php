<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/ /font-awesome/4.7.0/css/font-awesome.min.css%22%3E" >
<link rel='stylesheet' type='text/css' href='../css/style.css'>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<div class="haut_de_page">

    <!-- MENU BURGER -->
    <div class="topnav">
    <a class="icon" onclick="afficheCategorie()"></a>
    <i class="fa fa-bars"></i>
    </div>

    <!-- TITRE ALIZON -->
    <h1><a href="index.php" title="Accueil">Alizon</a></h1>

    <!-- BARRE DE RECHERCHE -->
    <form class="example" action="catalogue.php">
        <input type="search" placeholder="Rechercher..."
            name="search">
        <button type="submit"><i class="fa fa-search"></i></button>
    </form>
    

    <!-- VERIFICATION SI L'INTERNAUTE EST CONNECTE OU NON => AFFICHER OU PAS LE LOGO DU COMPTE -->
    <?php
    
        // SI LE CLIENT EST CONNECTE
        if(isset($_SESSION['id_client'])) {
            $lien = "monCompte.php";
            echo "<a href=$lien>";
            echo "<img src='../img/user-solid.svg' alt='Mon Compte' title='Mon Compte' width='40px' height='40px'/>";
            echo "</a>"; 
        }
        else {
            $lien = "connexion.php";
            /* ################################################## */
            /* !! A CHANGER => mettre le bouton "S'identifier" !! */
            /* ################################################## */
            echo "<a href=$lien>";
            echo "<img src='../img/user-solid.svg' alt='Mon Compte' title='Mon Compte' width='40px' height='40px'/>";
            echo "</a>"; 
        }
    ?>

    <!-- LOGO PANIER -->
    <a href="panier.php">
        <img src="../img/shopping-bag-line.svg" alt="Logo Panier" title="Votre panier" class="panier" width="40px" height="40px"/>
    </a>
    
</div>

<!-- BARRE DE NAVIGATION -->
<div class="navbar">
    <li class="navbar--liens"><a href="">Nouveautés</a></li>
    <li class="navbar--liens"><a href="">Promotion</a></li>
    <li class="navbar--liens"><a href="">Meilleures ventes</a></li>
    <li class="navbar--liens"><a href="">Service client</a></li>
    <li class="navbar--liens"><a href="">L'entreprise</a></li>
</div>

<div id="myLinks">
    <a onmouseout="CacherSousCategorie()" onmouseover="afficheSousCategorie()">Categorie1</a>
    <a href="#contact">Categorie2</a>
    <a href="#about">Categorie3</a>
</div>

<div id="SubmyLinks" onmouseout="CacherSousCategorie()" onmouseover="afficheSousCategorie()">
    <a href="#news">SousCategorie1</a>
    <a href="#contact">SousCategorie2</a>
    <a href="#about">SousCategorie3</a>
    <a href="#news">SousCategorie4</a>
    <a href="#contact">SousCategorie5</a>
    <a href="#about">SousCategorie6</a>
</div>