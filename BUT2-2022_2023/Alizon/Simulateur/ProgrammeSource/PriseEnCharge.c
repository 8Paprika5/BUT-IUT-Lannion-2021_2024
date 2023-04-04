#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <stdbool.h>
#include <sys/sem.h> 
#include <unistd.h>
#include <sys/types.h>
#include <sys/stat.h>
#include <fcntl.h>
#include <string.h>
#include <signal.h>

/*################################ DECLARATION CONSTANTES ######################################*/
#define PARAMACCELERATION 100000
#define TEMPS_VEILLE 60*15 //15 minutes
#define CAPACITEMAX 100

/*################################ DECLARATION STRUCTURE ######################################*/

/* ########## STRUCTURES LIVRAISONS ########## */
/**
 * DESCRIPTION
 *      Structure de base d'une livraison
 * 
 * - Entier Numero de commande
 * - Chaine de caractère Nom du client
 * - Chaine de caractère Prénom du client
 *
 **/
typedef struct {
    int numero_commande;
    char nom[50];
    char prenom[50];
} T_livraison;

/**
 * DESCRIPTION
 *      Structure de base d'un élément d'une file
 * 
 * - T_livraison livr : une livraison stockée dans cette file
 * - struct elem* suivant : Element qui suit l'élement présent pour représenter la file
 *
 **/
typedef struct elem_Livraison {
    T_livraison livr;
    struct elem_Livraison* suivant;
} telement_Livraison;

/**
 * DESCRIPTION
 *      Structure de base d'une file
 * 
 * - telement* queue : la queue de la file contenant 0, 1 ou plusieurs livraison(s)
 * - telement* queue : la queue de la file contenant 0 ou 1 livraison
 *
 **/
typedef struct {
    telement_Livraison* queue;
    telement_Livraison* tete;
    int taille;
} fileLivraison;

/* ########## STRUCTURES PID & LIVRAISON ########## */
typedef struct {
    int numero_commande;
    pid_t pidTransporteur;
} T_PidLivraison;

typedef struct elem_PidLivraison {
    T_PidLivraison pidLivraison;
    struct elem_PidLivraison* suivant;
} telement_PidLivraison;

typedef struct {
    telement_PidLivraison* queue;
    telement_PidLivraison* tete;
    int taille;
} filePidLivraison;

/*################################ DECLARATION VARIABLES GLOBALES ######################################*/
int S_livraison;
int capacite_max, numCommandeTemporaire, PID_Client;
fileLivraison livraisons_en_cours,livraisons_terminees;
filePidLivraison livraisonsPID_en_cours;

/*########################### DECLARATION FONCTIONS / PROCEDURES ###############################*/
/**
 * DESCRIPTION
 *      Initialise une file avec une queue et une tête à NULL
 * 
 * PARAMETRES
 *      file *f : file à initialiser
 * 
 * RETURN
 *      Un entier
 **/
void init_fileLivraison(fileLivraison *f) {
    f->queue=NULL;
    f->tete=NULL;
    f->taille = 0;
}

void init_filePidLivraison(filePidLivraison *f) {
    f->queue=NULL;
    f->tete=NULL;
    f->taille = 0;
}


/**
 * DESCRIPTION
 *      Affiche une file version simplifiée. Cela parcours toute la file en affichant au fur et à mesure
 * les élements dans la file.
 * 
 * PARAMETRES
 *      file f : file à afficher
 *
 **/
void afficherfilePidLivraison(filePidLivraison F) {
    if(F.queue!=NULL){
        while(F.queue!=NULL) {
        printf("\nPID %d | Commande %d", F.queue->pidLivraison.pidTransporteur, F.queue->pidLivraison.numero_commande);
        F.queue = F.queue->suivant;
        }
    }else{
        printf("\nFILE PIDLivraison vide !");
    }

}

void afficherfileLivraison(fileLivraison F) {
    if(F.queue!=NULL){
        while(F.queue!=NULL) {
            printf("\nNom %s %s | Commande %d", F.queue->livr.nom, F.queue->livr.prenom,F.queue->livr.numero_commande);
            F.queue = F.queue->suivant;
        }
    }else{
        printf("\nFILE Livraison vide !");
    }
}


/**
 * DESCRIPTION
 *      Insère une livraison dans une file. Cela vérifie d'abord si la file est pleine, si elle l'est, 
 * on ne pourra pas l'insérer. Sinon, cela parcourt toute la file pour l'ajouter dans la queue.
 * 
 * PARAMETRES
 *      file *F : file à modifier
 *      livraison l : livraison à insérer dans la file
 *
 **/
void enfilerLivraison(fileLivraison *F, T_livraison l) {
    telement_Livraison *p = (telement_Livraison *)malloc(sizeof(telement_Livraison));
    p->livr=l;
    p->suivant=NULL;

    if(F->taille>=capacite_max) {
        printf("File pleine !\n");
    }
    else {
        if ((F->queue==NULL) && (F->tete==NULL)){
            F->tete=p;
            F->queue=p;
        } else {
            printf("\nTAILLE%d  NUM%d\n",F->taille,l.numero_commande);
                p->suivant = F->queue;
                F->queue=p;
            
        }
        F->taille++;
        
    }
    printf("Commande %d insérée...\n", F->queue->livr.numero_commande);
}

