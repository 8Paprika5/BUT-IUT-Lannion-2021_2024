<?php
    include("fonctions/Session.php");
    include("fonctions/fonctions.php");

    $client = infos_cli($_SESSION["id_client"]);
    $nom = $client["nom_client"];
    $prenom = $client["prenom_client"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Confirmation de paiement</title>
</head>
<body>
    <?php include('header.php'); ?>

    <main class="confirm-paiement">
        <div class="confirm-paiement--client">
            <h1>Merci de votre achat <?php echo $prenom . "\n" . $nom ; ?> !</h1>
            <p>Votre paiement a bien été pris en compte, vous allez être redirigé dans 5 s.</p>
        </div>
    </main>

    <footer>
            <?php include('footer.php'); ?>

    </footer>
    <script>

        let time = 5;

        const display = document.querySelector('.confirm-paiement--client h1+p');
        document.getElementbyID

        setInterval(updateCompteaRebours, 1000);

        function updateCompteaRebours()
        {
            //let secondes = time % 60;
            //secondes = secondes < 10 ? '0' + secondes : secondes;
            time--;
            display.innerHTML = ` Votre paiement a bien été pris en compte, vous allez être redirigé dans ${time} s.`;

            if (time == 0)
            {
                window.location.href = "./index.php";
            }
        }
    </script>
</body>
</html>