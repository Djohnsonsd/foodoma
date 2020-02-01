<?php

Route::group(['prefix' => 'admin/modules', 'middleware' => 'admin'], function () {
    Route::get('/ratings', 'RatingController@ratings')->name('admin.ratings');
});
