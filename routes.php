<?php

// PAGES
if (isset($_SESSION['auth'])) {
    get('/profile', '/view/user/profile');
    get('/profile/$userID', '/view/user/profile');


    get('/logout', '/core/logout');
} else {
    get('/', '/view/welcome');
    get('/login', '/view/login');
    get('/signup', '/view/signup');
}


// API
if (isset($_SESSION['auth'])) {
    // Get user details
    any('/api/user', '/api/user');
    any('/api/user/$userID', '/api/user');
    // To follow & Unfollow a user
    any('/api/follow/$userID', '/api/follow');
} else {
    any('/api/signup', '/api/signup');
    any('/api/login', '/api/login');
}


any('/404', '/view/404');