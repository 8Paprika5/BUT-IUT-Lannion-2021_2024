
<?php 
    error_reporting(0);
    session_start();
    include("fonctions/databaseConnexion.php");
            /* CATEGORIES DYNAMIQUES */
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
        <div class="haut_de_page_admin">

            <!-- TITRE ALIZON -->
            <a href="index.php" title="Accueil" class="logo_admin">
                <img src="../img/logo2.0.png" alt="Logo Alizon" title="Logo Alizon" width='200' class="img_logo">
            </a>

            <!-- BARRE DE RECHERCHE FORMAT PC-->
            <form action="catalogue.php" method="GET" class="search-box">
            <input type="search" name="terme" class="search-txt" placeholder="Rechercher...">
                <button class="search-btn" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </form>
            
            <div class="logos">
            <!-- VERIFICATION SI L'INTERNAUTE EST CONNECTE OU NON -->
                <a href="GestionCompte.php" class='logo_compte' id='logo_c'>
                    <img src='../img/user-solid.svg' alt='Mon Compte' class='img_compte' title='Mon Compte' width='40' height='40'>
                    <p class='texte_img_compte_admin'>Gestion Comptes</p>
                </a>

                <a href="ExporterCatalogueAdmin.php" class="logo_exporter">
                    <i class="fa-solid fa-file-export fa-2x" style="color: #fff;"></i>
                    <p class='texte_img_compte_admin'>Exporter Catalogue</p>
                </a>
            <!-- LOGO Déconnexion -->
            <form action="header_admin.php" method="post">
                <div class="deconnexion-admin">
                    <i class="fa-solid fa-right-from-bracket fa-xl"></i>
                    <input type="submit" value="Se déconnecter" name="deco_admin">
                </div>
            </form>
            <?php
                if(isset($_POST["deco_admin"])) {
                    $_SESSION['admin_deconnecte'] = true;
                    unset($_SESSION["id_admin"]);
                    header("Location: ./index.php");
                    exit();
                } 
            ?>
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
            <div class="navbar_admin">
            <!-- MENU BUGER FORMAT PC-->
            <div class="topnav">
                <ul class="topnav--left">
                <li class="header_admin tel">
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
                    <li class="header_admin">
                        <div class="menu-btn">
                            <div class="menu-btn__lines"></div>
                        </div>
                        <ul class="menu-items">
                            <li class="mega-menu2-admin">
                            <a href="#" class="menu-item expand-btn">Catégories</a>
                            <div class="mega-menu-admin expandable">
                                <div class="content">
                                <div class="col">
                                
                                <section class="section-links1">
                                    <h2><?php echo $liste_Categories[0];?></h2>
                                    <ul class="mega-links">
                                        <?php foreach ($liste_SousCategories as $SousCategorie => $SupCategorie):?>
                                            <?php if($SupCategorie == $liste_Categories[0]):?> 
                                            <li><a href="catalogue.php?terme=<?php echo str_replace(' ', "_",$SousCategorie);?>"><?php echo $SousCategorie;?></a></li>  
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
                                                <li><a href="catalogue.php?terme=<?php echo str_replace(' ', "_",$SousCategorie);?>"><?php echo $SousCategorie;?></a></li>  
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
                </ul>
            </div>
        </div>

        <script>
        const menuBtn = document.querySelector(".menu-btn");
        const menuItems = document.querySelector(".menu-items");
        const expandBtn = document.querySelectorAll(".expand-btn");
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

        function passageBleu(){
                elem[0].src = "../img/user-solid-bleu.svg";
            }
            function passageBlanc(){
                elem[0].src = "../img/user-solid.svg";
            }
            var elem = document.getElementsByClassName("img_compte");
            elem[0].addEventListener("mouseover", passageBleu);
            elem[0].addEventListener("mouseout", passageBlanc);

        </script>