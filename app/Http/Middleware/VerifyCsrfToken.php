<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'rename',
        'delete',
        'refresh',
        'UploadString',
        'TaskUpload',
        'TaskDownload',
        'test',
        'hello',

        'horny_dragon',
        'one_piece',
        'push',
        'pushImage',
        'pushMultiImage'
    ];
}
