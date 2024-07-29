<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('auth/login');
});

Auth::routes();

Route::get('/contacts', [App\Http\Controllers\ContactController::class, 'index'])->name('contacts');
Route::get('/register/thankyou', [App\Http\Controllers\HomeController::class, 'showThankYou'])->name('thankyou');
Route::get('/contacts/add', [App\Http\Controllers\ContactController::class, 'displayAddContact'])->name('contacts.add');
Route::post('/contacts/add', [App\Http\Controllers\ContactController::class, 'addContact'])->name('contacts.store');
Route::get('/contacts/{id}/edit', [App\Http\Controllers\ContactController::class, 'editContact'])->name('contacts.edit');
Route::post('/contacts/{id}/edit', [App\Http\Controllers\ContactController::class, 'updateContact'])->name('contacts.update');
Route::delete('contacts/{id}', [App\Http\Controllers\ContactController::class, 'deleteContact'])->name('contacts.destroy');
Route::get('/contacts/search', [App\Http\Controllers\ContactController::class, 'search'])->name('contacts.search');