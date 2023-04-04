#include <stdlib.h>
#include <stdio.h>
#include <time.h>
#include <unistd.h>
#include <string.h>
#include <sys/types.h>
#include <sys/stat.h>
#include <fcntl.h>
#include <errno.h> 
#include <stdbool.h>
#include <math.h>
#include <signal.h>
//#include <sys/socket.h>
//#include <arpa/inet.h>

typedef union uwb {
    unsigned w;
    unsigned char b[4];
} WBunion;

typedef unsigned Digest[4];

unsigned f0( unsigned abcd[] ){
    return ( abcd[1] & abcd[2]) | (~abcd[1] & abcd[3]);}

unsigned f1( unsigned abcd[] ){
    return ( abcd[3] & abcd[1]) | (~abcd[3] & abcd[2]);}

unsigned f2( unsigned abcd[] ){
    return  abcd[1] ^ abcd[2] ^ abcd[3];}

unsigned f3( unsigned abcd[] ){
    return abcd[2] ^ (abcd[1] |~ abcd[3]);}

typedef unsigned (*DgstFctn)(unsigned a[]);

unsigned *calcKs( unsigned *k)
{
    double s, pwr; 
    double a;
    double b;
    int i;

    pwr = pow( 2, 32);
    for (i=0; i<64; i++) {
        b = i+1; 
        a = sin(b);
        s = fabs(a);
        k[i] = (unsigned)( s * pwr );
    }
    return k;
}

// ROtate v Left by amt bits
unsigned rol( unsigned v, short amt )
{
    unsigned  msk1 = (1<<amt) -1;
    return ((v>>(32-amt)) & msk1) | ((v<<amt) & ~msk1);
}

unsigned *md5( const char *msg, int mlen) 
{
    static Digest h0 = { 0x67452301, 0xEFCDAB89, 0x98BADCFE, 0x10325476 };
//    static Digest h0 = { 0x01234567, 0x89ABCDEF, 0xFEDCBA98, 0x76543210 };
    static DgstFctn ff[] = { &f0, &f1, &f2, &f3 };
    static short M[] = { 1, 5, 3, 7 };
    static short O[] = { 0, 1, 5, 0 };
    static short rot0[] = { 7,12,17,22};
    static short rot1[] = { 5, 9,14,20};
    static short rot2[] = { 4,11,16,23};
    static short rot3[] = { 6,10,15,21};
    static short *rots[] = {rot0, rot1, rot2, rot3 };
    static unsigned kspace[64];
    static unsigned *k;

    static Digest h;
    Digest abcd;
    DgstFctn fctn;
    short m, o, g;
    unsigned f;
    short *rotn;
    union {
        unsigned w[16];
        char     b[64];
    }mm;
    int os = 0;
    int grp, grps, q, p;
    unsigned char *msg2;

    if (k==NULL) k= calcKs(kspace);

    for (q=0; q<4; q++) h[q] = h0[q];   // initialize

    {
        grps  = 1 + (mlen+8)/64;
        msg2 = malloc(64*grps);
        memcpy(msg2, msg, mlen);
        msg2[mlen] = (unsigned char)0x80;  
        q = mlen + 1;
        while (q < 64*grps){ msg2[q] = 0; q++ ; }
        {
//            unsigned char t;
            WBunion u;
            u.w = 8*mlen;
//            t = u.b[0]; u.b[0] = u.b[3]; u.b[3] = t;
//            t = u.b[1]; u.b[1] = u.b[2]; u.b[2] = t;
            q -= 8;
            memcpy(msg2+q, &u.w, 4 );
        }
    }

    for (grp=0; grp<grps; grp++)
    {
        memcpy( mm.b, msg2+os, 64);
        for(q=0;q<4;q++) abcd[q] = h[q];
        for (p = 0; p<4; p++) {
            fctn = ff[p];
            rotn = rots[p];
            m = M[p]; o= O[p];
            for (q=0; q<16; q++) {
                g = (m*q + o) % 16;
                f = abcd[1] + rol( abcd[0]+ fctn(abcd) + k[q+16*p] + mm.w[g], rotn[q%4]);

                abcd[0] = abcd[3];
                abcd[3] = abcd[2];
                abcd[2] = abcd[1];
                abcd[1] = f;
            }
        }
        for (p=0; p<4; p++)
            h[p] += abcd[p];
        os += 64;
    }

    if( msg2 )
        free( msg2 );

    return h;
}

