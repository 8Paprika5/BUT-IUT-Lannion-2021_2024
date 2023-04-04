#include <stdlib.h>
#include <time.h>
#include <stdio.h>
#include <string.h>
#include <stdbool.h>


/**
*
* \brief Simulation d'un jeu de yam's en C
*
* \author PORTIER Loane
*
* \version 1.0
*
* \date 27 Novembre 2021
*
*
*/

/*####################################################################################################
############################       Déclaration de const constantes        ############################
####################################################################################################*/

/**
*
* \def CHARMAX
*
* \brief Nombre de caractères maximum pour les noms des joueurs.
*
*/
#define CHARMAX 15

#define TOURMAX 12

/**
*
* \def CDTBONUS
*
* \brief Condition d'obtention du Bonus.

* \details Si le total des sommes des 1,2,3,4,5 et 6 est supérieur à CDTBONUS : le BONUS est appliqué.

*/
#define CDTBONUS 62

#define BONUS 32

/**
* \def FULL
* \def P_SUITE
* \def G_SUITE
* \def YAMS
* \brief Valeur fixe de points.
*
*/
#define FULL 25
#define P_SUITE 30
#define G_SUITE 40
#define YAMS 50

/**
* \def init
*
* \brief Valeur d'initialisation des cases du tableau.
*
*/
#define init -1

#define nbr_lancer_max 2



/*####################################################################################################
#####################################     déclaration des Types     #################################
####################################################################################################*/

/**
* \typedef t_Val_de
*
* \brief Type Tableau d'entiers contenant les valeurs des 5 dés.
*
* \details Les valeurs correspondent respectivement à : de1, de2, de3, de4, de5.
*
*/
typedef int t_Val_de[5];

/**
*
* \typedef  t_pts_J
*
* \brief    Type Tableau d'entiers contenant les données des joueurs.
*
* \details  Les valeurs correspondent respectivement à :
*           Somme des 1, Somme des 2, Somme des 3, Somme des 4, Somme des 5, Somme des 6, 
*           Bonus, Total_1, Brelan, carré, Full, Petite suite, Grande suite, yam's,
*           chance, Total_2, nombre_de_lancer, nombre_Total_de_tours.
*/
typedef int t_pts_J[18];

/**
*
* \typedef t_options
*
* \brief Type Tableau de caractères contenant les options de jeu.
*
*/
typedef char t_options[21];

/**
* \typedef t_nom
*
* \brief Type Tableau de chaine de caractères contenant les noms des joueurs.
*
* \details  La première valeur correspond au nom du joueur 1.
*           La deuxième valeur correspond au nom du joueur 2.
*           La troisième valeur correspond au nom du joueur en train de jouer.
*
*/
typedef t_options t_nom[3];



/*####################################################################################################
#################################     déclaration des fonctions      #################################
####################################################################################################*/

/**
 * 
 * \fn  affichage(t_Val_de, t_pts_J, t_pts_J, t_options, t_options, t_options, t_options, t_options, t_nom)
 * 
 * \brief Affiche l'interface principal du jeu.
 * 
 * \param de Tableau d'entiers contenant les valeurs des 5 dés.
 * \param J1 Tableau d'entiers contenant les données des joueurs 1.
 * \param J2 Tableau d'entiers contenant les données des joueurs 2.
 * \param opt1 Tableau de caractères contenant les options de jeu.
 * \param opt2 Tableau de caractères contenant les options de jeu.
 * \param opt3 Tableau de caractères contenant les options de jeu.
 * \param opt4 Tableau de caractères contenant les options de jeu.
 * \param opt5 Tableau de caractères contenant les options de jeu.
 * \param nom_J Tableau de caractères contenant les noms des joueurs.
 *  
 */
void affichage(t_Val_de, t_pts_J, t_pts_J, t_options, t_options, t_options, t_options, t_options, t_nom);

/**
 * 
 * \fn  Menu_Principale(t_Val_de, t_pts_J, t_pts_J, t_options, t_options, t_options, t_options, t_options, t_nom)
 * 
 * \param de Tableau d'entiers contenant les valeurs des 5 dés.
 * \param J1 Tableau d'entiers contenant les données des joueurs 1.
 * \param J2 Tableau d'entiers contenant les données des joueurs 2.
 * \param opt1 Tableau de caractères contenant les options de jeu.
 * \param opt2 Tableau de caractères contenant les options de jeu.
 * \param opt3 Tableau de caractères contenant les options de jeu.
 * \param opt4 Tableau de caractères contenant les options de jeu.
 * \param opt5 Tableau de caractères contenant les options de jeu.
 * \param nom_J Tableau de caractères contenant les noms des joueurs.
 * 
 * \brief affiche le menu Principale,
 * 
 * \details donne les possibilités de jouer, lire les règle ou quitter
 * 
 */
void Menu_Principale(t_Val_de, t_pts_J, t_pts_J, t_options, t_options, t_options, t_options, t_options, t_nom);

/**
 * 
 * \fn  Menu_Jouer(t_Val_de, t_pts_J, t_pts_J, t_options, t_options, t_options, t_options, t_options, t_nom)
 * 
 * \param de Tableau d'entiers contenant les valeurs des 5 dés.
 * \param J1 Tableau d'entiers contenant les données des joueurs 1.
 * \param J2 Tableau d'entiers contenant les données des joueurs 2.
 * \param opt1 Tableau de caractères contenant les options de jeu.
 * \param opt2 Tableau de caractères contenant les options de jeu.
 * \param opt3 Tableau de caractères contenant les options de jeu.
 * \param opt4 Tableau de caractères contenant les options de jeu.
 * \param opt5 Tableau de caractères contenant les options de jeu.
 * \param nom_J Tableau de caractères contenant les noms des joueurs.
 * 
 * \brief affiche le menu du Jeu.
 * 
 * \details donne les possibilités de Commencer une partie, changer le noms des joueurs.
 * 
 */
void Menu_Jouer(t_Val_de, t_pts_J, t_pts_J, t_options, t_options, t_options, t_options, t_options, t_nom);

/**
 * 
 * \fn  Menu_Partie(t_Val_de, t_pts_J, t_pts_J, t_options, t_options, t_options, t_options, t_options, t_nom)
 * 
 * \param de Tableau d'entiers contenant les valeurs des 5 dés.
 * \param J1 Tableau d'entiers contenant les données des joueurs 1.
 * \param J2 Tableau d'entiers contenant les données des joueurs 2.
 * \param opt1 Tableau de caractères contenant les options de jeu.
 * \param opt2 Tableau de caractères contenant les options de jeu.
 * \param opt3 Tableau de caractères contenant les options de jeu.
 * \param opt4 Tableau de caractères contenant les options de jeu.
 * \param opt5 Tableau de caractères contenant les options de jeu.
 * \param nom_J Tableau de caractères contenant les noms des joueurs.
 * 
 * \brief affiche le menu de la partie.
 * 
 * \details donne les possibilités de Relancer les dés, choisir une case.
 * 
 */
