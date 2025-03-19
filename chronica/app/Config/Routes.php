<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;

$routes = Services::routes();

// Routes publiques
$routes->get('/', 'Home::index');
$routes->get('articles', 'Articles::index');
$routes->get('articles/(:segment)', 'Articles::view/$1');
$routes->get('categories/(:segment)', 'Categories::view/$1');
$routes->get('tags/(:segment)', 'Tags::view/$1');

// Routes d'authentification
$routes->get('login', 'Auth::login');
// $routes->post('login', 'Auth::authenticate');
$routes->post('login', 'Auth::login');
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::store');
$routes->get('logout', 'Auth::logout');
$routes->get('forgot-password', 'Auth::forgotPassword');
$routes->post('forgot-password', 'Auth::sendResetLink');
$routes->get('reset-password/(:segment)', 'Auth::resetPassword/$1');
$routes->post('reset-password/(:segment)', 'Auth::updatePassword/$1');

// Routes protégées (nécessitant une connexion)
$routes->group('', ['filter' => 'auth'], function($routes) {
    // Routes des articles
    $routes->get('articles/create', 'Articles::create');
    $routes->post('articles', 'Articles::store');
    $routes->get('articles/edit/(:num)', 'Articles::edit/$1');
    $routes->post('articles/update/(:num)', 'Articles::update/$1');
    $routes->get('articles/delete/(:num)', 'Articles::delete/$1');

    // Routes des commentaires
    $routes->post('comments', 'Comments::store');
    $routes->get('comments/replies/(:num)', 'Comments::getReplies/$1');

    // Routes du profil utilisateur
    $routes->get('profile', 'Users::profile');
    $routes->post('profile/update', 'Users::updateProfile');
});

// Routes d'administration
$routes->group('admin', ['filter' => 'admin'], function($routes) {
    // Tableau de bord
    $routes->get('/', 'Admin::index');

    // Gestion des articles
    $routes->get('articles', 'Articles::index');
    $routes->get('articles/create', 'Articles::create');
    $routes->post('articles', 'Articles::store');
    $routes->get('articles/edit/(:num)', 'Articles::edit/$1');
    $routes->post('articles/update/(:num)', 'Articles::update/$1');
    $routes->get('articles/delete/(:num)', 'Articles::delete/$1');

    // Gestion des catégories
    $routes->get('categories', 'Categories::index');
    $routes->get('categories/create', 'Categories::create');
    $routes->post('categories', 'Categories::store');
    $routes->get('categories/edit/(:num)', 'Categories::edit/$1');
    $routes->post('categories/update/(:num)', 'Categories::update/$1');
    $routes->get('categories/delete/(:num)', 'Categories::delete/$1');

    // Gestion des commentaires
    $routes->get('comments', 'Comments::index');
    $routes->get('comments/pending', 'Comments::pending');
    $routes->get('comments/approve/(:num)', 'Comments::approve/$1');
    $routes->get('comments/reject/(:num)', 'Comments::reject/$1');
    $routes->get('comments/delete/(:num)', 'Comments::delete/$1');

    // Gestion des utilisateurs
    $routes->get('users', 'Users::index');
    $routes->get('users/create', 'Users::create');
    $routes->post('users', 'Users::store');
    $routes->get('users/edit/(:num)', 'Users::edit/$1');
    $routes->post('users/update/(:num)', 'Users::update/$1');
    $routes->get('users/delete/(:num)', 'Users::delete/$1');
});
