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
trig_id_client:295
trig_id_panier:324
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

-- drop schema alizon;
-- create schema alizon;
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
    Nom_produit VARCHAR(50),
    Prix_coutant DECIMAL(5 , 2 ),
    Prix_vente_HT NUMERIC(5 , 2 ),
    Prix_vente_TTC NUMERIC(5 , 2 ),
    Quantite_disponnible INT,
    Description_produit VARCHAR(500),
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

/* TABLE QUESTION SECRETE */
CREATE TABLE Alizon._QuestionSecrete (
	ID_QuestionSecrete int(11) auto_increment not null,
    question varchar(500),
    
	constraint _QuestionSecrete_pk primary key (ID_QuestionSecrete)
);

/* TABLE CLIENT */
CREATE TABLE Alizon._Client (
  ID_Client int(11) auto_increment not null,
  nom_client varchar(50),
  prenom_client varchar(50),
  adresse_facturation varchar(50),
  date_de_naissance date,
  email varchar(50),
  mdp varchar(50),
  ID_QuestionSecrete int(11),
  QuestionReponse varchar(500),
  
  constraint _Client_pk primary key (ID_Client),
  constraint _RecupMDP_fk foreign key (ID_QuestionSecrete)
    references Alizon._QuestionSecrete(ID_QuestionSecrete)
);

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
  etat_commande varchar(50),
  adresse_livraison varchar(50),
  date_commande date ,
  date_livraison date,
  Duree_maximale_restante int,
  constraint _Commande_pk primary key (ID_Commande),
  constraint _Commande_Client_fk foreign key (ID_Client)
    references Alizon._Client (ID_Client)
);