void Menu_Partie(t_Val_de, t_pts_J, t_pts_J, t_options, t_options, t_options, t_options, t_options, t_nom);

/**
 * 
 * \fn  Menu_Nom(t_Val_de, t_pts_J, t_pts_J, t_options, t_options, t_options, t_options, t_options, t_nom)
 * 
 * \param de Tableau d'entiers contenant les valeurs des 5 dés.
 * \param J1 Tableau d'entiers contenant les données des joueurs 1.
 * \param J2 Tableau d'entiers contenant les données des joueurs 2.
 * \param opt1 Tableau de caractères contenant les options de jeu.
 * \param opt2 Tableau de caractères contenant les options de jeu.
 * \param opt3 Tableau de caractères contenant les options de jeu.
 * \param opt4 Tableau de caractères contenant les options de jeu.
 * \param opt5 Tableau de caractères contenant les options de jeu.
 * \param nom_J Tableau de caractères contenant les noms des joueurs.
 * 
 * \brief affiche le menu des noms
 * 
 * \details donne les possibilités de modifier le nom du joueur 1 ou du joueur2.
 * 
 */
void Menu_Nom(t_Val_de, t_pts_J, t_pts_J, t_options, t_options, t_options, t_options, t_options, t_nom);

/**
 * 
 * \fn  Menu_Cases(t_Val_de, t_pts_J, t_pts_J, t_options, t_options, t_options, t_options, t_options, t_nom)
 * 
 * \param de Tableau d'entiers contenant les valeurs des 5 dés.
 * \param J1 Tableau d'entiers contenant les données des joueurs 1.
 * \param J2 Tableau d'entiers contenant les données des joueurs 2.
 * \param opt1 Tableau de caractères contenant les options de jeu.
 * \param opt2 Tableau de caractères contenant les options de jeu.
 * \param opt3 Tableau de caractères contenant les options de jeu.
 * \param opt4 Tableau de caractères contenant les options de jeu.
 * \param opt5 Tableau de caractères contenant les options de jeu.
 * \param nom_J Tableau de caractères contenant les noms des joueurs.
 * 
 * \brief affiche le menu de la feuille de marque.
 * 
 * \details donne les possibilités de choisir une Somme de Nombres ou une combinaison.
 * 
 */
void Menu_Cases(t_Val_de, t_pts_J, t_pts_J, t_options, t_options, t_options, t_options, t_options, t_nom);

/**
 * 
 * \fn  Menu_Combinaisons_1(t_Val_de, t_pts_J, t_pts_J, t_options, t_options, t_options, t_options, t_options, t_nom)
 * 
 * \param de Tableau d'entiers contenant les valeurs des 5 dés.
 * \param J1 Tableau d'entiers contenant les données des joueurs 1.
 * \param J2 Tableau d'entiers contenant les données des joueurs 2.
 * \param opt1 Tableau de caractères contenant les options de jeu.
 * \param opt2 Tableau de caractères contenant les options de jeu.
 * \param opt3 Tableau de caractères contenant les options de jeu.
 * \param opt4 Tableau de caractères contenant les options de jeu.
 * \param opt5 Tableau de caractères contenant les options de jeu.
 * \param nom_J Tableau de caractères contenant les noms des joueurs.
 * 
 * \brief affiche le menu des combinaisons
 * 
 * \details donne les possibilités de choisir un yam's, un carré un full ou de passer au Menu_Combinaisons_2
 * 
 */
void Menu_Combinaisons_1(t_Val_de, t_pts_J, t_pts_J, t_options, t_options, t_options, t_options, t_options, t_nom);

/**
 * 
 * \fn  Menu_Combinaisons_2(t_Val_de, t_pts_J, t_pts_J, t_options, t_options, t_options, t_options, t_options, t_nom)
 * 
 * \param de Tableau d'entiers contenant les valeurs des 5 dés.
 * \param J1 Tableau d'entiers contenant les données des joueurs 1.
 * \param J2 Tableau d'entiers contenant les données des joueurs 2.
 * \param opt1 Tableau de caractères contenant les options de jeu.
 * \param opt2 Tableau de caractères contenant les options de jeu.
 * \param opt3 Tableau de caractères contenant les options de jeu.
 * \param opt4 Tableau de caractères contenant les options de jeu.
 * \param opt5 Tableau de caractères contenant les options de jeu.
 * \param nom_J Tableau de caractères contenant les noms des joueurs.
 * 
 * \brief affiche le menu des combinaisons
 * 
 * \details donne les possibilités de choisir un brelan, une petite ou grande suite ou de passer au Menu_Combinaisons_1
 * 
 */
void Menu_Combinaisons_2(t_Val_de, t_pts_J, t_pts_J, t_options, t_options, t_options, t_options, t_options, t_nom);

/**
 * 
 * \fn  Menu_Somme(t_Val_de, t_pts_J, t_pts_J, t_options, t_options, t_options, t_options, t_options, t_nom)
 * 
 * \param de Tableau d'entiers contenant les valeurs des 5 dés.
 * \param J1 Tableau d'entiers contenant les données des joueurs 1.
 * \param J2 Tableau d'entiers contenant les données des joueurs 2.
 * \param opt1 Tableau de caractères contenant les options de jeu.
 * \param opt2 Tableau de caractères contenant les options de jeu.
 * \param opt3 Tableau de caractères contenant les options de jeu.
 * \param opt4 Tableau de caractères contenant les options de jeu.
 * \param opt5 Tableau de caractères contenant les options de jeu.
 * \param nom_J Tableau de caractères contenant les noms des joueurs.
 * 
 * \brief affiche le menu des combinaisons
 * 
 * \details donne les possibilités de choisir une somme de nombre précis(1,2,3,4,5 ou 6) ou la case chance
 * 
 */
void Menu_Somme(t_Val_de, t_pts_J, t_pts_J, t_options, t_options, t_options, t_options, t_options, t_nom);

