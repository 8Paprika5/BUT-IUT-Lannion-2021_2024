-- Active: 1666353908946@@127.0.0.1@5432@Alizon
drop schema if exists Alizon cascade;
Create schema Alizon;
set schema 'Alizon';


/* ################################################################################################
######################################## CREATION DE TABLES ########################################
###################################################################################################*/


/* TABLE CATEGORIE */
CREATE TABLE Alizon._Categorie (
  ID_Categorie serial not null,
  Nom_Categorie varchar(50),
  constraint _Categorie_pk primary key (ID_Categorie)
);

/* TABLE SOUS CATEGORIE */
CREATE TABLE Alizon._Sous_Categorie (
  Id_Sous_Categorie serial not null,
  Id_Categorie_Sup integer not null,
  nom varchar(50) not null,
  tva float not null,
  constraint Sous_Categorie_pk primary key (Id_Sous_Categorie),
  constraint _Sous_Categorie_Categorie foreign key (Id_Categorie_Sup)
    references Alizon._Categorie(ID_Categorie)
);

/* TABLE PRODUIT */
CREATE TABLE Alizon._Produit (
  ID_Produit serial not null,
  Nom_produit varchar(50),
  Prix_coutant decimal(5,2),
  Prix_vente_HT numeric(5,2),
  Prix_vente_TTC numeric(5,2),
  Quantite_disponnible int,
  Description_produit varchar(500),
  images1 varchar(500),
  images2 varchar(500),
  images3 varchar(500),
  Moyenne_Note_Produit integer,
  Id_Sous_Categorie integer not null,
  ID_Vendeur int not null,
  constraint _Produit_pk primary key (ID_Produit),
  constraint _Produit_Sous_Categorie_fk foreign key (Id_Sous_Categorie)
    references Alizon._Sous_Categorie(Id_Sous_Categorie)
);

/* TABLE CLIENT */
CREATE TABLE Alizon._Client (
  ID_Client serial not null,
  nom_client varchar(50),
  prenom_client varchar(50),
  adresse_facturation varchar(50),
  date_de_naissance date,
  email varchar(50),
  mdp varchar(50),
  constraint _Client_pk primary key (ID_Client)
);

/* TABLE PANIER */
CREATE TABLE Alizon._Panier (
  ID_Panier serial not null,
  Prix_total_HT numeric(5,2),
  Prix_total_TTC numeric(5,2),
  ID_Client integer,
  constraint _Panier_pk primary key (ID_Panier),
  constraint _Panier_Client_fk foreign key (ID_Client)
    references Alizon._Client(ID_Client)
);

/* TABLE COMMANDE */
CREATE TABLE Alizon._Commande (
  ID_Commande serial not null,
  ID_Client integer not null,
  etat_commande varchar(50),
  adresse_livraison varchar(50),
  date_livraison date,
  Duree_maximale_restante int,
  constraint _Commande_pk primary key (ID_Commande),
  constraint _Commande_Client_fk foreign key (ID_Client)
    references Alizon._Client (ID_Client)
);

/* TABLE CONTIENT PRODUIT PANIER */
CREATE TABLE Alizon._Contient_Produit_p (
  ID_Panier integer not null, -- Différent
  ID_Produit integer not null,
  Quantite integer,
  Prix_produit_commande_HT float not null,
  Prix_produit_commande_TTC float not null,
  constraint _Contient_Produit_p_pk primary key (ID_Panier,ID_Produit),
  constraint _Contient_Produit_p_Panier_fk foreign key (ID_Panier)
    references Alizon._Panier(ID_Panier),
  constraint _Contient_Produit_p_Produit_fk foreign key (ID_Produit)
    references Alizon._Produit(ID_Produit)
);

/* TABLE CONTIENT PRODUIT COMMANDE */
CREATE TABLE Alizon._Contient_Produit_c (
  ID_Commande integer not null, -- Différent
  ID_Produit integer not null,
  Quantite integer,
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
  ID_Client integer not null,
  ID_Produit integer not null,
  Note_Produit integer not null,
  Commentaire varchar(500),
  Image_Avis varchar(500),
  constraint _Avis_pk primary key (ID_Client,ID_Produit),
   constraint _Avis_Produit_fk foreign key (ID_Produit)
    references Alizon._Produit(ID_Produit),
  constraint _Avis_Client_fk foreign key (ID_Client)
    references Alizon._Client(ID_Client)
);

