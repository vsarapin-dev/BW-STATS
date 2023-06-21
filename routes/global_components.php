<?php

Route::group([], function () {
    Route::group(['namespace' => 'GlobalFunctionality\Files', 'prefix' => 'files'], function () {
        Route::get('/', 'IndexController')->name('files.get');
        Route::post('/store-files', 'StoreController')->name('files.store');
        Route::post('/download-file', 'DownloadController')->name('files.download');
        Route::post('/delete-files', 'DeleteController')->name('files.delete');
        Route::post('/share', 'FilesShareController')->name('files.share');
        Route::post('/get-users', 'UsersShareController')->name('files.get-users');
    });
});
