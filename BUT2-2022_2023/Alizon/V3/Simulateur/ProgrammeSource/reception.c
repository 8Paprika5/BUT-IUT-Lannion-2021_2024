#include <stdlib.h>
#include <stdio.h>
#include <sys/socket.h>
#include <arpa/inet.h>
#include <time.h>
#include <unistd.h>
#include <string.h>
#include <sys/types.h>
#include <sys/stat.h>
#include <fcntl.h>
#include <errno.h> 
#include <signal.h>
#include <sys/ipc.h>
#include <sys/sem.h>
#include <time.h>

#define aurevoir "BYE\r"
#define listeCli "alizon --commandeFini\r"
#define help "alizon --help\r"
#define archiveConst "alizon --archive\r"
#define ajoutCommande "alizon --ajout\r"

pid_t PID_priseEncharge;
int S_livraison;

/**
* DESCRIPTION
*      Permet avec le nom du fichier qu'on veut, connaitre le nombre de caractères dans le fichier.
*      Il parcours le fichier et incrémente un compteur que l'on retourne.
*
* PARAMETRES
*      char *nomfic : le nom du fichier ou l'on veut connaitre la taille.
*     
*
* RETURN
*      Retourne en entier le nombre de caractères du fichier.
**/

int tailleFic(char *nomfic){
    int compteur = 0;
    FILE * file;
    
    /* ouverture du fichier */
    if ((file = fopen(nomfic,"r")) == NULL){
           perror("Erreur à l'ouverture du fichier");
    }else {
        /* parcours du fichier */
        while(fgetc(file) != EOF){
            compteur ++; /* incrémentation du compteur */
        }     
    }
    /* fermeture du fichier */
    fclose(file);
    return compteur;
}
/**
* DESCRIPTION
*      Permet de réinitialiser le fichier avec les livraisons terminées. 
*      Il supprime le fichier et ensuite le créer avec O_CREAT tout en nous donnant les droites.
*
* PARAMETRES
*      int cnx : Variable qui nous permet d'écrire dans le terminal du telnet.
**/

void reloadFic(){
    /*Pour vider et réavoir le fichier de commande vierge*/
    
    remove("livraisonsTerminees.txt");
    open("livraisonsTerminees.txt",O_CREAT,S_IRWXU); 
        
}

/**
* DESCRIPTION
*      Permet d'archiver les commandes. Il ouvre les 2 fichiers livraisonsTerminees et archive. Et place tous le contenu de livraisonsTerminees dans archive.
*      Sans supprimer le contenu de archive.
*
* PARAMETRES
*      int cnx : Variable qui nous permet d'écrire dans le terminal du telnet.
**/


void archiverListe(int cnx){
    /*On archive la liste des livraisons fini dans un autre fichier*/
    int livTerminees = open("livraisonsTerminees.txt", O_RDONLY);
    int archive = open("archive.txt", O_WRONLY | O_APPEND);
    char buffer[1024];
    ssize_t bytes_read;
    
    lseek(archive, 0, SEEK_END);//Permet d'aller à la fin de la ligne
    write(archive, "\n\r", 2);

    while ((bytes_read = read(livTerminees, buffer, sizeof(buffer))) > 0) {
        write(archive, buffer, bytes_read);
    }
        
    close(livTerminees);
    close(archive);
    
    
}

/**
* DESCRIPTION
*      On demande à l'utilisateur s'il veut archiver son fichier. 
*      Si oui d'abord il archive livraisonsTerminees et ensuite il le réinitialise.
*      Sinon il ne fait rien.
*
* PARAMETRES
*      int cnx : Variable qui nous permet d'écrire dans le terminal du telnet.
**/


void demandeArchive(int cnx){
    char *message = "\nVoulez-vous archiver la liste des commandes finis ?\nOui/Non\n";
    char buffer[50];

    write(cnx, message, strlen(message));
    write(cnx, "\n\r>", 3);
    read(cnx, buffer, sizeof(buffer));

    if (strncmp(buffer,"Oui",3)==0){       
        archiverListe(cnx);//On archive les commandes passés
        reloadFic();//On restaure le fichier
        write(cnx, "\nLe fichier a été archivé\n",28);
    }else{
        write(cnx, "\nLe fichier n'a pas été archivé\n",34);
    }

}
/**
* DESCRIPTION
*      Affiche tous le contenu de livraisonsTerminees dans le terminal.
*      Si le contenu est vide ça ne fait rien.
*      Sinon ça demande aussi si l'utilisateur veut archiver.
*
* PARAMETRES
*      int cnx : Variable qui nous permet d'écrire dans le terminal du telnet.
**/