/**
 * 
 * \fn  Menu_Fin(t_Val_de, t_pts_J, t_pts_J, t_options, t_options, t_options, t_options, t_options, t_nom)
 * 
 * \param de Tableau d'entiers contenant les valeurs des 5 dés.
 * \param J1 Tableau d'entiers contenant les données des joueurs 1.
 * \param J2 Tableau d'entiers contenant les données des joueurs 2.
 * \param opt1 Tableau de caractères contenant les options de jeu.
 * \param opt2 Tableau de caractères contenant les options de jeu.
 * \param opt3 Tableau de caractères contenant les options de jeu.
 * \param opt4 Tableau de caractères contenant les options de jeu.
 * \param opt5 Tableau de caractères contenant les options de jeu.
 * \param nom_J Tableau de caractères contenant les noms des joueurs.
 * 
 * \brief affiche le menu de fin de partie.
 * 
 * \details donne les possibilités de Rejouer, afficher les règles ou quitter.
 * 
 */
void Menu_Fin(t_Val_de, t_pts_J, t_pts_J, t_options, t_options, t_options, t_options, t_options, t_nom);


/**
 * 
 * \fn Alea()
 * 
 * \brief Génère un nombre aléatoire entre 1 et 6.
 * 
 * \return un entier aléatoire entre 1 et 6
 */
int Alea();

/**
 * \fn sleep()
 * 
 * \brief permet de mettre le programme en attente pendant une seconde
 * 
 * \details C'est une fonction complémentaire de Alea().
 *          sleep() donne la possibilité de générer plusieurs dés aléatoirement à la suite.
 * 
 */
void sleep();

/**
 * \fn taille(char nom[])
 * 
 * \param nom Chaine de caractères contenant le nom d'un joueur.
 * 
 * \brief Calcule la taille d'une chaine de caractère.
 * 
 * \return un Entier correspondant au nombre de caractère total (sans "\0").
 */
int taille(char nom[]);

/**
 * 
 * \fn Lancer(t_Val_de)
 * 
 * \param de Tableau d'entiers contenant les valeurs des 5 dés.
 * 
 * \brief lance les 5 dés aléatoirement
 * 
 */
void Lancer(t_Val_de);

/**
 * \fn Relancer(t_Val_de, bool*)
 * 
 * \brief Relance les dés choisie par l'utilisateur.
 * 
 * \param de Tableau d'entiers contenant les valeurs des 5 dés.
 * 
 * \param relance Booleen devient True si l'utilisateur relance un dé.
 * 
 * \details Si l'utilisateur choisit de garder tous ses dés, ce ne sera pas compter comme un relancer.
 *          relance sera donc égale à Faux.
 */
void Relancer(t_Val_de, bool*);

/**
 * \fn initialiser(t_Val_de, t_pts_J, t_pts_J)
 * 
 * \brief initialise la feuille de marque
 * 
 * \param de Tableau d'entiers contenant les valeurs des 5 dés. 
 * \param J1 Tableau d'entiers contenant les scores du joueur 1.
 * \param J2 Tableau d'entiers contenant les scores du joueur 2.
 */
void initialiser(t_Val_de, t_pts_J, t_pts_J);

/**
 * \fn trouve(t_Val_de, int)
 * 
 * \brief Effectue un recherhe séquentielle sur les dés obtenues.
 * 
 * \param de Tableau d'entiers contenant les valeurs des 5 dés.
 * \param val valeur recherché parmi les dés obtenues.
 * 
 * \return Un entier correspondant au nombre de dés égal à val.
 */
int trouve(t_Val_de, int);

/**
 * \fn remplir_case(t_Val_de, t_pts_J, t_pts_J, int, int, t_nom)
 * 
 * \brief Remplie la feuille de marque avec les scores correspondants.
 * 
 * \details 
 * 
 * \param de Tableau d'entiers contenant les valeurs des 5 dés.
 * \param J1 Tableau d'entiers contenant les scores du joueur 1.
 * \param J2 Tableau d'entiers contenant les scores du joueur 2.
 * \param num_combi entier correspondant a la combinaison choisie.
 * \param val_combi entier correspondant au score de la combinaison
 * \param nom_J Tableau de chaines de caractères contenant les noms des joueurs
 */
void remplir_case(t_Val_de, t_pts_J, t_pts_J, int, int, t_nom);

/**
 * \fn combinaison_possible(t_Val_de)
 * 
 * \brief determine les combinaisons possible avec les dés actuels
 * 
 * \details la determination d'une combinaison s'effectue grâce à la somme de liste_oc
 *          qui correspond respectivement au nombre de dés identiques. 
 *
 *          Si les dés tirés sont {2,5,2,5,2}, liste_oc sera donc égale à {3,2,3,2,3};
 *          car il y a 3 dés identique au dé 1, 2 dés identique au dé 2, 3 dés identique au dé 3, ...
 *           
 *          De cette maniere, la somme des valeurs de liste_oc (somme_oc) est unique.
 *          Chaque somme correspond alors a une combinaison différente.
 * 
 * \param de Tableau d'entiers contenant les valeurs des 5 dés.
 * \return Un entier correspondant à la valeur de la meilleur combinaison possible.
 */
int combinaison_possible(t_Val_de);




/*####################################################################################################
#################################     Définition des fonctions      ##################################
####################################################################################################*/

