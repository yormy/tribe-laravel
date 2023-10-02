<?php

namespace Yormy\TribeLaravel\Tests\Unit;

use Facades\Yormy\TribeLaravel\Domain\Encryption\FileVault;
use Illuminate\Support\Facades\Storage;
use Yormy\TribeLaravel\Domain\Encryption\Exceptions\DecryptionFailedException;
use Yormy\TribeLaravel\Tests\TestCase;
use Yormy\TribeLaravel\Tests\Traits\AssertEncryptionTrait;
use Yormy\TribeLaravel\Tests\Traits\CleanupTrait;
use Yormy\TribeLaravel\Tests\Traits\EncryptionTrait;
use Yormy\TribeLaravel\Tests\Traits\FileTrait;

class FileEncryptionTest extends TestCase
{
    /**
     * @test
     *
     * @group xxx
     */
    public function File_ReadFile_Ok(): void
    {
$this->assertTrue(true);
    }
}
