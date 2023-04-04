<?php
    // error_reporting(0);
    include("fonctions/Session.php");
    include("fonctions/fonctions.php");
    
    $catalogue = null;
    
    //Ajout au panier du produit
    if(isset($_POST['ajoutPanier'])) {
        ajoutPanier($_GET['id_produit'], 1);
    }
    //Initialisation des valeurs min et max
    $min = prix_min()["min(Prix_vente_TTC)"];
    $max = prix_max()["max(Prix_vente_TTC)"];

     // Recherche par Mot Clés
    if(isset($_GET['terme'])) {
        $terme = $_GET["terme"];
    }

    // Recherche par Categorie (navbar)
    if(isset($_GET['categorie'])) {
        $categorie_n = $_GET['categorie'];
        $Scategorie_n = $_GET['Scategorie'];
    }

    // Changement des valeurs min et max du filtre
    if (isset($_GET['Min'])OR isset($_GET['Max'])){
        $min = $_GET['Min'];
        $max = $_GET['Max'];
    }    
   
    $catalogue = filtre_prix();
    /** 
     ** Tableau $catalogue
     *   ['nom_categorie']
     *   ['nom_produit']          
     *   ['prix_vente_ht']
     *   ['prix_vente_ttc']
     *   ['quantite_disponnible']
     *   ['description_produit']
     *   ['id_produit']   
     *   ['images1']
     *   ['images2']
     *   ['images3']
     *   ['nom_souscategorie']
     *   ['moyenne_note_produit']
     *
     */
    

    if(isset($terme) AND (strlen($terme) != 0)) {
        $terme = strtolower($terme);
        if(strlen($terme) <= 3) {
            $terme = ucfirst($terme);
        }if(isset($_GET['Min'])OR isset($_GET['Max'])){
            $catalogue = filtre_prix();
        }else{
            $catalogue = donnees_catalogue_mot_cles($terme);
        }
        
    }else {
        $catalogue = donnees_catalogue();    
    }
    echo '<script> document.cookie = "lienPage=; expires=Thu, 01 Jan 1970 00:00:00 UTC" </script>';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
    <body>
        <?php
                if (isset($_SESSION["id_admin"])){
                    echo "<header>";
                    include("header_admin.php");
                    echo "</header>";
                }
                else{
                    if (isset($_SESSION["vendeur"]))
                    {
                        include("header_vendeur.php");
                    }
                    else {
                        echo "<header>";
                        include('header.php');
                        echo "</header>";
                    }
                }
            ?>
        <?php if(isset($_GET["produitajouté"])): ?>
        <div class='alert ajouterpanier'>
            <i class='fa-regular fa-circle-check fa-2x'></i>
            <p>Produit Ajouté</p>
        </div>
        <?php endif; ?>
        <main class="main-catalogue">
            <section class = "haut_catalogue">
                
                <?php 
                //Permet d'afficher un text différents selon la catégorie choisie
                if(sizeof($catalogue) == 0) { 
                    if(isset($_GET['Min'])OR isset($_GET['Max'])){
                        echo "<p class='erreur'>Aucun produit entre  ".$min."€ et ".$max."€"."</p>";
                    }else{
                        echo "<p class='erreur'>Aucun résultat pour ".str_replace('_',' ',$terme)."</p>"; 
                    } 
                }
                else { 
                    if(isset($_GET['Min'])OR isset($_GET['Max'])){
                        echo "<p class='trouve'>".sizeof($catalogue)." résultats trouvés entre ".$min."€ et ".$max."€"."</p>";
                    } else if(sizeof($catalogue) == 1) {
                        echo "<p class='trouve'>1 résultat pour ".str_replace('_',' ',$terme)."</p>";
                    } else if (sizeof(($catalogue)) == nbr_produits()[0]["Count(*)"]) {
                        echo "<p class='trouve plus'></p>";
                    } else {
                        echo "<p class='trouve'>".sizeof($catalogue)." résultats trouvés    </p>";
                    }
                }
                ?>
                <div id="sorting-options">
                    <details class="detailTri">
                        <summary class="triTitre"><h2>Trier par </h2></summary>

                        <div class="boutons_tri">
                            <button id="ascending-sort" class="boutonTri" onclick="sortProducts('asc', event)">Prix croissant</button>
                            <button id="descending-sort" class="boutonTri" onclick="sortProducts('desc', event)">Prix décroissant</button> 
                        </div>

                    </details>
                </div>
            </section>

            <?php 
                $vendeurs = all_vendeurs();
            ?>

            <section class="milieu_catalogue">
                <div class="liste-filtre">
                    <div class="titre-filtre">
                        <h1>Filtrer par Catégories</h1>
                    </div>
                    <div class="titre_filtres_resp">
                        <h2>Filtres Vendeur | Catégories</h2>
                        <img src="../img/fermer.png" alt="Logo Femrer Fenetre" title="Fermer les filtres" width='25px' height='25px' class="fermer_filtres_cat">
                    </div>
                    <form name="filters" method="get">
                        <div class="categorie-catalogue">
                            <?php 
                                $listeCat = liste_Categories_Only(); 
                                $liste_Sous_Cat = liste_Sous_Categories_Only();
                            ?>
                            <details class="details0">
                                <summary class="categorie"><h2>Vendeur</h2></summary>
                                
                                <?php foreach($vendeurs as $vendeur):?>
                                    <div class="sous-categorie categorie-vendeur" for=<?php echo ($vendeur['Nom_vendeur']);?>>
                                        <?php $nom_vendeur_propre = str_replace(' ', "_",$vendeur['Nom_vendeur']);?>
                                        <label for=<?php echo ($nom_vendeur_propre);?> class="sous-categorie-catalogue"><?php echo ($vendeur["Nom_vendeur"]);?></label>
                                        <input name=<?php echo ($nom_vendeur_propre);?> type="checkbox"  id=<?php echo ($nom_vendeur_propre);?> onchange="checkFiltre('<?php echo ($nom_vendeur_propre);?>')">
                                    </div>
                                <?php endforeach; ?>

                            </details>
                            <?php for ($i = 0; $i < sizeof($listeCat);$i++): ?>
                            <details class="details<?php echo $i+1; ?>">
                                <summary class="categorie"><h2><?php echo $listeCat[$i]["Nom_Categorie"];?></h2></summary>
                                
                                <!-- Liste des sous-catégories -->
                                <?php for($j = 0; $j < sizeof($liste_Sous_Cat); $j++) : ?>
                                    <?php if ($liste_Sous_Cat[$j]["Id_Categorie_Sup"] == $listeCat[$i]["ID_Categorie"]):?>
                                    <div class="sous-categorie categorie-produit" for="<?php echo $liste_Sous_Cat[$i]["nom"]?>">
                                        <label for="<?php echo str_replace(' ', "_",$liste_Sous_Cat[$j]["nom"]);?>" class="sous-categorie-catalogue"><?php echo $liste_Sous_Cat[$j]["nom"]?></label> <?php $liencat = str_replace(' ', "_",$liste_Sous_Cat[$j]["nom"]);?>
                                        <input name="<?php echo str_replace(' ', "_",$liste_Sous_Cat[$j]["nom"]);?>" class="checkbox" type="checkbox"  id = "<?php echo str_replace(' ', "_",$liste_Sous_Cat[$j]["nom"]);?>" onchange = 'checkFiltre("<?php echo $liencat?>")'>
                                    </div>
                                    <?php endif;?>
                                <?php endfor; ?>
                            </details>
                            <?php endfor; ?>
                        </div>

                        <form class="range_container" method = "get" name = "FiltrePrix" id = "FiltrePrix">
                            <div class="sliders_control">
                                <input id="fromSlider" type="range" value="<?php echo "$min"?>" min="0" max="999" onchange = "checkFiltre(null)"/>
                                <input id="toSlider" type="range" value="<?php echo "$max"?>" min="0" max="999" style ="z-index: 0;" onchange = "checkFiltre(null)"/>
                            </div>
                            <div class="form_control">
                                <div class="form_control_container">
                                    <div class="form_control_container__time">Min</div>
                                    <input class="form_control_container__time__input" type="number" id="fromInput" value="<?php echo "$min"?>" min="0" max="999" onchange = "checkFiltre(null)"/>
                                </div>
                                <div class="form_control_container">
                                    <div class="form_control_container__time">Max</div>
                                    <input class="form_control_container__time__input" type="number" id="toInput" value="<?php echo "$max"?>" min="0" max="999" onchange = "checkFiltre(null)"/>
                                </div>
                            </div>
                        </form>

                        <form action="catalogue.php" method="Get" class="form_suppr_filtres">
                            <button class="normal-button rouge supprimer-filtre">Supprimer tous les filtres</button>
                        </form>
                        
                    </form>

                    <div class="boutons_resp_filtres">    
                        <button class="valider_resp_filtres normal-button">Valider</button>
                    
                        <form action="catalogue.php" method="Get" class="form_suppr_filtres">
                            <button class="normal-button rouge supprimer-filtre resp_filtres_suppr">Supprimer tous les filtres</button>
                        </form>
                    </div>

                </div>
                <div class="article_cat">
                    <?php foreach ($catalogue as $id => $produit):?>
                        <div id = "<?php echo $id;?>" class="carte_produit_ajout <?php echo str_replace(' ', "_",$produit["nom_souscategorie"]); ?> <?php echo $produit["Nom_vendeur"]; ?>">
                        
                            <a class="carte_produit_lien" href="produit.php?idProduit=<?php echo $produit["id_produit"]; ?>">
                                <img src="<?php echo str_replace(' ', "_","../img/catalogue/Produits/".$produit['id_produit']."/".$produit["images1"] );?>" alt="photo du produit">
                            </a>
                            <h3><?php echo $produit["nom_produit"]; ?></h3>
                            <div class="stock_prixAvis">
                                <?php if($produit["quantite_disponnible"]>=1):?>
                                    <h3 class="produit_enStock">En Stock</h3>
                                <?php else: ?>
                                    <h3 class="produit_horsStock">Hors Stock</h3>
                                <?php endif;?>
                                <div class="prix_avis">
                                <div class="stars">
                                    <?php
                                    //Pour afficher les étoiles de notations
                                        for ($i = 0; $i < $produit['moyenne_note_produit'] ; $i++)
                                        {
                                            echo '<i class="fa fa-star gold"></i>';
                                        }
                                        for ($i = 0; $i < 5-$produit['moyenne_note_produit']; $i++){
                                            echo '<i class="fa fa-star grey"></i>';
                                        }
                                        
                                        ?>
                                        
                                    </div>
                                    <h4><?php echo $produit["prix_vente_ttc"]."€";?></h4>
                                </div>
                            </div>
                            <div class="carte_produit_ajout--boutons__panier">
                                <?php $vendeur = infos_vendeur($produit["id_produit"])[0];?>
                                <button class="infoVendeurProduit"> Vendu et expédié par <?php echo $vendeur['Nom_vendeur']; ?> </button>
                                <form id = "formAction" action="catalogue.php?<?php if(isset($terme)) {echo 'id_produit='.$produit["id_produit"].'&terme='.$terme;} else {echo 'id_produit='.$produit["id_produit"];} ?>&produitajouté" method="POST" >
                                    <button type='submit' class="ajoutPanier" name='ajoutPanier'> Ajouter au panier </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                        <div class="block-CatalogueVide">
                            <h1 class="titre-panierVide">Aucun produit disponible avec les critères sélectionnés</h1>
                    
                                <div class="block-texte_panierVide">
                                <img src="../img/catalogueVide.png" alt="Catalogue vide" title="Catalogue vide" width="350" height="300px">
                                   
                                </div>
                        </div>
                </div>
            </Section>

            <div class="filtres_responsive">
                <div>
                    <button class="cat_responsives">Filtres</button>
                    <button class="prix_responsives">Trier par</button>
                </div>
            </div>

            <div class="fond_opacity"></div>

            <div class="filtres_prix_responsives_block">
                <div id="sorting-options_resp">
                    <div class="boutons_tri">
                        <button id="ascending-sort" class="boutonTri_resp" onclick="sortProducts('asc', event)">Prix croissant</button>
                        <hr>
                        <button id="descending-sort" class="boutonTri_resp" onclick="sortProducts('desc', event)">Prix décroissant</button> 
                    </div>
                </div>
            </div>
            
        </main>
        <footer>
            <?php include("footer.php"); ?>
        </footer>
    </body>
