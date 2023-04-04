/*INDEX DU 01/12/2022

Derniere Modif : nb ligne = 610
Passage MySQL,
Commentaire;

Par:Mabileau Clément


#####################Table#####################
_Categorie:60
_Sous_Categorie:67
_Produit:78
_QuestionSecrete:98
_Client:106
_Panier:123
_Commande:135
_Contient_Produit_p:149
_Contient_Produit_c:163
_Avis:177
_Paiement:192
_Vendeur:207
_Signaler:216


####################Trigger####################
trig_Commande:252
trig_Pannier:274
trig_id_Client:295
trig_id_Panier:324
trig_TVA_Produit:348
trig_Moyenne_Note_Produit:366
trig_Moyenne_Note_Produit2:385
trig_Calcul_PrixTotal_Produit1:404
trig_Calcul_PrixTotal_Produit2:424
trig_Calcul_Total_Panier1:444
trig_Calcul_Total_Panier2:463
trig_Calcul_Total_Panier3:482
trig_Calcul_Signalement:500
trig_Commande_date:513
trig_id_Avis:539
#####################View######################
panier:530
catalogue:541
---------------------------------------------------------------------------------------------------------------
*/

/*################################################################################################
######################################## CREATION DE LA BASE #####################################
##################################################################################################*/
DROP DATABASE Alizon;
CREATE DATABASE Alizon;
USE Alizon;

 /* ################################################################################################
######################################## CREATION DE TABLES ########################################
###################################################################################################*/

/* TABLE CATEGORIE */
CREATE TABLE Alizon._Categorie (
  ID_Categorie int(11) auto_increment not null,
  Nom_Categorie varchar(50),
  constraint _Categorie_pk primary key (ID_Categorie)
);

/* TABLE SOUS CATEGORIE */
CREATE TABLE Alizon._Sous_Categorie (
  Id_Sous_Categorie int(11) auto_increment not null,
  Id_Categorie_Sup int(11) not null,
  nom varchar(50) not null,
  tva float not null,
  constraint Sous_Categorie_pk primary key (Id_Sous_Categorie),
  constraint _Sous_Categorie_Categorie foreign key (Id_Categorie_Sup)
    references Alizon._Categorie(ID_Categorie)
);

/* TABLE PRODUIT */
CREATE TABLE Alizon._Produit (
    ID_Produit INT(11) AUTO_INCREMENT NOT NULL,
    Nom_Produit VARCHAR(50),
    Prix_coutant DECIMAL(5 , 2 ),
    Prix_vente_HT NUMERIC(5 , 2 ),
    Prix_vente_TTC NUMERIC(5 , 2 ),
    Quantite_disponnible INT,
    Description_Produit VARCHAR(500),
    images1 VARCHAR(500),
    images2 VARCHAR(500),
    images3 VARCHAR(500),
    Moyenne_Note_Produit INT(11),
    Id_Sous_Categorie INT(11) NOT NULL,
    ID_Vendeur INT NOT NULL,
    CONSTRAINT _Produit_pk PRIMARY KEY (ID_Produit),
    CONSTRAINT _Produit_Sous_Categorie_fk FOREIGN KEY (Id_Sous_Categorie)
        REFERENCES Alizon._Sous_Categorie (Id_Sous_Categorie)
);

/* TABLE CLIENT */
CREATE TABLE Alizon._Client(
  ID_Client int(11) auto_increment not null,
  nom_Client varchar(50) not null,
  prenom_Client varchar(50) not null,
  date_de_naissance date not null,
  email varchar(50) not null,
  mdp varchar(500) not null, 
  ID_Adresse_facturation int(11), /* adresse pré-enregistré afin de la proposé en premier à la prochaine commande*/
  ID_Adresse_livraison int(11),   /* adresse pré-enregistré afin de la proposé en premier à la prochaine commande*/
  QuestionReponse varchar(500) not null,
  active integer DEFAULT 1,
  
  constraint _Client_pk primary key (ID_Client)
);

/* TABLE Adresse */
CREATE TABLE Alizon._Adresse (
  ID_Adresse int(11) auto_increment not null,
  ID_Client int(11) not null,
  nom_de_rue varchar(50) not null, 
  complement varchar(50), 
  ville varchar(50) not null,
  code_postale varchar(10) not null,

  constraint _Adresse_pk primary key (ID_Adresse),
  constraint _Adresse_Client_fk foreign key (ID_Client)
    references Alizon._Client(ID_Client)
);

/* CONSTRAINT */
ALTER TABLE Alizon._Client ADD CONSTRAINT _Adresse_Client_fk1 FOREIGN KEY (ID_Adresse_facturation)
    REFERENCES Alizon._Adresse(ID_Adresse);
  
ALTER TABLE Alizon._Client ADD CONSTRAINT _Adresse_Client_fk2 FOREIGN KEY (ID_Adresse_livraison)
    REFERENCES Alizon._Adresse(ID_Adresse);
/* TABLE PANIER */
CREATE TABLE Alizon._Panier (
  ID_Panier int(11) auto_increment not null,
  Prix_total_HT numeric(5,2),
  Prix_total_TTC numeric(5,2),
  ID_Client int(11),
  derniere_modif date,
  constraint _Panier_pk primary key (ID_Panier),
  constraint _Panier_Client_fk foreign key (ID_Client)
    references Alizon._Client(ID_Client)
);