void afficherCommandeFini(int cnx){
    int fin;
    //char buffer[1024];
    int livraisonFini;
    int tailleFichier;

    livraisonFini = open("livraisonsTerminees.txt",O_RDONLY,S_IRWXU); 
    tailleFichier = tailleFic("livraisonsTerminees.txt");

    char donnees[tailleFichier-1];
    write(cnx, "\n\r", 2);//retour à la ligne sinon ça fait moche

    if (tailleFichier == 0){
        char *message = "Il n'y a pas de livraison fini.";
        write(cnx, message, strlen(message));
    }else{
        while((fin = read(livraisonFini,donnees,sizeof(donnees)))> 0){
            write(cnx, donnees, fin);
        }
        demandeArchive(cnx);
    }
    close(livraisonFini);
    
}

/**
* DESCRIPTION
*      Affiche l'archive.
*      Si le contenu est vide il n'affiche rien à part un message dans le terminal.
*      
*
* PARAMETRES
*      int cnx : Variable qui nous permet d'écrire dans le terminal du telnet.
**/

void afficherArchive(int cnx){
    int fin;
    int archive;
    int tailleFichier;

    archive = open("archive.txt",O_RDONLY,S_IRWXU); 
    tailleFichier = tailleFic("archive.txt");
    char donnees[tailleFichier-1];
    write(cnx, "\n\r", 2);//retour à la ligne sinon ça fait moche
    while((fin = read(archive,donnees,sizeof(donnees)))> 0){
        write(cnx, donnees, fin);
    }
    close(archive);
}


/* ########## FONCTIONS TUBE ########## */
/**
* DESCRIPTION
*      Permet de lire des données dans un tube passé en paramètre
*
* PARAMETRES
*      char tube[50] : Nom du tube
*      char chaine[50] : Contient le message après lecture du tube
**/
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

/**
* DESCRIPTION
*      Permet d'écrire des données dans un tube passé en paramètre
*
* PARAMETRES
*      char tube[50] : Nom du tube
*      char chaine[50] : message à écrire dans le tube
**/
void ecritureTube(char tube[50],char message[50]){
    int mon_tube = open(tube,O_CREAT | O_WRONLY);

    if (mon_tube == -1) { // Erreur
        perror("Erreur d'ouverture en ecriture d'un tube");
    }
    write(mon_tube, message, strlen(message));
    close(mon_tube);
}

/**
* DESCRIPTION
*      Permet de lancer le programme prise en charge
*
* PARAMETRES
*      char capacite_max : capacité maximum du transporteur 
**/
void PriseEnCharge(int capacite_max){
    pid_t pid = fork();
    if (pid == 0) { // Fils
        char commande[300];
        char temp[6]; 
        strcpy(commande, "gcc PriseEnCharge.c -o PriseEnCharge && ./PriseEnCharge ");
        
        sprintf(temp, "%d", capacite_max);
        strcat(commande,temp);
        
        strcat(commande," ");
        
        sprintf(temp, "%d", getppid());
        strcat(commande,temp);

        system(commande);
        kill(getpid(),SIGKILL);
    }
}

/**
* DESCRIPTION
*      Permet de lancer une nouvelle commande. 
*      Les informations sont demandées puis envoyé au transporteur via un tube.
*      Un signal est envoyé au transporteur pour qu'il se mette en écoute sur le tube.
*
* PARAMETRES
*      Aucun
**/
void NouvelleCommande(){
    char nom[50], prenom[50],paramAcceleration[50];
    printf("Veuillez s'il vous plait, ajouter pour chaque information rentrée un point : \".\"");
    printf("Nom du destinataire : ");
    scanf("%s", nom);
    printf("Prenom du destinataire : ");
    scanf("%s", prenom);
    printf("Veuillez entrez le paramètre d'accélération : ");
    scanf("%s", paramAcceleration);
    ConsommerLiberer(S_livraison,-1);
    kill(PID_priseEncharge,SIGALRM);
    ecritureTube("AjoutCommande",nom);
    ecritureTube("AjoutCommande",prenom);
    ecritureTube("AjoutCommande",paramAcceleration);
    sleep(1000); // attente de la confirmation (signal SIGUSR1)
}

