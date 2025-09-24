# 🌱 Eco-Mobil – Location mobilité verte

## 📖 Contexte

Eco-Mobil est une société spécialisée dans la location de moyens de déplacement électriques. Le projet est né de la volonté d’un grand groupe de location de véhicules d’investir le marché de la mobilité « verte », en démarrant par la région Auvergne-Rhône-Alpes avec plusieurs axes dans Annecy, Grenoble, et Valence.

La société dispose d’agences de location et d’entrepôts de maintenance, et souhaite développer un système d’information complet pour :

* La gestion des **locations** (réservations, paiements, suivi des clients).
* La gestion des **ateliers de maintenance** (réparation, suivi des véhicules).
* La mise à disposition d’une **application de suivi pour la direction** (indicateurs financiers et opérationnels).
* Une **infrastructure informatique** sécurisée, évolutive et centralisée.

---

## 🚲 Parc de véhicules

* **Vélos électriques urbains** : 179 (Toutes agences)
* **VTT électriques** : 56 (Annecy & Grenoble, en projet à Chambéry)
* **Hoverboards** : 23 (Grenoble & Valence)
* **Trottinettes électriques** : 52 (Grenoble & Annecy)
* **Gyropodes (Segway)** : 30 (Toutes agences)
* **Skateboards électriques** : 17 (Annecy & Chambéry, bientôt Grenoble & Valence)

---

## 🛠️ Activités principales

### 1. Location

* Réservation en ligne via un site web (empreinte CB requise, paiement le jour J).
* Gestion sécurisée par les agences (sortie et restitution du matériel).
* Application mobile de suivi disponible pour les clients.
* Fonctionnalités clés :

  * Création de compte avec mot de passe « fort » au normes de l'ANSSI.
  * Réservation multi-véhicules, multi-personnes.
  * Gestion des annulations et disponibilités en temps réel.

### 2. Atelier

* Logiciel dédié à la maintenance et à la logistique inter-agences.
* Suivi du cycle de vie des véhicules : sortie location → atelier → réparation → retour en agence → disponible.
* Gestion des anomalies, vols et sinistres.

### 3. Application de suivi (Direction)

* Application lourde pour la direction (non disponible en ligne).
* Indicateurs disponibles :

  * Nombre de locations (par durée et type de véhicule).
  * Chiffre d’affaires global et par type de véhicule.
  * Liste détaillée des réservations.

---

## 📊 Technologies & situations professionnelles

* **Situation 1** : Base de données + Activité « Location »

  * MySQL + PHP + PHPStorm

* **Situation 2** : Application de suivi (Direction)

  * MySQL + C# WPF + Visual Studio
  
---

## 📌 Objectifs du projet

* Concevoir un système d’information **sécurisé, performant et évolutif**.
* Développer des outils adaptés à chaque métier (location, atelier, direction).
* Mettre en place une infrastructure robuste avec supervision et documentation complète.
