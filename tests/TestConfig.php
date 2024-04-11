<?php

declare(strict_types=1);

namespace Yormy\TribeLaravel\Tests;

use Yormy\TribeLaravel\Tests\Setup\Models\Member;

class TestConfig
{
    const USE_MINIO = true;

    public static function setup(): void
    {
        config(['filestore' => require __DIR__.'/../config/tribe.php']);
        config(['app.key' => 'base64:yNmpwO5YE6xwBz0enheYLBDslnbslodDqK1u+oE5CEE=']);

        config(['filesystems.disks.local.root' => getcwd().'/tests/Setup/Storage/encryption']);

        config(['tribe.models.member' => Member::class]);
        if (self::USE_MINIO) {
            self::setUpDiskMinio();
        } else {
            self::setUpDiskCi();
        }
    }

    protected static function setUpDiskCi(): void
    {
        $s3LocalFaked = [
            'driver' => 'local',
            'root' => getcwd().'/tests/Setup/Storage/localfake',
            'throw' => false,
        ];

        config(['filesystems.disks.digitalocean' => $s3LocalFaked]);
    }

    protected static function setUpDiskMinio(): void
    {
        $s3Storage = [
            'driver' => 's3',
            'root' => env('DO_STORAGE_ROOT'),
            'key' => env('DO_STORAGE_ACCESS_KEY_ID'),
            'secret' => env('DO_STORAGE_SECRET_ACCESS_KEY'),
            'endpoint' => env('DO_STORAGE_ENDPOINT'),
            'use_path_style_endpoint' => env('DO_STORAGE_PATH_STYLE', false),
            'version' => 'latest',
            'region' => env('DO_STORAGE_DEFAULT_REGION'),
            'bucket' => env('DO_STORAGE_BUCKET'),
        ];

        config(['filesystems.disks.digitalocean' => $s3Storage]);
    }
}
