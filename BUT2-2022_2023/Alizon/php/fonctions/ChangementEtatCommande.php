<?php

    

    function recupVraiEtatCommande($etat){
        $etatAbrev = ["acceptee", "en-charge", "en-cours", "finie", "annulee"];
        $etatReel = ["Acceptée", "Prise en charge","En cours de livraison", "Terminée", "Annulée"];

        $trouve = false;
        $i = 0;
        $idEtat = -1;
        while($i<sizeof($etatAbrev) && !$trouve){
            if($etatAbrev[$i] == $etat){
                $trouve = true;
                $idEtat = $i;
                
            }
            else {
                $i++;
            }
        }
        return $idEtat;
    }


    function changementEtat($idCom, $idProd, $etat){
        $etatAbrev = ["acceptee", "en-charge", "en-cours", "finie", "annulee"];
        $etatReel = ["Acceptée", "Prise en charge","En cours de livraison", "Terminée", "Annulée"];

        $i = recupVraiEtatCommande($etat);
        
        
?>
        <div class="filtre-flou">
            <section class="changement_etat-popup">
                <a href="vendeur.php"><i class="fa-solid fa-square-xmark croix-sortie"></i></a>
                <h3>État actuel : <?php echo $etatReel[$i]; ?></h3>
                <form action="vendeur.php" method="get">
                    <input class="radio_default" type="radio" name="default" id="default" checked >
                    <input class="radio_default" type="radio" name="Change_idCom" id="Change_idCom" value="<?php echo $idCom; ?>" checked >
                    <input class="radio_default" type="radio" name="Change_idProd" id="Change_idProd" value="<?php echo $idProd; ?>" checked >

                    <input class="radio_etat" type="radio" name="etat" id="etat-suivant" value="<?php echo $etatAbrev[$i+1]; ?>">
                    <label class="for-radio_etat" for="etat-suivant"><?php echo $etatReel[$i+1]; ?></label>

                    <input class="radio_etat" type="radio" name="etat" id="etat-annulee" value="annulee">
                    <label class="for-radio_etat" for="etat-annulee">Annulée</label>

                    <div class="boutons_finalisation">
                        <button class="bouton-annuler" type="submit" name="finalisation" value="Annuler modifications">Annuler modifications</button>
                        <button class="bouton-valider" type="submit" name="finalisation" value="Valider" disabled>Valider</button>
                        

                    </div>

                </form>
            </section>
        </div>

        <script>

            var inputElems = document.getElementsByClassName("radio_etat");
            var filtreFlou = document.querySelector(".filtre-flou");//:not(.changement_etat-popup)
            var popup = document.getElementsByClassName("changement_etat-popup")[0];

            for (var i = inputElems.length - 1; i >= 0; --i) {
                var elem = inputElems[i];
                elem.onchange = function () {
                    document.getElementsByClassName("bouton-valider")[0].removeAttribute("disabled");
                };
            }

            
            /*filtreFlou.addEventListener('click', function(e) {
                console.log("cringe !");
                if (!e.target.classList.contains('changement_etat-popup')) {
                    console.log("pouah l'odeur !");
                    document.location.href="vendeur.php";
                    
                }
            });*/
            

            /*
            function reload() {
                document.location.href="vendeur.php";
            }
            filtreFlou.addEventListener('click',reload());
            */

        </script>
<?php
    }

?>