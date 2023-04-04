
/** Question 1
 *
 * Pays dont au moins un pays frontalier n’est pas dans le même continent
 */
function outsideTheContinent() {
    console.log(`Pays dont au moins un pays frontalier n’est pas dans le même continent :`);
    console.log("");
    let listePays = [];
    for (let codeAlpha3 in all_countries) {
        let countrie = all_countries[codeAlpha3];
        let continent = countrie.continent;
        let borders = countrie.getBorders();
        for (const borderCountrie of borders) {
            if(borderCountrie.continent != continent && !listePays.includes(countrie.nom)){
                console.log(`${countrie.nom} [${countrie.codeAlpha3}], ${continent}`);
                listePays.push(countrie.nom);
            }
        }
    }
    console.log("");
    console.log(`${listePays.length} Resultats.`);
}
//outsideTheContinent();

/**Question 2
 *
 * Pays (possibilité de plusieurs) ayant le plus grand nombre de voisins. Achez aussi les voisins
 */
function moreNeighbors() {
    console.log(`Pays (possibilité de plusieurs) ayant le plus grand nombre de voisins.Affichez aussi les voisins.`);
    console.log("");
    let max = 0;
    let voisins = [];
    let res = null;
    for (const country in all_countries) {
        if (all_countries[country].paysFrontaliers != null) {
            if (all_countries[country].getBorders().length > max) {
                max = all_countries[country].getBorders().length;
                voisins = [];
                res = all_countries[country];
                for (let i = 0; i < max; i++) {
                    voisins.push(all_countries[country].getBorders()[i]);
                }
            }
        }
    }
    console.log(`${res.nom} [${res.codeAlpha3}], ${res.continent} ${voisins.length} voisins:`);
    for (let i = 0; i < voisins.length; i++) {
        console.log(`- ${voisins[i].nom} [${voisins[i].codeAlpha3}], ${voisins[i].continent}`);
    }
}
//moreNeighbors();

/** Question 3
 *
 * Pays n’ayant aucun voisin.
 */
function neighborless() {
    console.log(`Pays n’ayant aucun voisin :`);
    console.log("");
    let res = [];
    for (const country in all_countries) {
        if (all_countries[country].paysFrontaliers == null) {
            res.push(all_countries[country]);
        }
    }
    for (let i = 0; i < res.length; i++) {
        console.log(`- ${res[i].nom} [${res[i].codeAlpha3}], ${res[i].continent}`);
    }
    console.log("");
    console.log(`${res.length} Resultats.`);
}
//neighborless();

/** Question 4
 *
 * Pays (possibilité de plusieurs) parlant le plus de langues.  Affichez aussi les langues.
 **/
function moreLanguages() {
    let listCountrieMoreLanguages= [];
    console.log(`Pays (possibilité de plusieurs) parlant le plus de langues. Affichez aussi les langues `);
    console.log("");
    for (let codeAlpha3 in all_countries) {
        let countrie = all_countries[codeAlpha3];
        let languagesCountrie = countrie.langues;
        if(listCountrieMoreLanguages.length < 1){
            listCountrieMoreLanguages.push(countrie);
        }else{
            if(listCountrieMoreLanguages[0].langues.length == languagesCountrie.length){
                listCountrieMoreLanguages.push(countrie);
            }else{
                if(listCountrieMoreLanguages[0].langues.length < languagesCountrie.length){
                    listCountrieMoreLanguages = [countrie];
                }
            }
        }
    }

    // AFFICHAGE
    for (const countrieMoreLanguages of listCountrieMoreLanguages) {
        console.log(`${countrieMoreLanguages.nom} [${countrieMoreLanguages.codeAlpha3}], ${countrieMoreLanguages.continent} - Langues parlées : ${countrieMoreLanguages.langues.length}: `);
        for (const language of countrieMoreLanguages.langues) {
            console.log(`- ${language.name} (${language.iso639_2})`);
        }
        console.log("");
        console.log("------");
        console.log("");
    }
}
//moreLanguages();

/** Question 5
 * Pays ayant au moins un voisin parlant l’une de ses langues. Affichez aussi les pays voisins et les langues en question.
 */
function withCommonLanguage() {
    console.log(`Pays ayant au moins un voisin parlant l’une de ses langues. Affichez aussi les pays voisins et les langues en question :`);
    console.log("");
    let listeCountrieWithCommonLanguage = {};
    for (let codeAlpha3 in all_countries) {
        let countrie = all_countries[codeAlpha3];
        let languagesCountrie = countrie.langues;
        let borders = countrie.getBorders();
        for (const borderCountrie of borders) {
            let languagesBorderCountrie = borderCountrie.langues;
            for (const Borderlangues of languagesBorderCountrie) {
                for (const langues of languagesCountrie) {
                    if(langues.iso639_2 == Borderlangues.iso639_2){
                        if(listeCountrieWithCommonLanguage[countrie.codeAlpha3] == undefined){
                            listeCountrieWithCommonLanguage[countrie.codeAlpha3] = [[borderCountrie,langues]];
                        }else{
                            listeCountrieWithCommonLanguage[countrie.codeAlpha3].push([borderCountrie,langues]);
                        }
                    } 
                }
            }
        }
    }
    
    for (const codeAlpha3 in listeCountrieWithCommonLanguage){
        console.log(`## ${all_countries[codeAlpha3].nom}[${codeAlpha3}],${all_countries[codeAlpha3].continent}`);
        console.log("Voisin(s) : ")
        for (const countrieWithCommonLanguage of listeCountrieWithCommonLanguage[codeAlpha3]){
            console.log(`// ${countrieWithCommonLanguage[0].nom}[${countrieWithCommonLanguage[0].codeAlpha3}], ${countrieWithCommonLanguage[0].continent} ~~ ${countrieWithCommonLanguage[1].name} [${countrieWithCommonLanguage[1].iso639_2}]`);
        }
        console.log("=======================================================================");
    }
    console.log("");
    console.log(`${Object.keys(listeCountrieWithCommonLanguage).length} Resultats.`);

}
//withCommonLanguage();


