<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' type='text/css' href='../css/style.css'>
    
    <title>Confirmation de paiement</title>
</head>
<body>
    <?php include('header.php'); ?>

    <div class="confirmation">
        <p>Votre achat a bien été pris en compte !</p>
        <p>Votre paiement a bien été pris en compte, vous allez être redirigé dans 5 s.</p>
    </div>
    <footer>
            <?php include('footer.php'); ?>

    </footer>
    <script>

        let time = 5;

        const display = document.querySelector('.confirmation p+p');
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
                window.location.href = "./catalogue.php";
            }
        }
    </script>
</body>
</html>