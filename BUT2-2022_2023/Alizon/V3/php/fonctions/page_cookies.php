<?php
  /**
     * Description : 
     * Permet d'afficher une "pop up" de demande pour les cookies
     * 
     * Parametres : 
     * 
     * 
     * ¨Pas de return
     * 
     */
    function bloc_cookies() {
        ?>
        <div class="block_cookies">

            <div class="texte_cookies">
                <h3>Vos paramètres cookies</h3>

                <p>On vous demande d'accepter les cookies à des fins de performances. Pour obtenir plus d'informations sur ces cookies et le traitement de vos données personnelles, consultez notre politique de confitiendalité et de cookies. Acceptez vous ces cookies et le traitement des données personnelles qu'ils impliquent ?</p>
            </div>

            <div class="boutons_cookies">
                <div>
                    <button class="btn_refuse_cookies" onclick="formClose();">Je refuse</button>
                    
                    <button class="btn_accept_cookies" onclick="formClose();" class="btn_accept_cookies">J'accepte</button>
                </div>
            </div>

        </div>
        
        <script>
            let bloc_cookies = document.querySelector(".block_cookies");
/**
* Description : 
* Permet d'enlever la fenetre de cookie
* 
* Parametres : 
* 
* 
* ¨Pas de return
* 
*/
            function formClose() {
                bloc_cookies.style.display = "none";
            }
        </script>
        <?php
    }
?>
