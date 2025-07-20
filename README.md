# 🚀 Stratum Forge

> **Stratum Forge** est le bootstrapper officiel de [Stratum CMS](https://github.com/YuketsuSh/Stratum).  
> Il vous guide pas à pas dans l’installation du CMS, de la vérification de votre environnement à la configuration finale, en toute autonomie.

---

## 🎯 Objectif

Stratum Forge permet de :

- Vérifier que votre serveur est compatible avec Stratum CMS
- Télécharger automatiquement la **dernière version stable** depuis GitHub
- Installer les dépendances via Composer et npm
- Compiler les assets (CSS) et créer les symlinks nécessaires (`storage:link`)
- Supprimer **automatiquement** le bootstrapper une fois l’installation terminée
- Rediriger vers `/install`, l’installeur officiel de Stratum CMS

---

## 🛠️ Prérequis

Avant de lancer Stratum Forge, votre serveur doit disposer de :

- PHP **≥ 8.1**
- Composer
- Node.js & npm
- Les extensions PHP suivantes :
    - `mbstring`, `openssl`, `pdo`, `tokenizer`, `xml`, `ctype`

💡 Stratum Forge vous informera de tout élément manquant et vous proposera les commandes à exécuter pour corriger.

---

## 📦 Installation

Unzipper simplement les fichiers du dossier `stratum-forge/` à la racine de votre projet web.

Accédez ensuite via navigateur à : http://votre-domaine/

> Vous serez guidé à travers chaque étape de l’installation.

## ⚙️ Étapes prises en charge

1. Vérification de l’environnement serveur
2. Téléchargement de la dernière release stable de Stratum CMS depuis GitHub
3. Installation des dépendances :
```bash
   composer install
   npm install
   npm run dev:default
   npm run dev:install
   npm run dev:admin
   php artisan storage:link
```

4. Suppression complète de Stratum Forge

5. Redirection vers `/install`

## 🧱 Structure du projet

```java
/stratum-forge/
├── public/             → Point d’entrée de l’installeur
├── views/              → Pages HTML / UI
├── src/                → Logique PHP (MVC léger)
├── tmp/                → Fichiers temporaires (extraction CMS)
├── config/             → Paramètres du bootstrapper
├── composer.json       → Autoload PSR-4
└── README.md
```

## 🛡️ Sécurité

- Le bootstrapper est automatiquement supprimé une fois l’installation terminée 
- Aucun fichier d’installation ne persiste après la configuration 
- Téléchargement uniquement depuis le dépôt GitHub officiel
- (Optionnel) Support à venir pour la validation par hash/signature

## 📌 Licence
[MIT](LICENSE) – Fièrement développé pour simplifier l’installation de StratumCMS.

## 🤝 Contribuer
Vous êtes les bienvenus pour proposer des idées, corriger des bugs ou améliorer l’UI.