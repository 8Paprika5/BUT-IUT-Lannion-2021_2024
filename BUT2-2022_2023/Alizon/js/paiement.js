/*###################################################################################### 
############################# AUTO-COMPLETITION CARTE BLEU ############################# 
########################################################################################*/

/*#################### VARIABLES ####################*/
var BlockError = document.querySelector(".erreur_co");
var TypeCbPayement = document.getElementById("TypeCbPayement");
var BtnValiderPaiement = document.getElementById("BtnValiderPaiement");
// message d'erreur concernant le numéro de carte Bancaire
var TextErrorCardnumber = document.querySelector(".texte_erreur_cardnumber");

// message d'erreur concernant la date d'expiration de la carte Bancaire
var TextErrorDateCB = document.querySelector(".texte_erreur_dateCB");

//Entrée de texte du numéro de carte bancaire
var ccnum = document.getElementById("ccnum");

//Entrée de texte la date d'expiration de la carte Bancaire
var dateCB = document.getElementById("dateCB");

//Entrée de texte du cryptogramme visuel de la carte bancaire
var cvv = document.getElementById("cvv");


/*#################### FONCTIONS ####################*/
function typeCB(cb) {
    /** 
     * DESCRIPTION 
     *  Permet d'afficher le type de carte bancaire si le modèle est reconnu
     * 
     * PARAMETRES
     *  cb (int) : numéro de carte bancaire
     * 
     * RETURN
     *  une chaine de caractère correspondant au type de carte bancaire reconnu.
     * 
     **/
    TypeCbPayement.className = "";
    if (parseInt(cb.slice(0, 1)) == 4) {
        TypeCbPayement.className = "fa-brands fa-cc-visa";
        return "Visa";
    }

    if (parseInt(cb.slice(0, 1)) == 3) {
        TypeCbPayement.className = "fa-brands fa-cc-jcb";
        return "JCB";
    }

    if (parseInt(cb.slice(0, 2)) <= 34 && parseInt(cb.slice(0, 2)) >= 37) {
        TypeCbPayement.className = "fa-brands fa-cc-amex";
        return "American Express";
    }

    if (parseInt(cb.slice(0, 2)) <= 51 && parseInt(cb.slice(0, 2)) >= 55) {
        TypeCbPayement.className = "fa-brands fa-cc-mastercard";
        return "MasterCard";
    }

    if ((parseInt(cb.slice(0, 3)) <= 300 && parseInt(cb.slice(0, 3)) >= 305) || parseInt(cb.slice(0, 2)) == 36 || parseInt(cb.slice(0, 2)) == 38) {
        TypeCbPayement.className = "fa-brands fa-cc-diners-club";
        return "Diners Club et Carte Blanche";
    }

    if (parseInt(cb.slice(0, 4)) == 6011) {
        TypeCbPayement.className = "fa-brands fa-cc-discover";
        return "Discover";
    }

    if (parseInt(cb.slice(0, 4)) == 2123 || parseInt(cb.slice(0, 4)) == 1800) {
        TypeCbPayement.className = "fa-brands fa-cc-jcb";
        return "JCB";
    }

    ShowBlockError(true);
    ShowTextErrorCardnumber(true, "Type de carte inconnue");
    ShowTextErrorDateCB(false, NaN);
    return "Autre";
}

function ShowBlockError(show) {
    /** 
     * DESCRIPTION 
     *  affiche le bloc d'erreur (display block) si 'show' == true.
     *  Sinon le bloc est caché si 'show' == false et aucun texte d'erreur n'est affiché 
     * 
     * PARAMETRES
     *  show (boolean) : true si l'on souhaite afficher le block
     * 
     * RETURN
     *  None
     * 
     **/
    if (show) {
        BlockError.style['display'] = 'block';
    } else {
        if (TextErrorCardnumber.innerHTML == "" && TextErrorDateCB.innerHTML == "") {
            BlockError.style['display'] = 'none';
        }
    }
}

function ShowTextErrorCardnumber(show, text) {
    /** 
     * DESCRIPTION 
     *  affiche le bloc d'erreur (display block) si 'show' == true.
     *  Sinon le bloc est caché si 'show' == false et aucun texte d'erreur n'est affiché 
     * 
     * PARAMETRES
     *  show (boolean) : true si l'on souhaite afficher le block
     *  text (string) : text a affiché
     * 
     * RETURN
     *  None
     * 
     **/
    if (show) {
        ShowBlockError(show);
        DisabledBtnValiderPaiement(true);
        TextErrorCardnumber.style['display'] = 'block';
        TextErrorCardnumber.innerHTML = text;
    } else {
        if (text == "" || TextErrorCardnumber.innerHTML == "") {
            TextErrorCardnumber.style['display'] = 'none';
            TextErrorCardnumber.innerHTML = "";
        }
    }
    DisabledBtnValiderPaiement();
}

function ShowTextErrorDateCB(show, text) {
    /** 
     * DESCRIPTION 
     *  affiche le bloc d'erreur (display block) si 'show' == true.
     *  Sinon le bloc est caché si 'show' == false et aucun texte d'erreur n'est affiché 
     * 
     * PARAMETRES
     *  show (boolean) : true si l'on souhaite afficher le block
     *  text (string) : text a affiché
     * 
     * RETURN
     *  None
     * 
     **/
    if (show) {
        ShowBlockError(true);
        TextErrorDateCB.style['display'] = 'block';
        TextErrorDateCB.innerHTML = text;
    } else {
        if (text == "" || TextErrorDateCB.innerHTML == "") {
            TextErrorDateCB.style['display'] = 'none';
            TextErrorDateCB.innerHTML = "";
        }
    }
    DisabledBtnValiderPaiement();
}

