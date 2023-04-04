var triId = true;
function SortTableProduitsTri2(){

    resetBtnTri2()

    if(triId){
        sortTableCommandeProduits2();
        triId = false;

    }
    else{
        sortTableCommandeProduitsDecroissant2();
        triId= true;
    }
}

function resetBtnTri2(){

    let tab_Btn_tri = document.querySelectorAll(".catalogue i");
    
    tab_Btn_tri.forEach(btn_tri => {
        btn_tri.className = "fa-solid fa-sort";
    });
}

function sortTableCommandeProduits2() {
    var table, rows, switching, i, x, y, shouldSwitch;
    table = document.getElementById("myTable produits");
    switching = true;
    while (switching) {
        switching = false;
        rows = table.rows;
        for (i = 1; i < (rows.length - 1); i++) {
            shouldSwitch = false;
            console.log(x)
            x = rows[i].getElementsByTagName("TD")[1];
            y = rows[i + 1].getElementsByTagName("TD")[1];
                if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                    shouldSwitch = true;
                    break;
                }
            }
            if (shouldSwitch) {
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
            }
    }

    let btn_tri = document.querySelector(".id_produit + i");
    btn_tri.className = "fa-solid fa-sort-up";
}


function sortTableCommandeProduitsDecroissant2() {
    var table, rows, switching, i, x, y, shouldSwitch;
    table = document.getElementById("myTable produits");
    switching = true;
    while (switching) {
        switching = false;
        rows = table.rows;
        for (i = 1; i < (rows.length - 1); i++) {
            shouldSwitch = false;
            x = rows[i].getElementsByTagName("TD")[1];
            y = rows[i + 1].getElementsByTagName("TD")[1];
                if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                    shouldSwitch = true;
                    break;
                }
            }
            if (shouldSwitch) {
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
            }
    }

    let btn_tri = document.querySelector(".id_produit + i");
    btn_tri.className = "fa-solid fa-sort-down";

}

var triNom = true;

function SortNameProduits2(){
    
    resetBtnTri2()

    if(triNom){
        sortTableNameProducts2();
        triNom = false;
    }
    else{
        sortTableNameProductsDecroissant2();
        triNom = true;
    }
}

function sortTableNameProducts2() {
    var table, rows, switching, i, x, y, shouldSwitch;
    table = document.getElementById("myTable produits");
    
    switching = true;
    while (switching) {
    switching = false;
    rows = table.rows;
    
    for (i = 1; i < (rows.length - 1); i++) {
        shouldSwitch = false;
        x = rows[i].querySelector("td > a");
        y = rows[i + 1].querySelector("td > a");
        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
            shouldSwitch = true;
            break;
        }
        }
        if (shouldSwitch) {
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
        }
    }

    let btn_tri = document.querySelector(".nom_produit + i");
    btn_tri.className = "fa-solid fa-sort-up";
}

/**
 * DESCRIPTION
 *  trie dans l'odre alphabétique inverse les lignes du tableau produit de catalogue en fonction du nom des produit
 * 
 * PARAMETRES
 *  void
 * 
 * RETRURN
 *  void
 * 
 */
function sortTableNameProductsDecroissant2() {
    var table, rows, switching, i, x, y, shouldSwitch;
    table = document.getElementById("myTable produits");
    
    switching = true;
    while (switching) {
    switching = false;
    rows = table.rows;
    
    for (i = 1; i < (rows.length - 1); i++) {
        shouldSwitch = false;
        x = rows[i].querySelector("td > a");
        y = rows[i + 1].querySelector("td > a");
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
            shouldSwitch = true;
            break;
        }
        }
        if (shouldSwitch) {
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
        }
    }

    let btn_tri = document.querySelector(".nom_produit + i");
    btn_tri.className = "fa-solid fa-sort-down";
}

var SortPriceTri = true

/**
 * DESCRIPTION
 *  appel les fontion de trie sur le prix du produit et modifie l'affichage des icon de trie
 * 
 * PARAMETRES
 *  void
 * 
 * RETRURN
 *  void
 * 
 */
var SortPriceTri = true

function SortPrice2(index){

    resetBtnTri2();

    if(SortPriceTri){

        sortTablePriceProduits2(index)
        SortPriceTri =false;
    } else{
        sortTablePriceProduitsDecroissant2(index)
        SortPriceTri = true;
    }
}

/**
 * DESCRIPTION
 *  trie dans l'odre croissant les ligne du tableau produit de catalogue en fonction du prix
 * 
 * PARAMETRES
 *  void
 * 
 * RETRURN
 *  void
 * 
 */
function sortTablePriceProduits2(index) {
    var table, rows, switching, i, x, y, shouldSwitch;
    table = document.getElementById("myTable produits");

    switching = true;
    while (switching) {
        switching = false;
        rows = table.rows;
        for (i = 1; i < (rows.length - 1); i++) {
            shouldSwitch = false;
            x = parseInt(rows[i].querySelectorAll("td > p")[index-2].innerHTML);
            y = parseInt(rows[i+1].querySelectorAll("td > p")[index-2].innerHTML);

        if (x > y) {
            shouldSwitch = true;
            break;
        }
        }
        if (shouldSwitch) {
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
        }
    }
    switch(index){

        case 2 : 
            let btn_tri_prixHT = document.querySelector(".prixHT_produit + i");
            btn_tri_prixHT.className = "fa-solid fa-sort-up";
            break;
        case 3 :
            let btn_tri_prixTTC = document.querySelector(".prixTTC_produit + i");
            btn_tri_prixTTC.className = "fa-solid fa-sort-up";
            break;
        case 4:
            let btn_tri_stock = document.querySelector(".stock_produit + i");
            btn_tri_stock.className = "fa-solid fa-sort-up";
            break;          
    }


}

/**
 * DESCRIPTION
 *  trie dans l'odre decroissant les ligne du tableau produit de catalogue en fonction du prix
 * 
 * PARAMETRES
 *  void
 * 
 * RETRURN
 *  void
 * 
 */
function sortTablePriceProduitsDecroissant2(index) {
    var table, rows, switching, i, x, y, shouldSwitch;
    table = document.getElementById("myTable produits");

    switching = true;
    while (switching) {
        switching = false;
        rows = table.rows;
        for (i = 1; i < (rows.length - 1); i++) {
            shouldSwitch = false;
            x = parseInt(rows[i].querySelectorAll("td > p")[index -2].innerHTML);
            y = parseInt(rows[i+1].querySelectorAll("td > p")[index -2].innerHTML);

        if (x < y) {
            shouldSwitch = true;
            break;
        }
        }
        if (shouldSwitch) {
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
        }
    }

    switch(index){

        case 2 : 
            let btn_tri_prixHT = document.querySelector(".prixHT_produit + i");
            btn_tri_prixHT.className = "fa-solid fa-sort-down";
            break;
        case 3 :
            let btn_tri_prixTTC = document.querySelector(".prixTTC_produit + i");
            btn_tri_prixTTC.className = "fa-solid fa-sort-down";
            break;
        case 4:
            let btn_tri_stock = document.querySelector(".stock_produit + i");
            btn_tri_stock.className = "fa-solid fa-sort-down";
            break;          
    }
}