/**
 * DESCRIPTION
 *      Insère une livraison dans une file. Cela vérifie d'abord si la file est pleine, si elle l'est, 
 * on ne pourra pas l'insérer. Sinon, cela parcourt toute la file pour l'ajouter dans la queue.
 * 
 * PARAMETRES
 *      file *F : file à modifier
 *      livraison l : livraison à insérer dans la file
 *
 **/
void enfilerPidNumCommande(filePidLivraison *F, T_PidLivraison l) {
    telement_PidLivraison *p = (telement_PidLivraison *)malloc(sizeof(telement_PidLivraison));
    p->pidLivraison=l;
    p->suivant=NULL;
    
    if(F->taille>=capacite_max) {
        printf("File pleine !\n");
    }
    else {
        if ((F->queue==NULL) && (F->tete==NULL)){
            F->tete=p;
            F->queue=p;
        } else {
                p->suivant = F->queue;
                F->queue=p;
        }
        F->taille++;
    }
}


int recupererNumCommande(filePidLivraison F, pid_t pid) {
    int i = 1;
    bool trouve = false;
    while(F.queue!=NULL && !trouve) {
        if((F.queue->pidLivraison.pidTransporteur) == pid){
            trouve = true;
        }else{
            F.queue = F.queue->suivant;
            i++;
        }
    }
    if(trouve){
        return F.queue->pidLivraison.numero_commande;
    }else{
        return -1;
    }
}

int AjoutPID(filePidLivraison *F, pid_t pid, int numCommande) {
    int i = 1;
    bool trouve = false;
    while(F->queue!=NULL && !trouve) {
        if((F->queue->pidLivraison.numero_commande) == numCommande){
            trouve = true;
            F->queue->pidLivraison.pidTransporteur = pid;
        }else{
            F->queue = F->queue->suivant;
            i++;
        }
    }
    if(trouve){
        return F->queue->pidLivraison.numero_commande;
    }else{
        return -1;
    }
}


/**
 * DESCRIPTION
 *      Enlève une livraison dans la file grâce à son numéro de commande. Cela vérifie d'abord si le 
 * numéro de commande de la première livraison de la file est identique. Sinon cela parcourt la file
 * jusqu'à ce qu'il trouve le même numéro.
 * 
 * PARAMETRES
 *      file f : file à étudier
 *      entier num = numéro de commande à enlever
 *
 **/
void defilerLivraison(fileLivraison *F, int num) {
    telement_Livraison *p;
    
    printf("\n---AFFICHAGE DEFILER AVANT---");
    afficherfileLivraison(*F);
    printf("\n------\n");

    p = F->queue;
    if(F->queue->livr.numero_commande == num) {
        p = F->queue;
        F->queue = F->queue->suivant;
        free(p);
    } else {
        bool trouve = false;
        while(p->suivant!=NULL && !trouve) {
            if(p->suivant->livr.numero_commande == num){
                trouve = true;
                p->suivant = p->suivant->suivant;
            }else{
                p = F->queue->suivant;
            }
            
        }
        
    }
    printf("\n---AFFICHAGE DEFILER APRES---");
    afficherfileLivraison(*F);
    printf("\n------\n");
}

void defilerPidNumCommande(filePidLivraison *F, pid_t pid) {
    telement_PidLivraison *p;
    
    printf("\n---AFFICHAGE DEFILER AVANT---");
    afficherfilePidLivraison(*F);
    printf("\n------\n");

    p = F->queue;
    if(F->queue->pidLivraison.pidTransporteur == pid) {
        p = F->queue;
        F->queue = F->queue->suivant;
        free(p);
    } else {
        bool trouve = false;
        while(p->suivant!=NULL && !trouve) {
            if(p->suivant->pidLivraison.pidTransporteur == pid){
                trouve = true;
                p->suivant = p->suivant->suivant;
            }else{
                p = F->queue->suivant;
            }
            
        }
    }
}