/**
* DESCRIPTION
*      Permet d'ajouter un log dans le fichier "logs.txt"
*
* PARAMETRES
*      char id_client[50] : identifiant du client
*      char commande[50] : commande effectué par le client
**/
void ajoutLogs(char id_client[50], char commande[50]){

    int fic_logs = open("logs.txt",O_CREAT | O_WRONLY | O_APPEND, S_IRWXU);
    char ligne_fic[200];
    time_t now = time(NULL);
    struct tm tm_now = *localtime (&now);
    char s_now[sizeof "JJ/MM/AAAA HH:MM:SS"];
    strftime (s_now, sizeof s_now, "%d/%m/%Y %H:%M:%S", &tm_now);

    if (fic_logs == -1) { // Erreur
        perror("Erreur d'ouverture en ecriture du fichier des logs.");
    }

    strcpy(ligne_fic, s_now);
    strcat(ligne_fic, " : ");
    strcat(ligne_fic, id_client);
    strcat(ligne_fic, " : ");
    strcat(ligne_fic, " commande utilisée : ");

    if (strcmp(archiveConst, commande) == 0){

        strcat(ligne_fic, " alizon --archive : archive toutes les commandes achévées ");
    }
    else if(strcmp(help, commande) == 0){

        strcat(ligne_fic, " alizon --help : affiche toutes les commandes disponibles du simulateur");
    }
    else if(strcmp(listeCli, commande) == 0){

        strcat(ligne_fic, " alizon --commandeFini : affiche toutes les commandes achéveés");
    }
    else if(strcmp(ajoutCommande, commande) == 0){

        strcat(ligne_fic, " alizon --ajout : ajoute une nouvelle commande au simulateur");
    }
    strcat(ligne_fic, "\n");

    write(fic_logs, ligne_fic, strlen(ligne_fic));

    close(fic_logs);


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


/* ########## FONCTIONS SIGNAUX ########## */
void attrape_sig(int sig, siginfo_t *siginfo) {
    if (sig == SIGUSR1){
        // la commande est envoyé !
        char nom[50], numCommande[50];
        //lectureTube("AjoutCommande",nom);
        //lectureTube("AjoutCommande",numCommande);
        printf("Votre commande, à bien été prise en compte !");
    }

    if (sig == SIGUSR2){
        PID_priseEncharge = siginfo->si_pid;
    }
}


int main(int argc, char *argv[], char *envp[]){
    char identifiant_client[50];
    strcpy(identifiant_client,argv[1]);
    int sock;
    struct sockaddr_in addr;
    // SEMAPHORE
    S_livraison = creerSemaphore(0,1);
    int S_FichierlivraisonTerminee = creerSemaphore(1,1);
    // SIGNAUX
    struct sigaction act; 
    memset (&act, '\0', sizeof(act));
    act.sa_sigaction = attrape_sig;
    act.sa_flags = SA_SIGINFO;
    sigaction(SIGUSR1, &act, NULL);
    sigaction(SIGUSR2, &act, NULL);

    //LANCEMENT DU TRANSPORTEUR
    PriseEnCharge(100);
    sleep(100);

    
    // SOCKET
    printf("Sock en Cours...\n");
    sock = socket(AF_INET, SOCK_STREAM, 0);
    sleep(1);

    // BIND
    printf("Bind en Cours...\n");
    addr.sin_addr.s_addr = inet_addr("127.0.0.1");
    // ou addr.sin_addr.s_addr = INADDR_ANY;
    addr.sin_family = AF_INET;
    addr.sin_port = htons(8080);
    bind(sock, (struct sockaddr *)&addr, sizeof(addr));
    sleep(1);

    // LISTEN
    printf("Listen en Cours...\n");
    listen(sock, 1);
    sleep(1);

    // ACCEPT
    printf("Accept\n");
    int size;
    int cnx;
    struct sockaddr_in conn_addr;
    size = sizeof(conn_addr);
    cnx = accept(sock, (struct sockaddr *)&conn_addr, (socklen_t *)&size);

    char *message = "\nBienvenue dans le simulateur de livraison Alizon !\nVous pouvez consulter vos commandes passées, ainsi que les commandes en cours. \nPour plus d'informations, tapez la commande alizon --help\n";
    write(cnx, message, strlen(message));

    char buffer[50];
    read(cnx, buffer, sizeof(buffer));

    while (strncmp(buffer,aurevoir,4)!=0){
        if(strncmp(buffer,help,14)==0){
            message = "\nVoici la totalité des commandes réalisables sur Alizon :\nalizon --ajout : permet d'ajouter une commande\nalizon --commandeFini : permet d'afficher toutes les commandes qui viennent d'être livrés.\nalizon --archive : permet dafficher toutes les commandes passées\nBYE : pour arrêter le simulateur\n\r";
            write(cnx, message, strlen(message));
            ajoutLogs(identifiant_client, help);

        }
        else if (strncmp(buffer,listeCli,22)==0){       
            ConsommerLiberer(S_FichierlivraisonTerminee,-1); //ne pas lire ou effacer pendant qu'une livraison écrive dedans
            afficherCommandeFini(cnx);
            ConsommerLiberer(S_FichierlivraisonTerminee,1);
            ajoutLogs(identifiant_client, listeCli);
        }
        else if (strncmp(buffer,archiveConst,17)==0){       
            afficherArchive(cnx);
            ajoutLogs(identifiant_client, archiveConst);
        }
        else if(strncmp(buffer, ajoutCommande, strlen(ajoutCommande)) == 0){
            NouvelleCommande();
            ajoutLogs(identifiant_client, ajoutCommande);
        }else{
            message = "\nJe n'ai pas compris votre demande, si vous vous avez besoins d'aide : alizon --help\n\r";
            write(cnx, message, strlen(message));
        }
        write(cnx, "\n\r>", 3);
        read(cnx, buffer, sizeof(buffer));
    }
    write(cnx, "Au revoir\r", 10);

    return EXIT_SUCCESS;
}