</html>

<script>

    let main = document.querySelector(".main-catalogue");

    let filtres_prix = document.querySelector(".prix_responsives");
    let filtres_cat = document.querySelector(".cat_responsives");

    let block_tri_resp = document.querySelector(".filtres_prix_responsives_block");
    let block_cat_resp = document.querySelector(".liste-filtre");

    console.log(block_cat_resp);

    let fermerCat = document.querySelectorAll(".fermer_filtres_cat");
    let fermerTri = document.querySelectorAll(".fermer_filtres_tri");

    let BtnValide = document.querySelector(".valider_resp_filtres");

    const isHidden1 = () => block_tri_resp.classList.contains("form-popup2--affiche");
    var fond_opacity = document.querySelector(".fond_opacity");

    filtres_prix.addEventListener("click", () => {
        block_tri_resp.style.display = "block";
        fond_opacity.style.display = "block";
    });

    filtres_cat.addEventListener("click", () => {
        block_cat_resp.style.display = "block";
        main.style.overflowY = "hidden";
    });

    fermerCat[0].addEventListener("click", () => {
        block_cat_resp.style.display = "none";
        main.style.overflowY = "visible";
    });

    BtnValide.addEventListener("click", () => {
        block_cat_resp.style.display = "none";
        main.style.overflowY = "visible";
    });

    block_tri_resp.addEventListener("click", () => {
        block_tri_resp.style.display = "none";
        fond_opacity.style.display = "none";
    })

    block_tri_resp.addEventListener("transitionend", function () {
        if (isHidden1()) {
            block_tri_resp.style.display = "block";
            fond_opacity.style.display = "block";
        }
    });

    filtres_prix.addEventListener("click", function (e) {
        if (isHidden1()) {
            fond_opacity.style.removeProperty("display");
            setTimeout(() => block_tri_resp.classList.remove("fond_opacity--affiche"), 0);
        } 
        else {
            block_tri_resp.classList.add("form-popup2--affiche");
            fond_opacity.classList.add("fond_opacity--affiche");
        }
    });

    let categorie_nav = null;
    let Scategorie_nav = null;

    <?php if(isset($categorie_n)):?>
        categorie_nav = '<?php echo str_replace(' ', "_",$categorie_n)?>';
        Scategorie_nav = '<?php echo str_replace(' ', "_",$Scategorie_n)?>';
    <?php endif;?>

    // Tab pour garder en mémoire les checkbox
    let checkboxTab = [];