/* TABLE COMMANDE */
CREATE TABLE Alizon._Commande (
  ID_Commande int(11) auto_increment not null,
  ID_Client int(11) not null,
  etat_Commande varchar(50),
  adresse_livraison varchar(50),
  date_Commande date ,
  date_livraison date,
  Duree_maximale_restante int,
  prix_total numeric(5,2),
  constraint _Commande_pk primary key (ID_Commande),
  constraint _Commande_Client_fk foreign key (ID_Client)
    references Alizon._Client (ID_Client)
);

/* TABLE CONTIENT PRODUIT PANIER */
CREATE TABLE Alizon._Contient_Produit_p (
  ID_Panier int(11) not null, -- Différent
  ID_Produit int(11) not null,
  Quantite int(11),
  Prix_Produit_Commande_HT float not null,
  Prix_Produit_Commande_TTC float not null,
  constraint _Contient_Produit_p_pk primary key (ID_Panier,ID_Produit),
  constraint _Contient_Produit_p_Panier_fk foreign key (ID_Panier)
    references Alizon._Panier(ID_Panier) ON DELETE CASCADE, -- ON DELETE CASCADE delete les contitent produit d'un panier si le panier est delete
  constraint _Contient_Produit_p_Produit_fk foreign key (ID_Produit)
    references Alizon._Produit(ID_Produit)
);

/* TABLE CONTIENT PRODUIT COMMANDE */
CREATE TABLE Alizon._Contient_Produit_c (
  ID_Commande int(11) not null, -- Différent
  ID_Produit int(11) not null,
  Quantite int(11),
  Prix_Produit_Commande_HT float not null,
  Prix_Produit_Commande_TTC float not null,
  constraint _Contient_Produit_c_pk primary key (ID_Commande,ID_Produit),
  constraint _Contient_Produit_c_Commande_fk foreign key (ID_Commande)
    references Alizon._Commande(ID_Commande),
  constraint _Contient_Produit_c_Produit_fk foreign key (ID_Produit)
    references Alizon._Produit(ID_Produit)
);

/* TABLE AVIS */
CREATE TABLE Alizon._Avis (
  ID_Commentaire int(11) auto_increment not null,
  ID_Client int(11) not null,
  ID_Produit int(11) not null,
  Note_Produit int(11) not null,
  Commentaire varchar(500),
  Image_Avis varchar(500),
  signalement int default 0,
  constraint _Avis_pk primary key (ID_Commentaire),
   constraint _Avis_Produit_fk foreign key (ID_Produit)
    references Alizon._Produit(ID_Produit),
  constraint _Avis_Client_fk foreign key (ID_Client)
    references Alizon._Client(ID_Client)
);

/* TABLE VENDEUR */
CREATE TABLE Alizon._Vendeur (
  ID_Vendeur int(11) auto_increment not null,
  Prenom varchar(50),
  Nom varchar(50),
  Email varchar(50),
  mdp varchar(100),
  constraint _Vendeur_pk primary key (ID_Vendeur)
);

CREATE TABLE Alizon._Reponse(
  ID_Commentaire int(11) not null,
  ID_Vendeur int(11),
  Commentaire varchar(500),
  constraint _Reponse_pk primary key (ID_Commentaire),
   constraint _Reponse_Avis_fk foreign key (ID_Commentaire)
    references Alizon._Avis(ID_Commentaire),
  constraint _Reponse_Client_fk foreign key (ID_Vendeur)
    references Alizon._Vendeur(ID_Vendeur)
);

/* TABLE PAIEMENT */
CREATE TABLE Alizon._Paiement (
  ID_Commande int(11) not null,
  ID_Client int(11) not null,
  choix_type_Paiement varchar(50) not null,
  numero_carte varchar(50) not null,
  date_carte date,
  cryptogramme int,
  constraint _Paiement_pk primary key (ID_Commande,ID_Client),
  constraint _Paiement_Commande_fk foreign key (ID_Commande)
    references Alizon._Commande(ID_Commande),
  constraint _Paiement_Client_fk foreign key (ID_Client)
    references Alizon._Client(ID_Client)
);



/* TABLE SIGNALER*/
CREATE TABLE Alizon._Signaler (
  ID_Signaleur int(11) not null,  
  ID_Commentaire int(11) not null,
  constraint _Signaler_pk primary key (ID_Signaleur,ID_Commentaire),
  constraint _Posteur_fk foreign key (ID_Commentaire)
    references Alizon._Avis(ID_Commentaire) ON DELETE CASCADE,
  constraint _Signaleur_fk foreign key (ID_Signaleur)
    references Alizon._Client(ID_Client)
);

/* ##############################################################################################################
######################################## CREATION DE FONCTIONS & TRIGGER ########################################
#################################################################################################################*/

-- Declaration des delimiter pour les trigger important pour que la syntaxe de begin et end fonctionne
DELIMITER @@; 