function DisabledBtnValiderPaiement() {
    /** 
     * DESCRIPTION 
     *  desactive le boutton de paiement en cas d'erreur dans le formulaire
     * 
     * PARAMETRES
     *  None
     * 
     * RETURN
     *  None
     * 
     **/
    if (TextErrorCardnumber.innerHTML == "" && TextErrorDateCB.innerHTML == "") {
        BtnValiderPaiement.removeAttribute('disabled');
    } else {
        BtnValiderPaiement.setAttribute("disabled", '');
    }
}


/*#################### EVENEMENTS ####################*/
// Tous les blocs et textes d'erreur sont mis à zéro
ShowBlockError(false);
ShowTextErrorCardnumber(false, "");
ShowTextErrorDateCB(false, "");
BtnValiderPaiement.removeAttribute('disabled');
// Nombre de caractères rentrées dans 'ccnum'(voir variables ci-dessus) sans les '-' .
var cpt_ccnum = 0;

// Evenement déclanché lors d'une entrée ou supression de texte dans 'ccnum'.
ccnum.addEventListener("input", function (event) {

    // Si entrée de texte  : 
    if (event.inputType == 'insertText') {
        cpt_ccnum++;
        if (cpt_ccnum % 4 == 0 && cpt_ccnum < 15) {
            // on sépare les nombres en groupe de 4 chiffres séparés par '-'
            ccnum.value = ccnum.value + "-";
        }

        // Si supression de texte  :     
    } else {
        if ((cpt_ccnum) % 4 == 0) {
            // On retire automatiquement les '-'
            ccnum.value = ccnum.value.slice(0, -1);
        }

        //Si l'entrée de texte est vide
        if (ccnum.value.length == 0) {
            // On supprime les messages d'erreur du numéro de carte bleu
            ShowTextErrorCardnumber(false, "");
            cpt_ccnum = 0;
        } else {
            cpt_ccnum--;
        }
    }

    // Affichage et verification du type de carte bancaire
    if (cpt_ccnum == 4) {
        typeCB(ccnum.value);
    }

});

// Evenement déclanché lorsque l'on quitte le champs 'dateCB'(voir variables ci-dessus).
dateCB.addEventListener("blur", function (event) {
    //Si la date n'est pas rentré complètement, pas de verification d'expiration
    let Today = new Date();
    let ExpDate = new Date(dateCB.value);
    ShowTextErrorDateCB(true, "Carte Expirée");
    if (dateCB.value.length <= 7) {
        if ((ExpDate.getYear() < Today.getYear()) || (ExpDate.getYear() == Today.getYear() && ExpDate.getMonth() < Today.getMonth())) {
            ShowTextErrorDateCB(true, "Carte Expirée");
        } else {
            ShowTextErrorDateCB(false, "");
        }
    } else {
        ShowTextErrorDateCB(false, "");
    }
});


/*###################################################################################### 
############################# VERIFICATION CARTE BLEU ##################################
########################################################################################*/

function verifDate(date) {
    /** 
     * DESCRIPTION 
     *  verifie la date d'expiration et affiche un message d'erreur si besoin
     * 
     * PARAMETRES
     *  date (string) : date d'expiration de la carte bleu
     * 
     * RETURN
     *  None
     * 
     **/
    let dateExp = new Date(date);
    let today = new Date();

    if (dateCB.value.length >= 10 && (dateExp < today)) {
        ShowTextErrorDateCB(true, "Carte Expirée");
    } else {
        ShowTextErrorDateCB(false, "");
    }
}

function calcul_cle(cb) {
    /*
        DESCRIPTION
            Vérifie si un numéro de carte bancaire est valide à l'aide de la clef de Luhn
        
        PARAMETRES
            - carteBancaire : numéro de carte bancaire 

        RETURN
            un boolean : True si la carte est valide

        TEST
            calcul_cle(4624 7482 3324 9080) >>> False
            calcul_cle(4624 7482 3324 9180) >>> True          
    */


    cb = cb.replaceAll('-', ''); // Permet de retirer tout les tirets
    if (cb != "" || cb.length < 16) {

        // Calcule de la somme par la droite
        var somme = 0;

        for (let i = cb.length - 1; i > -1; i--) {
            let nb = parseInt(cb[i]);

            if (i % 2 == 0) {
                somme = somme + nb * 2;
            } else {
                somme += nb;
            }
        }


        var res = somme != NaN && somme % 10 == 0;
    } else {
        res = false;
    }
    if (!res) {
        ShowTextErrorCardnumber(true, "Carte Invalide");
        BtnValiderPaiement.removeAttribute('disabled');
    }
    return res;


}

function VerificationCarteBleu() {
    /** 
     * DESCRIPTION 
     *  verifie la validité de la carte bleu
     * 
     * PARAMETRES
     *  None
     * 
     * RETURN
     *  true si la cb est valide.
     * 
     **/
    if (calcul_cle(ccnum.value)) {
        return true;
    } else {
        return false;
    }
}