/* TABLE PAIEMENT */
CREATE TABLE Alizon._Paiement (
  ID_Commande integer not null,
  ID_Client integer not null,
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
  ID_Vendeur serial not null,
  Prenom varchar(50),
  Nom varchar(50),
  Email varchar(50),
  constraint _Vendeur_pk primary key (ID_Vendeur)
);


/* ##############################################################################################################
######################################## CREATION DE FONCTIONS & TRIGGER ########################################
#################################################################################################################*/

-- Lorsque le client creer un compte, le panier est créer
CREATE FUNCTION Alizon._Creation_Panier() returns trigger as 
$val$
  BEGIN
      INSERT INTO alizon._panier(id_client,prix_total_ht,prix_total_ttc)VALUES(new.id_client,0.00,0.00);
      RETURN NULL;
  END;
$val$ language plpgsql;

CREATE TRIGGER trig_CreationPanier
after INSERT on Alizon._Client
FOR EACH ROW
execute procedure Alizon._Creation_Panier();

-- Lorsque le client commande, le panier est supprimé
CREATE FUNCTION Alizon._Remplacement_Panier() returns trigger as 
$val$
  BEGIN
      DELETE from Alizon._Panier where ID_Client = Alizon._Commande.ID_Client;
      RETURN NULL;
  END;
$val$ language plpgsql;

CREATE TRIGGER trig_Remplacement_Panier
after INSERT on Alizon._Commande
FOR EACH ROW
execute procedure Alizon._Remplacement_Panier();

-- C
CREATE FUNCTION Alizon._Conservation_Quantite() returns trigger as $val$
  BEGIN 
     UPDATE Alizon._Contient_Produit_c SET (Quantite, Prix_produit_commande_HT, Prix_produit_commande_TTC) = (select Quantite, Prix_produit_commande_HT, Prix_produit_commande_TTC from _Contient_Produit inner join _Commande on _Contient_Produit_p.ID_Client = _Commande.ID_Client where new.ID_Client = _Contient_Produit_p.ID_Client) where
     new.ID_Client = Alizon._Content_Produit.ID_Client;
     RETURN NULL;
  END;
$val$ language plpgsql;

CREATE TRIGGER trig_Commande_2
after INSERT on Alizon._Commande
FOR EACH ROW
execute procedure Alizon._Conservation_Quantite();

-- TVA
CREATE FUNCTION Alizon._Insert_Prix_TTC() returns trigger as $val$
  DECLARE
    tva_n float;
  BEGIN 
    tva_n := (SELECT tva from Alizon._Sous_Categorie inner join Alizon._Produit on Alizon._Sous_Categorie.Id_Sous_Categorie = Alizon._produit.Id_Sous_Categorie WHERE Alizon._Produit.ID_Produit = new.ID_Produit); 
    UpDATE Alizon._Produit SET prix_vente_ttc = (tva_n*prix_vente_ht) + prix_vente_ht WHERE Alizon._Produit.ID_Produit = new.ID_Produit;  
    RETURN NULL;
  END;
$val$ language plpgsql;

CREATE TRIGGER trig_TVA_Produit
after INSERT on Alizon._Produit
FOR EACH ROW
execute procedure Alizon._Insert_Prix_TTC();  

-- Moyenne de la note du produit
CREATE FUNCTION Alizon.Calcul_Note_Produit() returns trigger as $val$
  DECLARE
    somme bigint;
    compte bigint;
  BEGIN 
    somme := (select sum(Note_Produit) from Alizon._Avis);
    compte := (select count(Note_Produit) from Alizon._Avis);
    UpDATE Alizon._Produit SET Moyenne_Note_Produit = (somme/compte) WHERE new.ID_Produit = ID_Produit;
    RETURN NULL;
  END;
$val$ language plpgsql;

