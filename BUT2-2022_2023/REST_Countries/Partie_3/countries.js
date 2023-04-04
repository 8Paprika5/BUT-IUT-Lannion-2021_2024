/* ########################################### DATA ########################################### */
var countries;
/* ########################################### AJAX ########################################### */

let xhr = new  XMLHttpRequest ();
let url = "https://restcountries.com/v2/all";
if(!xhr) {
    alert("Erreur de creation de l'objet XML HTTP Request");
}else {
    xhr.open("GET", url, true);
    xhr.onreadystatechange = process;
    xhr.send(null);
}

function process () {
    if (xhr.readyState == 4) {
        if (xhr.status == 200) {
            countries = JSON.parse(xhr.responseText);

            // Remplissage du tableau
            fill_db();
            deleteUndefinedCountries();
            
            // Affichage des pays
            afficherPays(null, null, null);
            update();
            afficheBoutons();
            
            //Création des filtres
            CreationFiltre();
        } else {
            alert("Erreur retour requete XML HTTP : "+xhr.status);
        }
    }
}

  /* ########################################### TABLEAUX ########################################### */
  all_countries = []; // tableau associatif d'objets Country
  all_currencies = []; // tableau associatif d'objets Currency
  all_languages = []; // tableau associatif d'objets Language
  
  /* ########################################### CLASSE COUNTRY ########################################### */
  class Country {
    constructor(
      codeAlpha3,
      superficie,
      paysFrontaliers,
      capitale,
      continent,
      gentile,
      drapeau,
      nom,
      population,
      topLevelDomains,
      monnaies,
      langues
    ) {
      this._codeAlpha3 = codeAlpha3;
      this._superficie = superficie;
      this._paysFrontaliers = paysFrontaliers;
      this._capitale = capitale;
      this._continent = continent;
      this._gentile = gentile;
      this._drapeau = drapeau;
      this._nom = nom;
      this._population = population;
      this._topLevelDomains = topLevelDomains;
      this._monnaies = monnaies;
      this._langues = langues;
    }
  
    // Methods
    toString() {
      return "Nom du pays : " + this._nom;
    }
  
    // -------------------- METHODS --------------------
    add_country() {
      all_countries[this.codeAlpha3] = this;
    }
  
    getPopDensity() {
      if (this._population == null || this._superficie == null) {
        return null;
      }
      return this._population / this._superficie;
    }
  
    getBorders() {
      let bordersObject = [];
      if (this._paysFrontaliers != null) {
        for (const value of this._paysFrontaliers) {
          bordersObject.push(all_countries[value]);
        }
      }
      return bordersObject;
    }
  
    getCurrencies() {
      return this._monnaies;
    }
  
    getLanguages() {
      return this._langues;
    }
  
    // -------------------- GETTER & SETTER --------------------
    set codeAlpha3(codeAlpha3) {
      this._codeAlpha3 = codeAlpha3;
    }
  
    get codeAlpha3() {
      return this._codeAlpha3;
    }
  
    set superficie(superficie) {
      this._superficie = superficie;
    }
  
    get superficie() {
      return this._superficie;
    }
  
    set paysFrontaliers(paysFrontaliers) {
      this._paysFrontaliers = paysFrontaliers;
    }
  
    get paysFrontaliers() {
      return this._paysFrontaliers;
    }
  
    set capitale(capitale) {
      this._capitale = capitale;
    }
  
    get capitale() {
      return this._capitale;
    }
  
    set continent(continent) {
      this._continent = continent;
    }
  
    get continent() {
      return this._continent;
    }
  
    set gentile(gentile) {
      this._gentile = gentile;
    }
  
    get gentile() {
      return this._gentile;
    }
  
    set drapeau(drapeau) {
      this._drapeau = drapeau;
    }
  
    get drapeau() {
      return this._drapeau;
    }
  
    set nom(nom) {
      this._nom = nom;
    }
  
    get nom() {
      return this._nom;
    }
  
    set population(population) {
      this._population = population;
    }
  
    get population() {
      return this._population;
    }
  
    set topLevelDomains(topLevelDomains) {
      this._topLevelDomains = topLevelDomains;
    }
  
    get topLevelDomains() {
      return this._topLevelDomains;
    }
  
    set monnaies(monnaies) {
      this._monnaies = monnaies;
    }
  
    get monnaies() {
      return this._monnaies;
    }
  
    set langues(langues) {
      this._langues = langues;
    }
  
    get langues() {
      return this._langues;
    }
  }
  
  /* ########################################### CLASSE CURRENCY ########################################### */
  class Currency {
    // Constructor
    constructor(code, nom, symbole) {
      this._code = code;
      this._nom = nom;
      this._symbole = symbole;
    }
  
    // Methods
    toString() {
      return "Monnaie du pays : " + this._nom;
    }
  
    addCurrency() {
      all_currencies.push(this);
    }
  
    // Methods GETTER & SETTER
    set code(code) {
      this._code = code;
    }
  
    get code() {
      return this._code;
    }
  
    set nom(nom) {
      this._nom = nom;
    }
  
    get nom() {
      return this._nom;
    }
  
    set symbole(symbole) {
      this._symbole = symbole;
    }
  
    get symbole() {
      return this._symbole;
    }
  }
  
  /* ########################################### CLASSE LANGUAGE ########################################### */
  class Language {
    // Constructeur
    constructor(iso639_2, name) {
      this._iso639_2 = iso639_2;
      this._name = name;
    }
  
    // Methods
    toString() {
      return "Langues du pays : " + this._name;
    }
  
    addLanguage() {
      all_languages.push(this);
    }
  
    // Methods GETTER & SETTER
    set iso639_2(iso639_2) {
      this._iso639_2 = iso639_2;
    }
  
    get iso639_2() {
      return this._iso639_2;
    }
  
    set name(name) {
      this._name = name;
    }
  
    get name() {
      return this._name;
    }
  }
  
  function deleteUndefinedCountries() {
    for (let codeAlpha3 in all_countries) {
      let countrie = all_countries[codeAlpha3];
      let borders = countrie.getBorders();
      for (const borderCountrie of borders) {
        let borderCountrieGetBorders = borderCountrie.getBorders();
        for (let index = 0; index < borderCountrieGetBorders.length; index++) {
          if (borderCountrieGetBorders[index] == undefined) {
            borderCountrie.paysFrontaliers = borderCountrie.paysFrontaliers.filter(border => border != borderCountrie.paysFrontaliers[index]);
          }
        }
      }
  
    }
  }
  
  function fill_db() {
    
    countries.forEach((value) => {
      if (value.hasOwnProperty("area")) {
        superficie = value.area;
      } else {
        superficie = null;
      }
  
      if (value.hasOwnProperty("borders")) {
        paysFrontaliers = value.borders;
      } else {
        paysFrontaliers = null;
      }
  
      if (value.hasOwnProperty("capital")) {
        capitale = value.capital;
      } else {
        capitale = null;
      }
  
      if (value.hasOwnProperty("region")) {
        continent = value.region;
      } else {
        continent = null;
      }
  
      if (value.languages[0].hasOwnProperty("nativeName")) {
        gentile = value.languages[0].nativeName;
      } else {
        gentile = null;
      }
  
      codeAlpha3 = value.alpha3Code;
      drapeau = value.flags["svg"];
      nom = value.name;
      population = value.population;
      topLevelDomains = value.topLevelDomain;
  
      let tab_monnaies = [];
  
      // S'il existe le champ "currencies" dans le pays
      if (value.hasOwnProperty("currencies")) {
        // S'il a au moins 1 dans currencies
        if (value.currencies.length >= 1) {
          for (let i = 0; i < value.currencies.length; i++) {
            let cpt = 0;
            // Boucle qui vérifie la monnaie existe déjà dans all_currencies
            for (let j = 0; j < all_currencies.length; j++) {
              if (all_currencies[j].currency === value.currencies[i].code) {
                break;
              }
              cpt++;
            }
            let monnaie = new Currency(value.currencies[i].code, value.currencies[i].name, value.currencies[i].symbol);
            tab_monnaies.push(monnaie);
  
            // Si on n'a pas trouvé la monnaie dans all_currencies
            if (cpt == all_currencies.length) {
              monnaie.addCurrency();
            }
          }
        }
      } else {
        tab_monnaies = null;
      }
  
      let tab_langues = [];
  
      // S'il existe le champ "languages" dans le pays
      if (value.hasOwnProperty("languages")) {
        // S'il a au moins 1 dans languages
  
        if (value.languages.length >= 1) {
          for (let i = 0; i < value.languages.length; i++) {
  
            let cpt = 0;
            // Boucle qui vérifie la langue existe déjà dans all_languages
            for (let j = 0; j < all_languages.length; j++) {
              if (all_languages[j].iso639_2 === value.languages[i].iso639_2) {
                break;
              }
              cpt++;
            }
  
            let langue = new Language(value.languages[i].iso639_2, value.languages[i].name);
            tab_langues.push(langue);
  
            // Si on n'a pas trouvé la langue dans all_languages
            if (cpt == all_languages.length) {
              langue.addLanguage();
            }
  
          }
        }
      } else {
        tab_langues = null;
      }
  
      let country = new Country(
        codeAlpha3,
        superficie,
        paysFrontaliers,
        capitale,
        continent,
        gentile,
        drapeau,
        nom,
        population,
        topLevelDomains,
        tab_monnaies,
        tab_langues
      );
  
      country.add_country();
  
      return;
    });
  
    return;
  }
  
  