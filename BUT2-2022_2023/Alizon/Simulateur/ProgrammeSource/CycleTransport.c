#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <time.h>

#include <sys/types.h>
#include <sys/ipc.h>
#include <sys/sem.h>

#include <signal.h>
#include <unistd.h>
#include <sys/sem.h>
#include <fcntl.h>
/* ############################## DECLARATION DE CONSTANTES ##############################*/

// Nombre de seconde dans une journée
#define NB_SEC_PAR_JOUR 86400
int paramAcceleration,S_livraisonTerminee,S_FichierlivraisonTerminee, PidSource, PID_Client;

/* ########## STRUCTURES ##########*/
typedef struct {
    int numero_commande;
    char nom[50];
    char prenom[50];
} Tlivraison;

/**
 * DESCRIPTION
 *      Structure de base d'une file avec comme élement une livraison
 **/
typedef struct elem {
    Tlivraison livr;
    struct elem* suivant;
} telement;

typedef struct {
    telement* queue;
    telement* tete;
    int taille;
} file;

/* ############################## DECLARATION DE FONCTIONS ##############################*/

/* ########## FONCTIONS SEMAPHORE ##########*/
/**
 * DESCRIPTION
 *      Creer et initialise un semaphore
 * 
 * PARAMETRES
 *      entier numCle : identifiant du semaphore
 *      entier val : valeur d'initialisation
 * 
 * RETURN
 *      l'identifiant du semaphore.
 **/
int creerSemaphore(int numCle, int val){
    key_t cle = ftok(".", numCle);
    int handler = semget(cle, 1, IPC_CREAT | 0640);
    semctl(handler, 0, SETVAL, val);
    return handler;
}

/**
 * DESCRIPTION
 *      consome ou libère un sémaphore
 * 
 * PARAMETRES
 *      entier handler : identifiant du semaphore
 *      entier val : -1 pour consommer ou +1 pour liberer
 * 
 * RETURN
 *      void.
 **/
void ConsommerLiberer(int handler, int val){
    struct sembuf sop;
    sop.sem_num = 0;
    sop.sem_flg = 0;
    sop.sem_op = val;
    semop(handler, &sop, 1);
}



/* ########## FONCTIONS TUBES ########## */
void lectureTube(char tube[50],char chaine[50]){
    int mon_tube = open(tube,O_RDONLY);
    char message[50];
    if (mon_tube == -1) { // Erreur
        perror("Erreur d'ouverture en lecture d'un tube");
    }
    read(mon_tube, &message, sizeof(message));
	int iterateur=0;
	while(message[iterateur] != '.' && message[iterateur] != '\0') { 
	    printf("%c.",message[iterateur]);
	    chaine[iterateur] = message[iterateur];
	    iterateur++;
	}
	
    close(mon_tube);
}

void ecritureTube(char tube[50],char message[50]){
    int mon_tube = open(tube,O_WRONLY);

    if (mon_tube == -1) { // Erreur
        perror("Erreur d'ouverture en ecriture d'un tube");
    }
    write(mon_tube, message, strlen(message));
    close(mon_tube);
}



/* ########## FONCTIONS LIVRAISON ##########*/
/**
 * DESCRIPTION
 *      Genere un entier aléatoire entre les bornes min et max (inclus)
 * 
 * PARAMETRES
 *      entier min : borne minimum inclusive 
 *      entier max : borne maximum inclusive 
 * 
 * RETURN
 *      un entier generer aleatoirement
 **/
int Alea(int min, int max){
    if(max == 0){
        return 0;
    }
    return (rand()%max)+min;
}

/**
 * DESCRIPTION
 *      calcule le nombre de seconde total de 'nbJours' accélérer par 'acceleration'
 * 
 * PARAMETRES
 *      entier nbJours : nombre de jours initiaux
 *      entier acceleration : vitesse d’accélération d'une journée
 * 
 * RETURN
 *      Renvoie le temps recalculé en secondes.
 **/
int CalculeTempsAccelere(int nbJours, int acceleration){
    return (nbJours*NB_SEC_PAR_JOUR)/acceleration;
}

/**
 * DESCRIPTION
 *      Affiche une etape et effectue une pause de x seconde selon 'nbJours' et le parametre d'acceleration
 * 
 * PARAMETRES
 *      Chaine etape : Description d'une étape
 *      entier nbJours : nombre de jours de l'étape
 * 
 * RETURN
 *      Void.
 **/
void EffectuerEtape(char etape[], int nbJours){
    if(nbJours <= 0){
        printf("%s. (Instantanée)\n",etape);
        sleep(1);
    }else{
        printf("%s. (Temps estimé %d jours)\n",etape,nbJours);
        sleep(CalculeTempsAccelere(nbJours,paramAcceleration));
    }
}