/* -Lorsque le client creer un compte, le panier est créer
Ce trigger s'active lors de la creation d'une commande

Debut Boucle la boucle va parcourir tous les id produit de la table _Produit pour verifier si il existe dans  _Contient_Produit_p
et de pouvoir les ajouter dans la table _Contient_Produit_c

Premierement le trigger recupere tous les produit du panier du client X dans des variable
@id_Produit l'identifiant des produit
@Quantite la quantite acheter
@Prix_Produit_Commande_HT le prix des produits X hors taxe
@Prix_Produit_Commande_TTC le prix des produits X avec taxe

Deuxiement il insert dans la table _Contient_Produit_c le produit X si il existe dans le panier du client X

Fin de boucle

Aprés avoir insert dans _Contient_Produit_c le trigger suprime le pannier du client XAlizon
*/
CREATE TRIGGER Alizon.trig_Commande3
after INSERT on Alizon._Commande
FOR EACH ROW
BEGIN
set @i =1 ;
while @i <= (select max(id_Produit) from _Produit) do
	set @id_Produit = (select id_Produit from _Contient_Produit_p where _Contient_Produit_p.ID_Panier in (select ID_Panier from _Panier where ID_Client = new.ID_Client) and _Contient_Produit_p.id_Produit =@i);
	set @Quantite = (select Quantite from _Contient_Produit_p where _Contient_Produit_p.ID_Panier in (select ID_Panier from _Panier where ID_Client = new.ID_Client) and _Contient_Produit_p.id_Produit =@i);
	set @Prix_Produit_Commande_HT = (select Prix_Produit_Commande_HT from _Contient_Produit_p where _Contient_Produit_p.ID_Panier in (select ID_Panier from _Panier where ID_Client = new.ID_Client) and _Contient_Produit_p.id_Produit =@i);
	set @Prix_Produit_Commande_TTC = (select Prix_Produit_Commande_TTC from _Contient_Produit_p where _Contient_Produit_p.ID_Panier in (select ID_Panier from _Panier where ID_Client = new.ID_Client) and _Contient_Produit_p.id_Produit =@i);
	if @id_Produit is not null then
		INSERT INTO Alizon._Contient_Produit_c(ID_Commande,id_Produit,quantite,Prix_Produit_Commande_HT,Prix_Produit_Commande_TTC)VALUES(new.Id_Commande,@id_Produit,@Quantite,@Prix_Produit_Commande_HT,@Prix_Produit_Commande_TTC);
	END IF;
	set @i = @i+1;
END WHILE;
DELETE from Alizon._Panier where ID_Client = New.ID_Client;
INSERT INTO Alizon._Panier (id_Client,prix_total_ht,prix_total_ttc)VALUES(new.id_Client,0.00,0.00);
END;
@@;

/* -Lors de la creation d'un client 
Lors de la creation d'un nouveau client le trigger lui assigne un nouveau pannier
*/
CREATE TRIGGER Alizon.trig_Pannier
after INSERT on Alizon._Client
FOR EACH ROW
BEGIN
INSERT INTO Alizon._Panier (id_Client,prix_total_ht,prix_total_ttc)VALUES(new.id_Client,0.00,0.00);
END;
@@;

/* -Lors de la creation d'un client 
Ce trigger verifie si des id ont etais liberer avant auto_increment

Les variables:
@i idClient tester 
@modif bolean qui verifie si un client a reçu sont ID

Debut boucle la boucle continuras tant que @i n'est pas arriver au plus grand @id de la classe _Client ou quand le bolean modif sera a true

Si le nombre de client pour ID_Client @i = 0 alors id est libre et le set dans le new.CLIENT

Fin boucle
*/
CREATE TRIGGER Alizon.trig_id_Client
before INSERT on Alizon._Client
FOR EACH ROW
BEGIN
set @i =1;
set @modif =0;
while @i< (select max(ID_Client) from Alizon._Client) && @modif = 0 do
	if (select count(*) from Alizon._Client where Id_Client = @i) = 0 then
		set New.Id_Client = @i;
		set @modif =1;
	end if;
	set @i= @i+1;
end while;
END;
@@;

/* -Lors de la creation d'un panier 
Ce trigger verifie si des id ont etais liberer avant auto_increment

Les variables:
@i idPanier tester 
@modif bolean qui verifie si un panier a reçu sont ID

Debut boucle la boucle continuras tant que @i n'est pas arriver au plus grand @id de la classe _Panier ou quand le bolean modif sera a true

Si le nombre de panier pour ID_Panier @i = 0 alors id est libre et le set dans le new.panier

Fin boucle
*/
CREATE TRIGGER Alizon.trig_id_Panier
before INSERT on Alizon._Panier
FOR EACH ROW
BEGIN
set @i =1;
set @modif =0;
while @i< (select max(ID_Panier) from Alizon._Panier) && @modif = 0 do
	if (select count(*) from Alizon._Panier where Id_Panier = @i) = 0 then
		set New.Id_Panier = @i;
		set @modif =1;
	end if;
	set @i= @i+1;
end while;
END;
@@;

/* -Lors de la creation d'un produit
Ce trigger Calcul le prix avec taxe d'un produit

variables:
@tva_n recupere la tva du produit X par son Id_Sous_Categorie

set Prix_vente_TTC avec le resultat de la formule de calcule de Prix TTC : (PrixHT*TVA)+PRIXHT
*/
CREATE TRIGGER Alizon.trig_TVA_Produit
before INSERT on Alizon._Produit
FOR EACH ROW
BEGIN 
set @tva_n := (SELECT tva from Alizon._Sous_Categorie where Alizon._Sous_Categorie.Id_Sous_Categorie = new.Id_Sous_Categorie); 
set new.Prix_vente_TTC := (@tva_n*new.prix_vente_ht) + new.prix_vente_ht;
END;
@@;

