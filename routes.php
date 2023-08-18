<?php

// PAGES
if (isset($_SESSION['auth'])) {
    get('/profile', '/view/user/profile');
} else {
    get('/', '/view/welcome');
    get('/login', '/view/login');
    get('/signup', '/view/signup');
}


// API
if (isset($_SESSION['auth'])) {
} else {
    any('/api/signup', '/api/signup');
    any('/api/login', '/api/login');
}


any('/404', '/view/404');