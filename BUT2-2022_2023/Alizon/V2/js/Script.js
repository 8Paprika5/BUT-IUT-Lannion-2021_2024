/*
JS version 1.0
date de derniere modif : 25/10/2002 13h:52
par: Clément Mabileau
detail:
Creation du squelette du JS
*/

function afficheCategorie() {
    var x = document.getElementById("myLinks");
    if (x.style.display === "flex") {
      x.style.display = "none";
    } else {
      x.style.display = "flex";
      x.style.flexDirection = "column";
      x.style.position = "absolute";
      x.style.textAlign = "center";
      x.style.fontSize ="30px";
      x.style.backgroundColor= "#ffffff";
    }
  }

function afficheSousCategorie() {
    var x = document.getElementById("SubmyLinks");
    if (x.style.display === "flex") {
      x.style.display = "none";
    } else {
      x.style.display = "flex";
      x.style.flexDirection = "column";
      x.style.position = "absolute";
      x.style.left ="137.63px"
      x.style.textAlign = "center";
      x.style.fontSize ="20px";
      x.style.backgroundColor= "#ffffff";
    }
  }


  function CacherSousCategorie() {
    var x = document.getElementById("SubmyLinks");
      x.style.display = "none";
  }