/* TABLE CONTIENT PRODUIT PANIER */
CREATE TABLE Alizon._Contient_Produit_p (
  ID_Panier int(11) not null, -- Différent
  ID_Produit int(11) not null,
  Quantite int(11),
  Prix_produit_commande_HT float not null,
  Prix_produit_commande_TTC float not null,
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
  Prix_produit_commande_HT float not null,
  Prix_produit_commande_TTC float not null,
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

/* TABLE PAIEMENT */
CREATE TABLE Alizon._Paiement (
  ID_Commande int(11) not null,
  ID_Client int(11) not null,
  choix_type_paiement varchar(50) not null,
  numero_carte varchar(50) not null,
  date_carte date,
  cryptogramme int,
  constraint _Paiement_pk primary key (ID_Commande,ID_Client),
  constraint _Paiement_Commande_fk foreign key (ID_Commande)
    references Alizon._Commande(ID_Commande),
  constraint _Paiement_Client_fk foreign key (ID_Client)
    references Alizon._Client(ID_Client)
);

/* TABLE VENDEUR */
CREATE TABLE Alizon._Vendeur (
  ID_Vendeur int(11) auto_increment not null,
  Prenom varchar(50),
  Nom varchar(50),
  Email varchar(50),
  constraint _Vendeur_pk primary key (ID_Vendeur)
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
et de pouvoir les ajouter dans la table _contient_produit_c

Premierement le trigger recupere tous les produit du panier du client X dans des variable
@id_produit l'identifiant des produit
@Quantite la quantite acheter
@Prix_produit_commande_HT le prix des produits X hors taxe
@Prix_produit_commande_TTC le prix des produits X avec taxe

Deuxiement il insert dans la table _contient_produit_c le produit X si il existe dans le panier du client X

Fin de boucle

Aprés avoir insert dans _contient_produit_c le trigger suprime le pannier du client X
*/
CREATE TRIGGER trig_Commande
after INSERT on Alizon._Commande
FOR EACH ROW
BEGIN
set @i =1 ;
while @i <= (select max(id_produit) from _Produit) do
	set @id_produit = (select id_produit from _Contient_Produit_p where _contient_produit_p.ID_Panier = (select ID_Panier from _panier where ID_Client = new.ID_Client) and _contient_produit_p.id_produit =@i);
	set @Quantite = (select Quantite from _Contient_Produit_p where _contient_produit_p.ID_Panier = (select ID_Panier from _panier where ID_Client = new.ID_Client) and _contient_produit_p.id_produit =@i);
	set @Prix_produit_commande_HT = (select Prix_produit_commande_HT from _Contient_Produit_p where _contient_produit_p.ID_Panier = (select ID_Panier from _panier where ID_Client = new.ID_Client) and _contient_produit_p.id_produit =@i);
	set @Prix_produit_commande_TTC = (select Prix_produit_commande_TTC from _Contient_Produit_p where _contient_produit_p.ID_Panier = (select ID_Panier from _panier where ID_Client = new.ID_Client) and _contient_produit_p.id_produit =@i);
	if @id_produit is not null then
		INSERT INTO alizon._contient_produit_c(ID_Commande,id_produit,quantite,Prix_produit_commande_HT,Prix_produit_commande_TTC)VALUES(new.Id_Commande,@id_produit,@Quantite,@Prix_produit_commande_HT,@Prix_produit_commande_TTC);
	END IF;
	set @i = @i+1;
END WHILE;
DELETE from Alizon._Panier where ID_Client = New.ID_Client;
END;
@@;

/* -Lors de la creation d'un client 
Lors de la creation d'un nouveau client le trigger lui assigne un nouveau pannier
*/
CREATE TRIGGER trig_Pannier
after INSERT on alizon._Client
FOR EACH ROW
BEGIN
INSERT INTO alizon._panier (id_client,prix_total_ht,prix_total_ttc)VALUES(new.id_client,0.00,0.00);
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
CREATE TRIGGER trig_id_client
before INSERT on alizon._Client
FOR EACH ROW
BEGIN
set @i =1;
set @modif =0;
while @i< (select max(ID_Client) from alizon._Client) && @modif = 0 do
	if (select count(*) from alizon._Client where Id_Client = @i) = 0 then
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

Debut boucle la boucle continuras tant que @i n'est pas arriver au plus grand @id de la classe _panier ou quand le bolean modif sera a true

Si le nombre de panier pour ID_Panier @i = 0 alors id est libre et le set dans le new.panier

Fin boucle
*/
CREATE TRIGGER trig_id_panier
before INSERT on alizon._Panier
FOR EACH ROW
BEGIN
set @i =1;
set @modif =0;
while @i< (select max(ID_Panier) from alizon._Panier) && @modif = 0 do
	if (select count(*) from alizon._Panier where Id_Panier = @i) = 0 then
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
CREATE TRIGGER trig_TVA_Produit
before INSERT on Alizon._Produit
FOR EACH ROW
BEGIN 
set @tva_n := (SELECT tva from Alizon._Sous_Categorie where alizon._Sous_Categorie.Id_Sous_Categorie = new.Id_Sous_Categorie); 
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
CREATE TRIGGER trig_Moyenne_Note_Produit
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
CREATE TRIGGER trig_Moyenne_Note_Produit2
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

set les nouveaux totaux dans _contient_produit_p
*/
CREATE TRIGGER trig_Calcul_PrixTotal_Produit1
BEFORE INSERT ON Alizon._contient_produit_p
FOR EACH ROW
BEGIN
set @prixht := (SELECT prix_vente_ht FROM alizon._produit WHERE _produit.id_produit = NEW.id_produit);
set @prixttc := (SELECT prix_vente_ttc FROM alizon._produit WHERE _produit.id_produit = NEW.id_produit);
set new.Prix_produit_commande_HT := @prixht*new.quantite;
set new.Prix_produit_commande_TTC := @prixttc*new.quantite;
END;
@@;

/* -Lors d'une modification d'un produit dans le panier
Ce trigger calcule le prix totale TCC et HT avec la quantite

Variables:
@prixht prix total ht
@prixttc prix total ttc

set les nouveaux totaux dans _contient_produit_p
*/
CREATE TRIGGER trig_Calcul_PrixTotal_Produit2
BEFORE UPDATE ON Alizon._contient_produit_p
FOR EACH ROW
BEGIN
set @prixht := (SELECT prix_vente_ht FROM alizon._produit WHERE _produit.id_produit = NEW.id_produit);
set @prixttc := (SELECT prix_vente_ttc FROM alizon._produit WHERE _produit.id_produit = NEW.id_produit);
set new.Prix_produit_commande_HT := @prixht*new.quantite;
set new.Prix_produit_commande_TTC := @prixttc*new.quantite;
END;
@@;

/* -Lors de l' ajout d'un produit dans le panier
Ce trigger calcule le prix totale TCC et HT du panier

Variables:
@Somprixht prix total ht
@Somprixttc prix total ttc

set les nouveaux totaux dans _panier
*/
CREATE TRIGGER trig_Calcul_Total_Panier1
after INSERT on Alizon._contient_produit_p
FOR EACH ROW
BEGIN
SET @Somprixht := (SELECT sum(prix_produit_commande_ht) FROM alizon._contient_produit_p WHERE id_panier = NEW.id_panier);
SET @Somprixttc := (SELECT sum(prix_produit_commande_ttc) FROM alizon._contient_produit_p WHERE id_panier = NEW.id_panier);
UPDATE alizon._panier SET prix_total_ht = @Somprixht, prix_total_ttc = @Somprixttc, derniere_modif = NOW() WHERE id_panier = NEW.id_panier;
END;
@@;

/* -Lors de modification d'un produit dans le panier
Ce trigger calcule le prix totale TCC et HT du panier

Variables:
@Somprixht prix total ht
@Somprixttc prix total ttc

set les nouveaux totaux dans _panier
*/
CREATE TRIGGER trig_Calcul_Total_Panier2
after update on Alizon._contient_produit_p
FOR EACH ROW
BEGIN
SET @Somprixht := (SELECT sum(prix_produit_commande_ht) FROM alizon._contient_produit_p WHERE id_panier = NEW.id_panier);
SET @Somprixttc := (SELECT sum(prix_produit_commande_ttc) FROM alizon._contient_produit_p WHERE id_panier = NEW.id_panier);
UPDATE alizon._panier SET prix_total_ht = @Somprixht, prix_total_ttc = @Somprixttc, derniere_modif = NOW() WHERE id_panier = NEW.id_panier;
END;
@@;

/* -Lors de la suppresion d'un produit dans le panier
Ce trigger calcule le prix totale TCC et HT du panier

Variables:
@Somprixht prix total ht
@Somprixttc prix total ttc

set les nouveaux totaux dans _panier
*/
CREATE TRIGGER trig_Calcul_Total_Panier3
after delete on Alizon._contient_produit_p
FOR EACH ROW
BEGIN
SET @Somprixht := (SELECT sum(prix_produit_commande_ht) FROM alizon._contient_produit_p WHERE id_panier = old.id_panier);
SET @Somprixttc := (SELECT sum(prix_produit_commande_ttc) FROM alizon._contient_produit_p WHERE id_panier = old.id_panier);
UPDATE alizon._panier SET prix_total_ht = @Somprixht, prix_total_ttc = @Somprixttc, derniere_modif = NOW() WHERE id_panier = old.id_panier;
END;
@@;

/* -Lors de l'ajout d'un signalment
Ce trigger compte le nombre de signalement

Variables:
@nbs nombre de signalement avant l'ajout du nouveau

set les total de signalement dans avis
*/
CREATE trigger trig_Calcul_Signalement
after insert on alizon._Signaler
FOR EACH ROW
BEGIN
set @nbs = (select signalement from alizon._Avis where ID_Commentaire = new.ID_Commentaire);
UPDATE alizon._Avis SET signalement = @nbs+1   where ID_Commentaire = new.ID_Commentaire;
END;
@@;

/* -Lors de l'ajout d'une commande
Ce trigger automatise les date de livraison

Variable:
@date date courrante
*/
CREATE trigger trig_Commande_date
before insert on alizon._Commande
for each row
BEGIN
set @date = (SELECT curdate());
set new.date_commande = @date;
set new.date_livraison = (select date_sub(curdate(),interval -30 day));
set new.Duree_maximale_restante = (SELECT DATEDIFF(new.date_livraison, new.date_commande));
END;
@@;

/* -Lors de la creation d'un panier 
Ce trigger verifie si des id ont etais liberer avant auto_increment
Et que le client n'a pas déjà donnes son avis

Les variables:
@i idcommentaire tester 
@modif bolean qui verifie si un avis a reçu sont ID

Debut boucle la boucle continuras tant que @i n'est pas arriver au plus grand @id de la classe _avis ou quand le bolean modif sera a true

Si le nombre de panier pour ID_Commentaire @i = 0 alors id est libre et le set dans le new.avis

Fin boucle
*/
CREATE TRIGGER trig_id_Avis
before INSERT on alizon._avis
FOR EACH ROW
BEGIN
set @i =1;
set @modif =0;
if (select count(*) from alizon._avis where ID_Client=new.ID_Client and ID_Produit = new.ID_Produit) = 0 then
while @i< (select max(ID_Commentaire) from alizon._avis) && @modif = 0 do
	if (select count(*) from alizon._avis where ID_Commentaire = @i) = 0 then
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

CREATE OR REPLACE View Alizon.panier AS
SELECT nom_produit,images1, images2, images3, _contient_produit_p.id_produit,nom_categorie,quantite_disponnible,quantite,prix_total_ttc, prix_produit_commande_ttc, prenom as vendeur , description_produit, _panier.id_panier, prix_vente_ttc, _Sous_Categorie.nom as nom_SouCategorie  FROM Alizon._contient_produit_p 
INNER JOIN Alizon._produit ON Alizon._produit.id_produit = Alizon._contient_produit_p.id_produit
INNER JOIN Alizon._Sous_Categorie ON Alizon._Sous_Categorie.Id_Sous_Categorie = Alizon._produit.id_sous_categorie
INNER JOIN Alizon._categorie ON Alizon._categorie.id_categorie = Alizon._Sous_Categorie.Id_categorie_sup
INNER JOIN Alizon._vendeur ON Alizon._vendeur.id_vendeur = Alizon._produit.id_vendeur
INNER JOIN Alizon._panier ON Alizon._contient_produit_p.id_panier = Alizon._panier.id_panier;




CREATE OR REPLACE View Alizon.catalogue AS
SELECT id_produit, nom_produit, prix_vente_ht, prix_vente_ttc, quantite_disponnible, description_produit, images1, images2, images3, nom as nom_souscategorie, nom_categorie, moyenne_note_produit FROM Alizon._produit
INNER JOIN Alizon._Sous_Categorie ON Alizon._Sous_Categorie.Id_Sous_Categorie = Alizon._produit.id_sous_categorie
INNER JOIN Alizon._Categorie ON Alizon._Categorie.id_categorie = Alizon._Sous_Categorie.id_categorie_sup;




/* ###################################################################################################
######################################## INSERTION DE DONNEES ########################################
######################################################################################################*/

insert into alizon._vendeur (Prenom, Nom, Email) values ('Selestina', 'Darwent', 'sdarwent0@sourceforge.net');
insert into alizon._vendeur (Prenom, Nom, Email) values ('Reinold', 'Henrichs', 'rhenrichs1@wix.com');
insert into alizon._vendeur (Prenom, Nom, Email) values ('Cornela', 'Cordelette', 'ccordelette2@creativecommons.org');
insert into alizon._vendeur (Prenom, Nom, Email) values ('Korry', 'Esby', 'kesby3@nih.gov');
insert into alizon._vendeur (Prenom, Nom, Email) values ('Daloris', 'Castelletto', 'dcastelletto4@yale.edu');
insert into alizon._vendeur (Prenom, Nom, Email) values ('Kimble', 'Nucciotti', 'knucciotti5@tmall.com');
insert into alizon._vendeur (Prenom, Nom, Email) values ('Ophelia', 'Quinsee', 'oquinsee6@rediff.com');
insert into alizon._vendeur (Prenom, Nom, Email) values ('Sophi', 'MacDonogh', 'smacdonogh7@slashdot.org');
insert into alizon._vendeur (Prenom, Nom, Email) values ('Gunilla', 'Tunuy', 'gtunuy8@linkedin.com');
insert into alizon._vendeur (Prenom, Nom, Email) values ('Ode', 'Ladell', 'oladell9@pbs.org');
insert into alizon._vendeur (Prenom, Nom, Email) values ('Julieta', 'Feronet', 'jferoneta@senate.gov');
insert into alizon._vendeur (Prenom, Nom, Email) values ('Don', 'Axel', 'daxelb@liveinternet.ru');
insert into alizon._vendeur (Prenom, Nom, Email) values ('Gizela', 'Charville', 'gcharvillec@unblog.fr');
insert into alizon._vendeur (Prenom, Nom, Email) values ('Brock', 'Dallas', 'bdallasd@dailymail.co.uk');
insert into alizon._vendeur (Prenom, Nom, Email) values ('Eolanda', 'Quade', 'equadee@examiner.com');
insert into alizon._vendeur (Prenom, Nom, Email) values ('Gianni', 'Bore', 'gboref@mediafire.com');
insert into alizon._vendeur (Prenom, Nom, Email) values ('Mathilde', 'Burnett', 'mburnettg@moonfruit.com');
insert into alizon._vendeur (Prenom, Nom, Email) values ('Ethelred', 'Chainey', 'echaineyh@wired.com');
insert into alizon._vendeur (Prenom, Nom, Email) values ('Jodie', 'Beadel', 'jbeadeli@fema.gov');
insert into alizon._vendeur (Prenom, Nom, Email) values ('Genovera', 'Ferrari', 'gferrarij@live.com');
insert into alizon._vendeur (Prenom, Nom, Email) values ('Stanley', 'Mantha', 'smanthak@youtu.be');
insert into alizon._vendeur (Prenom, Nom, Email) values ('Cassey', 'Connechie', 'cconnechiel@artisteer.com');
insert into alizon._vendeur (Prenom, Nom, Email) values ('Lyle', 'Truckett', 'ltruckettm@ycombinator.com');
insert into alizon._vendeur (Prenom, Nom, Email) values ('Dion', 'Rowlinson', 'drowlinsonn@aboutads.info');
insert into alizon._vendeur (Prenom, Nom, Email) values ('Starla', 'Birmingham', 'sbirminghamo@unesco.org');
insert into alizon._vendeur (Prenom, Nom, Email) values ('Michaelina', 'Flew', 'mflewp@nationalgeographic.com');
insert into alizon._vendeur (Prenom, Nom, Email) values ('Marilyn', 'Bennike', 'mbennikeq@cisco.com');
insert into alizon._vendeur (Prenom, Nom, Email) values ('Lane', 'Trengrouse', 'ltrengrouser@answers.com');
insert into alizon._vendeur (Prenom, Nom, Email) values ('Etan', 'Othick', 'eothicks@examiner.com');
insert into alizon._vendeur (Prenom, Nom, Email) values ('Bennie', 'Gotcliff', 'bgotclifft@jiathis.com');

INSERT into alizon._QuestionSecrete(question) values('Nom de jeune fille de la mère?');
INSERT into alizon._QuestionSecrete(question) values('Dans quelle ville êtes-vous né?');
INSERT into alizon._QuestionSecrete(question) values('Quel est le nom de votre animal de compagnie préféré ?');
INSERT into alizon._QuestionSecrete(question) values('Quel lycée as-tu fréquenté ?');
INSERT into alizon._QuestionSecrete(question) values('Quel était le nom de votre école primaire ?');
INSERT into alizon._QuestionSecrete(question) values('Quel était le fabricant de votre première voiture?');
INSERT into alizon._QuestionSecrete(question) values('Quelle était votre nourriture préférée quand vous étiez enfant ?');


-- mofif les reponce connard
INSERT into alizon._client (prenom_client, nom_client, adresse_facturation, date_de_naissance, email, mdp, ID_QuestionSecrete, QuestionReponse) values ('Garnet', 'Walcot', '048 Sheridan Pass', '2021/09/28', 'gwalcot0@addthis.com', '8nzGxoLmY',1,'etranger');
INSERT into alizon._client (prenom_client, nom_client, adresse_facturation, date_de_naissance, email, mdp, ID_QuestionSecrete, QuestionReponse) values ('Phil', 'Laughren', '4264 Burning Wood Crossing', '2017-08-17', 'plaughren1@ucla.edu', '3HHXeh',1,'chat');
INSERT into alizon._client (prenom_client, nom_client, adresse_facturation, date_de_naissance, email, mdp, ID_QuestionSecrete, QuestionReponse) values ('Jasen', 'Henrique', '5 Hollow Ridge Road', '1990-06-01', 'jhenrique2@w3.org', 'CJrtgHY',2,'sylvie');
INSERT into alizon._client (prenom_client, nom_client, adresse_facturation, date_de_naissance, email, mdp, ID_QuestionSecrete, QuestionReponse) values ('Lusa', 'Iddens', '40539 Carioca Court', '2006-02-04', 'liddens3@netlog.com', '9Xqam72Eqyr',2,'mamoune');
INSERT into alizon._client (prenom_client, nom_client, adresse_facturation, date_de_naissance, email, mdp, ID_QuestionSecrete, QuestionReponse) values ('Germain', 'Held', '35 Lien Street', '2011-03-03', 'gheld4@behance.net', 'GczFv5HNBDo',2,'elise');
INSERT into alizon._client (prenom_client, nom_client, adresse_facturation, date_de_naissance, email, mdp, ID_QuestionSecrete, QuestionReponse) values ('Koralle', 'Croyser', '2649 Golden Leaf Junction', '1984-03-04', 'kcroyser5@mozilla.com', '2U0sHz',3,'evoli');
INSERT into alizon._client (prenom_client, nom_client, adresse_facturation, date_de_naissance, email, mdp, ID_QuestionSecrete, QuestionReponse) values ('Jacinta', 'Yanshonok', '978 Shopko Crossing', '1971-04-30', 'jyanshonok6@lulu.com', 'CEs31QDCFR',3,'pikachu');
INSERT into alizon._client (prenom_client, nom_client, adresse_facturation, date_de_naissance, email, mdp, ID_QuestionSecrete, QuestionReponse) values ('Neddy', 'Prayer', '58 Chive Junction', '1990-01-11', 'nprayer7@amazonaws.com', '0QxsJQgPIbb',3,'ronflex');
INSERT into alizon._client (prenom_client, nom_client, adresse_facturation, date_de_naissance, email, mdp, ID_QuestionSecrete, QuestionReponse) values ('Jefferson', 'Gomez', '09 Cordelia Crossing', '1990-07-09', 'jgomez8@oakley.com', 'n2Q6ue33xlg',4,'Big Papoo');
INSERT into alizon._client (prenom_client, nom_client, adresse_facturation, date_de_naissance, email, mdp, ID_QuestionSecrete, QuestionReponse) values ('Susette', 'Kellart', '2111 Hoffman Road', '2007-08-28', 'skellart9@icq.com', 'E5hi4929emm',4,'Genaivre non je deconne');
INSERT into alizon._client (prenom_client, nom_client, adresse_facturation, date_de_naissance, email, mdp, ID_QuestionSecrete, QuestionReponse) values ('Jere', 'Wilshaw', '50832 Sloan Place', '1978-06-15', 'jwilshawa@bloomberg.com', 'WDlXDKEEA',4,'Vialat');
INSERT into alizon._client (prenom_client, nom_client, adresse_facturation, date_de_naissance, email, mdp, ID_QuestionSecrete, QuestionReponse) values ('Carly', 'Mayhead', '0 Arizona Parkway', '1987-04-06', 'cmayheadb@csmonitor.com', 'xb6LYdcdSBV',4,'Dubois UwU');
INSERT into alizon._client (prenom_client, nom_client, adresse_facturation, date_de_naissance, email, mdp, ID_QuestionSecrete, QuestionReponse) values ('Daria', 'Rolse', '7946 Morningstar Terrace', '2009-03-08', 'drolsec@reference.com', 'gk4eCDtZpKm',1,'renard');
INSERT into alizon._client (prenom_client, nom_client, adresse_facturation, date_de_naissance, email, mdp, ID_QuestionSecrete, QuestionReponse) values ('Oliver', 'Ockleshaw', '1 Rutledge Court', '2000-09-21', 'oockleshawd@japanpost.jp', 'qi3qimu',1,'femme');
INSERT into alizon._client (prenom_client, nom_client, adresse_facturation, date_de_naissance, email, mdp, ID_QuestionSecrete, QuestionReponse) values ('Lila', 'Hymus', '4105 Westerfield Parkway', '1990-02-14', 'lhymuse@jigsy.com', 'GHMZ30WXm',2,'Grand-mére');



INSERT into alizon._categorie (nom_categorie) values ('Epicerie');
INSERT into alizon._categorie (nom_categorie) values ('Vetements');
INSERT into alizon._categorie (nom_categorie) values ('Souvenirs');
INSERT into alizon._categorie (nom_categorie) values ('Produits frais');


INSERT INTO alizon._sous_categorie(id_categorie_sup,nom,tva) VALUES(1,'Gateaux',0.20);
INSERT INTO alizon._sous_categorie(id_categorie_sup,nom,tva) VALUES(1,'Déjeuner',0.20);
INSERT INTO alizon._sous_categorie(id_categorie_sup,nom,tva) VALUES(2,'Pull',0.20);
INSERT INTO alizon._sous_categorie(id_categorie_sup,nom,tva) VALUES(2,'Pantalons',0.20);
INSERT INTO alizon._sous_categorie(id_categorie_sup,nom,tva) VALUES(2,'Vêtements de pluie',0.20);
INSERT INTO alizon._sous_categorie(id_categorie_sup,nom,tva) VALUES(3,'Poster',0.20);
INSERT INTO alizon._sous_categorie(id_categorie_sup,nom,tva) VALUES(3,'Cartes postales',0.20);
INSERT INTO alizon._sous_categorie(id_categorie_sup,nom,tva) VALUES(3,'Portes clefs',0.20);
INSERT INTO alizon._sous_categorie(id_categorie_sup,nom,tva) VALUES(4,'Poissons',0.20);
INSERT INTO alizon._sous_categorie(id_categorie_sup,nom,tva) VALUES(4,'Viande',0.20);



INSERT INTO alizon._produit(nom_produit,prix_coutant,prix_vente_ht,quantite_disponnible,description_produit,id_sous_categorie,id_vendeur)VALUES('kouign amann',10.00,19.99,100,'Maecenas tincidunt lacus at velit. Vivamus vel nulla eget eros elementum pellentesque. Quisque porta volutpat erat.',1,1);
INSERT INTO alizon._produit(nom_produit,prix_coutant,prix_vente_ht,quantite_disponnible,description_produit,id_sous_categorie,id_vendeur)VALUES('Breizh gaufrettes à la vanille - 175g',3.00,4.99,80,'Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec pharetra, magna vestibulum aliquet ultrices, erat tortor sollicitudin mi, sit amet lobortis sapien sapien non mi. Integer ac neque. Duis bibendum.',1,1);
INSERT INTO alizon._produit(nom_produit,prix_coutant,prix_vente_ht,quantite_disponnible,description_produit,id_sous_categorie,id_vendeur)VALUES('Miel Breton toutes fleurs Pot 125g',3.00,4.99,48,'Etiam faucibus cursus urna. Ut tellus. Nulla ut erat id mauris vulputate elementum. Nullam varius.',2,2);
INSERT INTO alizon._produit(nom_produit,prix_coutant,prix_vente_ht,quantite_disponnible,description_produit,id_sous_categorie,id_vendeur)VALUES('Beurre salé - 500g',4.00,7.35,350,'Nulla ut erat id mauris vulputate elementum.',2,3);
INSERT INTO alizon._produit(nom_produit,prix_coutant,prix_vente_ht,quantite_disponnible,description_produit,id_sous_categorie,id_vendeur)VALUES('Caramel au beurre salé - 500ml',30.00,49.50,100,'Etiam justo. Etiam pretium iaculis justo. In hac habitasse platea dictumst. Etiam faucibus cursus urna. Ut tellus.',2,4);
INSERT INTO alizon._produit(nom_produit,prix_coutant,prix_vente_ht,quantite_disponnible,description_produit,id_sous_categorie,id_vendeur)VALUES('Crêpes dentelle – Cœur Cacao Noisettes 90g',1.00,1.99,350,'Nulla ut erat id mauris vulputate elementum.',1,1);
INSERT INTO alizon._produit(nom_produit,prix_coutant,prix_vente_ht,quantite_disponnible,description_produit,id_sous_categorie,id_vendeur)VALUES('Crêpes Bretonnes Pur Beurre 360g',7.00,11.50,100,'Etiam justo. Etiam pretium iaculis justo. In hac habitasse platea dictumst. Etiam faucibus cursus urna. Ut tellus.',1,1);
INSERT INTO alizon._produit(nom_produit,prix_coutant,prix_vente_ht,quantite_disponnible,description_produit,id_sous_categorie,id_vendeur)VALUES('Maquereau, poivron jaune, piment d’Espelette 90g',8.50,11.25,350,'Nulla ut erat id mauris vulputate elementum.',9,9);
INSERT INTO alizon._produit(nom_produit,prix_coutant,prix_vente_ht,quantite_disponnible,description_produit,id_sous_categorie,id_vendeur)VALUES('Palette demi-sel BIO VPF 500g',15.00,23.50,100,'Etiam justo. Etiam pretium iaculis justo. In hac habitasse platea dictumst. Etiam faucibus cursus urna. Ut tellus.',10,10);
INSERT INTO alizon._produit(nom_produit,prix_coutant,prix_vente_ht,quantite_disponnible,description_produit,id_sous_categorie,id_vendeur)VALUES('Sweet Alive M',15,22.33,0,'Nulla ut erat id mauris vulputate elementum.',3,3);
INSERT INTO alizon._produit(nom_produit,prix_coutant,prix_vente_ht,quantite_disponnible,description_produit,id_sous_categorie,id_vendeur)VALUES('Poster nolwenn leroy',5.00,6.50,100,'Etiam justo. Etiam pretium iaculis justo. In hac habitasse platea dictumst. Etiam faucibus cursus urna. Ut tellus.',6,15);
INSERT INTO alizon._produit(nom_produit,prix_coutant,prix_vente_ht,quantite_disponnible,description_produit,id_sous_categorie,id_vendeur)VALUES('Portes clefs bien clichés',45.00,74.50,100,'Etiam justo. Etiam pretium iaculis justo. In hac habitasse platea dictumst. Etiam faucibus cursus urna. Ut tellus.',8,6);


INSERT INTO alizon._contient_produit_p(id_panier,id_produit,quantite)VALUES(1,1,10);
INSERT INTO alizon._contient_produit_p(id_panier,id_produit,quantite)VALUES(12,3,2);
INSERT INTO alizon._contient_produit_p(id_panier,id_produit,quantite)VALUES(7,5,5);
INSERT INTO alizon._contient_produit_p(id_panier,id_produit,quantite)VALUES(7,1,12);
INSERT INTO alizon._contient_produit_p(id_panier,id_produit,quantite)VALUES(7,2,3);

INSERT INTO alizon._contient_produit_p(id_panier,id_produit,quantite)VALUES(2,5,5);
INSERT INTO alizon._contient_produit_p(id_panier,id_produit,quantite)VALUES(2,1,7);
INSERT INTO alizon._contient_produit_p(id_panier,id_produit,quantite)VALUES(2,2,3);
INSERT INTO alizon._commande(ID_Client, etat_commande, adresse_livraison)VALUES(2, 'en cours', 'Résidence Lannion');


INSERT INTO _avis (ID_Client, ID_Produit, Note_Produit, Commentaire, Image_Avis) VALUES ('1', '1', '5', 'Maecenas tincidunt lacus at velit. Vivamus vel nulla eget eros elementum', NULL);
INSERT INTO _avis (ID_Client, ID_Produit, Note_Produit, Commentaire, Image_Avis) VALUES ('2', '1', '4', 'Etiam faucibus cursus urna. Ut tellus. Nulla ut erat id mauris', NULL);
INSERT INTO _avis (ID_Client, ID_Produit, Note_Produit, Commentaire, Image_Avis) VALUES ('3', '3', '2', 'Justo. Etiam pretium iaculis justo.entaire', NULL);
INSERT INTO _avis (ID_Client, ID_Produit, Note_Produit, Commentaire, Image_Avis) VALUES ('1', '3', '3', 'Vivamus vel nulla eget eros elementum pellentesque.', NULL);
INSERT INTO _avis (ID_Client, ID_Produit, Note_Produit, Commentaire, Image_Avis) VALUES ('2', '2', '4', 'Maecenas tincidunt lacus', NULL);
INSERT INTO _avis (ID_Client, ID_Produit, Note_Produit, Commentaire, Image_Avis) VALUES ('3', '1', '5', 'Justo', NULL);

INSERT INTO _signaler(ID_Commentaire,ID_Signaleur) VALUES(1,1);
INSERT INTO _signaler(ID_Commentaire,ID_Signaleur) VALUES(1,2);
INSERT INTO _signaler(ID_Commentaire,ID_Signaleur) VALUES(2,3);
INSERT INTO _signaler(ID_Commentaire,ID_Signaleur) VALUES(1,10);