/* ############################## PROGRAMME PRINCIPALE ##############################*/
/**
 * DESCRIPTION
 *      Affiche une etape et effectue une pause de x seconde selon 'nbJours' et le parametre d'acceleration
 * 
 * PARAMETRES
 *      Chaine etape : Description d'une étape
 *      entier nbJours : nombre de jours de l'étape
 * 
 * RETURN
 *      Void.
 **/
int main(int argc, char const *argv[]){
    // Initialisation de la graine de génération de nombre aléatoire
    srand( time(NULL) ); 

    /* ############################## DECLARATION DE VARIABLES ##############################*/
    S_livraisonTerminee = 0;
    S_FichierlivraisonTerminee = 1;
    char numCommande[50], nom[50],prenom[50], chaine[50];
    int temps, pidSource;
    strcpy(numCommande,argv[1]);
    strcpy(nom,argv[2]);
    strcpy(prenom,argv[3]);
    pidSource = atoi(argv[4]);
    PID_Client = atoi(argv[5]);
    
    if(argv[6]==NULL){
        paramAcceleration = 100000;
    }else{
        paramAcceleration = atoi(argv[5]);
    }
    
    //On informe le debut de la livraison
    kill(pidSource, SIGUSR1);
    
    /* ########## Initialisation du temps de chaque étapes ##########*/

        // Initialisation du temps minimum et maximimum pour la prise en charge.
        int Temps_PriseEnCharge[2] = {0,0}; //{Temps min,Temps max}

        // Initialisation du temps minimum et maximimum pour le transport vers la plateforme régionale.
        int Temps_TransportPlateformeRegionale[2] = {1,3}; //{Temps min,Temps max}

        // Initialisation du temps minimum et maximimum pour le transport entre la plateforme et le site local de livraison
        int Temps_TransportSiteLocal[2] = {1,1}; //{Temps min,Temps max}

        // Initialisation du temps minimum et maximimum pour la livraison au destinataire
        int Temps_LivraisonDestinataire[2] = {0,0}; //{Temps min,Temps max}
        
        // Initialisation du temps minimum et maximimum pour l'accusé de réception de clôture de cycle 
        int Temps_AccuseReception[2] = {0,0}; //{Temps min,Temps max}

    /* ############################## ETAPES DE TRANSPORT ##############################*/
        temps = Alea(Temps_PriseEnCharge[0],Temps_PriseEnCharge[1]);
        printf("\n----------------------------------------\n");
    // -------------------- Prise en charge de la commande --------------------
        strcpy(chaine,"Prise en charge de la commande N°");
        strcat(chaine,numCommande);
        EffectuerEtape(chaine, temps);

    // -------------------- Transport vers la plateforme régionale --------------------
        temps = Alea(Temps_TransportPlateformeRegionale[0],Temps_TransportPlateformeRegionale[1]);
        EffectuerEtape("Transport vers la plateforme régionale", temps);


    // ---------- Transport entre la plateforme et le site local de livraison ----------
        temps = Alea(Temps_TransportSiteLocal[0],Temps_TransportSiteLocal[1]);
        EffectuerEtape("Transport entre la plateforme et le site local de livraison", temps);

    // -------------------- En cours de livraison chez le destinataire --------------------
        strcpy(chaine,"En cours de livraison chez ");
        strcat(chaine,nom);
        strcat(chaine," ");
        strcat(chaine,prenom);
        temps = Alea(Temps_LivraisonDestinataire[0],Temps_LivraisonDestinataire[1]);
        EffectuerEtape(chaine, temps);

        //On informe la fin de livraison
        ConsommerLiberer(S_livraisonTerminee, -1);
        kill(pidSource, SIGUSR2);

    // -------------------- Accusé de réception de clôture de cycle --------------------
        temps = Alea(Temps_AccuseReception[0],Temps_AccuseReception[1]);
        EffectuerEtape("Accusé de réception de clôture de cycle", temps);
        printf("----------------------------------------\n");


        ConsommerLiberer(S_FichierlivraisonTerminee,-1); //ne pas écrire pendant que le client lit ou efface le fichier texte
        int openFile = open("livraisonsTerminees.txt",O_CREAT | O_WRONLY | O_APPEND, S_IRWXU);
        char informationCommande[200];
        time_t now = time(NULL);
        struct tm tm_now = *localtime (&now);
        char s_now[sizeof "JJ/MM/AAAA HH:MM:SS"];
        strftime (s_now, sizeof s_now, "%d/%m/%Y %H:%M:%S", &tm_now);
        if (openFile == -1) { // Erreur
            perror("Erreur d'ouverture en ecriture du fichier des logs.");
        }
        strcpy(informationCommande, s_now);
        strcat(informationCommande, ";");
        strcpy(informationCommande, nom);
        strcat(informationCommande, " ");
        strcpy(informationCommande, prenom);
        strcat(informationCommande, ";");
        strcpy(informationCommande, numCommande);
        strcat(informationCommande, "\n");
        write(openFile, informationCommande, strlen(informationCommande));
        close(openFile);
        ConsommerLiberer(S_FichierlivraisonTerminee,1);

    return 0;
}