void affichage( t_Val_de de, t_pts_J J1, t_pts_J J2, t_options opt1, t_options opt2, t_options opt3, t_options opt4, t_options opt5, t_nom nom_J){
    printf("\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n");
    printf("________________________________________\t");                  printf("\t\t_________________\n");
    printf("|Yam's\t|%s|%s|\t",nom_J[0],nom_J[1]);          printf("\t\t|Lancée de Dés\t|\n");
    printf("|1\t|%d\t\t|%d\t\t|\t", J1[0],J2[0]);               printf("\t\t|dé 1\t|%d\t|\n", de[0]);
    printf("|2\t|%d\t\t|%d\t\t|\t", J1[1],J2[1]);               printf("\t\t|dé 2\t|%d\t|\n", de[1]);
    printf("|3\t|%d\t\t|%d\t\t|\t", J1[2],J2[2]);               printf("\t\t|dé 3\t|%d\t|\n", de[2]);
    printf("|4\t|%d\t\t|%d\t\t|\t", J1[3],J2[3]);               printf("\t\t|dé 4\t|%d\t|\n", de[3]);
    printf("|5\t|%d\t\t|%d\t\t|\t", J1[4],J2[4]);               printf("\t\t|dé 5\t|%d\t|\n", de[4]);
    printf("|6\t|%d\t\t|%d\t\t|\t", J1[5],J2[5]);               printf("\t\t¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯\n");
    printf("|Bonus\t|%d\t\t|%d\t\t|\t",     J1[6],J2[6]);       printf("\t\t_________________________\n");
    printf("|Total 1|%d\t\t|%d\t\t|\t",     J1[7],J2[7]);       printf("\t\t|Menu Principal\t\t|\n");
    printf("|\t|\t\t|\t\t|\t");                                 printf("\t\t|1 %s|\n", opt1);
    printf("|Brelan\t|%d\t\t|%d\t\t|\t",    J1[8],J2[8]);       printf("\t\t|2 %s|\n", opt2);
    printf("|Carré\t|%d\t\t|%d\t\t|\t",     J1[9],J2[9]);       printf("\t\t|3 %s|\n", opt3);
    printf("|Full\t|%d\t\t|%d\t\t|\t",      J1[10],J2[10]);     printf("\t\t|4 %s|\n", opt4);
    printf("|P_suite|%d\t\t|%d\t\t|\t",     J1[11],J2[11]);     printf("\t\t|5 %s|\n", opt5);
    printf("|G_Suite|%d\t\t|%d\t\t|\t",     J1[12],J2[12]);     printf("\t\t¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯\n");
    printf("|Yam's\t|%d\t\t|%d\t\t|\t",     J1[13],J2[13]);     printf("\t\t_________________\n");
    printf("|Chance\t|%d\t\t|%d\t\t|\t",    J1[14],J2[14]);     printf("\t\t|Au tour de :\t|\n");
    printf("|\t|\t\t|\t\t|\t");                                 printf("\t\t|%s|\n",nom_J[2]);
    printf("|Total 2|%d\t\t|%d\t\t|\t",     J1[15],J2[15]);     printf("\t\t|\t\t|\n");
    printf("¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯\t");       printf("\t\t¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯\n\n");
    
    
}



void Menu_Principale(t_Val_de de, t_pts_J J1, t_pts_J J2, t_options opt1, t_options opt2, t_options opt3, t_options opt4, t_options opt5, t_nom nom_J){
    affichage(de,J1,J2,opt1,opt2,opt3,opt4,opt5,nom_J);
    int choix;
    do{
        printf("entrer votre choix: ");
        scanf("%d",&choix);
        switch(choix){
            case 1 :    Menu_Jouer(de, J1, J2,"Commencer une Partie\t", "nom des joueurs\t", "Retour\t\t","\t\t\t","\t\t\t",nom_J);
                        break;
            case 2 :
                        printf("\n\nLe Yams se joue avec 5 dés.\n");
                        printf("\tLe Jeu se finit une fois toutes les cases de la feuille de marque remplie (il y a 13 combinaisons à réaliser)\n");
                        printf("\tLes joueurs jouent à tour de rôle et chaque joueur dispose de 3 lancers a chaque coup.\n\n");
                        
                        printf("A chaque lancer, le joueur a le choix de relancer tous les dés \n");
                        printf("\tou seulement une partie des dés, selon son gré, pour tenter d’obtenir la combinaison voulue\n\n");

                        printf("À chaque tour, le joueur doit obligatoirement inscrire son score dans une des cases de la feuille de marque\n");
                        printf("\tque ce soit par les points qu’il a obtenus ou par un 0.\n\n");

                        printf("Bien entendu, il ne pourra plus faire cette combinaison par la suite.\n");
                        printf("\tLe gagnant d’une partie de Yams est le joueur qui comptabilise le plus de points à la fin des 13 coups.\n\n");
                        break;

            case 3 :    printf("\n\nAu revoir\n\n");
                        scanf(3); //evite les boucles d'appel
                        break;
                        
            default:    printf("\n\nErreur de saisi\n\n");
                        Menu_Principale(de, J1, J2,"Jouer\t\t", "Les Règles\t\t", "Quitter\t\t","\t\t\t","\t\t\t",nom_J);
                        break;
        }
    }while(choix !=3);
}

void Menu_Jouer(t_Val_de de, t_pts_J J1, t_pts_J J2, t_options opt1, t_options opt2, t_options opt3, t_options opt4, t_options opt5, t_nom nom_J){
    affichage(de,J1,J2,opt1,opt2,opt3,opt4,opt5,nom_J);
    int choix;
    do{
        printf("entrer votre choix: ");
        scanf("%d",&choix);
        switch(choix){
            case 1 :    // initialisation des variables a chaques début de partie
                        initialiser(de,J1,J2);
                        Menu_Partie(de, J1, J2,"Relancer les dés\t", "choisir une case\t", "Retour\t\t","\t\t\t","\t\t\t",nom_J);
                        break;

            case 2 :    Menu_Nom(de, J1, J2,nom_J[0],nom_J[1],"Retour\t\t","\t\t\t","\t\t\t",nom_J);
                        break;
            case 3 :    Menu_Principale(de, J1, J2,"Jouer\t\t", "Les Règles\t\t", "Quitter\t\t","\t\t\t","\t\t\t",nom_J);
                        break;
                        
            default:    printf("\n\nErreur de saisi\n\n");
                        Menu_Jouer(de, J1, J2,"Commencer une Partie\t", "nom des joueurs\t", "Retour\t\t","\t\t\t","\t\t\t",nom_J);
                        break;
        }
    }while(choix !=3);
}

