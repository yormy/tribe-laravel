<?php

namespace Yormy\TribeLaravel\Domain\Shared\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Yormy\TribeLaravel\Domain\Upload\DataObjects\Enums\MimeTypeEnum;
use Yormy\Xid\Models\Traits\Xid;

class MemberFile extends BaseModel
{
    use SoftDeletes;
    use Xid;

    protected $table = 'member_files';

    protected $fillable = [
        'xid',
        'member_id',
        'original_filename',
        'original_extension',
        'size_kb',
        'total_pages',
        'disk',
        'path',
        'filename',
        'mime',
        'is_encrypted',
        'variants',
        'allow_pdf_embedding',
        'access_log',
        'user_encryption',
    ];

    public function getFullPathAttribute()
    {
        return $this->getFullPath();
    }

    public function getFullPath(string $filename = null)
    {
        if (! $filename) {
            $filename = $this->filename;
        }

        return $this->path.DIRECTORY_SEPARATOR.$filename;
    }

    public function isPdf(): bool
    {
        return $this->mime === MimeTypeEnum::ApplicationPdf->value;
    }
}