CREATE TRIGGER trig_Moyenne_Note_Produit
after INSERT on Alizon._Avis
FOR EACH ROW
execute procedure Alizon.Calcul_Note_Produit();


-- Lorsque le client ajoute un produit ou modifie un quantité d'article dans le panier, le prix total pour cet article est mis a jour
CREATE FUNCTION Alizon._Calcul_PrixTotal_Produit() returns trigger as 
$val$
  DECLARE
    prixht FLOAT;
    prixttc FLOAT;
  BEGIN
      prixht := (SELECT prix_vente_ht FROM alizon._produit WHERE _produit.id_produit = NEW.id_produit);
      prixttc := (SELECT prix_vente_ttc FROM alizon._produit WHERE _produit.id_produit = NEW.id_produit);
      IF(TG_OP = 'INSERT') THEN
        INSERT INTO alizon._contient_produit_p VALUES(NEW.id_panier,NEW.id_produit,NEW.quantite,prixht*NEW.quantite,prixttc*NEW.quantite);
      ELSE
        UPDATE alizon._contient_produit_p SET quantite = NEW.quantite , prix_produit_commande_ht = prixht*NEW.quantite , prix_produit_commande_ttc = prixttc*NEW.quantite WHERE id_panier=NEW.id_panier AND id_produit = NEW.id_produit;
      END IF;
      RETURN NULL;
  END;
$val$ language plpgsql;

CREATE TRIGGER trig_Calcul_PrixTotal_Produit
BEFORE INSERT OR UPDATE OF quantite ON Alizon._contient_produit_p
FOR EACH ROW
WHEN (pg_trigger_depth() = 0)
execute procedure Alizon._Calcul_PrixTotal_Produit();
/*EXEMPLES : 
- INSERT INTO alizon._contient_produit_p(id_panier,id_produit,quantite)VALUES(7,5,5);
- UPDATE alizon._contient_produit_p SET quantite = 25 WHERE id_produit = 3;
*/


-- Lorsque le client ajoute un produits au panier, le total du panier est mis a jour
CREATE FUNCTION Alizon._Calcul_Total_Panier() returns trigger as 
$val$
  DECLARE
    Somprixht FLOAT;
    Somprixttc FLOAT;
  BEGIN
      Somprixht := (SELECT sum(prix_produit_commande_ht) FROM alizon._contient_produit_p WHERE id_panier = NEW.id_panier);
      Somprixttc := (SELECT sum(prix_produit_commande_ttc) FROM alizon._contient_produit_p WHERE id_panier = NEW.id_panier);
      UPDATE alizon._panier SET prix_total_ht = Somprixht, prix_total_ttc = Somprixttc WHERE id_panier = NEW.id_panier;
      RETURN NULL;
  END;
$val$ language plpgsql;

CREATE TRIGGER trig_Calcul_Total_Panier
AFTER INSERT OR UPDATE OR DELETE on Alizon._contient_produit_p
FOR EACH ROW
execute procedure Alizon._Calcul_Total_Panier();

/* ###############################################################################################
######################################## CREATION DE VUES ########################################
##################################################################################################*/


/* --------------VUE---------------*/
CREATE OR REPLACE View Alizon.panier AS
--
SELECT nom_produit,images1, images2, images3, _contient_produit_p.id_produit,nom_categorie,quantite_disponnible,quantite,prix_total_ttc, prix_produit_commande_ttc, prenom as vendeur , description_produit, _panier.id_panier, prix_vente_ttc, _Sous_Categorie.nom as nom_SouCategorie  FROM Alizon._contient_produit_p 
INNER JOIN Alizon._produit ON Alizon._produit.id_produit = Alizon._contient_produit_p.id_produit
INNER JOIN Alizon._Sous_Categorie ON Alizon._Sous_Categorie.Id_Sous_Categorie = Alizon._produit.id_sous_categorie
INNER JOIN Alizon._categorie ON Alizon._categorie.id_categorie = Alizon._Sous_Categorie.Id_categorie_sup
INNER JOIN Alizon._vendeur ON Alizon._vendeur.id_vendeur = Alizon._produit.id_vendeur
INNER JOIN Alizon._panier ON Alizon._contient_produit_p.id_panier = Alizon._panier.id_panier;