void md5_String(char msg[50]){
    
    int j,k;
    int cpt = 0;
    char hexa[50][50];
    char chainedef[50];
    chainedef[0] = '0';
    
    unsigned  *d = md5(msg, strlen(msg));
    WBunion u;

    //printf("= 0x");
    for (j=0;j<4; j++){
        u.w = d[j];

        for (k=0;k<4;k++) {
            //printf("%02x",u.b[k]);
            sprintf(hexa[cpt],"%x",u.b[k]);
            cpt++;
        }
    }
    
    chainedef[1] = hexa[0][0];

    for (int cpt2 = 0; cpt2 < cpt; cpt2++){
        //printf("%s",hexa[cpt2]);
        if (cpt2 == 0){
            chainedef[2] = hexa[cpt2][1];
        }
        else{
            strcat(chainedef,hexa[cpt2]);
        }
    }
    strcpy(msg,chainedef);
    //return chainedef;
}

int main(int argc, char *argv[], char* envp[]){

    

    // int sock;
    // struct sockaddr_in addr;
    // printf("Bind en Cours...\n");
    // addr.sin_addr.s_addr = inet_addr("127.0.0.1");
    // // ou addr.sin_addr.s_addr = INADDR_ANY;
    // addr.sin_family = AF_INET;
    // addr.sin_port = htons(8080);
    // bind(sock, (struct sockaddr *)&addr, sizeof(addr));


    // system("telnet localhost 8080");

    int fic_mdp = open(argv[1], O_RDONLY,S_IRWXU);
    char buffer[500];

    char *token;
    int cp = 0;

    char tab_ID[20][100];
    char tab_mdp[20][100];

    bool verification = false;
    int cp_verif = 0;
    char identifiant_client[20];
    char identifiant_client2[20];
    char mdp_client[20];

    if(fic_mdp != -1)
    {

        printf("Entrez votre identifant et votre mot de passe pour vous connecter : ");
        printf("\nEntrez votre identifiant : ");
        scanf("%s", identifiant_client);
        printf("ID de l\'utilisateur : %s", identifiant_client);
        strcpy(identifiant_client2,identifiant_client);
        printf("\nEntrez votre mot de passe : ");
        scanf("%s", mdp_client);
        md5_String(mdp_client);

        read(fic_mdp, &buffer,sizeof(buffer));
        close(fic_mdp);

        token = strtok(buffer, ":");

        while(token != NULL){
                
            if (token[0] == '\n') {
                int len = strlen(token);
                memmove(token, token+1, len);
            }
            strcpy(tab_ID[cp], token);
            token = strtok(NULL, ":");

            if(token != NULL){
                strcpy(tab_mdp[cp], token);
                cp++;
            }
            token = strtok(NULL, ":");
        }
        
        // for (int i = 0; i < cp; i++)
        // {
        //     printf("%d : %s\n", i, tab_ID[i]);
        // }
        

        // for (int i = 0; i < cp; i++)
        // {
        //     printf("%d : %s\n", i, tab_mdp[i]);
        // }

       
        while (!verification && cp_verif < cp)
        {
            if(strcmp(identifiant_client, tab_ID[cp_verif]) == 0){

                if(strcmp(mdp_client, tab_mdp[cp_verif]) == 0)
                {
                    verification = true;
                }
                
            }
            cp_verif++;
        }

        if(verification){

            printf("Vous êtes connectées.\n");
            char commande[200];
            strcpy(commande,"gcc reception.c -o reception && ./reception ");
            strcat(commande,identifiant_client);
            
            system(commande);
            //kill(getpid(), SIGKILL);
        }else{

            printf("La connexion a échouée, veuillez relancer le programme.");
            kill(getpid(), SIGKILL);
        }

        
    }
    else
    {
        perror("Le fichier n'existe pas : ");
    }


    return EXIT_SUCCESS;
}