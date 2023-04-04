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

-- drop schema Alizon;
-- create schema Alizon;
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

/* TABLE CLIENT */
CREATE TABLE Alizon._Client(
  ID_Client int(11) auto_increment not null,
  nom_client varchar(50) not null,
  prenom_client varchar(50) not null,
  date_de_naissance date not null,
  email varchar(50) not null,
  mdp varchar(500) not null, 
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
  adresse_facturation BOOLEAN not null,
  constraint _Adresse_pk primary key (ID_Adresse),
  constraint _Adresse_Client_fk foreign key (ID_Client)
    references Alizon._Client(ID_Client)
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
  nom_de_rue_livraison varchar(50) not null, 
  complement_livraison varchar(50), 
  ville_livraison varchar(50) not null,
  code_postale_livraison varchar(10) not null,
  nom_de_rue_facturation varchar(50) not null, 
  complement_facturation varchar(50), 
  ville_facturation varchar(50) not null,
  code_postale_facturation varchar(10) not null,
  date_commande date ,
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
  etat_produit_c VARCHAR(50),
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

/* TABLE VENDEUR */
CREATE TABLE Alizon._Vendeur (
  ID_Vendeur int(11) auto_increment not null,
  Nom_vendeur varchar(50) not null,
  Raison_sociale varchar(50) not null,
  Email varchar(50) not null,
  nom_de_rue varchar(50) not null, 
  complement varchar(50), 
  ville varchar(50) not null,
  code_postale varchar(10) not null,
  TVA varchar(50) not null,
  Siret varchar(50) not null,
  mdp varchar(100) not null,
  logo varchar(500),
  texte_Presentation varchar(1000),
  note varchar(1000),
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

Aprés avoir insert dans _contient_produit_c le trigger suprime le pannier du client XAlizon
*/
CREATE TRIGGER trig_Commande3
after INSERT on Alizon._Commande
FOR EACH ROW
BEGIN
set @i =1 ;
while @i <= (select max(id_produit) from _Produit) do
	set @id_produit = (select id_produit from _Contient_Produit_p where _contient_produit_p.ID_Panier in (select ID_Panier from _panier where ID_Client = new.ID_Client) and _contient_produit_p.id_produit =@i);
	set @Quantite = (select Quantite from _Contient_Produit_p where _contient_produit_p.ID_Panier in (select ID_Panier from _panier where ID_Client = new.ID_Client) and _contient_produit_p.id_produit =@i);
	set @Prix_produit_commande_HT = (select Prix_produit_commande_HT from _Contient_Produit_p where _contient_produit_p.ID_Panier in (select ID_Panier from _panier where ID_Client = new.ID_Client) and _contient_produit_p.id_produit =@i);
	set @Prix_produit_commande_TTC = (select Prix_produit_commande_TTC from _Contient_Produit_p where _contient_produit_p.ID_Panier in (select ID_Panier from _panier where ID_Client = new.ID_Client) and _contient_produit_p.id_produit =@i);
	if @id_produit is not null then
		INSERT INTO Alizon._contient_produit_c(ID_Commande,id_produit, etat_produit_c, quantite,Prix_produit_commande_HT,Prix_produit_commande_TTC)VALUES(new.Id_Commande,@id_produit, "acceptee",@Quantite,@Prix_produit_commande_HT,@Prix_produit_commande_TTC);
	END IF;
	set @i = @i+1;
END WHILE;
DELETE from Alizon._Panier where ID_Client = New.ID_Client;
INSERT INTO Alizon._panier (id_client,prix_total_ht,prix_total_ttc)VALUES(new.id_client,0.00,0.00);
END;
@@;

/* -Lors de la creation d'un client 
Lors de la creation d'un nouveau client le trigger lui assigne un nouveau pannier
*/
CREATE TRIGGER trig_Pannier
after INSERT on Alizon._Client
FOR EACH ROW
BEGIN
INSERT INTO Alizon._panier (id_client,prix_total_ht,prix_total_ttc)VALUES(new.id_client,0.00,0.00);
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

Debut boucle la boucle continuras tant que @i n'est pas arriver au plus grand @id de la classe _panier ou quand le bolean modif sera a true

Si le nombre de panier pour ID_Panier @i = 0 alors id est libre et le set dans le new.panier

Fin boucle
*/
CREATE TRIGGER trig_id_panier
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
CREATE TRIGGER trig_TVA_Produit
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
set @prixht := (SELECT prix_vente_ht FROM Alizon._produit WHERE _produit.id_produit = NEW.id_produit);
set @prixttc := (SELECT prix_vente_ttc FROM Alizon._produit WHERE _produit.id_produit = NEW.id_produit);
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
set @prixht := (SELECT prix_vente_ht FROM Alizon._produit WHERE _produit.id_produit = NEW.id_produit);
set @prixttc := (SELECT prix_vente_ttc FROM Alizon._produit WHERE _produit.id_produit = NEW.id_produit);
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
SET @Somprixht := (SELECT sum(prix_produit_commande_ht) FROM Alizon._contient_produit_p WHERE id_panier = NEW.id_panier);
SET @Somprixttc := (SELECT sum(prix_produit_commande_ttc) FROM Alizon._contient_produit_p WHERE id_panier = NEW.id_panier);
UPDATE Alizon._panier SET prix_total_ht = @Somprixht, prix_total_ttc = @Somprixttc, derniere_modif = NOW() WHERE id_panier = NEW.id_panier;
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
SET @Somprixht := (SELECT sum(prix_produit_commande_ht) FROM Alizon._contient_produit_p WHERE id_panier = NEW.id_panier);
SET @Somprixttc := (SELECT sum(prix_produit_commande_ttc) FROM Alizon._contient_produit_p WHERE id_panier = NEW.id_panier);
UPDATE Alizon._panier SET prix_total_ht = @Somprixht, prix_total_ttc = @Somprixttc, derniere_modif = NOW() WHERE id_panier = NEW.id_panier;
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
SET @Somprixht := (SELECT sum(prix_produit_commande_ht) FROM Alizon._contient_produit_p WHERE id_panier = old.id_panier);
SET @Somprixttc := (SELECT sum(prix_produit_commande_ttc) FROM Alizon._contient_produit_p WHERE id_panier = old.id_panier);
UPDATE Alizon._panier SET prix_total_ht = @Somprixht, prix_total_ttc = @Somprixttc, derniere_modif = NOW() WHERE id_panier = old.id_panier;
END;
@@;

/* -Lors de l'ajout d'un signalment
Ce trigger compte le nombre de signalement

Variables:
@nbs nombre de signalement avant l'ajout du nouveau

set les total de signalement dans avis
*/
CREATE trigger trig_Calcul_Signalement
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
CREATE trigger trig_Commande_date
before insert on Alizon._Commande
for each row
BEGIN
set @date = (SELECT curdate());
set new.date_commande = @date;
set new.date_livraison = (select date_sub(curdate(),interval -30 day));
set new.Duree_maximale_restante = (SELECT DATEDIFF(new.date_livraison, new.date_commande));
set new.prix_total = (select sum(Prix_produit_commande_TTC) from _Contient_Produit_p where _contient_produit_p.ID_Panier in (select ID_Panier from _panier where ID_Client = new.ID_Client));
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
before INSERT on Alizon._avis
FOR EACH ROW
BEGIN
set @i =1;
set @modif =0;
if (select count(*) from Alizon._avis where ID_Client=new.ID_Client and ID_Produit = new.ID_Produit) = 0 then
while @i< (select max(ID_Commentaire) from Alizon._avis) && @modif = 0 do
	if (select count(*) from Alizon._avis where ID_Commentaire = @i) = 0 then
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
SELECT nom_produit,images1, images2, images3, _contient_produit_p.id_produit,nom_categorie,quantite_disponnible,quantite,prix_total_ttc, prix_produit_commande_ttc, Nom_vendeur as vendeur , description_produit, _panier.id_panier, prix_vente_ttc, _Sous_Categorie.nom as nom_SouCategorie  FROM Alizon._contient_produit_p 
INNER JOIN Alizon._produit ON Alizon._produit.id_produit = Alizon._contient_produit_p.id_produit
INNER JOIN Alizon._Sous_Categorie ON Alizon._Sous_Categorie.Id_Sous_Categorie = Alizon._produit.id_sous_categorie
INNER JOIN Alizon._categorie ON Alizon._categorie.id_categorie = Alizon._Sous_Categorie.Id_categorie_sup
INNER JOIN Alizon._vendeur ON Alizon._vendeur.id_vendeur = Alizon._produit.id_vendeur
INNER JOIN Alizon._panier ON Alizon._contient_produit_p.id_panier = Alizon._panier.id_panier;




CREATE OR REPLACE View Alizon.catalogue AS
SELECT id_produit, nom_produit, prix_vente_ht, prix_vente_ttc, quantite_disponnible, description_produit, images1, images2, images3,ID_Vendeur, nom as nom_souscategorie, nom_categorie, moyenne_note_produit FROM Alizon._produit
INNER JOIN Alizon._Sous_Categorie ON Alizon._Sous_Categorie.Id_Sous_Categorie = Alizon._produit.id_sous_categorie
INNER JOIN Alizon._Categorie ON Alizon._Categorie.id_categorie = Alizon._Sous_Categorie.id_categorie_sup;



/* ###################################################################################################
######################################## INSERTION DE DONNEES ########################################
######################################################################################################*/

insert into Alizon._vendeur (Nom_vendeur,Raison_sociale,Email,nom_de_rue,complement,ville,code_postale,TVA,Siret,mdp,logo,texte_Presentation,note) values ('Totale','TotaleProduit', 'totale@gmail.com', '1 rue des potillé',NULL,'Ecommoy',72220, 'FR59542051180','542051180','277 474 277 605 426 312 407 268 442 605 117 426 23 471 474 442','img1','TOTAL est la 4ème compagnie de denrée alimentaire Bretonne (sur la base de la capitalisation boursière). aval ( trading et transport maritime de produits alimentaires et de produits agricoles, distribution).','Totale est une entreprise Bretonne totalement écologique, qui cultivie des produits BIO sans pesticide');
insert into Alizon._vendeur (Nom_vendeur,Raison_sociale,Email,nom_de_rue,complement,ville,code_postale,TVA,Siret,mdp,logo,texte_Presentation,note) values ('Alpinne','AlpinneCorp', 'alpinne@gmail.com', '24 rue des voitures',NULL,'Le mans',72220, 'FR34489786343','489786343','605 426 224 117 384 384 312 407 268 442 605 117 426 23 471 474 442','img1','Notre entreprise Alpine, appelée "Alpes Gourmandes", est spécialisée dans la vente de produits alimentaires de qualité issus de la région alpine. Nous travaillons en étroite collaboration avec des producteurs locaux pour offrir à nos clients des produits frais, savoureux et écologique','Alpine est une entreprise dynamique qui se dévoue à fournir des produits alimentaires de qualité supérieure à ses clients. En se concentrant sur les ingrédients locaux, Alpine offre des produits frais et de saison qui répondent aux normes les plus élevées en matière de qualité et de durabilité.');
insert into Alizon._vendeur (Nom_vendeur,Raison_sociale,Email,nom_de_rue,complement,ville,code_postale,TVA,Siret,mdp,logo,texte_Presentation,note) values ('Picardddd','PicardIndustrie', 'pica@gmail.com', '11 rue des surgelés',NULL,'Paris',75000, 'FR31784939688','784939688',' 224 117 471 605 407 268 442 605 117 426 23 471 474 442','img1','Picard est une entreprise bien établie dans le domaine des produits surgelés, qui se distingue par son large choix de produits de qualité. Avec plus de 1 000 produits différents, Picard offre une variété de plats, de desserts et de collations pour répondre aux besoins de ses clients.','Picard est une entreprise qui se dévoue à fournir des produits de qualité exceptionnelle à ses clients. Grâce à sa large sélection de produits,picardddd offre une grande variété de denrée pour tous les goûts et tous les besoins.');
insert into Alizon._vendeur (Nom_vendeur,Raison_sociale,Email,nom_de_rue,complement,ville,code_postale,TVA,Siret,mdp,logo,texte_Presentation,note) values ('LVP','LuisPasViton', 'lVP@gmail.com', 'Zone industrielle des peupliers','Batiment D','Marseille',13000, 'FR15347662454','347662454','426 189 567 407 268 442 605 117 426 23 471 474 442','img1','LuisPasViton est une entreprise renommée dans le domaine alimentaire, qui se dévoue à fournir des produits de qualité exceptionnelle à ses clients. Grâce à leur expertise en matière de luxe, Louis Vuitton offre une gamme de produits culinaires haut de gamme, qui allient qualité et raffinement.','LuisPasViton est une entreprise qui se dévoue à fournir une expérience gastronomique de luxe à ses clients. Grâce à leur savoir-faire et leur engagement envers la qualité, ils proposent une gamme de produits alimentaires haut de gamme qui allient saveurs raffinées et ingrédients de qualité exceptionnelle.');

insert into Alizon._vendeur (Nom_vendeur,Raison_sociale,Email,nom_de_rue,complement,ville,code_postale,TVA,Siret,mdp,logo,texte_Presentation,note) values ('Kribi', 'KribiCorp','Kribi@gmail.com', '7 Rue Jean Marie',NULL,'Plounévézels',29270, 'FR17327652457','276183130','407 277 474 277 474 277 605 277 605','img1','Bienvenue chez Le Kribi, votre destination de choix pour des vêtements de Bretagne de haute qualité. Nous sommes une entreprise fièrement bretonne, spécialisée dans la vente de vêtements inspirés de la culture et des traditions de notre belle région.Notre collection de vêtements pour hommes, femmes et enfants est conçue avec soin pour offrir un confort optimal tout en mettant en avant le patrimoine breton. Nous proposons des designs modernes et élégants, ainsi que des pièces plus traditionnelles qui célèbrent les symboles et les légendes de la Bretagne.Chez Le Kribi, nous sommes attachés à la qualité de nos produits et à la satisfaction de nos clients. Nous travaillons avec des fournisseurs locaux pour garantir que nos vêtements sont fabriqués avec les meilleurs matériaux et les techniques les plus avancées. Nous sommes également engagés dans des pratiques éthiques et durables pour préserver notre environnement.Nous sommes fiers de faire partie de la communauté bretonne et sommes heureux de partager notre passion pour notre culture à travers nos vêtements. Nous espérons que vous trouverez chez Le Kribi des pièces qui vous accompagneront avec fierté dans votre quotidien.',"Nous sommes heureux de vous annoncer que notre entreprise, Le Kribi, se spécialise désormais dans la vente de vêtements bretons. Notre passion pour la Bretagne et son patrimoine nous a poussés à créer une collection unique qui saura satisfaire vos besoins en matière de mode et d'authenticité.");



INSERT INTO Alizon._client (nom_client, prenom_client, date_de_naissance, email, mdp, QuestionReponse) VALUES ('Portier', 'Loane', '2003-12-15', 'paprika@gmail.com', '224 605 224 321 117 19 605 407 268 442 605 117 426 23 471 474 442', 'Gardeur');
INSERT INTO Alizon._client (nom_client, prenom_client, date_de_naissance, email, mdp, QuestionReponse) VALUES ('Titouan', 'Laughren', '2002-05-24', 'TitouanRobe@gmail.com', '548 117 277 474 3 605 384 669 474 501 312 407 268 442 605 117 426 23 471 474 442', 'Rennes');
INSERT INTO Alizon._client (nom_client, prenom_client, date_de_naissance, email, mdp, QuestionReponse) VALUES ('Maincent', 'Oscar', '2003-01-05', 'OscarMaincent@gmail.com', '548 117 277 474 3 605 384 669 474 501 312 407 268 442 605 117 426 23 471 474 442', 'Saint-Martin');
INSERT INTO Alizon._client (nom_client, prenom_client, date_de_naissance, email, mdp, QuestionReponse) VALUES ('Demany', 'Theo', '2003-10-01', 'TheoDemany@gmail.com', '548 261 312 474 68 312 442 605 384 386 407 268 442 605 117 426 23 471 474 442', 'Audi');

INSERT into Alizon._Adresse (ID_Client,nom_de_rue, complement, ville, code_postale, adresse_facturation) values (1,"1 Rue édouard Branly",NULL,"Lannion","22300",TRUE);
INSERT into Alizon._Adresse (ID_Client,nom_de_rue, complement, ville, code_postale, adresse_facturation) values (1,"11 chemin de traverse",NULL,"Guingamp","22200",TRUE);
INSERT into Alizon._Adresse (ID_Client,nom_de_rue, complement, ville, code_postale, adresse_facturation) values (2,"45 Avenue Fosh","11e arrondissement","Paris","75111",TRUE);
INSERT into Alizon._Adresse (ID_Client,nom_de_rue, complement, ville, code_postale, adresse_facturation) values (3,"29 rue cherbourg","3e Arrondissement","Paris","75003",TRUE);
INSERT into Alizon._Adresse (ID_Client,nom_de_rue, complement, ville, code_postale, adresse_facturation) values (4,"Résidence de la haute rive","Batiment D","Lannion","22300",TRUE);


INSERT into Alizon._categorie (nom_categorie) values ('Epicerie');
INSERT into Alizon._categorie (nom_categorie) values ('Boissons');
INSERT into Alizon._categorie (nom_categorie) values ('Vêtements');
INSERT into Alizon._categorie (nom_categorie) values ('Souvenirs');
INSERT into Alizon._categorie (nom_categorie) values ('Produits frais');


INSERT INTO Alizon._sous_categorie(id_categorie_sup,nom,tva) VALUES(1,'Gateaux',0.10);
INSERT INTO Alizon._sous_categorie(id_categorie_sup,nom,tva) VALUES(1,'Déjeuner',0.10);

INSERT INTO Alizon._sous_categorie(id_categorie_sup,nom,tva) VALUES(2,'Soda',0.10);
INSERT INTO Alizon._sous_categorie(id_categorie_sup,nom,tva) VALUES(2,'Jus de fruits',0.10);
INSERT INTO Alizon._sous_categorie(id_categorie_sup,nom,tva) VALUES(2,'Boissons alcoolisés',0.20);

INSERT INTO Alizon._sous_categorie(id_categorie_sup,nom,tva) VALUES(3,'Pull',0.20);
INSERT INTO Alizon._sous_categorie(id_categorie_sup,nom,tva) VALUES(3,'Vêtements de pluie',0.20);
INSERT INTO Alizon._sous_categorie(id_categorie_sup,nom,tva) VALUES(3,'Accessoires',0.20);

INSERT INTO Alizon._sous_categorie(id_categorie_sup,nom,tva) VALUES(4,'Bolée',0.20);
INSERT INTO Alizon._sous_categorie(id_categorie_sup,nom,tva) VALUES(4,'Cartes postales',0.20);
INSERT INTO Alizon._sous_categorie(id_categorie_sup,nom,tva) VALUES(4,'Portes clefs',0.20);

INSERT INTO Alizon._sous_categorie(id_categorie_sup,nom,tva) VALUES(5,'Poissons',0.0550);
INSERT INTO Alizon._sous_categorie(id_categorie_sup,nom,tva) VALUES(5,'Viande',0.0550);


INSERT INTO Alizon._produit( `Nom_produit`, `Prix_coutant`, `Prix_vente_HT`, `Prix_vente_TTC`, `Quantite_disponnible`, `Description_produit`, `images1`, `images2`, `images3`, `Moyenne_Note_Produit`, `Id_Sous_Categorie`, `ID_Vendeur`) VALUES
("Kouign-amann", "10.00", "18.71", "22.71", 71, "Notre kouign-amann est préparé selon une recette traditionnelle, qui lui confère sa texture fondante et son goût riche et savoureux. Il est fabriqué avec du beurre salé de qualité supérieure, qui lui donne son goût unique et sa texture moelleuse.", "img1.jpg", "img2.jpg", "img3.jpg", 5, 1, 3),
("Quatre-Quarts", "4.80", "7.10", "8.35", 35, "Notre quatre-quarts réalisé en petite ou grande barre, un régal au goûter, ce gâteau complètement barré saura satisfaire les petits (et les grands) gourmands.", "img1.jpg", "img2.jpg", NULL, 5, 1, 3),
("Miel Breton toutes fleurs", "20.00", "29.20", "31.20", 120, "Récolté à la main et avec soin par des apiculteurs passionnés, notre miel toutes fleurs breton de 125g est doté d'une texture onctueuse et d'une couleur dorée, caractéristiques de sa qualité exceptionnelle. Il est également riche en nutriments et en propriétés médicinales, pour vous offrir un choix de santé et de bien-être.", "img1.jpg", "img2.jpg", "img3.jpg", NULL, 2, 2),
("Beurre salé", "3.00", "6.55", "7,55 ", 55, "Notre beurre salé de 500g est fabriqué à partir de lait de qualité supérieure, provenant de vaches élevées dans des pâturages verts et sains. Son goût crémeux et sa texture fondante en font le choix parfait pour les amateurs de beurre.", "img1.jpg", "img2.jpg", "img3.jpg", NULL, 2, 2),
("Caramel au beurre salé", "10.00", "12,90", "15,90 ", 90, "Notre caramel au beurre salé de 500ml est fabriqué selon une recette artisanale,qui lui confère son goût unique et sa texture crémeuse. Il est enrichi en beurre salé de qualité supérieure, pour vous offrir une expérience gustative riche et savoureuse.", "img1.jpg", "img2.jpg", NULL, NULL, 2, 1),
("Crêpe Dentelle cœur cacao noisette", "1.00", "1.99", "2.39", 148, "Nos crêpes dentelle de 90g sont fabriquées selon une recette artisanale,qui leur confère leur texture croustillante et légère. Elles sont garnies d'un cœur fondant au cacao noisettes, qui apporte une touche de gourmandise supplémentaire à chaque bouchée.", "img1.jpg", "img2.jpg", "img3.jpg", NULL, 2, 4),
("Cola bien frais - 2,5L", "3.00", "3.99", "4.99", 380, "Dégustez la fraîcheur absolue avec notre cola bien frais ! Notre boisson pétillante et rafraîchissante est le choix parfait pour vous rafraîchir lors de journées chaudes et pour étancher votre soif.", "img1.jpg", "img2.jpg", "img3.jpg", NULL, 3, 4),
("Breizh Thé - 1,5L", "0.50", "1.10", "1.55", 210, "Une boisson fraîche au bon goût de thé et de pêche.Son secret ? Une véritable infusion de thé noir qui donne à ce thé glacé une saveur unique.", "img1.jfif", NULL, NULL, NULL, 3, 1),
("Breizh Agrume - 1,5L", "0.80", "1.20", "1.80", 126, "Une boisson fraîche aux arômes naturels d'agrumes, idéale pour l'été.Son secret ? De fines bulles et un goût subtil de pamplemousse.", "img1.jpg", NULL, NULL, NULL, 3, 1),
("Breizh Cola - 1,5L", "0.60", "1.16", "1.67", 367, "La recette originale, créée en 2002.Son secret ? Un dosage parfait des ingrédients, lui donnant de fines bulles.Un gout plébiscité par les consommateurs depuis 20 ans !", "img1.jfif", NULL, NULL, NULL, 3, 1),
("Breizh lim' - 1,5L", "0.40", "0.94", "1.40", 94, "Suivant l'authentique recette de la limonaderie Wittersheim créée à Brest, dans le Finistère, en 1905, Breizh Lim' perpétue un savoir faire traditionnel.", "img1.webp", NULL, NULL, NULL, 3, 1),
("Jus de Pomme Tradition - 1,6L", "2.20", "4.70", "5.99", 80, "Issu de plusieurs variétés de pommes saines biologiques récoltées à maturité optimale et de jus biologique concentré, notre jus de pomme frais biologique Tradition est le meilleur dans sa catégorie. Toutes les pommes ont été choisies, lavées, broyées et simplement pressées à froid. Comportant seulement les sucres naturels des pommes, ce jus sera un atout majeur dans une saine alimentation.", "img1.jpg", NULL, NULL, NULL, 4, 4),
("Jus de Pommes qui pétille - 75cl", "1.80", "4.00", "5.10", 46, "Une fabrication artisanale avec 100% de pur jus de pommes à cidre. Idéal pour fêter un évènement entre amis !", "img1.jpg", "img2.jpg",NULL, NULL, 4, 1),
("Liqueur de fraise de Plougastel - 70cl", "14.00", "21.90", "24.50", 24, "La liqueur de fraise de Plougastel  est le résultat entre un savoir-faire centenaire et un produit typique breton, au goût unique.  Une fabrication authentique puisque les fraises provenant de Plougastel sont macérées dans l'alcool avant d'aller dans un pressoir traditionnel en bois.", "img1.jpg", NULL, NULL, NULL, 5, 4),
("Breizh Whisky - 70cl", "18.00", "24.99", "39,30", 28, "Le Breizh Whisky est le parfait équilibre entre whisky de malt et whisky de grain, un vieillisement en fûts de chêne traditionnels, le tout sublimé par le climat si particulier de la Bretagne. Voici quelques uns des paramètres qui sont à l'origine de cet excellent whisky Breton blended.", "img1.jpg", "img2.jpg", "img3.jpg", NULL, 5, 4),
("Armorik Tradition BIO - 70cl", "26.50", "32,20", "43,40", 17, "Le Whisky Armorik Tradition Bio est fruité, malté et contient des notes épicées. Il est élaboré à partir d'un assemblage de fûts de bourbon et de sherry oloroso.Armorik, Whisky Breton Single Malt, est distillé, vieilli et embouteillé en Bretagne par la Distillerie Warenghem à Lannion, France.Elaboré à partir d'orge malté issue de l'agriculture biologique, double distillé dans nos alambics de cuivre et vieilli en fût de chêne, il est non filtré à froid et sans ajout de caramel, pour préserver son authenticité et ses arômes.", "img1.jpg", "img2.jpg", "img3.jpg", NULL, 5, 4),
("Bottes de pluie", "7.00", "11.50", "13.80", 89, "Nos bottes de pluie sont conçues pour être à la fois résistantes et confortables, avec une semelle antidérapante pour une meilleure adhérence sur les surfaces mouillées. Elles sont fabriquées à partir de caoutchouc naturel de qualité supérieure, qui les rendent imperméables et durables.", "img1.jpg", "img2.jpg", NULL, NULL, 7, 5),
("Bob circuit Paul Ricard", "15.00", "22.33", "26.80", 0, "Notre bob est fabriqué avec une coque en plastique résistant et un cadre en acier robuste, pour une durabilité et une solidité à toute épreuve. Il est également équipé d'un siège ergonomique et confortable, pour vous offrir une expérience de conduite agréable.", "img1.jpg", "img2.jpg", NULL, NULL, 8, 1),
("Bolée triskell marron sans anse", "4.10", "4.99", "6.80", 45, "Bolée triskell marron sans anse en grès véritable.","img1.jpg", "img2.jpg", NULL, NULL, 9, 2),
("Bolée sans anse 'Poisson noir'", "4.10", "4.99", "6.80", 68, "Bolée sans anse poisson noir en grès véritable.", "img1.jpg", "img2.jpg", NULL, NULL, 9, 2),
("Bolée triskell marron avec anse'", "4.15", "4.90", "6.90", 37, "Cette bolée à cidre typiquement bretonne apportera une touche traditionnelle à vos soirées crêpes & galettes. Elle est idéale pour déguster le cidre dans la plus pure tradition Bretonne !", "img1.jpg", "img2.jpg", NULL, NULL, 9, 2),
("Bolée avec anse 'Poisson noir'", "4.15", "4.90", "6.90", 61, "A votre prochaine soirée crêpes, pensez à notre bolée anse poisson noire pour déguster du cidre ou du jus de pomme. Des boissons traditionnelles qui peuvent être idéalement accompagnées à des crêpes et des galettes Bretonnes.", "img1.jpg", "img2.jpg", NULL, NULL, 9, 2),
("Porte-clé Breton", "2.00", "3.50", "4.50", 100, "Notre porte-clés breton mesure environ 7 cm de long et présente un motif en relief représentant un triskèle, symbole traditionnel de la Bretagne. Le porte-clés est également équipé d'un anneau en métal solide, pour une utilisation pratique et durable.", "img1.jpg", NULL, NULL, NULL, 11, 2),
("Maquereau, poivron jaune et piment d'espelette", "8.50", "9.25", "11.50", 350, "Notre maquereau est pêché dans les eaux froides et cristallines des côtes bretonnes, pour garantir sa qualité et sa fraîcheur. Il est ensuite accompagné de poivrons jaunes savoureux et de piment d'Espelette.", "img1.jpg", "img2.jpg", NULL, NULL, 12, 1),
("Palette demi-sel BIO VPF", "15.00", "23.50", "28.20", 100, "Notre palette est fabriquée selon des méthodes artisanales traditionnelles,qui lui confèrent une texture fondante et un goût riche et savoureux. Elle est demi-sel pour une saveur légèrement salée, qui se marie parfaitement avec les autres ingrédients en 500g.", "img1.jpg", "img2.jpg", NULL, NULL, 13, 3),
("Montre bretonne", "50", "800", "960", 50, "Donne l'heure et possède une très belle hermine", "img1.jpg", NULL, NULL, NULL, 8, 3);




INSERT INTO Alizon._panier(Prix_total_HT,Prix_total_TTC,ID_Client)VALUES(0,0,1);
INSERT INTO Alizon._panier(Prix_total_HT,Prix_total_TTC,ID_Client)VALUES(0,0,2);
INSERT INTO Alizon._panier(Prix_total_HT,Prix_total_TTC,ID_Client)VALUES(0,0,3);
INSERT INTO Alizon._panier(Prix_total_HT,Prix_total_TTC,ID_Client)VALUES(0,0,4);


INSERT INTO Alizon._contient_produit_p(id_panier,id_produit,quantite)VALUES(1,1,10);
INSERT INTO Alizon._contient_produit_p(id_panier,id_produit,quantite)VALUES(1,3,2);
INSERT INTO Alizon._contient_produit_p(id_panier,id_produit,quantite)VALUES(2,4,5);
INSERT INTO Alizon._contient_produit_p(id_panier,id_produit,quantite)VALUES(4,1,12);
INSERT INTO Alizon._contient_produit_p(id_panier,id_produit,quantite)VALUES(4,10,3);
INSERT INTO Alizon._contient_produit_p(id_panier,id_produit,quantite)VALUES(1,2,5);
INSERT INTO Alizon._contient_produit_p(id_panier,id_produit,quantite)VALUES(2,8,7);
INSERT INTO Alizon._contient_produit_p(id_panier,id_produit,quantite)VALUES(2,2,3);


INSERT INTO Alizon._commande(ID_Client, nom_de_rue_livraison, complement_livraison, ville_livraison, code_postale_livraison, nom_de_rue_facturation, complement_facturation, ville_facturation, code_postale_facturation)
VALUES(1, '1 Rue édouard Branly',NULL,'Lannion','22300','1 Rue des écoles',NULL,'Liez','85420');




INSERT INTO _avis (ID_Client, ID_Produit, Note_Produit, Commentaire, Image_Avis) VALUES ('1', '1', '5', 'Très bon produit, le prix est pas très élevé pas sa qualité', NULL);
INSERT INTO _avis (ID_Client, ID_Produit, Note_Produit, Commentaire, Image_Avis) VALUES ('2', '1', '4', 'Très apprécié ce produit qui me fait ravivé les papilles !', NULL);
INSERT INTO _avis (ID_Client, ID_Produit, Note_Produit, Commentaire, Image_Avis) VALUES ('3', '6', '2', 'Pas aimé du tous ! Trop chère pour la qualité, et il y avait trop de chocolat', NULL);
INSERT INTO _avis (ID_Client, ID_Produit, Note_Produit, Commentaire, Image_Avis) VALUES ('1', '2', '3', 'Moyen, je ne pense pas racheter ce produit car ça ne ma pas fait réver', NULL);
INSERT INTO _avis (ID_Client, ID_Produit, Note_Produit, Commentaire, Image_Avis) VALUES ('2', '6', '4', 'Bien aimé, le chocolat est onctueux, et la pate crocante !', NULL);
INSERT INTO _avis (ID_Client, ID_Produit, Note_Produit, Commentaire, Image_Avis) VALUES ('3', '1', '5', 'Parfait ! La livraison était rapide, et le produit était comme la photo', NULL);



INSERT INTO _signaler(ID_Commentaire,ID_Signaleur) VALUES(1,1);
INSERT INTO _signaler(ID_Commentaire,ID_Signaleur) VALUES(1,2);
INSERT INTO _signaler(ID_Commentaire,ID_Signaleur) VALUES(2,3);
INSERT INTO _signaler(ID_Commentaire,ID_Signaleur) VALUES(1,4);


INSERT INTO _reponse(ID_Commentaire, ID_Vendeur, Commentaire) VALUES ('3', '1', "Merci pour votre commentaire.");
