<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('welcome');
Route::view('/register', 'auth.register')->name('register.form');
Route::view('/login', 'auth.login')->name('login.form');
Route::view('/publish', 'layouts.publish')->name('articles.publish'); // se você tiver essa view