void Menu_Partie(t_Val_de de, t_pts_J J1, t_pts_J J2, t_options opt1, t_options opt2, t_options opt3, t_options opt4, t_options opt5, t_nom nom_J){
    
    if(J1[17]==TOURMAX && J2[17]==TOURMAX){
        Menu_Fin(de,J1,J2,"Rejouer\t\t", "Les Règles\t\t", "Quitter\t\t","\t\t\t","\t\t\t",nom_J);
    }else{
        // si c'est le premier lancer du tour actuel, tous les des sont lancés une première fois
        if(J1[16]==init && J2[16]==init){Lancer(de);}
        
        affichage(de,J1,J2,opt1,opt2,opt3,opt4,opt5,nom_J);
        int choix;
        bool relance;
        do{
            if(J1[16]>=nbr_lancer_max || J2[16]>=nbr_lancer_max){printf("nombre de lancer maximum atteint \n");}
            printf("entrer votre choix: ");
            scanf("%d",&choix);
            switch(choix){
                case 1 :
                        if(J1[16]<nbr_lancer_max && J2[16]<nbr_lancer_max){
                            Relancer(de, &relance);
                            printf("1 : J1[16] : %d \n",J1[16]);
                            printf("1 : J2[16] : %d \n",J2[16]);
                            if(relance){
                                if(J1[17]==J2[17]){
                                    if(J1[16]==init){J1[16]=1;
                                    }else{J1[16]++;}
                                }else{
                                    if(J2[16]==init){J2[16]=1;
                                    }else{J2[16]++;}
                                }
                            }else{
                                if(J1[17]==J2[17]){
                                    if(J1[16]==init){J1[16]=0;}
                                }else{
                                    if(J2[16]==init){J2[16]=0;}
                                }
                            }
                            printf("2 : J1[16] : %d \n",J1[16]);
                            printf("2 : J2[16] : %d \n",J2[16]);
                            Menu_Partie(de, J1, J2,"Relancer les dés\t", "choisir une case\t", "Retour\t\t","\t\t\t","\t\t\t",nom_J);

                        }else{ Menu_Cases(de, J1, J2,"Somme de Nombres\t", "combinaison\t\t", "Retour\t\t","\t\t\t","\t\t\t",nom_J);}
                        break;

                case 2 :
                        Menu_Cases(de, J1, J2,"Somme de Nombres\t", "combinaison\t\t", "Retour\t\t","\t\t\t","\t\t\t",nom_J);
                        break;

                case 3 :    
                        Menu_Jouer(de, J1, J2,"Commencer une Partie\t", "nom des joueurs\t", "Retour\t\t","\t\t\t","\t\t\t",nom_J);
                        break;
                            
                default:
                        printf("\n\nErreur de saisi\n\n");
                        Menu_Partie(de, J1, J2,"Relancer les dés\t", "choisir une case\t", "Retour\t\t","\t\t\t","\t\t\t",nom_J);
                        break;
            }
        }while(choix !=3);
    }
}

void Menu_Fin(t_Val_de de, t_pts_J J1, t_pts_J J2, t_options opt1, t_options opt2, t_options opt3, t_options opt4, t_options opt5, t_nom nom_J){
    if(J1[15]>J2[15]){
        strcpy(nom_J[2],nom_J[0]);
    }else{
        if(J1[15]<J2[15]){
            strcpy(nom_J[2],nom_J[1]);
        }else{
            strcpy(nom_J[2],"egalite");
        } 
    }

    printf("\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n");
    printf("________________________________________\t");       printf("\t\t_________________\n");
    printf("|Yam's\t|%s|%s|\t",nom_J[0],nom_J[1]);              printf("\t\t|Lancée de Dés\t|\n");
    printf("|1\t|%d\t\t|%d\t\t|\t", J1[0],J2[0]);               printf("\t\t|dé 1\t|%d\t|\n", de[0]);
    printf("|2\t|%d\t\t|%d\t\t|\t", J1[1],J2[1]);               printf("\t\t|dé 2\t|%d\t|\n", de[1]);
    printf("|3\t|%d\t\t|%d\t\t|\t", J1[2],J2[2]);               printf("\t\t|dé 3\t|%d\t|\n", de[2]);
    printf("|4\t|%d\t\t|%d\t\t|\t", J1[3],J2[3]);               printf("\t\t|dé 4\t|%d\t|\n", de[3]);
    printf("|5\t|%d\t\t|%d\t\t|\t", J1[4],J2[4]);               printf("\t\t|dé 5\t|%d\t|\n", de[4]);
    printf("|6\t|%d\t\t|%d\t\t|\t", J1[5],J2[5]);               printf("\t\t¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯\n");
    printf("|Bonus\t|%d\t\t|%d\t\t|\t",     J1[6],J2[6]);       printf("\t\t_________________________\n");
    printf("|Total 1|%d\t\t|%d\t\t|\t",     J1[7],J2[7]);       printf("\t\t|Menu Principal\t\t|\n");
    printf("|\t|\t\t|\t\t|\t");                                 printf("\t\t|1 %s|\n", opt1);
    printf("|Brelan\t|%d\t\t|%d\t\t|\t",    J1[8],J2[8]);       printf("\t\t|2 %s|\n", opt2);
    printf("|Carré\t|%d\t\t|%d\t\t|\t",     J1[9],J2[9]);       printf("\t\t|3 %s|\n", opt3);
    printf("|Full\t|%d\t\t|%d\t\t|\t",      J1[10],J2[10]);     printf("\t\t|4 %s|\n", opt4);
    printf("|P_suite|%d\t\t|%d\t\t|\t",     J1[11],J2[11]);     printf("\t\t|5 %s|\n", opt5);
    printf("|G_Suite|%d\t\t|%d\t\t|\t",     J1[12],J2[12]);     printf("\t\t¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯\n");
    printf("|Yam's\t|%d\t\t|%d\t\t|\t",     J1[13],J2[13]);     printf("\t\t_________________\n");
    printf("|Chance\t|%d\t\t|%d\t\t|\t",    J1[14],J2[14]);     printf("\t\t|Gagnant de la partie:\t|\n");
    printf("|\t|\t\t|\t\t|\t");                                 printf("\t\t|%s|\n",nom_J[2]);
    printf("|Total 2|%d\t\t|%d\t\t|\t",     J1[15],J2[15]);     printf("\t\t|\t\t|\n");
    printf("¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯\t");       printf("\t\t¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯\n\n");
        
        
    int choix;
    do{
        printf("entrer votre choix: ");
        scanf("%d",&choix);
        switch(choix){
            case 1 :    Menu_Jouer(de, J1, J2,"Commencer une Partie\t", "nom des joueurs\t", "Retour\t\t","\t\t\t","\t\t\t",nom_J);
                        break;
            case 2 :
                        printf("\n\nLe Yams se joue avec 5 dés.\n");
                        printf("\tLe Jeu se finit une fois toutes les cases de la feuille de marque remplie (il y a 13 combinaisons à réaliser)\n");
                        printf("\tLes joueurs jouent à tour de rôle et chaque joueur dispose de 3 lancers a chaque coup.\n\n");
                        
                        printf("A chaque lancer, le joueur a le choix de relancer tous les dés \n");
                        printf("\tou seulement une partie des dés, selon son gré, pour tenter d’obtenir la combinaison voulue\n\n");

                        printf("À chaque tour, le joueur doit obligatoirement inscrire son score dans une des cases de la feuille de marque\n");
                        printf("\tque ce soit par les points qu’il a obtenus ou par un 0.\n\n");

                        printf("Bien entendu, il ne pourra plus faire cette combinaison par la suite.\n");
                        printf("\tLe gagnant d’une partie de Yams est le joueur qui comptabilise le plus de points à la fin des 13 coups.\n\n");
                        break;
            case 3 :    printf("\n\nAu revoir\n\n");
                        break;
                        
            default:    printf("\n\nErreur de saisi\n\n");
                        Menu_Fin(de,J1,J2,"Rejouer\t\t", "Les Règles\t\t", "Quitter\t\t","\t\t\t","\t\t\t",nom_J);
                        break;
        }
    }while(choix !=3);
}