/* -Lors de la creation d'un avis
Ce trigger calcule la moyenne d'un produit lorsque qu'un avis est ajouter

Variables:
@somme somme des notes donner par les clients
@compte Nombre de clients ayant donner une note

mes a jour dans la table _Produit Moyenne_Note_Produit @somme/@compte pour le produit X
*/
CREATE TRIGGER Alizon.trig_Moyenne_Note_Produit
after INSERT on Alizon._Avis
FOR EACH ROW
BEGIN
set @somme := (select sum(Note_Produit) from Alizon._Avis WHERE new.ID_Produit = ID_Produit);
set @compte := (select count(Note_Produit) from Alizon._Avis WHERE new.ID_Produit = ID_Produit);
UPDATE Alizon._Produit SET Moyenne_Note_Produit = (@somme/@compte) WHERE new.ID_Produit = ID_Produit;
END;
@@;

/* -Lors de la suppresion d'un avis
Ce trigger calcule la moyenne d'un produit lorsque qu'un avis est suprimer

Variables:
@somme somme des notes donner par les clients
@compte Nombre de clients ayant donner une note

mais a jour dans la table _Produit Moyenne_Note_Produit @somme/@compte pour le produit X
*/
CREATE TRIGGER Alizon.trig_Moyenne_Note_Produit2
after delete on Alizon._Avis
FOR EACH ROW
BEGIN
set @somme := (select sum(Note_Produit) from Alizon._Avis WHERE old.ID_Produit = ID_Produit);
set @compte := (select count(Note_Produit) from Alizon._Avis WHERE old.ID_Produit = ID_Produit);
UPDATE Alizon._Produit SET Moyenne_Note_Produit = (@somme/@compte) WHERE old.ID_Produit = ID_Produit;
END;
@@;

/* -Lors de l' ajout d'un produit dans le panier
Ce trigger calcule le prix totale TCC et HT du produit avec la quantite

Variables:
@prixht prix total ht
@prixttc prix total ttc

set les nouveaux totaux dans _Contient_Produit_p
*/
CREATE TRIGGER Alizon.trig_Calcul_PrixTotal_Produit1
BEFORE INSERT ON Alizon._Contient_Produit_p
FOR EACH ROW
BEGIN
set @prixht := (SELECT prix_vente_ht FROM Alizon._Produit WHERE _Produit.id_Produit = NEW.id_Produit);
set @prixttc := (SELECT prix_vente_ttc FROM Alizon._Produit WHERE _Produit.id_Produit = NEW.id_Produit);
set new.Prix_Produit_Commande_HT := @prixht*new.quantite;
set new.Prix_Produit_Commande_TTC := @prixttc*new.quantite;
END;
@@;

/* -Lors d'une modification d'un produit dans le panier
Ce trigger calcule le prix totale TCC et HT avec la quantite

Variables:
@prixht prix total ht
@prixttc prix total ttc

set les nouveaux totaux dans _Contient_Produit_p
*/
CREATE TRIGGER Alizon.trig_Calcul_PrixTotal_Produit2
BEFORE UPDATE ON Alizon._Contient_Produit_p
FOR EACH ROW
BEGIN
set @prixht := (SELECT prix_vente_ht FROM Alizon._Produit WHERE _Produit.id_Produit = NEW.id_Produit);
set @prixttc := (SELECT prix_vente_ttc FROM Alizon._Produit WHERE _Produit.id_Produit = NEW.id_Produit);
set new.Prix_Produit_Commande_HT := @prixht*new.quantite;
set new.Prix_Produit_Commande_TTC := @prixttc*new.quantite;
END;
@@;

/* -Lors de l' ajout d'un produit dans le panier
Ce trigger calcule le prix totale TCC et HT du panier

Variables:
@Somprixht prix total ht
@Somprixttc prix total ttc

set les nouveaux totaux dans _Panier
*/
CREATE TRIGGER Alizon.trig_Calcul_Total_Panier1
after INSERT on Alizon._Contient_Produit_p
FOR EACH ROW
BEGIN
SET @Somprixht := (SELECT sum(prix_Produit_Commande_ht) FROM Alizon._Contient_Produit_p WHERE id_Panier = NEW.id_Panier);
SET @Somprixttc := (SELECT sum(prix_Produit_Commande_ttc) FROM Alizon._Contient_Produit_p WHERE id_Panier = NEW.id_Panier);
UPDATE Alizon._Panier SET prix_total_ht = @Somprixht, prix_total_ttc = @Somprixttc, derniere_modif = NOW() WHERE id_Panier = NEW.id_Panier;
END;
@@;

/* -Lors de modification d'un produit dans le panier
Ce trigger calcule le prix totale TCC et HT du panier

Variables:
@Somprixht prix total ht
@Somprixttc prix total ttc

set les nouveaux totaux dans _Panier
*/
CREATE TRIGGER Alizon.trig_Calcul_Total_Panier2
after update on Alizon._Contient_Produit_p
FOR EACH ROW
BEGIN
SET @Somprixht := (SELECT sum(prix_Produit_Commande_ht) FROM Alizon._Contient_Produit_p WHERE id_Panier = NEW.id_Panier);
SET @Somprixttc := (SELECT sum(prix_Produit_Commande_ttc) FROM Alizon._Contient_Produit_p WHERE id_Panier = NEW.id_Panier);
UPDATE Alizon._Panier SET prix_total_ht = @Somprixht, prix_total_ttc = @Somprixttc, derniere_modif = NOW() WHERE id_Panier = NEW.id_Panier;
END;
@@;

