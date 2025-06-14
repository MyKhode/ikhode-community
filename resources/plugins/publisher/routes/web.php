<?php

use Illuminate\Support\Facades\Route;
use Wave\Plugins\Publisher\Components\PaperSubmission;

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/papers/submit', PaperSubmission::class)->name('papers.submit');
});