void Menu_Nom(t_Val_de de, t_pts_J J1, t_pts_J J2, t_options opt1, t_options opt2, t_options opt3, t_options opt4, t_options opt5, t_nom nom_J){
    affichage(de,J1,J2,opt1,opt2,opt3,opt4,opt5,nom_J);
    int choix;
    t_options nom;
    do{
        printf("entrer votre choix: ");
        scanf("%d",&choix);
        switch(choix){
                case 1 :    
                            do{ //choix du nom du J1
                                printf("\nentrer le nouveau nom (15 caractères max) : ");
                                scanf("%s",nom);
                            }while(taille(nom)>=CHARMAX);

                            if(taille(nom)<CHARMAX/2){
                                strcpy(nom_J[0],strcat(nom,"\t\t"));
                            }else{
                                if(taille(nom)<CHARMAX){
                                    strcpy(nom_J[0],strcat(nom,"\t"));
                                }else{
                                    strcpy(nom_J[0],nom);
                                }
                            }
                            
                            
                            Menu_Nom(de, J1, J2,nom_J[0],nom_J[1],"Retour\t\t","\t\t\t","\t\t\t",nom_J);
                            break;
                            
                case 2 :
                            do{ //choix du nom du J2
                                printf("\nentrer le nouveau nom (15 caractères max) : ");
                                scanf("%s",nom);
                            }while(taille(nom)>=CHARMAX);

                            if(taille(nom)<CHARMAX/2){
                                strcpy(nom_J[1],strcat(nom,"\t\t"));
                            }else{
                                if(taille(nom)<CHARMAX){
                                    strcpy(nom_J[1],strcat(nom,"\t"));
                                }else{
                                    strcpy(nom_J[1],nom);
                                }
                            }
                            Menu_Nom(de, J1, J2,nom_J[0],nom_J[1], "Retour\t\t","\t\t\t","\t\t\t",nom_J);
                            break;
                case 3 :
                            Menu_Jouer(de, J1, J2,"Commencer une Partie\t", "nom des joueurs\t", "Retour\t\t","\t\t\t","\t\t\t",nom_J);
                            break;
                            
                default:    printf("\n\nErreur de saisi\n\n");
                            Menu_Nom(de, J1, J2,nom_J[0],nom_J[1],"Retour\t\t","\t\t\t","\t\t\t",nom_J);
                            break;
            }
    }while(choix !=3);
    
    
    
}

void Menu_Cases(t_Val_de de, t_pts_J J1, t_pts_J J2, t_options opt1, t_options opt2, t_options opt3, t_options opt4, t_options opt5, t_nom nom_J){
    affichage(de,J1,J2,opt1,opt2,opt3,opt4,opt5,nom_J);

    int choix;
    do{
        printf("entrer votre choix: ");
        scanf("%d",&choix);
        switch(choix){
        case 1 :
                    Menu_Somme(de, J1, J2,"Somme\t\t","chance\t\t","Retour\t\t","\t\t\t","\t\t\t",nom_J);
                    break;
        case 2 :
                    Menu_Combinaisons_1(de, J1, J2,"Yam's\t\t","Carré\t\t","Full\t\t\t","autres Combinaisons\t","Retour\t\t",nom_J);
                    break;
        case 3 :
                    Menu_Partie(de, J1, J2,"Relancer les dés\t", "choisir une case\t", "Retour\t\t","\t\t\t","\t\t\t",nom_J);
                    break;
                    
        default:    printf("\n\nErreur de saisi\n\n");
                    Menu_Cases(de, J1, J2,"Somme de Nombres\t", "combinaison\t\t", "Retour\t\t","\t\t\t","\t\t\t",nom_J);
                    break;
    }
    }while(choix !=3);
    
    
}

void Menu_Combinaisons_1(t_Val_de de, t_pts_J J1, t_pts_J J2, t_options opt1, t_options opt2, t_options opt3, t_options opt4, t_options opt5, t_nom nom_J){
    affichage(de,J1,J2,opt1,opt2,opt3,opt4,opt5,nom_J);
    int choix;
    int cmb = combinaison_possible(de);
    do{
        printf("entrer votre choix: ");
        scanf("%d",&choix);
        switch(choix){
        case 1 :    //Yam's
                    if(cmb==25){ remplir_case(de, J1, J2, 13, YAMS, nom_J);
                    }else{ remplir_case(de, J1, J2, 13, 0, nom_J);}
                    break;
                    
        case 2 :    //Carré
                    if(cmb==25 || cmb==17){ 
                        remplir_case(de, J1, J2, 9, de[0]+de[1]+de[2]+de[3]+de[4], nom_J);
                    }else{ remplir_case(de, J1, J2, 9, 0, nom_J);}
                    break;
                    
        case 3 :    //Full
                    if(cmb==25 || cmb==13){ remplir_case(de, J1, J2, 10, FULL, nom_J);
                    }else{ remplir_case(de, J1, J2, 9, 0, nom_J);}
                    break;
                    
        case 4:     Menu_Combinaisons_2(de, J1, J2,"Brelan\t\t","Petite Suite\t\t","Grande Suite\t\t","autres Combinaisons\t","Retour\t\t",nom_J);
                    break;
        
        case 5:     Menu_Cases(de, J1, J2,"Somme de Nombres\t", "combinaison\t\t", "Retour\t\t","\t\t\t","\t\t\t",nom_J);
        
        default:    printf("\n\nErreur de saisi\n\n");
                    Menu_Combinaisons_1(de, J1, J2,"Yam's\t\t","Carré\t\t","Full\t\t\t","autres Combinaisons\t","Retour\t\t",nom_J);
                    break;
    }
    }while(choix !=3);
}

