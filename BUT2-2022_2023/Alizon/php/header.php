<?php 
  include("fonctions/databaseConnexion.php");
  include("fonctions/page_cookies.php");
  require_once("fonctions/fonctions.php");
    
    /*############### MENU DE CATEGORIES DYNAMIQUES ###############*/
    $sth = $dbh -> prepare("SELECT nom_categorie, nom as nom_Souscategorie FROM $schema._categorie INNER JOIN $schema._sous_categorie ON id_categorie_sup = id_categorie");
    $sth->execute();
    $res = $sth->fetchAll();

    $liste_SousCategories = array();
    foreach($res as $categorie){
        $liste_SousCategories[$categorie['nom_Souscategorie']] = $categorie['nom_categorie'];
    }

    $sth = $dbh -> prepare("SELECT nom_categorie FROM $schema._categorie");
    $sth->execute();
    $res = $sth->fetchAll();
    $liste_Categories = array();

    foreach($res as $categorie){
        array_push($liste_Categories,$categorie['nom_categorie']);
    }
    
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

<link rel='stylesheet' type='text/css' href='../css/style.css'>
<div class='sticky-header'>
  <div class="haut_de_page">

    <!-- TITRE ALIZON -->
    <a href="index.php" title="Accueil" class="logo">
        <img src="../img/logo2.0.png" alt="Logo Alizon" title="Logo Alizon" width='200' class="img_logo">
    </a>

    <!-- BARRE DE RECHERCHE FORMAT PC-->
    <form action="catalogue.php" method="GET" class="search-box">
      <input type="search" name="terme" class="search-txt" placeholder="Rechercher..." required>
        <button class="search-btn" type="submit">
            <i class="fas fa-search"></i>
        </button>
    </form>
    
    <div class="logos">
    <?php 
      // Si le client se déconnecte
      if(isset($_POST['deco'])){

        $_SESSION['id_client'] = null;
        setcookie("id_panier","");
        $_SESSION['deco'] = $_POST['deco'];
        
        if(substr($_SERVER["SCRIPT_FILENAME"], -9) === "index.php"){

          echo "<div class='alert deco no_bootstrap'>";
          echo "<i class='fa-regular fa-circle-check fa-2x'></i>";
          echo "<p>Vous êtes déconnecté </p>";
          echo "</div>";
        } else{
          echo "<div class='alert deco'>";
          echo "<i class='fa-regular fa-circle-check fa-2x'></i>";
          echo "<p>Vous êtes déconnecté </p>";
          echo "</div>";
        }

      }
      ?>

      <!-- VERIFICATION SI L'INTERNAUTE EST CONNECTE OU NON -->
      <?php
          
          // SI LE CLIENT EST CONNECTE
          if(isset($_SESSION['id_client'])) {
              $lien = "monCompte.php";
              $nom_client = infos_Client($_SESSION["id_client"])["nom_client"];
              $prenom_client = infos_Client($_SESSION["id_client"])["prenom_client"];
              echo "<div class='logo_compte' id='logo_c'>";
                echo "<img src='../img/user-solid.svg' alt='Mon Compte' class='img_compte' title='Mon Compte' width='40' height='40'>";
                echo "<p class='texte_img_compte'>Compte</p>";
              echo "</div>";
              echo "<div class='bloc-compte'>";
                echo "<div class='nom_compte'>";
                  echo "<i class='fa-solid fa-circle-user'></i>";
                  echo "<h3>".$prenom_client . " " . $nom_client . "</h3>";
                echo "</div>";
                echo "<hr>";
                echo "<a href='$lien'>Mon compte</a>";
                echo "<form action='#' method='post'>";
                echo "<input name='deco' type='submit' value='Se Déconnecter'>";
                echo "</form>";
              echo "</div>";
          }
          else {
              $lienC = "connexion.php";
              $lienI = "inscription.php";
              echo "<div class='logo_compte' id='logo_c'>";
              echo "<img src='../img/user-solid.svg' class='img_compte' alt='Mon Compte' title='Mon Compte' width='40' height='40'>";
              echo "<p class='texte_img_compte'>Compte</p>";
              echo "<div class='bloc-compte nonco'>";
              echo "<a href='$lienI'>S'inscrire</a>";
              echo "<a href='$lienC'>Se Connecter</a>";
              echo "</div>";
              echo "</div>";
              
          }
      ?>

      <!-- LOGO PANIER -->
      <a href="panier.php" class="logo_panier" id="logo_p">
          <img src="../img/shopping-bag-line.svg" class="panier" alt="Logo Panier" title="Votre panier" width="40" height="40">
          <p class="texte_panier">Panier</p>
          <?php
              if(isset($_COOKIE["id_panier"]))
              {
                $nb_articles = nb_articles_panier($_COOKIE["id_panier"]);
                if($nb_articles > 0)
                {
                  echo "<div class='nb_articles_panier'>";
                  echo $nb_articles;
                  echo "</div>";
                }
              }

          ?>
      </a>
    </div>
  </div>

    <!-- BARRE DE RECHERCHE FORMAT TELEPHONE-->
    <div class="fond_jaune">
        <form class="tel" method="GET" action="catalogue.php">
            <input type="search" placeholder="Rechercher..." name="terme">
            <button type="submit"><i class="fa fa-search"></i></button>
        </form>
    </div>

    <!-- BARRE DE NAVIGATION -->
    <div class="navbar">
      <!-- MENU BUGER FORMAT PC-->
      <div class="topnav">
        <ul class="topnav--left">
          <li class="header tel">
                  <div class="menu-btn">
                    <div class="menu-btn__lines"></div>
                  </div>
                  <ul class="menu-items">
                    <li class="dropdown">
                      <a href="#" class="menu-item expand-btn">Catégories</a>
                      <ul class="dropdown-menu expandable">
                        <?php foreach ($liste_Categories as $categorie):?>
                          <li class="dropdown dropdown-right">
                          <a href="#" class="menu-item expand-btn">
                            <?php echo $categorie;?>
                            <i class="fas fa-angle-right"></i>
                          </a>
                          <ul class="menu-right expandable">
                            <?php foreach ($liste_SousCategories as $SousCategorie => $SupCategorie):?>
                                <?php if($SupCategorie == $categorie):?> 
                                  <li><a href="catalogue.php?terme=<?php echo str_replace(' ', "_",$SousCategorie);?>"><?php echo $SousCategorie;?></a></li>  
                              <?php endif;?>   
                            <?php endforeach;?>  

                          </ul>
                        </li>
                          <?php endforeach;?>  

                        
                      </ul>
                    </li>
                    </ul>
            </li>
            <li class="header">
                  <div class="menu-btn">
                    <div class="menu-btn__lines"></div>
                  </div>
                  <ul class="menu-items">
                    <li class="mega-menu2">
                      <a href="#" class="menu-item expand-btn">Catégories</a>
                      <div class="mega-menu expandable">
                        <div class="content">
                          <div class="col">
                          
                          <section class="section-links1">
                              <h2><?php echo $liste_Categories[0];?></h2>
                              <ul class="mega-links">
                                <?php foreach ($liste_SousCategories as $SousCategorie => $SupCategorie):?>
                                    <?php if($SupCategorie == $liste_Categories[0]):?> 
                                      <li><a href="catalogue.php?categorie=<?php echo str_replace(' ', "_",$SupCategorie);?>&Scategorie=<?php echo str_replace(' ', "_",$SousCategorie);?>"><?php echo $SousCategorie;?></a></li>
                                  <?php endif;?>    
                                <?php endforeach;?>  
                              </ul>
                            </section>

                          </div>
                          <?php foreach ($liste_Categories as $categorie):?>
                            <?php if($categorie != $liste_Categories[0]):?> 
                              <div class="col">
                                <section>
                                <h2><?php echo $categorie;?></h2>
                                <ul class="mega-links">
                                  <?php foreach ($liste_SousCategories as $SousCategorie => $SupCategorie):?>
                                      <?php if($SupCategorie == $categorie):?> 
                                        <li><a href="catalogue.php?categorie=<?php echo str_replace(' ', "_",$SupCategorie);?>&Scategorie=<?php echo str_replace(' ', "_",$SousCategorie);?>"><?php echo $SousCategorie;?></a></li> 
                                    <?php endif;?>
                                  <?php endforeach;?>    
                                </ul>
                                </section>
                              </div>
                            <?php endif;?> 
                          <?php endforeach;?>          
                        </div>
                      </div>
                    </li>
                  </ul>
            </li>
            <li class="v-line"></li>
            <li><a href="">Meilleures Ventes</a></li>
            <li><a href="">Promotions</a></li>
        </ul>

        <div class="topnav--right">
            <a href="">Le Club</a>
            <a href="">Bons Plans</a>
        </div>
      </div>
    </div>
