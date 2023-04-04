# **Documentation Catalogueur**

## **Sommaire**
[1 - Installation](#1---installation)<br>
&nbsp;&nbsp;&nbsp;&nbsp;[1.1 - Prérequis](#11---prérequis)<br>
&nbsp;&nbsp;&nbsp;&nbsp;[1.2 - Étape 1](#12---étape-1)<br>
&nbsp;&nbsp;&nbsp;&nbsp;[1.3 - Étape 2](#13---étape-2)<br>


[2 - Génerer un fichier JSON](#2---génerer-un-fichier-json)<br>
&nbsp;&nbsp;&nbsp;&nbsp;[2.1 - Étape 3.1 (compte vendeur)](#21---étape-31-compte-vendeur)<br>
&nbsp;&nbsp;&nbsp;&nbsp;[2.2 - Étape 3.2 (compte administrateur)](#22---étape-32-compte-administrateur)<br>


[3 - Génerer un catalogue](#3---génerer-un-catalogue)<br>
&nbsp;&nbsp;&nbsp;&nbsp;[3.1 - Étape 4.1 (compte vendeur)](#31---étape-41-compte-vendeur)<br>
&nbsp;&nbsp;&nbsp;&nbsp;[3.2 - Étape 4.2 (compte administrateur)](#32---étape-42-compte-administrateur)<br>


[4 - À propos](#4---à-propos)<br>

<br><br>

---

## **1 - Installation**

### **1.1 - Prérequis**
Pour utiliser le catalogueur, vous devez au préalable utiliser un environnement sous Linux et avoir installer Docker.<br>
Vous devez également posséder un compte vendeur ou administrateur sur le site [Alizon.bzh](https://Alizon.bzh).<br>

Pour toutes informations concernant l'installation de Docker, consultez la [documentation officiel de docker](https://docs.docker.com/engine/install/)<br>
<br>

---
### **1.2 - Étape 1**
Décompressez [la catalogueur](https://drive.google.com/drive/folders/1D2MsFZ6mxtNMgJagG1SW7c046T20tdEw?usp=share_link) sur votre ordinateur.<br>
(Dans votre dossier téléchargement)<br>
<br>

---
### **1.3 - Étape 2**
Ouvrez un nouveau terminale à cet endroit et tapez les commandes suivantes :
```bash
docker image pull bigpapoo/sae4-php
```
puis :
```bash
docker image pull bigpapoo/sae4-html2pdf
```
<br><br>

---

## **2 - Génerer un fichier JSON**
Connecter vous sur le site [Alizon.bzh](https://Alizon.bzh).<br>
Si utilisez un compte vendeur passez à [l'étape 3.1 (compte vendeur)](#21---étape-31-compte-vendeur).<br>
Si utilisez un compte administrateur passez à [l'étape 3.2 (compte administrateur)](#22---étape-32-compte-administrateur).<br>
<br>

### **2.1 - Étape 3.1 (compte vendeur)**
Une fois connecté en tant que vendeur, allez dans la rubrique "catalogue".<br>
Selectionnez les produits à exporter puis validez.<br>
Déplacez le fichier mono.json dans le Dossier "Sample" du catalogueur.<br>
Passez ensuite à [l'étape 4.1 (compte vendeur)](#31---étape-41-compte-vendeur).<br>
<br>

### **2.2 - Étape 3.2 (compte administrateur)**
Une fois connecté en tant que vendeur, allez dans la rubrique "?".<br>
Selectionnez les produits à exporter puis validez.<br>
Déplacez le fichier multi.json dans le Dossier "Sample" du catalogueur.<br>
Passez ensuite à [l'étape 4.2 (compte administrateur)](#32---étape-42-compte-administrateur).<br>
<br>

---

## **3 - Génerer un catalogue**
### **3.1 - Étape 4.1 (compte vendeur)**
Toujours dans le même terminal ([voir étape 1](#12---étape-1)), exécuter la commande suivante :<br>

```bash
./script/go mono
```
Retouver ensuite le catalogue "mono.pdf" généré dans le dossier "Sample".<br>
<br>

### **3.2 - Étape 4.2 (compte administrateur)**
Toujours dans le même terminal ([voir étape 1](#12---étape-1)), exécuter la commande suivante :<br>

```bash
./script/go multi
```
Retouver ensuite le catalogue "multi.pdf" généré dans le dossier "Sample".<br>
<br>

---

## **4 - À propos**
Ce document à été rédigé avec soin par l'équipe POBEC.<br>
En cas de disfonctionnement ou de mauvaise utilisation du catalogueur, la responsabilité revient uniquement à l'utilisateur.