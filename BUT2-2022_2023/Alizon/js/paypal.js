/*<!-- Replace "test" with your own sandbox Business account app client ID -->
<script src="https://www.paypal.com/sdk/js?client-id=AQkj6hJvkI1m0FOnkisTztVhXaHk_ZrTFfnUXNDZ3Au1uO4LwQH10yjHrXkuuXP9XkIg7jWgKT88a2X8&currency=EUR"></script>
<!-- Set up a conteneur element for the button -->
<div id="paypal-button-conteneur"></div>

<script>
paypal.Buttons({
    // Sets up the transaction when a payment button is clicked
    createOrder: (data, actions) => {
    return actions.order.create({
        "purchase_units": [{
            "amount": {
            "currency_code": "EUR",
            "value": "<?php echo $prixTotal;?>",
            "breakdown": {
                "item_total": {  
                "currency_code": "EUR",
                "value": "<?php echo $prixTotal;?>"
                }
            }
            },
            "items": [
                <?php 
                //$result = elems_panier();
                foreach($result as $produit)
                {
                    ?>
                    {
                        "name": "<?php echo $produit["Nom_produit"] ; ?>", 
                        "description": "<?php echo $produit["Description_produit"] ; ?>", 
                        "unit_amount": {
                        "currency_code": "EUR",
                        "value": "<?php echo $produit["Prix_vente_TTC"] ; ?>"
                        },
                        "quantity": "<?php echo $produit["Quantite"] ; ?>"
                    },
                    <?php
                }
                ?>
            ]
        }]
    });
    },
    // Finalize the transaction after payer approval
    onApprove: (data, actions) => {
    return actions.order.capture().then(function(orderData) {
        // Successful capture! For dev/demo purposes:
        console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
        const transaction = orderData.purchase_units[0].payments.captures[0];
        //alert(`Transaction ${transaction.status}: ${transaction.id}\n\nSee console for all available details`);
        // When ready to go live, remove the alert and show a success message within this page. For example:
        //const element = document.getElementById('paypal-button-conteneur');
        //element.innerHTML = '<h3>Thank you for your payment!</h3>';
        // Or go to another URL:  actions.redirect('thank_you.html');
        window.location.href = "./confirmation.php";
    });
    }
}).render('#paypal-button-conteneur');
*/