/* -Lors de la suppresion d'un produit dans le panier
Ce trigger calcule le prix totale TCC et HT du panier

Variables:
@Somprixht prix total ht
@Somprixttc prix total ttc

set les nouveaux totaux dans _Panier
*/
CREATE TRIGGER Alizon.trig_Calcul_Total_Panier3
after delete on Alizon._Contient_Produit_p
FOR EACH ROW
BEGIN
SET @Somprixht := (SELECT sum(prix_Produit_Commande_ht) FROM Alizon._Contient_Produit_p WHERE id_Panier = old.id_Panier);
SET @Somprixttc := (SELECT sum(prix_Produit_Commande_ttc) FROM Alizon._Contient_Produit_p WHERE id_Panier = old.id_Panier);
UPDATE Alizon._Panier SET prix_total_ht = @Somprixht, prix_total_ttc = @Somprixttc, derniere_modif = NOW() WHERE id_Panier = old.id_Panier;
END;
@@;

/* -Lors de l'ajout d'un signalment
Ce trigger compte le nombre de signalement

Variables:
@nbs nombre de signalement avant l'ajout du nouveau

set les total de signalement dans avis
*/
CREATE trigger Alizon.trig_Calcul_Signalement
after insert on Alizon._Signaler
FOR EACH ROW
BEGIN
set @nbs = (select signalement from Alizon._Avis where ID_Commentaire = new.ID_Commentaire);
UPDATE Alizon._Avis SET signalement = @nbs+1   where ID_Commentaire = new.ID_Commentaire;
END;
@@;

/* -Lors de l'ajout d'une commande
Ce trigger automatise les date de livraison

Variable:
@date date courrante
*/
CREATE trigger Alizon.trig_Commande_date
before insert on Alizon._Commande
for each row
BEGIN
set @date = (SELECT curdate());
set new.date_Commande = @date;
set new.date_livraison = (select date_sub(curdate(),interval -30 day));
set new.Duree_maximale_restante = (SELECT DATEDIFF(new.date_livraison, new.date_Commande));
set new.prix_total = (select sum(Prix_Produit_Commande_TTC) from _Contient_Produit_p where _Contient_Produit_p.ID_Panier in (select ID_Panier from _Panier where ID_Client = new.ID_Client));
END;
@@;

/* -Lors de la creation d'un panier 
Ce trigger verifie si des id ont etais liberer avant auto_increment
Et que le client n'a pas déjà donnes son avis

Les variables:
@i idcommentaire tester 
@modif bolean qui verifie si un avis a reçu sont ID

Debut boucle la boucle continuras tant que @i n'est pas arriver au plus grand @id de la classe _Avis ou quand le bolean modif sera a true

Si le nombre de panier pour ID_Commentaire @i = 0 alors id est libre et le set dans le new.avis

Fin boucle
*/
CREATE TRIGGER Alizon.trig_id_Avis
before INSERT on Alizon._Avis
FOR EACH ROW
BEGIN
set @i =1;
set @modif =0;
if (select count(*) from Alizon._Avis where ID_Client=new.ID_Client and ID_Produit = new.ID_Produit) = 0 then
while @i< (select max(ID_Commentaire) from Alizon._Avis) && @modif = 0 do
	if (select count(*) from Alizon._Avis where ID_Commentaire = @i) = 0 then
		set New.ID_Commentaire = @i;
		set @modif =1;
	end if;
	set @i= @i+1;
end while;
else
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Cette personne a déjà donner son avis !';
End if;
END;
@@;

/* ###############################################################################################
######################################## CREATION DE VUES ########################################
##################################################################################################*/

/*CREATE OR REPLACE View Alizon.panier AS
SELECT nom_Produit,images1, images2, images3, Alizon._Contient_Produit_p.id_Produit,nom_Categorie,quantite_disponnible,quantite,prix_total_ttc, prix_Produit_Commande_ttc, prenom as vendeur , description_Produit, Alizon._Panier.id_Panier, prix_vente_ttc, Alizon._Sous_Categorie.nom as nom_SouCategorie  FROM Alizon._Contient_Produit_p 
INNER JOIN Alizon._Produit ON Alizon._Produit.id_Produit = Alizon._Contient_Produit_p.id_Produit
INNER JOIN Alizon._Sous_Categorie ON Alizon._Sous_Categorie.Id_Sous_Categorie = Alizon._Produit.id_Sous_Categorie
INNER JOIN Alizon._Categorie ON Alizon._Categorie.id_Categorie = Alizon._Sous_Categorie.Id_Categorie_sup
INNER JOIN Alizon._Vendeur ON Alizon._Vendeur.id_Vendeur = Alizon._Produit.id_Vendeur
INNER JOIN Alizon._Panier ON Alizon._Contient_Produit_p.id_Panier = Alizon._Panier.id_Panier;




CREATE OR REPLACE View Alizon.catalogue AS
SELECT id_Produit, nom_Produit, prix_vente_ht, prix_vente_ttc, quantite_disponnible, description_Produit, images1, images2, images3, nom as nom_souscategorie, nom_Categorie, moyenne_note_Produit FROM Alizon._Produit
INNER JOIN Alizon._Sous_Categorie ON Alizon._Sous_Categorie.Id_Sous_Categorie = Alizon._Produit.id_Sous_Categorie
INNER JOIN Alizon._Categorie ON Alizon._Categorie.id_Categorie = Alizon._Sous_Categorie.id_Categorie_sup;*/



/* ###################################################################################################
######################################## INSERTION DE DONNEES ########################################
######################################################################################################*/

