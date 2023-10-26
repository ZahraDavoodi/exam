<?php

// Home
Breadcrumbs::for('home', function ($trail) {
    $trail->push(' خانه ', route('home'));
});


Breadcrumbs::for('contact', function ($trail) {
    $trail->parent('home');
    $trail->push(' ارتباط با ما ', route('contact'));
});

Breadcrumbs::for('about', function ($trail) {
    $trail->parent('home');
    $trail->push(' درباره ما', route('about'));
});
Breadcrumbs::for('comments', function ($trail) {
    $trail->parent('home');
    $trail->push(' نظرات ', route('comment'));
});

Breadcrumbs::for('cooperation', function ($trail) {
    $trail->parent('home');
    $trail->push(' همکاری با ما ', route('cooperation'));
});
Breadcrumbs::for('rules', function ($trail) {
    $trail->parent('home');
    $trail->push(' قوانین و مقررات ', route('rules'));
});

Breadcrumbs::for('help', function ($trail) {
    $trail->parent('home');
    $trail->push(' راهنمای خرید ', route('help'));
});

// Home > Blog > [Category]
Breadcrumbs::for('hotels', function ($trail, $category) {
    $trail->parent('home');
    $trail->push(' هتل های '.$category->category_name, route('hotels', $category->slug));
});

// Home > Blog > [Category] > [Post]
Breadcrumbs::for('hotel_info', function ($trail, $hotel,$category) {

    $trail->push(' هتل '.$hotel->hotel_pName, route('hotel_info', $hotel->slug));
});