</div>

<script>
  /*############### ANIMATION MENU DE CATEGORIES ###############*/
  const menuBtn = document.querySelector(".menu-btn");
  const menuItems = document.querySelector(".menu-items");
  const expandBtn = document.querySelectorAll(".expand-btn");
  var elem = document.getElementsByClassName("img_compte");
  elem[0].addEventListener("mouseover", passageOrange);
  elem[0].addEventListener("mouseout", passageBlanc);

  var elem2 = document.getElementsByClassName("panier");
  elem2[0].addEventListener("mouseover", passageOrange2);
  elem2[0].addEventListener("mouseout", passageBlanc2);
  // humburger toggle
  menuBtn.addEventListener("click", () => {
    menuBtn.classList.toggle("open");
    menuItems.classList.toggle("open");
  });

  // mobile menu expand
  expandBtn.forEach((btn) => {
    btn.addEventListener("click", () => {
      btn.classList.toggle("open");
    });
  });

  function passageOrange(){
    elem[0].src = "../img/user-solid-orange.svg";
  }
      
  function passageBlanc(){
    elem[0].src = "../img/user-solid.svg";
  }


  function passageOrange2(){
    elem2[0].src = "../img/shopping-bag-line2.svg" ;
  }
      
  function passageBlanc2(){
    elem2[0].src = "../img/shopping-bag-line.svg" ;
  }

  let btn_compte = document.querySelector(".logo_compte");
  let bloc_compte = document.querySelector(".bloc-compte");

  // let user = document.querySelector(".logo_user");
  // let signIn_SignUp = document.querySelector(".Block_signIn_signUp");

  btn_compte.addEventListener("click", function (event) {
    bloc_compte.classList.toggle("actif");
    bloc_compte.classList.remove("nonactif");
    event.stopPropagation();
  });

  // user.addEventListener("click", function (event) {
  //   signIn_SignUp.classList.toggle("open");
  //   signIn_SignUp.classList.remove("hide");
  //   event.stopPropagation();
  // });

  // document.addEventListener("click", function (event) {
  //   if (!user.contains(event.target)) {
  //     signIn_SignUp.classList.remove("open");
  //     signIn_SignUp.classList.add("hide");
  //   }
  // });

  document.addEventListener("click", function (event) {
    if(!btn_compte.contains(event.target)) {
      bloc_compte.classList.remove("actif");
      bloc_compte.classList.add("nonactif");
    }
  })

</script>
