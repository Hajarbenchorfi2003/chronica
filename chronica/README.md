# CodeIgniter 4 Application Starter

## What is CodeIgniter?

CodeIgniter is a PHP full-stack web framework that is light, fast, flexible and secure.
More information can be found at the [official site](https://codeigniter.com).

This repository holds a composer-installable app starter.
It has been built from the
[development repository](https://github.com/codeigniter4/CodeIgniter4).

More information about the plans for version 4 can be found in [CodeIgniter 4](https://forum.codeigniter.com/forumdisplay.php?fid=28) on the forums.

You can read the [user guide](https://codeigniter.com/user_guide/)
corresponding to the latest version of the framework.

## Installation & updates

`composer create-project codeigniter4/appstarter` then `composer update` whenever
there is a new release of the framework.

When updating, check the release notes to see if there are any changes you might need to apply
to your `app` folder. The affected files can be copied or merged from
`vendor/codeigniter4/framework/app`.

## Setup

Copy `env` to `.env` and tailor for your app, specifically the baseURL
and any database settings.

## Important Change with index.php

`index.php` is no longer in the root of the project! It has been moved inside the *public* folder,
for better security and separation of components.

This means that you should configure your web server to "point" to your project's *public* folder, and
not to the project root. A better practice would be to configure a virtual host to point there. A poor practice would be to point your web server to the project root and expect to enter *public/...*, as the rest of your logic and the
framework are exposed.

**Please** read the user guide for a better explanation of how CI4 works!

## Repository Management

We use GitHub issues, in our main repository, to track **BUGS** and to track approved **DEVELOPMENT** work packages.
We use our [forum](http://forum.codeigniter.com) to provide SUPPORT and to discuss
FEATURE REQUESTS.

This repository is a "distribution" one, built by our release preparation script.
Problems with it can be raised on our forum, or as issues in the main repository.

## Server Requirements

PHP version 8.1 or higher is required, with the following extensions installed:

- [intl](http://php.net/manual/en/intl.requirements.php)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)

> [!WARNING]
> - The end of life date for PHP 7.4 was November 28, 2022.
> - The end of life date for PHP 8.0 was November 26, 2023.
> - If you are still using PHP 7.4 or 8.0, you should upgrade immediately.
> - The end of life date for PHP 8.1 will be December 31, 2025.

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php) if you plan to use MySQL
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library




Ce projet est une application web permettant aux utilisateurs de publier et gérer des articles avec un système de commentaires et de gestion des utilisateurs (admin/auteur).

🔹 Technologies utilisées :
Backend : CodeIgniter 4, PHP, MySQL
Frontend : HTML, CSS, JavaScript (AJAX pour les commentaires)
Sécurité : Hashage des mots de passe, protection CSRF, validation des entrées
Authentification : Système de login/signup avec rôles (Admin, Auteur)
📌 Fonctionnalités principales
1️⃣ Gestion des utilisateurs
👤 Admin :
✅ Peut ajouter, modifier et supprimer des articles.
✅ Gérer les utilisateurs (ajouter/supprimer un auteur).
✅ Gérer les commentaires (modération).

✍ Auteur :
✅ Peut ajouter, modifier et supprimer ses propres articles.
✅ Peut répondre aux commentaires sous ses articles.

👥 Visiteur :
✅ Peut s’inscrire et se connecter.
✅ Peut lire les articles et laisser des commentaires.

2️⃣ Gestion des articles 📰
✅ CRUD complet : Ajouter, modifier, supprimer et afficher les articles.
✅ Éditeur Markdown ou WYSIWYG pour écrire les articles.
✅ Catégorisation des articles (ex : Tech, Lifestyle, Actualités…).
✅ Affichage des articles avec pagination.

3️⃣ Système de commentaires 💬
✅ Un visiteur connecté peut laisser un commentaire sous un article.
✅ Les auteurs et l’admin peuvent répondre aux commentaires.
✅ Modération des commentaires par l’admin (supprimer, bloquer).

4️⃣ Authentification et gestion des rôles 🔑
✅ Inscription et connexion sécurisées (avec password_hash).
✅ Rôles Admin & Auteur → Gestion des accès aux fonctionnalités.
✅ Middleware pour protéger les pages réservées aux admins/auteurs.