insert into Alizon._Vendeur (Prenom, Nom, Email,mdp) values ('Selestina', 'Darwent', 'sdarwent0@sourceforge.net', '701 605 384 384 117 474 384 109 321 312 277 605 268 384 312');
insert into Alizon._Vendeur (Prenom, Nom, Email,mdp) values ('Reinold', 'Henrichs', 'rhenrichs1@wix.com', '701 605 384 384 117 474 384 109 321 312 277 605 268 384 312');
insert into Alizon._Vendeur (Prenom, Nom, Email,mdp) values ('Cornela', 'Cordelette', 'ccordelette2@creativecommons.org', '701 605 384 384 117 474 384 109 321 312 277 605 268 384 312');
insert into Alizon._Vendeur (Prenom, Nom, Email,mdp) values ('Korry', 'Esby', 'kesby3@nih.gov', '701 605 384 384 117 474 384 109 321 312 277 605 268 384 312');
insert into Alizon._Vendeur (Prenom, Nom, Email,mdp) values ('Daloris', 'Castelletto', 'dcastelletto4@yale.edu', '701 605 384 384 117 474 384 109 321 312 277 605 268 384 312');
insert into Alizon._Vendeur (Prenom, Nom, Email,mdp) values ('Kimble', 'Nucciotti', 'knucciotti5@tmall.com', '701 605 384 384 117 474 384 109 321 312 277 605 268 384 312');
insert into Alizon._Vendeur (Prenom, Nom, Email,mdp) values ('Ophelia', 'Quinsee', 'oquinsee6@rediff.com', 'abc');
insert into Alizon._Vendeur (Prenom, Nom, Email,mdp) values ('Sophi', 'MacDonogh', 'smacdonogh7@slashdot.org', 'abc');
insert into Alizon._Vendeur (Prenom, Nom, Email,mdp) values ('Gunilla', 'Tunuy', 'gtunuy8@linkedin.com', 'abc');
insert into Alizon._Vendeur (Prenom, Nom, Email,mdp) values ('Ode', 'Ladell', 'oladell9@pbs.org', 'abc');
insert into Alizon._Vendeur (Prenom, Nom, Email,mdp) values ('Julieta', 'Feronet', 'jferoneta@senate.gov', 'abc');
insert into Alizon._Vendeur (Prenom, Nom, Email,mdp) values ('Don', 'Axel', 'daxelb@liveinternet.ru', 'abc');
insert into Alizon._Vendeur (Prenom, Nom, Email,mdp) values ('Gizela', 'Charville', 'gcharvillec@unblog.fr', 'abc');
insert into Alizon._Vendeur (Prenom, Nom, Email,mdp) values ('Brock', 'Dallas', 'bdallasd@dailymail.co.uk', 'abc');
insert into Alizon._Vendeur (Prenom, Nom, Email,mdp) values ('Eolanda', 'Quade', 'equadee@examiner.com', 'abc');
insert into Alizon._Vendeur (Prenom, Nom, Email,mdp) values ('Gianni', 'Bore', 'gboref@mediafire.com', 'abc');
insert into Alizon._Vendeur (Prenom, Nom, Email,mdp) values ('Mathilde', 'Burnett', 'mburnettg@moonfruit.com', 'abc');
insert into Alizon._Vendeur (Prenom, Nom, Email,mdp) values ('Ethelred', 'Chainey', 'echaineyh@wired.com', 'abc');
insert into Alizon._Vendeur (Prenom, Nom, Email,mdp) values ('Jodie', 'Beadel', 'jbeadeli@fema.gov', 'abc');
insert into Alizon._Vendeur (Prenom, Nom, Email,mdp) values ('Genovera', 'Ferrari', 'gferrarij@live.com', 'abc');
insert into Alizon._Vendeur (Prenom, Nom, Email,mdp) values ('Stanley', 'Mantha', 'smanthak@youtu.be', 'abc');
insert into Alizon._Vendeur (Prenom, Nom, Email,mdp) values ('Cassey', 'Connechie', 'cconnechiel@artisteer.com', 'abc');
insert into Alizon._Vendeur (Prenom, Nom, Email,mdp) values ('Lyle', 'Truckett', 'ltruckettm@ycombinator.com', 'abc');
insert into Alizon._Vendeur (Prenom, Nom, Email,mdp) values ('Dion', 'Rowlinson', 'drowlinsonn@aboutads.info', 'abc');
insert into Alizon._Vendeur (Prenom, Nom, Email,mdp) values ('Starla', 'Birmingham', 'sbirminghamo@unesco.org', 'abc');
insert into Alizon._Vendeur (Prenom, Nom, Email,mdp) values ('Michaelina', 'Flew', 'mflewp@nationalgeographic.com', 'abc');
insert into Alizon._Vendeur (Prenom, Nom, Email,mdp) values ('Marilyn', 'Bennike', 'mbennikeq@cisco.com', 'abc');
insert into Alizon._Vendeur (Prenom, Nom, Email,mdp) values ('Lane', 'Trengrouse', 'ltrengrouser@answers.com', 'abc');
insert into Alizon._Vendeur (Prenom, Nom, Email,mdp) values ('Etan', 'Othick', 'eothicks@examiner.com', 'abc');
insert into Alizon._Vendeur (Prenom, Nom, Email,mdp) values ('Bennie', 'Gotcliff', 'bgotclifft@jiathis.com', 'abc');