/** Question 6
 *
 * Pays sans aucun voisin ayant au moins une de ses
monnaies.
 */
function withoutCommonCurrency() {
    let res = [];

    for (const country in all_countries) {

        let doublon = false;
        let pays_v = all_countries[country].paysFrontaliers;
        
        // On étudie que les pays qui ont des voisins
        if (all_countries[country].getBorders().length !== 0) {

            // Pour tous les pays voisins de country
            for (let i = 0; i < all_countries[country].paysFrontaliers.length; i++) {

                // console.log(all_countries[pays_v[i]]);
                // Pour toutes les monnaies du pays voisins
                for (let j = 0; j < all_countries[country].monnaies.length; j++) {

                    // Pour toutes les monnaies du pays qu'on étudie
                    for (let k = 0; k < all_countries[pays_v[i]].getCurrencies().length; k++) {

                        // Si les 2 monnaies sont identiques, on signale qu'il y a un doublon détecté
                        if ((all_countries[pays_v[i]].getCurrencies()[k].code == all_countries[country].monnaies[j].code) ) {
                            doublon = true;
                        }
                    }
                }
            }
        }
        if (!doublon) {
            res.push(all_countries[country]);
        }
    }
    console.log(`Pays n’ayant aucun voisin ayant au moins une de ses monnaies :`);
    console.log("");
    for (let i = 0; i < res.length; i++) {
        console.log(`- ${res[i].nom}`);        
    }
    console.log("");
    console.log(`${res.length} Resultats.`);
}
//withoutCommonCurrency();


// Question 7


/**
 * Pays triés par ordre décroissant de densité de
population.
 */
function sortingDecreasingDensity() {
    res = all_countries;
    res2 = [];
    undefinedPopDensity = []
    let cpt = 0;
    for (const codeAlpha3 in res) {
        if(res[codeAlpha3].getPopDensity() == null){
            undefinedPopDensity.push(res[codeAlpha3]);
        }
        cpt++;
    }
    
    for (let i = 0; i < cpt; i++) {
        let country_max = null;
        let max = 0;
        
        // Recherche du Maximum
        for (const codeAlpha3 in res) {
            if (res[codeAlpha3].getPopDensity() > max && !res2.includes(res[codeAlpha3])) {
                country_max = res[codeAlpha3];
                max = res[codeAlpha3].getPopDensity();
            }
        }
        if(country_max != null){
            res2.push(res[country_max.codeAlpha3]);
        }
    }

    
    
    for (const countrie of res2) {
        console.log(`- ${countrie.nom} [${countrie.codeAlpha3}] : ${countrie.getPopDensity()} hab./km²`);
    }

    for (const countrie of undefinedPopDensity) {
        console.log(`- ${countrie.nom} [${countrie.codeAlpha3}] : Unknown`);
    }
    
}
//sortingDecreasingDensity();

// Question 8
function moreTopLevelDomains() {
    console.log(`Pays ayant plusieurs Top Level Domains Internet : `);
    console.log("");
    let cpt = 0;
    for (const country in all_countries) {
        if (all_countries[country]._topLevelDomains.length > 1) {
            console.log(`${all_countries[country].nom} [${all_countries[country].codeAlpha3}], ${all_countries[country].continent} <> Top Level Domain : ${all_countries[country].topLevelDomains} `);
            cpt++;

        } 
    }
    console.log("");
    console.log(`${cpt} Resultats.`);
}
//moreTopLevelDomains();

// Question 9
function veryLongTrip(nom_pays){
    var LongTrip = new Set();
    
    function StartCountrie_search(nom_pays) {
        var StartCountrie;
        for (const codeAlpha3 in all_countries) {
            if(all_countries[codeAlpha3].nom == nom_pays){
                StartCountrie = all_countries[codeAlpha3];
                break;
            }
        }
        return StartCountrie;
    }

    let StartCountrie = StartCountrie_search(nom_pays);
    for (const BorderCountrie of StartCountrie.getBorders()) {
        LongTrip.add(BorderCountrie.nom);
    }

    let i = 0;
    let CountrieList = LongTrip;
    let trouve = true;
    while(trouve){
        trouve = false;
        let LongTrip_temp = new Set();
        for (const Countrie of CountrieList){
            StartCountrie = StartCountrie_search(Countrie);
            for (const BorderCountrie of StartCountrie.getBorders()) {
                if(!LongTrip.has(BorderCountrie.nom)){
                    LongTrip_temp.add(BorderCountrie.nom);
                    trouve = true;
                }
            }
        }
        CountrieList = LongTrip;
        i++;
        LongTrip_temp.forEach(LongTrip.add, LongTrip);
    }
    console.log(`Liste des pays visitable en partant de ${nom_pays} : (${LongTrip.size} pays)`);
    for (const Countrie of LongTrip) {
        console.log(`- ${Countrie}`);
    }
}
//veryLongTrip("France");