/* ########## FONCTIONS SEMAPHORE ########## */
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
int creerSemaphore(int numCle, int val) {
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
void ConsommerLiberer(int handler, int val) {
    struct sembuf sop;
    sop.sem_num = 0;
    sop.sem_flg = 0;
    sop.sem_op = val;
    semop(handler, &sop, 1);
}



/* ########## FONCTIONS TUBES ########## */
void lectureTube(char tube[50],char chaine[50]){
    int mon_tube = open(tube,O_CREAT | O_RDONLY);
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
    int mon_tube = open(tube,O_CREAT | O_WRONLY);

    if (mon_tube == -1) { // Erreur
        perror("Erreur d'ouverture en ecriture d'un tube");
    }
    write(mon_tube, message, strlen(message));
    close(mon_tube);
}


/* ########## FONCTIONS LIVRAISONS ########## */
void ExecuterLivraison(T_livraison livraison, int paramAcceleration){
    pid_t pid = fork();
    if (pid == 0) { // Fils
        char commande[300];
        char temp[6]; 
        strcpy(commande, "gcc CycleTransport.c -o CycleTransport -Wall && ./CycleTransport " );
        
        sprintf(temp, "%d", livraison.numero_commande);
        strcat(commande,temp);
        strcat(commande," ");
        
        strcat(commande,livraison.nom);
        strcat(commande," ");
        
        strcat(commande,livraison.prenom);
        strcat(commande," ");
        
        int ppid = getppid();
        
        sprintf(temp, "%d", ppid);
        strcat(commande,temp);

        sprintf(temp, "%d", PID_Client);
        strcat(commande,temp);
        strcat(commande," ");

        if(paramAcceleration != 0){
            strcat(commande," ");
            sprintf(temp, "%d", paramAcceleration);
            strcat(commande,temp);
        }
        system(commande);
        kill(getpid(),SIGKILL);
    }else{
        // Ajouter la livraison dans la file
        
        enfilerLivraison(&livraisons_en_cours, livraison);
        
        T_PidLivraison l;
        l.numero_commande = livraison.numero_commande;
        numCommandeTemporaire = livraison.numero_commande;
        l.pidTransporteur = 0;
        enfilerPidNumCommande(&livraisonsPID_en_cours, l);
        printf("\nCOMMANDE N°%d PRETE\n",livraison.numero_commande);
        wait();
    }
}

void ajouterLivraison(int numero_commande, char nom[50], char prenom[50], int paramAcceleration){
    printf("\nCOMMANDE N°%d AJOUTE\n",numero_commande);
    T_livraison l1;
    l1.numero_commande = numero_commande;
    strcpy(l1.nom, nom);
    strcpy(l1.prenom, prenom);
    ExecuterLivraison(l1,paramAcceleration);
    wait();
}



/* ########## FONCTIONS SIGNAUX ########## */
void attrape_sig(int sig, siginfo_t *siginfo) {
    if(sig == SIGUSR1){// LIVRAISON CONFIRME
        printf("\nCOMMANDE N°%d ENVOYE\n",numCommandeTemporaire);
        AjoutPID(&livraisonsPID_en_cours,siginfo->si_pid,numCommandeTemporaire);
        afficherfileLivraison(livraisons_en_cours);
        afficherfilePidLivraison(livraisonsPID_en_cours);
        ConsommerLiberer(S_livraison, 1);
    }
    
    if(sig == SIGUSR2){// LIVRAISON TERMINE
        printf("COMMANDE TERMINEE %d!",recupererNumCommande(livraisonsPID_en_cours, siginfo->si_pid));
        defilerLivraison(&livraisons_en_cours,recupererNumCommande(livraisonsPID_en_cours, siginfo->si_pid));
        defilerPidNumCommande(&livraisonsPID_en_cours,siginfo->si_pid);
        ConsommerLiberer(S_livraison, 1);
    }

    if (sig == SIGALRM){
        char nom[50],prenom[50], paramAcceleration[50];
        lectureTube("AjoutCommande",nom);
        lectureTube("AjoutCommande",prenom);
        lectureTube("AjoutCommande",paramAcceleration);
        printf("Param : %s\n",nom);
        printf("Param : %s\n",prenom);
        printf("Param : %s\n",paramAcceleration);
        //ajouterLivraison(1,nom,prenom,0);
        ajouterLivraison(1,nom,prenom,atoi(paramAcceleration));
    }
    
    if(sig == SIGINT){
    	afficherfileLivraison(livraisons_en_cours);
        afficherfilePidLivraison(livraisonsPID_en_cours);
        
        printf("\nARRET DU PROGRAMME\n");
        kill(getpid(),SIGKILL);
    }

}



int main(int argc, char *argv[], char *envp[]) {
    // #################################### DECLARATION DE VARIABLES ####################################
    printf("\nPID PRISE EN CHARGE : %d\n",getpid());
    S_livraison = creerSemaphore(0, 1);
    
    capacite_max = atoi(argv[1]);
    PID_Client = atoi(argv[2]);
    kill(PID_Client,SIGUSR2);
    init_fileLivraison(&livraisons_en_cours);
    init_fileLivraison(&livraisons_terminees);
    init_filePidLivraison(&livraisonsPID_en_cours);
    

    // ################ RECUPERATION DE SIGNAUX ################
    struct sigaction act; 
    memset (&act, '\0', sizeof(act));
    act.sa_sigaction = attrape_sig;
    act.sa_flags = SA_SIGINFO;
    sigaction(SIGUSR1, &act, NULL);
    sigaction(SIGUSR2, &act, NULL);
    sigaction(SIGINT, &act, NULL);
    sigaction(SIGALRM, &act, NULL);


    
    
    wait();
    wait();
    sleep(TEMPS_VEILLE);
    printf("\nARRET DU PROGRAMME APRES 15MN D'INNACTIVITE\n");

    return 0;
}