CREATE OR REPLACE View Alizon.catalogue AS
--
SELECT id_produit, nom_produit, prix_vente_ht, prix_vente_ttc, quantite_disponnible, description_produit, images1, images2, images3, nom as nom_souscategorie, nom_categorie, moyenne_note_produit FROM Alizon._produit
INNER JOIN Alizon._Sous_Categorie ON Alizon._Sous_Categorie.Id_Sous_Categorie = Alizon._produit.id_sous_categorie
INNER JOIN Alizon._Categorie ON Alizon._Categorie.id_categorie = Alizon._Sous_Categorie.id_categorie_sup;


/* ###################################################################################################
######################################## INSERTION DE DONNEES ########################################
######################################################################################################*/

----------------------------------------------------------------------- Vendeurs

INSERT into alizon._vendeur (prenom, nom, email) values ('Silvie', 'Spittall', 'sspittall0@baidu.com');
INSERT into alizon._vendeur (prenom, nom, email) values ('Budd', 'Surman', 'bsurman1@blinklist.com');
INSERT into alizon._vendeur (prenom, nom, email) values ('Zerk', 'Ternault', 'zternault2@unicef.org');
INSERT into alizon._vendeur (prenom, nom, email) values ('Margy', 'Dybald', 'mdybald3@symantec.com');
INSERT into alizon._vendeur (prenom, nom, email) values ('Cesya', 'Cowdry', 'ccowdry4@shop-pro.jp');


----------------------------------------------------------------------- CLients

INSERT into alizon._client (prenom_client, nom_client, adresse_facturation, date_de_naissance, email, mdp) values ('Garnet', 'Walcot', '048 Sheridan Pass', '28/09/2021', 'gwalcot0@addthis.com', '8nzGxoLmY');
INSERT into alizon._client (prenom_client, nom_client, adresse_facturation, date_de_naissance, email, mdp) values ('Phil', 'Laughren', '4264 Burning Wood Crossing', '17/08/2017', 'plaughren1@ucla.edu', '3HHXeh');
INSERT into alizon._client (prenom_client, nom_client, adresse_facturation, date_de_naissance, email, mdp) values ('Jasen', 'Henrique', '5 Hollow Ridge Road', '01/06/1990', 'jhenrique2@w3.org', 'CJrtgHY');
INSERT into alizon._client (prenom_client, nom_client, adresse_facturation, date_de_naissance, email, mdp) values ('Lusa', 'Iddens', '40539 Carioca Court', '04/02/2006', 'liddens3@netlog.com', '9Xqam72Eqyr');
INSERT into alizon._client (prenom_client, nom_client, adresse_facturation, date_de_naissance, email, mdp) values ('Germain', 'Held', '35 Lien Street', '03/03/2011', 'gheld4@behance.net', 'GczFv5HNBDo');
INSERT into alizon._client (prenom_client, nom_client, adresse_facturation, date_de_naissance, email, mdp) values ('Koralle', 'Croyser', '2649 Golden Leaf Junction', '04/03/1984', 'kcroyser5@mozilla.com', '2U0sHz');
INSERT into alizon._client (prenom_client, nom_client, adresse_facturation, date_de_naissance, email, mdp) values ('Jacinta', 'Yanshonok', '978 Shopko Crossing', '30/04/1971', 'jyanshonok6@lulu.com', 'CEs31QDCFR');
INSERT into alizon._client (prenom_client, nom_client, adresse_facturation, date_de_naissance, email, mdp) values ('Neddy', 'Prayer', '58 Chive Junction', '11/01/1990', 'nprayer7@amazonaws.com', '0QxsJQgPIbb');
INSERT into alizon._client (prenom_client, nom_client, adresse_facturation, date_de_naissance, email, mdp) values ('Jefferson', 'Gomez', '09 Cordelia Crossing', '09/07/1990', 'jgomez8@oakley.com', 'n2Q6ue33xlg');
INSERT into alizon._client (prenom_client, nom_client, adresse_facturation, date_de_naissance, email, mdp) values ('Susette', 'Kellart', '2111 Hoffman Road', '28/08/2007', 'skellart9@icq.com', 'E5hi4929emm');
INSERT into alizon._client (prenom_client, nom_client, adresse_facturation, date_de_naissance, email, mdp) values ('Jere', 'Wilshaw', '50832 Sloan Place', '15/06/1978', 'jwilshawa@bloomberg.com', 'WDlXDKEEA');
INSERT into alizon._client (prenom_client, nom_client, adresse_facturation, date_de_naissance, email, mdp) values ('Carly', 'Mayhead', '0 Arizona Parkway', '06/04/1987', 'cmayheadb@csmonitor.com', 'xb6LYdcdSBV');
INSERT into alizon._client (prenom_client, nom_client, adresse_facturation, date_de_naissance, email, mdp) values ('Daria', 'Rolse', '7946 Morningstar Terrace', '08/03/2009', 'drolsec@reference.com', 'gk4eCDtZpKm');
INSERT into alizon._client (prenom_client, nom_client, adresse_facturation, date_de_naissance, email, mdp) values ('Oliver', 'Ockleshaw', '1 Rutledge Court', '21/09/2000', 'oockleshawd@japanpost.jp', 'qi3qimu');
INSERT into alizon._client (prenom_client, nom_client, adresse_facturation, date_de_naissance, email, mdp) values ('Lila', 'Hymus', '4105 Westerfield Parkway', '14/02/1990', 'lhymuse@jigsy.com', 'GHMZ30WXm');


