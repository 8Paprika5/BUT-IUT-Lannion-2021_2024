<?php
/*#################### PRELIMINAIRES ####################*/
function convert_ASCII($texte){
    /*
    DESCRIPTION
        Converti les lettres d'un texte en ASCII
    
    PARAMETRES
        - texte{ texte a traduire

    RETURN
        Une chaine de caractère ou les lettres sont en ASCII
    */

    $res="";
    for ($i=0; $i < strlen($texte); $i++) { 
        $res = $res . " " . strval(ord($texte[$i]));
    }
    return $res;
}

function convert_CHAR($ascii){
    /*
    DESCRIPTION
        Converti une chaine de caractère ou les lettres sont en ASCII en lettres
    
    PARAMETRES
        - ascii{ chaine a traduire en caractères

    RETURN
        Une chaine de caractère avec des lettres
    */

    $res="";
    foreach (explode(" ", $ascii) as $key=>$lettre) {
        $res = $res. chr(intval($lettre));
    }
    
    return $res;
}

function estPremier($number){
    # Permet de tester si un nombre est premier
    if ($number == 1)
    return 0;
     
    for ($i = 2; $i <= sqrt($number); $i++){
        if ($number % $i == 0)
            return false;
    }
    return true;
}

$RANDMAX = 100;   # Limite maximum de génération de nombre aléatoires

/*#################### CHIFFREMENT RSA ####################*/
function gen_key($p,$q){
    /*
        DESCRIPTION
            Génère une clé de chiffremment/déchiffremment RSA
        
        PARAMETRES
            - p{ 
            - q{ 

        RETURN
            Un dictionnaire contenant la clé de chiffrement
    */
    $N = $p*$q;
    $n = ($p - 1)*($q - 1);
    $list = array();
    for ($i=0; $i < $N; $i++) { 
        if(gmp_gcd($i,$n)==1){
            array_push($list,$i);
        }
    }
    return array("N"=>$N,"c"=>$list[rand(1,sizeof($list))], "n"=>$n);
}

$KEY = array('N'=> 913, 'c'=> 57, 'n'=> 820);
function chiffrement_RSA($m,$key){
    /*
        DESCRIPTION
            Chiffre un nombre selon sa clé de chiffrement
        
        PARAMETRES
            - m{ nombre à chiffrer
            - key{ clé de chiffrement

        RETURN
            Le nombre m chffré
    */
    return bcmod(bcpow($m,$key['c']),$key['N']);
}

function dechiffrement_RSA($b,$key){
    /*
        DESCRIPTION
            Déchiffre un nombre selon sa clé de chiffrement
        
        PARAMETRES
            - b{ nombre à déchiffrer
            - key{ clé de déchiffrement

        RETURN
            Le nombre b déchffré
    */
    $d = 0;
    for ($i=0; $i < $key['n']; $i++) { 
        //echo "test {".$key['c']*$i.'<br>';
        if(bcmod(bcmul($key['c'],$i),$key['n']) == 1){
            $d = $i;    
        }
    }
    
    return bcmod(bcpow($b,$d),$key['N']);
}

/*#################### CHIFFREMENT DE MOTS DE PASSE ####################*/
function chiffrementMDP($mdp){
    $KEY = array('N'=> 713, 'c'=> 217, 'n'=> 660);     # clé de déchifrement stocké en dure
    
    # on converti les caractères en ASCII
    $mdpASCII = convert_ASCII($mdp) ;

    # on sépare la chaine de caractère en un tableau
    $Tabmdp = explode(" ", $mdpASCII);

    $mdpChiffreASCII = array();
    foreach ($Tabmdp as $key => $x) {
        if($key != 0){
            array_push($mdpChiffreASCII,chiffrement_RSA((intval($x)), $KEY));
        }
    }

    return implode(" ",$mdpChiffreASCII);
}

function dechiffrementMDP($mdpChiffreASCII){
    $KEY = array('N'=> 713, 'c'=> 217, 'n'=> 660);     # clé de déchifrement stcocké en dure
    
    $TabmdpChiffre = explode(" ", $mdpChiffreASCII);
    
    $dechifrement = array();
    foreach ($TabmdpChiffre as $key => $x) {
        array_push($dechifrement,dechiffrement_RSA((intval($x)), $KEY));
        
    }

    $chaineDechiffre = "";
    foreach ($dechifrement as $key => $x) {
        $chaineDechiffre = $chaineDechiffre.chr($x);
    }
    
    return $chaineDechiffre;
}

?>
