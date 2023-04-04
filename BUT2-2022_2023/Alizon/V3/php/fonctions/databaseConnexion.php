<?php
    include('connect_params.php');

    try {
        //Connexion a la base de donnée
        
        //$dbh = new PDO("jdbc:postgresql://host:port/name_of_database", $user, $pass);
        //$dbh = new PDO("mysql:postgresql://host=localhost:port=5432/dbname=postgres", $user, $pass);
        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $user, $pass);
        //$dbh = new PDO("plsql:host=$server;dbname=$dbname", $user, $pass);
        
        //Paramètres supplémentaires
        $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
        $dbh->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, PDO::ATTR_PERSISTENT);
        
        /*-------------------- LIBERATION DE LA MEMOIRE --------------------*/
        //$dbh = null; afaire après vos requetes dans les autres fichier

    } catch (PDOException $e) {
        print "Erreur :( : " . $e->getMessage() . "<br/>";
    }
?>