INSERT INTO Alizon._Client (nom_Client, prenom_Client, date_de_naissance, email, mdp, QuestionReponse) VALUES ('Portier', 'Loane', '2003-12-15', 'paprika@gmail.com', '224 605 224 321 117 19 605 407 268 442 605 117 426 23 471 474 442', 'Gardeur');
INSERT INTO Alizon._Client (nom_Client, prenom_Client, date_de_naissance, email, mdp, QuestionReponse) VALUES ('Titouan', 'Laughren', '2002-05-24', 'TitouanRobe@gmail.com', '548 117 277 474 3 605 384 669 474 501 312 407 268 442 605 117 426 23 471 474 442', 'Rennes');
INSERT INTO Alizon._Client (nom_Client, prenom_Client, date_de_naissance, email, mdp, QuestionReponse) VALUES ('Maincent', 'Oscar', '2003-01-05', 'OscarMaincent@gmail.com', '548 117 277 474 3 605 384 669 474 501 312 407 268 442 605 117 426 23 471 474 442', 'Saint-Martin');
INSERT INTO Alizon._Client (nom_Client, prenom_Client, date_de_naissance, email, mdp, QuestionReponse) VALUES ('Demany', 'Theo', '2003-10-01', 'TheoDemany@gmail.com', '548 261 312 474 68 312 442 605 384 386 407 268 442 605 117 426 23 471 474 442', 'Audi');

INSERT into Alizon._Adresse (ID_Client,nom_de_rue, complement, ville, code_postale) values (1,"1 Rue édouard Branly",null,"Lannion","22300");
INSERT into Alizon._Adresse (ID_Client,nom_de_rue, complement, ville, code_postale) values (1,"11 chemin de traverse",null,"Guingamp","22200");
INSERT into Alizon._Adresse (ID_Client,nom_de_rue, complement, ville, code_postale) values (2,"45 Avenue Fosh","11e arrondissement","Paris","75111");
INSERT into Alizon._Adresse (ID_Client,nom_de_rue, complement, ville, code_postale) values (3,"29 rue cherbourg","3e Arrondissement","Paris","75003");
INSERT into Alizon._Adresse (ID_Client,nom_de_rue, complement, ville, code_postale) values (4,"Résidence de la haute rive","Batiment D","Lannion","22300");

INSERT into Alizon._Categorie (nom_Categorie) values ('Epicerie');
INSERT into Alizon._Categorie (nom_Categorie) values ('Vetements');
INSERT into Alizon._Categorie (nom_Categorie) values ('Souvenirs');
INSERT into Alizon._Categorie (nom_Categorie) values ('Produits frais');


INSERT INTO Alizon._Sous_Categorie(id_Categorie_sup,nom,tva) VALUES(1,'Gateaux',0.20);
INSERT INTO Alizon._Sous_Categorie(id_Categorie_sup,nom,tva) VALUES(1,'Déjeuner',0.20);
INSERT INTO Alizon._Sous_Categorie(id_Categorie_sup,nom,tva) VALUES(2,'Pull',0.20);
INSERT INTO Alizon._Sous_Categorie(id_Categorie_sup,nom,tva) VALUES(2,'Pantalons',0.20);
INSERT INTO Alizon._Sous_Categorie(id_Categorie_sup,nom,tva) VALUES(2,'Vêtements de pluie',0.20);
INSERT INTO Alizon._Sous_Categorie(id_Categorie_sup,nom,tva) VALUES(3,'Poster',0.20);
INSERT INTO Alizon._Sous_Categorie(id_Categorie_sup,nom,tva) VALUES(3,'Cartes postales',0.20);
INSERT INTO Alizon._Sous_Categorie(id_Categorie_sup,nom,tva) VALUES(3,'Portes clefs',0.20);
INSERT INTO Alizon._Sous_Categorie(id_Categorie_sup,nom,tva) VALUES(4,'Poissons',0.20);
INSERT INTO Alizon._Sous_Categorie(id_Categorie_sup,nom,tva) VALUES(4,'Viande',0.20);


