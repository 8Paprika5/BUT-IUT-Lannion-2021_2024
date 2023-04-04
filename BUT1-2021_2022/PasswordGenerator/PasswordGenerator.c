#include <stdio.h>
#include <stdlib.h>
#include <time.h>
#include <string.h>
#include <unistd.h>

#define NB_LETTRES 26
#define NB_NOMBRE 10
#define NB_TYPE 3

#define CHAR_MAX 50

typedef char alphabet[NB_LETTRES];

const alphabet minuscules = {'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'};

const alphabet majuscules = {'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'};

const char nombres[NB_NOMBRE] = {'0','1','2','3','4','5','6','7','8','9'};


char completePassword();

int Alea(int max);

void generatePassword(int max, char password[]);

void writeFile(char website[], char password[], int max);

int Alea(int max){
    return rand() % max;
}

int typeNumber(){
    int num;
    scanf("%d",&num);
    return num;
}

void generatePassword(int max, char password[]){
    //char password[max];

    for(int i = 0; i <max; i++){
        password[i] = completePassword();
    }
    password[max] = '\0';
    
}

char completePassword(){
    int type = Alea(NB_TYPE);

    if (type == 0){
        return minuscules[Alea(NB_LETTRES)];

    }else{
        if (type == 1){
            return majuscules[Alea(NB_LETTRES)];

        }else{
            if(type == 2){
                return nombres[Alea(NB_NOMBRE)];

            }else{
                printf("\nError!\n");
            }
        }
    }
}

void writeFile(char website[], char password[], int max){
    FILE * filename;
    filename = fopen("MyPassword.txt","ab");
    fwrite(website, 1 ,strlen(website) , filename);
    fwrite(" : ", 1 ,3 , filename);
    fwrite(password , 1 ,strlen(password) , filename);
    fwrite("\n" , 1 ,1 , filename);
}

int main(){
    
    srand(time(NULL));

    //Initialisation
    int max ;
    char password[CHAR_MAX];
    char choice[CHAR_MAX];
    char website[CHAR_MAX];
    
    printf("\nName of website (50 charactere max): ");
    scanf("%s",website);

    printf("\nSize of password generate (40 max): ");
    scanf("%d",&max);

    generatePassword(max,password);
    printf("\npassword : %s",password);
    
    printf("\nDo you want change the password (yes / no / stop) ? ");
    scanf("%s",choice);

    if(strcmp("stop",choice)==0){
        printf("\n\nNo passwords have been saved !\n\n");
        sleep(2);
        return EXIT_SUCCESS;
    }

    while(strcmp("yes",choice)==0){
        printf("\nSize of password generate (40 max): ");
        scanf("%d",&max);

        generatePassword(max,password);
        printf("\n%s",password);
        
        printf("\nDo you want change the password (yes / no / stop) ? ");
        scanf("%s",choice);

        if(strcmp("stop",choice)==0){
            printf("\n\nNo passwords have been saved !\n\n");
            sleep(2);
            return EXIT_SUCCESS;
        }
    }

    writeFile(website,password,max);
    printf("\n\nYour password is saved !\n\n");
    sleep(2);
    return EXIT_SUCCESS;

}