//Fonction pour le filtre du prix

/**
     * Description : 
     * met à jour la valeur du curseur "from" et du champ de saisie "from" en fonction de la valeur du champ de saisie "to"
     * 
     * 
     * ¨Pas de return
     * 
     */
    function controlFromInput(fromSlider, fromInput, toInput, controlSlider) {
        const [from, to] = getParsed(fromInput, toInput);
        fillSlider(fromInput, toInput, '#C6C6C6', '#FFB703', controlSlider);
        if (from > to) {
            fromSlider.value = to;
            fromInput.value = to;
        } else {
            fromSlider.value = from;
        }
    }
    /**
     * Description : 
     * met à jour la valeur du curseur "to" et du champ de saisie "to" en fonction de la valeur du champ de saisie "from"
     * 
     * 
     * ¨Pas de return
     * 
     */
        
    function controlToInput(toSlider, fromInput, toInput, controlSlider) {
        const [from, to] = getParsed(fromInput, toInput);
        fillSlider(fromInput, toInput, '#C6C6C6', '#FFB703', controlSlider);
        setToggleAccessible(toInput);
        if (from <= to) {
            toSlider.value = to;
            toInput.value = to;
        } else {
            toInput.value = from;
        }
    }
    /**
     * Description : 
     * met à jour la valeur du champ de saisie "from" en fonction de la valeur du curseur "from"
     * 
     * 
     * ¨Pas de return
     * 
     */
        

    function controlFromSlider(fromSlider, toSlider, fromInput) {
        const [from, to] = getParsed(fromSlider, toSlider);
        fillSlider(fromSlider, toSlider, '#C6C6C6', '#FFB703', toSlider);
        if (from > to) {
            fromSlider.value = to;
            fromInput.value = to;
        } else {
            fromInput.value = from;
        }
    }
     /**
     * Description : 
     * met à jour la valeur du champ de saisie "to" en fonction de la valeur du curseur "to"
     * 
     * 
     * ¨Pas de return
     * 
     */
        

    function controlToSlider(fromSlider, toSlider, toInput) {
        const [from, to] = getParsed(fromSlider, toSlider);
        fillSlider(fromSlider, toSlider, '#C6C6C6', '#FFB703', toSlider);
        setToggleAccessible(toSlider);
        if (from <= to) {
            toSlider.value = to;
            toInput.value = to;
        } else {
            toInput.value = from;
            toSlider.value = from;
        }
    }
 /**
     * Description : 
     * retourne un tableau de deux valeurs entières parsées à partir des valeurs actuelles des champs de saisie ou des curseurs
     * 
     * 
     * 
     * 
     */
        
    function getParsed(currentFrom, currentTo) {
    const from = parseInt(currentFrom.value, 10);
    const to = parseInt(currentTo.value, 10);
    return [from, to];
    }
     /**
     * Description : 
     *  remplit les curseurs avec des couleurs en fonction de la plage de filtre sélectionnée
     * 
     * 
     * ¨Pas de return
     * 
     */
        

    function fillSlider(from, to, sliderColor, rangeColor, controlSlider) {
        const rangeDistance = to.max-to.min;
        const fromPosition = from.value - to.min;
        const toPosition = to.value - to.min;
        controlSlider.style.background = `linear-gradient(
        to right,
        ${sliderColor} 0%,
        ${sliderColor} ${(fromPosition)/(rangeDistance)*100}%,
        ${rangeColor} ${((fromPosition)/(rangeDistance))*100}%,
        ${rangeColor} ${(toPosition)/(rangeDistance)*100}%, 
        ${sliderColor} ${(toPosition)/(rangeDistance)*100}%, 
        ${sliderColor} 100%)`;
    }
     /**
     * Description : 
     * mgère l'affichage des curseurs en fonction de la valeur actuelle de la cible
     * 
     * 
     * ¨Pas de return
     * 
     */
        

    function setToggleAccessible(currentTarget) {
        const toSlider = document.querySelector('#toSlider');
        if (Number(currentTarget.value) <= 0 ) {
            toSlider.style.zIndex = 2;
        } else {
            toSlider.style.zIndex = 0;
        }
    }
    //définies pour les éléments de l'interface utilisateur (curseurs et champs de saisie), qui sont ensuite utilisées 
    //pour gérer les événements d'entrée et mettre à jour les valeurs de l'interface utilisateur
    const fromSlider = document.querySelector('#fromSlider');
    const toSlider = document.querySelector('#toSlider');
    const fromInput = document.querySelector('#fromInput');
    const toInput = document.querySelector('#toInput');
    fillSlider(fromSlider, toSlider, '#C6C6C6', '#FFB703', toSlider);
    setToggleAccessible(toSlider);

    fromSlider.oninput = () => controlFromSlider(fromSlider, toSlider, fromInput);
    toSlider.oninput = () => controlToSlider(fromSlider, toSlider, toInput);
    fromInput.oninput = () => controlFromInput(fromSlider, fromInput, toInput, toSlider);
    toInput.oninput = () => controlToInput(toSlider, fromInput, toInput, toSlider);


   
    //Pour avoir un tableau avec tous les éléments
    var tabElem = [];
    var elem = document.getElementsByClassName("carte_produit_ajout");
    
    // console.log("elem :");
    // console.log(elem);
    // for (var i = 0; i < elem.length;i++){
    //     tabElem[i]=elem[i];
    //     //console.log(tabElem[i]);
        
    // }
    //Tab pour garder en mémoire les checkbox
   
        
    
    //savoir si une case a été coché
   

    /**
     * Description : 
     * Permet selon les filtres choisis d'afficher les produits correspondants
     * 
     * Parametres :
     * elem (String): Sous-catégorie que l'on souhaite filtrer
     * 
     * 
     * ¨Pas de return
     * 
     * prendre tous les produits dans la fourchette de prix précisée et les mettre dans un tableau
     * compter le nombre de vendeur cochés (si 0 = tous)
     * foreach sur les vendeurs
     * créer tableau qui contient tous les produits du vendeur depuis le tableau au dessus(si plusieurs vendeurs push dans le tableau)
     * vérifier le nombre de catégories cochées
     * foreach sur les catégories
     * prendre les produits pour chaque catégorie dans le tableau de vendeur si dessus
     * afficher les produits de ce tableau là
     *
     * 
     * 
     * 
     * 
     * 
     */
    function checkFiltre(elem) { 

        // Si on filtre sur la slide prix
        if (elem == null) {
            
            // S'il n'y a pas de catégories sélectionnées avant
            if (checkboxTab.length==0) {

                //Si aucune catégorie n'a été choisie et donc tous les produits sont à l'écran
                var cartes = document.getElementsByClassName("carte_produit_ajout");

                // Pour tous les produits sur le catalogue
                for (let indexCat=0;indexCat<cartes.length;indexCat++) {

                    // On va chercher le prix du produit
                    var prix = cartes[indexCat].childNodes[5].childNodes[3].childNodes[3].innerHTML.slice(0, -1);

                    let pointenmoins = prix.indexOf(".");

                    // On enlève le point décimal comme 27.68
                    if (pointenmoins != null){
                        if (pointenmoins == 3){
                            prix = prix.slice(pointenmoins-3,pointenmoins);
                        }
                        else{
                            if ((prix.slice(pointenmoins-2,pointenmoins)) != ""){
                                prix = prix.slice(pointenmoins-2,pointenmoins);
                            }
                            else{
                                prix = prix.slice(pointenmoins-1,pointenmoins);
                            }
                        }
                    }   

                    intPrix = parseInt(prix);
                    intFromSlider = parseInt(fromSlider.value);
                    intToSlider = parseInt(toSlider.value);

                    // Compare le produit à la slide barre et l'affiche s'il est dedans
                    if ((intPrix >= intFromSlider) && (intPrix <= intToSlider)) {
                        cartes[indexCat].style.display = "flex";
                    } else{
                        cartes[indexCat].style.display = "none";
                    }
                } 
            
            } else {
                // Si une catégorie a été choisi, quand on touche au slider ça rentre ici
                var boolVendeurCoché = false;
                let vendeurs = document.getElementsByClassName("categorie-vendeur");
                //console.log(vendeurs);
                var tab_vendeur_checked = [];
                for (let compte_vendeur = 0; compte_vendeur < vendeurs.length; compte_vendeur++){
                    if (vendeurs[compte_vendeur].childNodes[3].checked){
                        boolVendeurCoché = true;
                        //console.log(vendeurs[compte_vendeur].childNodes[1].innerHTML);
                        tab_vendeur_checked.push(vendeurs[compte_vendeur].childNodes[1].innerHTML);
                        
                    }
                }

                var boolCategorieCoché = false;
                let categories = document.getElementsByClassName("categorie-produit");
                //console.log(categories);
                var tab_categories_checked = [];
                for (let compte_categorie = 0; compte_categorie < categories.length; compte_categorie++){
                    if (categories[compte_categorie].childNodes[3].checked){
                        boolCategorieCoché = true;
                        //console.log("AAAAAAAAAAAAAAA");
                        //console.log(categories[compte_categorie].children);
                        //console.log(categories[compte_categorie].childNodes[3].id);
                        tab_categories_checked.push(categories[compte_categorie].childNodes[3].id);
                    }
                }
                // console.log("Début de la merde");
                // console.log(elem);
                let checkbox = document.getElementById(elem);
                console.log("Checkbox : ",checkbox);
                /*console.log("checkbox");
                console.log(checkbox);*/
                if (checkbox != null){
                    if((checkbox.checked) && (!checkboxTab.includes(checkbox))) {
                        checkboxTab.push(checkbox);
                    }
                    else{
                        if(checkboxTab.includes(checkbox) && !(checkbox.checked)) {
                            console.log("oeoeoeoe");
                            console.log(checkboxTab);
                            console.log(checkboxTab.indexOf(checkbox));
                            let value = checkboxTab.indexOf(checkbox);
                            checkboxTab.splice(value,1);
                        }
                    }
                }

                console.log("checkboxTab : ");
                console.log(checkboxTab);
                
                let allElem = document.getElementsByClassName("carte_produit_ajout");

                //console.log("allElem");
                //console.log(allElem);
                for (let i = 0; i < allElem.length; i++) {
                    allElem[i].style.display = "none";
                }

                var tableau_de_verif = [];

                for (let j=0; j<checkboxTab.length; j++) {
                    let tabCat = document.getElementsByClassName(checkboxTab[j].id);
                    let boolProduitVendeurValidé = false;
                    let boolProduitCategorieValidé = false;

                    if (boolVendeurCoché == true){
                        let tabClassProduit = [];
                        for (let indexSupprCat = 0; indexSupprCat < tabCat.length; indexSupprCat++){
                            boolProduitVendeurValidé = false;
                            boolProduitCategorieValidé = false;
                            if (tab_categories_checked.length == 0){
                                boolProduitCategorieValidé = true;
                            }
                            tabClassProduit = [];
                            for (let indextabClassProduit = 0; indextabClassProduit < tabCat[indexSupprCat].classList.length; indextabClassProduit++){
                                tabClassProduit.push(tabCat[indexSupprCat].classList[indextabClassProduit]);
                            }
                            console.log("TabClassProduit",tabClassProduit);
                            console.log("tab_categories_checked :",tab_categories_checked);
                            for (let nbVendeursChecked = 0; nbVendeursChecked < tab_vendeur_checked.length; nbVendeursChecked++){
                                if (tabClassProduit.includes(tab_vendeur_checked[nbVendeursChecked])){
                                    boolProduitVendeurValidé = true;
                                }
                            }
                            for (let nbCategoriesChecked = 0; nbCategoriesChecked < tab_categories_checked.length; nbCategoriesChecked++){
                                if (tabClassProduit.includes(tab_categories_checked[nbCategoriesChecked])){
                                    boolProduitCategorieValidé = true;
                                }
                            }
                            console.log("Catégorie Validée ? : ",boolProduitCategorieValidé);
                            if (boolProduitVendeurValidé == false || boolProduitCategorieValidé == false){
                                tabCat[indexSupprCat].style.display = "none";
                            }
                            else{
                                console.log("Element Validé normalement : ",tableau_de_verif[indexSupprCat]);
                                tableau_de_verif.push(tabCat[indexSupprCat]);
                            }
                        }
                    }
                    else {
                        let tabClassProduit = [];
                        for (let indexSupprCat = 0; indexSupprCat < tabCat.length; indexSupprCat++){
                            boolProduitVendeurValidé = false;
                            boolProduitCategorieValidé = false;
                            if (tab_categories_checked.length == 0){
                                boolProduitCategorieValidé = true;
                            }
                            tabClassProduit = [];
                            for (let indextabClassProduit = 0; indextabClassProduit < tabCat[indexSupprCat].classList.length; indextabClassProduit++){
                                tabClassProduit.push(tabCat[indexSupprCat].classList[indextabClassProduit]);
                            }
                            console.log("TabClassProduit",tabClassProduit);
                            console.log("tab_categories_checked :",tab_categories_checked);
                            for (let nbCategoriesChecked = 0; nbCategoriesChecked < tab_categories_checked.length; nbCategoriesChecked++){
                                if (tabClassProduit.includes(tab_categories_checked[nbCategoriesChecked])){
                                    boolProduitCategorieValidé = true;
                                }
                            }
                            console.log("Catégorie Validée ? : ",boolProduitCategorieValidé);
                            if (boolProduitCategorieValidé == false){
                                tabCat[indexSupprCat].style.display = "none";
                            }
                            else{
                                console.log("Element Validé normalement : ",tableau_de_verif[indexSupprCat]);
                                tableau_de_verif.push(tabCat[indexSupprCat]);
                            }
                        }
                    }
                    console.log("Tableau de vérif",tableau_de_verif); 
                }
                for (let j=0; j<checkboxTab.length; j++) {
                    console.log("Catégorie cochée : ",checkboxTab[j].id);
                    let tabCat = document.getElementsByClassName(checkboxTab[j].id);
                    //console.log(tabCat.length);
                    console.log("TabCat :",tabCat);
                    // Pour les produits catégorisées avant sur le catalogue
                    for (let indexCat = 0; indexCat < tabCat.length; indexCat++) {
                        var prix = tabCat[indexCat].childNodes[5].childNodes[3].childNodes[3].innerHTML;
                        prix = prix.slice(0, -1);
                        let pointenmoins = prix.indexOf(".");
                        if (pointenmoins != null){
                            if (pointenmoins == 3){
                                prix = prix.slice(pointenmoins-3,pointenmoins);
                            }
                            else{
                                if ((prix.slice(pointenmoins-2,pointenmoins)) != ""){
                                    prix = prix.slice(pointenmoins-2,pointenmoins);
                                }
                                else{
                                    prix = prix.slice(pointenmoins-1,pointenmoins);
                                }
                            }
                        }   
                        console.log("Prix",prix);
                        intPrix = parseInt(prix);
                        intFromSlider = parseInt(fromSlider.value);
                        intToSlider = parseInt(toSlider.value);
                        console.log(tableau_de_verif);
                        console.log("Prix",intPrix);
                        console.log("Prix min", intFromSlider);
                        console.log("Prix max", intToSlider);

                        if ((intPrix >= intFromSlider) && (intPrix <= intToSlider)){
                            if (tableau_de_verif.includes(tabCat[indexCat])){
                                console.log("Element affiché :",tabCat[indexCat]);
                                tabCat[indexCat].style.display = "flex";
                            }
                            else{
                                tabCat[indexCat].style.display = "none";
                            }
                        } 
                        else{
                            tabCat[indexCat].style.display = "none";
                        }
                    }
                }
            }
        // Si on filtre par catégorie
        } 
        else {
            var boolVendeurCoché = false;
            let vendeurs = document.getElementsByClassName("categorie-vendeur");
            //console.log(vendeurs);
            var tab_vendeur_checked = [];
            for (let compte_vendeur = 0; compte_vendeur < vendeurs.length; compte_vendeur++){
                if (vendeurs[compte_vendeur].childNodes[3].checked){
                    boolVendeurCoché = true;
                    //console.log(vendeurs[compte_vendeur].childNodes[1].innerHTML);
                    tab_vendeur_checked.push(vendeurs[compte_vendeur].childNodes[1].innerHTML);
                    
                }
            }

            var boolCategorieCoché = false;
            let categories = document.getElementsByClassName("categorie-produit");
            //console.log(categories);
            var tab_categories_checked = [];
            for (let compte_categorie = 0; compte_categorie < categories.length; compte_categorie++){
                if (categories[compte_categorie].childNodes[3].checked){
                    boolCategorieCoché = true;
                    //console.log("AAAAAAAAAAAAAAA");
                    //console.log(categories[compte_categorie].children);
                    //console.log(categories[compte_categorie].childNodes[3].id);
                    tab_categories_checked.push(categories[compte_categorie].childNodes[3].id);
                }
            }
            // console.log("Début de la merde");
            // console.log(elem);
            let checkbox = document.getElementById(elem);
            // console.log("Checkbox : ",checkbox);
            /*console.log("checkbox");
            console.log(checkbox);*/
            if((checkbox.checked) && (!checkboxTab.includes(checkbox))) {
                checkboxTab.push(checkbox);
            }
            else{
                if (checkbox != null){
                    if(checkboxTab.includes(checkbox) && !(checkbox.checked)) {
                        console.log("oeoeoeoe");
                        console.log(checkboxTab);
                        console.log(checkboxTab.indexOf(checkbox));
                        let value = checkboxTab.indexOf(checkbox);
                        checkboxTab.splice(value,1);
                    }   
                }
            }

            console.log("checkboxTab : ");
            console.log(checkboxTab);
            
            let allElem = document.getElementsByClassName("carte_produit_ajout");

            //console.log("allElem");
            //console.log(allElem);
            for (let i = 0; i < allElem.length; i++) {
                allElem[i].style.display = "none";
            }
            
            for (let j=0; j<checkboxTab.length; j++) {
                let tabCat = document.getElementsByClassName(checkboxTab[j].id);
                let boolProduitVendeurValidé = false;
                let boolProduitCategorieValidé = false;

                let tableau_de_verif = [];
                if (boolVendeurCoché == true){
                    let tabClassProduit = [];
                    for (let indexSupprCat = 0; indexSupprCat < tabCat.length; indexSupprCat++){
                        boolProduitVendeurValidé = false;
                        boolProduitCategorieValidé = false;
                        if (tab_categories_checked.length == 0){
                            boolProduitCategorieValidé = true;
                        }
                        tabClassProduit = [];
                        for (let indextabClassProduit = 0; indextabClassProduit < tabCat[indexSupprCat].classList.length; indextabClassProduit++){
                            tabClassProduit.push(tabCat[indexSupprCat].classList[indextabClassProduit]);
                        }
                        console.log(tabClassProduit);
                        console.log(tab_categories_checked);
                        for (let nbVendeursChecked = 0; nbVendeursChecked < tab_vendeur_checked.length; nbVendeursChecked++){
                            if (tabClassProduit.includes(tab_vendeur_checked[nbVendeursChecked])){
                                boolProduitVendeurValidé = true;
                            }
                        }
                        for (let nbCategoriesChecked = 0; nbCategoriesChecked < tab_categories_checked.length; nbCategoriesChecked++){
                            if (tabClassProduit.includes(tab_categories_checked[nbCategoriesChecked])){
                                boolProduitCategorieValidé = true;
                            }
                        }
                        console.log(boolProduitCategorieValidé);
                        if (boolProduitVendeurValidé == false || boolProduitCategorieValidé == false){
                            tabCat[indexSupprCat].style.display = "none";
                            tableau_de_verif[indexSupprCat] = null;
                        }
                        else{
                            tableau_de_verif[indexSupprCat] = tabCat[indexSupprCat];
                        }
                    }
                } 
                console.log("Tableau de vérif :",tableau_de_verif);

                for (let indexCat=0; indexCat<tabCat.length; indexCat++) {
                    
                    var prix = tabCat[indexCat].childNodes[5].childNodes[3].childNodes[3].innerHTML;

                    prix = prix.slice(0, -1);
                    let pointenmoins = prix.indexOf(".");

                    if (pointenmoins != null){
                        if (pointenmoins == 3){
                            prix = prix.slice(pointenmoins-3,pointenmoins);
                        }
                        else{
                            if ((prix.slice(pointenmoins-2,pointenmoins)) != "") {
                                prix = prix.slice(pointenmoins-2,pointenmoins);
                            }
                            else {
                                prix = prix.slice(pointenmoins-1,pointenmoins);
                            }
                        }
                    }
                    console.log("Prix",prix);
                    intPrix = parseInt(prix);
                    intFromSlider = parseInt(fromSlider.value);
                    intToSlider = parseInt(toSlider.value);
                    console.log("Prix",intPrix);
                    console.log("Prix min", fromSlider);
                    console.log("Prix max",toSlider);

                    if ((intPrix >= intFromSlider) && (intPrix <= intToSlider)) {
                        if (boolVendeurCoché) {
                            if (tableau_de_verif[indexCat] != null){
                                tabCat[indexCat].style.display = "flex";
                            }
                            else{

                            }
                        }
                        else{
                            tabCat[indexCat].style.display = "flex";
                        }
                        console.log("eeeeee");
                        console.log(tabCat[indexCat]);
                    } 
                }
                let pElement = document.getElementsByClassName("classname")[0];
                
            }

            //pour quand une catégorie a été coché, si elle est décoché que ça réaffiche tous
            var sousCat = document.getElementsByClassName("sous-categorie");
            var boolC = false;
            for (let catchecked =0;catchecked<sousCat.length;catchecked++){
                if(sousCat[catchecked].getElementsByTagName('input')[0].checked){
                    boolC = true;
                }   
            }
            if(!boolC){
                var cartes = document.getElementsByClassName("carte_produit_ajout");
                for (let i =0;i<cartes.length;i++){
                    cartes[i].style.display = "flex";
                } 
            }
        }

        // Si on a filtré mais il n'y a aucun produit à l'écran, ça affiche une image et un texte l'indiquant au client
        var boolAff = false;
        var vide = document.getElementsByClassName("block-CatalogueVide")[0];
        var sousCat = document.getElementsByClassName("carte_produit_ajout");
        vide.style.display = "none";
   
        for(var i = 0; i < sousCat.length; i++) {
            if(sousCat[i].style.display == "flex"){
                boolAff = true;
            }
        }
        if (boolAff ===false){
            vide.style.display = "flex";
            vide.style.flexDirection = "column";
            
        }else{
            vide.style.display = "none";
          
        }
        boolAff = false;
    }
    
    var vide = document.getElementsByClassName("block-CatalogueVide")[0];
    vide.style.display = "none";


    /**
     * Description : 
     * Permet de trier les produits en croissant décroissant
     * 
     * Parametres :
     * order (String): croissant/décroissant -> indique comment on veut trier
     * 
     * 
     * ¨Pas de return
     * 
     */
    
    function sortProducts(order) {
        var products = document.getElementsByClassName("carte_produit_ajout");

        var sortedProducts = Array.prototype.slice.call(products).sort(function(a, b) {
            var priceA = parseFloat(a.childNodes[5].childNodes[3].childNodes[3].innerHTML);
   
            var priceB = parseFloat(b.childNodes[5].childNodes[3].childNodes[3].innerHTML);
    
            if (order === "asc") {
                return priceA - priceB;
                } else {
                return priceB - priceA;
            }
        });
        console.log(sortedProducts);
        for (var j = 0; j < sortedProducts.length; j++){
            sortedProducts[j].id = j;
        }
        var parent = document.getElementsByClassName("article_cat")[0];
        console.log(parent);
        for (var i = 0; i < sortedProducts.length; i++) {
            parent.appendChild(sortedProducts[i]);
        }
        console.log(parent);
        triActif(event)
    }
       

    function triActif(e){

        let btnsTri = document.getElementsByClassName("boutonTri")

        for(i = 0 ; i < btnsTri.length ; i++){
            btnsTri[i].classList.remove("actif")
        }

        let trichoisi = e.target
        trichoisi.classList.add("actif");
    }
    
    const boutonstri = document.querySelector("#sorting-options");
    const trititre = document.querySelector(".triTitre");

    boutonstri.addEventListener("click", function(event) {
        if (event.target !== trititre) {
            return;
        }
        
        if (this.style.border === "") {
            this.style.border = "1px solid grey";
        } else {
            this.style.border = "";
        }
    });

    if(Scategorie_nav != null) {

        let checkbox = document.querySelectorAll(".sous-categorie input");
        let details = document.querySelectorAll(".categorie");
        
        // Parcours de toutes les checkbox
        for (let i = 0; i < checkbox.length; i++) {
            console.log(checkbox[i].name);
            console.log(Scategorie_nav);
            if((checkbox[i].name) == (Scategorie_nav)) {
                checkboxTab.push(checkbox[i]);
                console.log("Ajouté");
            }
        }

        // On coche la case correspondante
        for (let i = 0; i < checkboxTab.length; i++) {
            checkboxTab[i].checked = true;
            console.log(checkboxTab);
        }
        // On filtre selon la catégorie cochée
        console.log(checkboxTab[0]);
        checkFiltre(checkboxTab[0].name);
        console.log("Tableau vide ?");
        console.log(checkboxTab[0].parentNode.parentNode);
        checkboxTab[0].parentNode.parentNode.open = true;
    }

</script>