INSERT INTO Alizon._Produit( `Nom_Produit`, `Prix_coutant`, `Prix_vente_HT`, `Prix_vente_TTC`, `Quantite_disponnible`, `Description_Produit`, `images1`, `images2`, `images3`, `Moyenne_Note_Produit`, `Id_Sous_Categorie`, `ID_Vendeur`) VALUES
('kouign amann', '10.00', '19.99', '23.99', 100, 'Maecenas tincidunt lacus at velit. Vivamus vel nulla eget eros elementum pellentesque. Quisque porta volutpat erat.', 'img1.jpg', 'img2.jpg', 'img3.jpg', 5, 1, 1),
('COLA BIEN FRAIS', '3.00', '4.99', '5.99', 80, 'Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec pharetra, magna vestibulum aliquet ultrices, erat tortor sollicitudin mi, sit amet lobortis sapien sapien non mi. Integer ac neque. Duis bibendum.', 'img1.jpg', 'img2.jpg', 'img3.jpg', NULL, 1, 1),
('Miel Breton toutes fleurs Pot 125g', '3.00', '4.99', '5.99', 48, 'Etiam faucibus cursus urna. Ut tellus. Nulla ut erat id mauris vulputate elementum. Nullam varius.', 'img1.jpg', 'img2.jpg', NULL, NULL, 2, 2),
('Beurre salé - 500g', '4.00', '7.35', '8.82', 350, 'Nulla ut erat id mauris vulputate elementum.', 'img1.jpg', 'img2.jpg', 'img3.jpg', NULL, 2, 3),
('Caramel au beurre salé - 500ml', '30.00', '49.50', '59.40', 100, 'Etiam justo. Etiam pretium iaculis justo. In hac habitasse platea dictumst. Etiam faucibus cursus urna. Ut tellus.', 'img1.jpg', 'img2.jpg', NULL, NULL, 2, 4),
('Crêpes dentelle – Cœur Cacao Noisettes 90g', '1.00', '1.99', '2.39', 350, 'Nulla ut erat id mauris vulputate elementum.', 'img1.jpg', 'img2.jpg', 'img3.jpg', NULL, 1, 1),
('Bottes de pluie', '7.00', '11.50', '13.80', 100, 'Etiam justo. Etiam pretium iaculis justo. In hac habitasse platea dictumst. Etiam faucibus cursus urna. Ut tellus.', 'img1.jpg', 'img2.jpg', NULL, NULL, 5, 1),
('Maquereau, poivron jaune, piment d’Espelette 90g', '8.50', '11.25', '13.50', 350, 'Nulla ut erat id mauris vulputate elementum.', 'img1.jpg', 'img2.jpg', NULL, NULL, 9, 9),
('Palette demi-sel BIO VPF 500g', '15.00', '23.50', '28.20', 100, 'Etiam justo. Etiam pretium iaculis justo. In hac habitasse platea dictumst. Etiam faucibus cursus urna. Ut tellus.', 'img1.jpg', 'img2.jpg', NULL, NULL, 10, 10),
('Bob circuit Paul Ricard', '15.00', '22.33', '26.80', 0, 'Nulla ut erat id mauris vulputate elementum.', 'img1.jpg', 'img2.jpg', NULL, NULL, 3, 3),
('Poster nolwenn leroy', '5.00', '6.50', '7.80', 100, 'Etiam justo. Etiam pretium iaculis justo. In hac habitasse platea dictumst. Etiam faucibus cursus urna. Ut tellus.', 'img1.jpg', 'img2.jpg', NULL, NULL, 6, 15),
('Portes clefs bien clichés', '45.00', '74.50', '89.40', 100, 'Etiam justo. Etiam pretium iaculis justo. In hac habitasse platea dictumst. Etiam faucibus cursus urna. Ut tellus.', 'img1.jpg', NULL, NULL, NULL, 8, 6);


INSERT INTO Alizon._Panier(Prix_total_HT,Prix_total_TTC,ID_Client)VALUES(0,0,1);
INSERT INTO Alizon._Panier(Prix_total_HT,Prix_total_TTC,ID_Client)VALUES(0,0,2);
INSERT INTO Alizon._Panier(Prix_total_HT,Prix_total_TTC,ID_Client)VALUES(0,0,3);
INSERT INTO Alizon._Panier(Prix_total_HT,Prix_total_TTC,ID_Client)VALUES(0,0,4);


INSERT INTO Alizon._Contient_Produit_p(id_Panier,id_Produit,quantite)VALUES(1,1,10);
INSERT INTO Alizon._Contient_Produit_p(id_Panier,id_Produit,quantite)VALUES(1,3,2);
INSERT INTO Alizon._Contient_Produit_p(id_Panier,id_Produit,quantite)VALUES(2,4,5);
INSERT INTO Alizon._Contient_Produit_p(id_Panier,id_Produit,quantite)VALUES(4,1,12);
INSERT INTO Alizon._Contient_Produit_p(id_Panier,id_Produit,quantite)VALUES(4,2,3);
INSERT INTO Alizon._Contient_Produit_p(id_Panier,id_Produit,quantite)VALUES(1,2,5);
INSERT INTO Alizon._Contient_Produit_p(id_Panier,id_Produit,quantite)VALUES(2,1,7);
INSERT INTO Alizon._Contient_Produit_p(id_Panier,id_Produit,quantite)VALUES(2,2,3);


INSERT INTO Alizon._Commande(ID_Client, etat_Commande, adresse_livraison)VALUES(1, 'en cours', 'Résidence Lannion');


INSERT INTO _Avis (ID_Client, ID_Produit, Note_Produit, Commentaire, Image_Avis) VALUES ('1', '1', '5', 'Maecenas tincidunt lacus at velit. Vivamus vel nulla eget eros elementum', NULL);
INSERT INTO _Avis (ID_Client, ID_Produit, Note_Produit, Commentaire, Image_Avis) VALUES ('2', '1', '4', 'Etiam faucibus cursus urna. Ut tellus. Nulla ut erat id mauris', NULL);
INSERT INTO _Avis (ID_Client, ID_Produit, Note_Produit, Commentaire, Image_Avis) VALUES ('3', '3', '2', 'Justo. Etiam pretium iaculis justo.entaire', NULL);
INSERT INTO _Avis (ID_Client, ID_Produit, Note_Produit, Commentaire, Image_Avis) VALUES ('1', '3', '3', 'Vivamus vel nulla eget eros elementum pellentesque.', NULL);
INSERT INTO _Avis (ID_Client, ID_Produit, Note_Produit, Commentaire, Image_Avis) VALUES ('2', '2', '4', 'Maecenas tincidunt lacus', NULL);
INSERT INTO _Avis (ID_Client, ID_Produit, Note_Produit, Commentaire, Image_Avis) VALUES ('3', '1', '5', 'Justo', NULL);



INSERT INTO _Signaler(ID_Commentaire,ID_Signaleur) VALUES(1,1);
INSERT INTO _Signaler(ID_Commentaire,ID_Signaleur) VALUES(1,2);
INSERT INTO _Signaler(ID_Commentaire,ID_Signaleur) VALUES(2,3);
INSERT INTO _Signaler(ID_Commentaire,ID_Signaleur) VALUES(1,4);


INSERT INTO _Reponse(ID_Commentaire, ID_Vendeur, Commentaire) VALUES ('3', '1', 'Réel');
