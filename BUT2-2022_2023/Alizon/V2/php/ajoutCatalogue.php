<?php
    include("fonctions/Session.php");
?>
<!DOCTYPE html>
<head>
	<title>Drag and Drop File Upload</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta charset="utf-8">
</head>
<body>
    <header>
        <?php include('header.php'); ?>
    </header>

    <main class="main-ajoutCatalogue">
        <h1 class="titre-ajoutC">Importer catalogue</h1>
        <section>
            <div class="block-ajoutCatalogue">
                <h2>Déposer le fichier csv</h2>
                <div id="drop-area">
                    <form enctype="multipart/form-data" action="insert_catalogue.php" method="post">
                        <div class="input-row">
                            <input class="importCSV" type="file" name="file" id="file" accept=".csv" pattern="\.csv" onchange="handleFiles(this.files)">
                            <div id="gallery"></div>
                            <button type="submit" id="submit" name="import">Importer</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <?php include('footer.php'); ?>
    </footer>

    <script> 
        function previewFile(file) {
            let reader = new FileReader()
            reader.readAsDataURL(file)
            reader.onloadend = function() {
                let img = document.createElement('img')
                img.src = reader.result
                document.getElementById('gallery').appendChild(img)
            }
        }

        function handleFiles(files) {
            files = [...files]
            files.forEach(previewFile)
        }
    </script>
      
</body>