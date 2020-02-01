<?php

Route::post('/get-ratable-order', [
    'uses' => 'RatingController@getRatableOrder',
]);

Route::post('/save-new-rating', [
    'uses' => 'RatingController@saveNewRating',
]);
