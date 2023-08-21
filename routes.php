<?php

// PAGES
if (isset($_SESSION['auth'])) {
    get('/', '/view/welcome');

    get('/profile/edit', '/view/user/edit');
    get('/profile', '/view/user/profile');
    get('/profile/$userID', '/view/user/profile');

    get('/blog/create', '/view/blog/create');

    get('/post/create', '/view/post/create');
    get('/post/$postID', '/view/post/show');

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
    // Create post
    any('/api/post', '/api/post');
    any('/api/post/$postID', '/api/post');

    any('/api/post-comment', '/api/post-comment');
    any('/api/post-comment/$commentID', '/api/post-comment');
} else {
    any('/api/signup', '/api/signup');
    any('/api/login', '/api/login');
}


any('/404', '/view/404');