----------------------------------------------------------------------- Catégories et sous-catégories

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

----------------------------------------------------------------------- Produits

INSERT INTO alizon._produit(nom_produit,prix_coutant,prix_vente_ht,quantite_disponnible,description_produit,id_sous_categorie,id_vendeur)VALUES('kouign amann',10.00,19.99,100,'Maecenas tincidunt lacus at velit. Vivamus vel nulla eget eros elementum pellentesque. Quisque porta volutpat erat.',1,1);
INSERT INTO alizon._produit(nom_produit,prix_coutant,prix_vente_ht,quantite_disponnible,description_produit,id_sous_categorie,id_vendeur)VALUES('Breizh gaufrettes à la vanille - 175g',3.00,4.99,80,'Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec pharetra, magna vestibulum aliquet ultrices, erat tortor sollicitudin mi, sit amet lobortis sapien sapien non mi. Integer ac neque. Duis bibendum.',1,1);
INSERT INTO alizon._produit(nom_produit,prix_coutant,prix_vente_ht,quantite_disponnible,description_produit,id_sous_categorie,id_vendeur)VALUES('Miel Breton toutes fleurs Pot 125g',3.00,4.99,48,'Etiam faucibus cursus urna. Ut tellus. Nulla ut erat id mauris vulputate elementum. Nullam varius.',2,2);
INSERT INTO alizon._produit(nom_produit,prix_coutant,prix_vente_ht,quantite_disponnible,description_produit,id_sous_categorie,id_vendeur)VALUES('Beurre salé - 500g',4.00,7.35,350,'Nulla ut erat id mauris vulputate elementum.',2,3);
INSERT INTO alizon._produit(nom_produit,prix_coutant,prix_vente_ht,quantite_disponnible,description_produit,id_sous_categorie,id_vendeur)VALUES('Caramel au beurre salé - 500ml',30.00,49.50,100,'Etiam justo. Etiam pretium iaculis justo. In hac habitasse platea dictumst. Etiam faucibus cursus urna. Ut tellus.',2,4);

----------------------------------------------------------------------- Contient_produit_p

INSERT INTO alizon._contient_produit_p(id_panier,id_produit,quantite)VALUES(1,1,10);
INSERT INTO alizon._contient_produit_p(id_panier,id_produit,quantite)VALUES(12,3,2);
INSERT INTO alizon._contient_produit_p(id_panier,id_produit,quantite)VALUES(7,5,5);
INSERT INTO alizon._contient_produit_p(id_panier,id_produit,quantite)VALUES(7,1,12);
INSERT INTO alizon._contient_produit_p(id_panier,id_produit,quantite)VALUES(7,2,3);




SELECT * FROM Alizon.catalogue;