void Menu_Combinaisons_2(t_Val_de de, t_pts_J J1, t_pts_J J2, t_options opt1, t_options opt2, t_options opt3, t_options opt4, t_options opt5, t_nom nom_J){
    affichage(de,J1,J2,opt1,opt2,opt3,opt4,opt5,nom_J);
    int choix;
    int cmb = combinaison_possible(de);
    do{
        printf("entrer votre choix: ");
        scanf("%d",&choix);
        switch(choix){
                    case 1 :    //Brelan
                                if(cmb==25 || cmb==17 || cmb==13 || cmb==11){
                                    remplir_case(de, J1, J2, 8, de[0]+de[1]+de[2]+de[3]+de[4], nom_J);
                                }else{ remplir_case(de, J1, J2, 8, 0, nom_J);}
                                
                    case 2 :    //Petite Suite
                                if(cmb==7 || cmb==5){ remplir_case(de, J1, J2, 11, P_SUITE, nom_J);
                                }else{ remplir_case(de, J1, J2, 11, 0, nom_J);}
                                
                    case 3 :    //Grande Suite
                                if(cmb==5){ remplir_case(de, J1, J2, 12, G_SUITE, nom_J);
                                }else{ remplir_case(de, J1, J2, 12, 0, nom_J);}
                    
                    case 4:     Menu_Combinaisons_1(de, J1, J2,"Yam's\t\t","Carré\t\t","Full\t\t\t","autres Combinaisons\t","Retour\t\t",nom_J);
                                break;
                                
                    case 5:     Menu_Cases(de, J1, J2,"Somme de Nombres\t", "combinaison\t\t", "Retour\t\t","\t\t\t","\t\t\t",nom_J);
                                break;
                                
                    default:    printf("\n\nErreur de saisi\n\n");
                                Menu_Combinaisons_2(de, J1, J2,"Brelan\t\t","Petite Suite\t\t","Grande Suite\t\t","autres Combinaisons\t","Retour\t\t",nom_J);
                                break;
                }
    }while(choix !=3);
    
    
}

void Menu_Somme(t_Val_de de, t_pts_J J1, t_pts_J J2, t_options opt1, t_options opt2, t_options opt3, t_options opt4, t_options opt5, t_nom nom_J){
    affichage(de,J1,J2,opt1,opt2,opt3,opt4,opt5,nom_J);

    int choix, nombre;
    do{
        printf("entrer votre choix: ");
        scanf("%d",&choix);
        switch(choix){
                    case 1 :    //Somme de nombres
                                do{ 
                                    printf("\nentrer la somme correspondante (1,2,3,4,5,ou 6): ");
                                    scanf("%d",&nombre);
                                }while(nombre<=0 || nombre>=7);
                                remplir_case(de, J1, J2, nombre-1, trouve(de,nombre)*nombre, nom_J);
                                break;
                                
                    case 2 :    //Chance
                                remplir_case(de, J1, J2, 14, de[0]+de[1]+de[2]+de[3]+de[4], nom_J);
                                break;
                                
                    case 3 :    Menu_Cases(de, J1, J2,"Somme de Nombres\t", "combinaison\t\t", "Retour\t\t","\t\t\t","\t\t\t",nom_J);
                                break;
                                
                    default:    printf("\n\nErreur de saisi\n\n");
                                Menu_Somme(de, J1, J2,"Somme\t\t","Chance\t\t","retour\t\t","\t\t\t","\t\t\t",nom_J);
                                break;
                }
    }while(choix !=3);
    
}



int trouve(t_Val_de de, int val){
    int nbr = 0;
    for (int i = 0; i <5; i++){
        if(de[i]==val){nbr++;}
    }
    return nbr;}


int combinaison_possible(t_Val_de de){
    int liste_oc[] = {0,0,0,0,0};
    int somme_oc = 0; /* somme des valeurs de liste_oc*/
    int som = 0;      /* somme des de tous les dé */
    int autre; 
    
    for (int i = 0; i <5; i++){ /* remplissage de liste_oc */
        liste_oc[i]=trouve(de,de[i]);
        somme_oc = somme_oc + liste_oc[i];
    }
    switch(somme_oc){
        /*
        Les différentes possibilités de liste_oc :
        liste_oc    =   somme_oc    Meilleure combinaison correspondante
        5 5 5 5 5   =   25          yam's
        4 4 4 4 1   =   17          Carré
        3 3 3 2 2   =   13          Full
        3 3 3 1 1   =   11          brelan
        2 2 2 2 1   =   9           double paire
        2 2 1 1 1   =   7           P_suite
        1 1 1 1 1   =   5           G_suite
        */
        case 25 : /* Yam's*/
            return 25;
            break;

        case 17 : /* carré */
            return 17;
            break;

        case 13 : /* full */
            return 13;
            break;

        case 11 : /* brelan */
            return 11;
            break;

        case  7: 
                /*
                    On sait qu'il y a déjà 4 dés différents.
                    il existe alors une valeur x qui est égal a un autre dé.

                    Les cas de petite suites sont donc :
                    x 1 2 3 4 = 10 + x
                    x 2 3 4 5 = 14 + x
                    x 3 4 5 6 = 18 + x

                    Les sommes des dés correspondants à une petite suite sont : 10 + x , 14 + x et 18 + x.
                    Donc il suffit de calculer cette somme puis de retirer x, pour verifier la présence d'une petite suite
                */

                for (int i = 0; i<5; i++){
                    som = som + de[i];  /* calcule de la somme de tous les dés*/
                    if(liste_oc[i]==2){autre = de[i];} /* recherche de x*/
                }
                som = som - autre; /* on retire x de la somme de tous les dés */

                if (som == 10 || som == 14 || som == 18 ){
                    return 7; /* Petite suite*/
                }else{
                    return 0; /* Rien */
                }
        
        case 5 :  
                /* On sait que tous les dés sont différents.
                    les cas de Grande suite sont donc : 
                        1 2 3 4 5 = 15
                        2 3 4 5 6 = 20

                    Les sommes des dés correspondants à une Grande suite sont : 15 et 20.
                    Si la somme est différente il y a tout de meme une petite suite dans tous les cas
                    (car les 5 dés sont différents).
                */
            if ( (de[0]+de[1]+de[2]+de[3]+de[4]) == 15 || (de[0]+de[1]+de[2]+de[3]+de[4]) == 20){
                return 5; /*Grande suite*/
            }else{
                return 7; /* Petite suite*/
            }
        default: return 0;
    } 
}


