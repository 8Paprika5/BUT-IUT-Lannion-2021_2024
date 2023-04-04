
let btn_fermer = document.querySelector(".fa-xmark");

if (btn_fermer != null){

    btn_fermer.addEventListener("click", fermerNotif)
}



function afficherProduits(){

    let bloc_produit = document.querySelector(".alert-import-csv table");
    
    bloc_produit.classList.toggle("-affiche");
}

function fermerNotif(){

    let popup = document.querySelector(".alert-import-csv");
    let fond = document.querySelector(".fond_opacity");

    popup.classList.remove("-affiche");
    fond.classList.remove("fond_opacity--affiche");
}