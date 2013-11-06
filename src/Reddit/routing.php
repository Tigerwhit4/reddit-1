<?php

$router->get('/', 'Reddit\Front:index');
$router->get('/{subreddit}', 'Reddit\Subreddit:show');
$router->get('/{subreddit}/{title}/{id}', 'Reddit\Topic:show');

$router->get('/user/{username}', 'Reddit\User:show');

$router->post('/api/login', 'Reddit\User:login');
$router->post('/api/register', 'Reddit\User:register');
$router->get('/api/logout', 'Reddit\User:logout');

$router->post('/api/votetopic', 'Reddit\Api:voteTopic');
$router->post('/api/votecomment', 'Reddit\Api:voteComment');
$router->post('/api/addpost', 'Reddit\Api:addPost');
$router->post('/api/addcomment', 'Reddit\Api:addComment');
$router->post('/api/sendpm', 'Reddit\Api:sendPm');

$router->get('/me/messages', 'Reddit\User:messages');
