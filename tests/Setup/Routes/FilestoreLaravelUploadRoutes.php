<?php

namespace Yormy\TribeLaravel\Tests\Setup\Routes;

use Illuminate\Support\Facades\Route;
use Yormy\TribeLaravel\Tests\Setup\Controllers\DownloadController;
use Yormy\TribeLaravel\Tests\Setup\Controllers\UploadController;

class TribeLaravelUploadRoutes
{
    public static function register(): void
    {
        Route::macro('TribeLaravelUpload', function (string $prefix = '') {
            Route::post('/upload-chunked', [UploadController::class, 'uploadLargeFiles'])->name('api.upload_chunked');
            Route::post('/upload', [UploadController::class, 'upload'])->name('api.upload');
            Route::post('/upload-user-encryption', [UploadController::class, 'uploadUserEncryption'])->name('api.upload-user-encryption');

            Route::prefix('file/img/{xid}')
                ->as('file.img.')
                ->group(function () {
                    Route::get('view/{variant?}', [DownloadController::class, 'view'])->name('view');
                    Route::get('download/{variant?}', [DownloadController::class, 'download'])->name('download');
                });

            Route::prefix('file/{xid}/pdf/')
                ->as('file.pdf.')
                ->group(function () {
                    Route::get('view', [DownloadController::class, 'view'])->name('view');
                    Route::get('cover/{variant?}', [DownloadController::class, 'cover'])->name('cover');
                    Route::get('download', [DownloadController::class, 'download'])->name('download');

                    Route::get('pages', [DownloadController::class, 'pages'])->name('pages');
                    Route::get('pages/{page}', [DownloadController::class, 'page'])->name('page');
                });
        });
    }
}