void remplir_case(t_Val_de de, t_pts_J J1, t_pts_J J2, int num_combi, int val_combi, t_nom nom_J){
    if(J1[17]==J2[17]){                 /* Si c'est le tour du joueur 1 */
        if(J1[num_combi]==init){        /* Si la case n'a pas été modifié */
            J1[17]++;                   /* incrémente le total de tours du joueur 1 */
            J1[16]=init;                /* le nombre de lancer du joueur 1réinitialisé */
            strcpy(nom_J[2],nom_J[1]);  /* Passage au joueur 2 */

            J1[num_combi]=val_combi;    /* remplissage de la case correspondante */

            J1[7]=0;                    
            J1[15]=0;                   
            for (int i = 0; i < 15; i++){                       
                if(i>=0 && i<=5){           /* remplissage de total 1 */
                    if(J1[i]!=init){J1[7]=J1[7]+J1[i];}
                }else{
                    if(J1[i]!=init && i!=6){J1[15]=J1[15]+J1[i];} /* remplissage de total 2 */
                }
            }
            
            if (-(CDTBONUS-J1[7])>0){   /* Si le score_1 est supérieure ou égal a CDTBONUS */
                J1[6]=BONUS;            /* le bonus est appliqué*/
                J1[7]=J1[7]+BONUS;
                J1[15]=J1[15]+BONUS;      
            }else{
                J1[6]=-(CDTBONUS-J1[7]);
            }
            Menu_Partie(de, J1, J2,"Relancer les dés\t", "choisir une case\t", "Retour\t\t","\t\t\t","\t\t\t",nom_J);

        }else{
            printf("la case à déja été rempli\n"); /* Aucune modification n'est effectué*/
            Menu_Combinaisons_1(de, J1, J2,"Yam's\t\t","Carré\t\t","Full\t\t\t","autres Combinaisons\t","Retour\t\t",nom_J);
        }
    }else{ /* meme procédure si c'est au tour du joueur 2*/
        if(J2[num_combi]==init){
            J2[17]++;
            J2[16]=init;
            strcpy(nom_J[2],nom_J[0]);

            J2[num_combi]=val_combi;
            J2[15]=0;
            J2[7]=0;
            for (int i = 0; i < 15; i++){
                if(i>=0 && i<=5){
                    if(J2[i]!=init){J2[7]=J2[7]+J2[i];}
                }else{
                    if(J2[i]!=init && i!=6){J2[15]=J2[15]+J2[i];}
                }
            }

            if (-(CDTBONUS-J2[7])>0){
                J2[6]=BONUS;
                J2[7]=J2[7]+BONUS;
                J2[15]=J2[15]+BONUS; 
            }else{
                J2[6]=-(CDTBONUS-J2[7]);
            }

            Menu_Partie(de, J1, J2,"Relancer les dés\t", "choisir une case\t", "Retour\t\t","\t\t\t","\t\t\t",nom_J);

        }else{
            printf("la case à déja été rempli\n");
            Menu_Combinaisons_1(de, J1, J2,"Yam's\t\t","Carré\t\t","Full\t\t\t","autres Combinaisons\t","Retour\t\t",nom_J);
        }
    }
}

void Lancer(t_Val_de de){
    int i = 0;
    printf("Lancement des dés\n");
    while(i<=4){
        de[i]=Alea();
        sleep(); /* Met le programme en attente pendant une seconde */
        i++;}
}

void Relancer(t_Val_de de, bool *relance){
    * relance = false;
    int choix_de[5];
    /* l'utilisateur précise les dés qu'il souhaite garder ou relancer */
    do{
        printf("Vouler vous garder le premier de ? 0 = non / 1 = oui : ");
        scanf("%d",&choix_de[0]);
    }while(choix_de[0] != 0 && choix_de[0] != 1);
    
    do{
        printf("Vouler vous garder le deuxieme dé ? 0 = non / 1 = oui : ");
        scanf("%d",&choix_de[1]);
    }while(choix_de[1] != 0 && choix_de[1] != 1);
    
    do{
        printf("Vouler vous garder le troisième dé ? 0 = non / 1 = oui : ");
        scanf("%d",&choix_de[2]);
    }while(choix_de[2] != 0 && choix_de[2] != 1);
    
    do{
        printf("Vouler vous garder le quatrième de ? 0 = non / 1 = oui : ");
        scanf("%d",&choix_de[3]);
    }while(choix_de[3] != 0 && choix_de[3] != 1);
    
    do{
        printf("Vouler vous garder le cinquème de ? 0 = non / 1 = oui : ");
        scanf("%d",&choix_de[4]);
    }while(choix_de[4] != 0 && choix_de[4] != 1);
    
    /* les dés spécifier sont alors relancer, les autres ne changent donc pas */
    int i =0;
    printf("Lancement des dés\n");
    for(i=0;i<5;i++){
        if(choix_de[i] == 0){
            * relance = true;
            de[i]=Alea();
            sleep();
        }
        i++;
    }
    /* si l'utilisateur ne relance aucun dés */
    if(!(*relance)){printf("Aucun dé n'a été relancé\n");}
}

int taille(char nom[]){
    int taille = 0;
    while(nom[taille]!='\0'){
        printf("%c",nom[taille]);
        taille ++;}
    return taille;}

int Alea(){
    srand(time(NULL));
    return rand() % 6 +1;}

void sleep(){
	clock_t goal;
	goal = 1*(CLOCKS_PER_SEC) + clock();
	while(goal > clock()){;}}

void initialiser(t_Val_de de, t_pts_J J1, t_pts_J J2){
    // initialisation des scores des joueurs.
    int i = 0;
    while(i<=18){
        if (i==7 || i==15){
            J1[i]=0;
            J2[i]=0;
        }else{
            J1[i]=init;
            J2[i]=init;
        }
    i++; 
    }
    J1[6]=-(CDTBONUS-J1[7]);
    J2[6]=-(CDTBONUS-J2[7]);
    
    // initialisation des valeurs des dés
    i = 0;
    while(i<=4){
        de[i]=0;
       i++; 
    }
}

/**
 * \fn main()
 * 
 * \brief Programme principale
 * 
 * \return le code de sortie du programme
 */
int main(){
    /* Noms des joueurs par default */
    t_options nom_J1 = "joueur1\t";
    t_options nom_J2 = "joueur2\t";
    
    t_nom nom_J;
    strcpy(nom_J[0],nom_J1);
    strcpy(nom_J[1],nom_J2);
    strcpy(nom_J[2],nom_J1);

    t_Val_de de;
    t_pts_J J1;
    t_pts_J J2;
    initialiser(de,J1,J2);

    /* Début de la partie */
    Menu_Principale(de,J1,J2,"Jouer\t\t", "Les Règles\t\t", "Quitter\t\t","\t\t\t","\t\t\t",nom_J);
    return EXIT_SUCCESS;   
}

/*
il reste a faire : 

    - quelques bug d'affichage (\t)
    - ajouter des commentaires 
    - les petites suites  (fait)

    - condition de fin de partie (fait)
    - afficher la fin de partie (fait)

    - inititialiser les variables si début de partie (fait)

    - ne pas compter un relancer si aucun dés sont lancés dans relancer(); (fait)

    - les combinaisons possibles (fait)
    
    - les sommes de nombre (fait)

    - les conditions si bonus (fait)
    - ne pas pouvoir modifier une case deja remplie (fait)

*/











