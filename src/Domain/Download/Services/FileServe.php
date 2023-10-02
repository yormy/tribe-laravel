<?php

namespace Yormy\TribeLaravel\Domain\Download\Services;

use Facades\Yormy\TribeLaravel\Domain\Encryption\FileVault;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Yormy\TribeLaravel\Domain\Shared\Models\MemberFile;
use Yormy\TribeLaravel\Domain\Shared\Repositories\MemberFileAccessRepository;
use Yormy\TribeLaravel\Domain\Upload\DataObjects\Enums\MimeTypeEnum;
use Yormy\TribeLaravel\Domain\Upload\Services\PdfImageService;
use Yormy\TribeLaravel\Exceptions\EmbeddingNotAllowedException;
use Yormy\TribeLaravel\Exceptions\InvalidVariantException;
use Yormy\Xid\Services\XidService;

class FileServe
{
    public static function view(Request $request, string $xid, string $variant = null): array
    {
        $fileRecord = self::getFileRecord($request, $xid);

        if ($fileRecord->isPdf() && ! $fileRecord->allow_pdf_embedding) {
            throw new EmbeddingNotAllowedException();
        }

        $filename = self::getFilename($variant, $fileRecord);
        $mime = $fileRecord->mime;

        $data = [
            'height' => $fileRecord->height,
            'width' => $fileRecord->width,
            'data' => self::display($filename, $fileRecord->disk, $mime, $fileRecord),
        ];

        return $data;
    }

    public static function viewCover(Request $request, string $xid, string $variant = null): string
    {
        $fileRecord = self::getFileRecord($request, $xid);

        $filename = self::getFilename($variant, $fileRecord);
        $mime = $fileRecord->mime;

        if ($fileRecord->isPdf()) {
            $filename .= '.png';
            $mime = MimeTypeEnum::ImagePng->value;
        }

        return self::display($filename, $fileRecord->disk, $mime, $fileRecord);
    }

    public static function page(Request $request, string $xid, int $pageNr): string
    {
        $fileRecord = self::getFileRecord($request, $xid);

        $filename = PdfImageService::createFilename($pageNr);
        $path =
            $fileRecord->path.
            DIRECTORY_SEPARATOR.
            PdfImageService::PATH_PAGES.
            DIRECTORY_SEPARATOR.
            $fileRecord->filename.
            $filename.'.png';

        $mime = MimeTypeEnum::ImagePng->value;

        return self::display($path, $fileRecord->disk, $mime, $fileRecord);
    }

    public static function pages(Request $request, string $xid): array
    {
        $fileRecord = self::getFileRecord($request, $xid);
        $pageCount = $fileRecord->total_pages;

        $i = 1;
        $pages = [];
        while ($i <= $pageCount) {
            $pages[$i] = self::page($request, $xid, $i);
            $i++;
        }

        return $pages;
    }

    private static function getFileRecord(Request $request, string $xid): MemberFile
    {
        XidService::validateOrAbort($xid);

        $fileRecord = MemberFile::where('xid', $xid)->firstOrFail();
        $data = self::getLogData($request);
        $memberFileAccessRepository = new MemberFileAccessRepository();
        $memberFileAccessRepository->createAsViewed($fileRecord, $data);

        return $fileRecord;
    }

    private static function display(string $filename, string $disk, string $mime, $fileRecord)
    {
        $encryptionKey = self::getKey($fileRecord);

        if (self::isEncrypted($filename)) {
            return self::displayEncrypted($disk, $filename, $mime, $encryptionKey);
        }

        return self::displayPlain($disk, $filename, $mime);
    }

    private static function getKey($fileRecord)
    {
        $encryptionKey = null;
        if ($fileRecord->user_encryption) {
            $user = Auth::user();
            $encryptionKey = $user->encryption_key;
        }

        return $encryptionKey;
    }

    public static function download(Request $request, string $xid, string $variant = null, string $downloadAs = null)
    {
        XidService::validateOrAbort($xid);

        $fileRecord = MemberFile::where('xid', $xid)->firstOrFail();
        $data = self::getLogData($request);
        $memberFileAccessRepository = new MemberFileAccessRepository();
        $memberFileAccessRepository->createAsDownloaded($fileRecord, $data);

        $filename = self::getFilename($variant, $fileRecord);

        if (! $downloadAs) {
            $downloadAs = basename($filename);
        }

        if (! $downloadAs) {
            $extension = config('filestore.vault.extension');
            $downloadAs = str_replace($extension, '', $filename);
            $downloadAs = basename($downloadAs);
        }

        $encryptionKey = self::getKey($fileRecord);

        if (self::isEncrypted($filename)) {
            return self::downloadEncrypted($fileRecord->disk, $filename, $downloadAs, $encryptionKey);
        }

        return self::downloadPlain($fileRecord->disk, $filename, $downloadAs);

    }

    private static function getFilename(?string $variant, MemberFile $fileRecord): string
    {
        $filename = $fileRecord->getFullPath();
        $useVariant = null;

        if (isset($variant)) {
            $useVariant = self::findVariant($variant, $fileRecord);
        }

        if ($useVariant) {
            $filename = $fileRecord->getFullPath($useVariant['filename']);
        }

        return $filename;
    }

    private static function findVariant(string $selectedVariant, MemberFile $file)
    {
        $existingVariants = json_decode($file->variants, true);

        if (! $existingVariants) {
            throw new InvalidVariantException();
        }

        $useVariant = null;
        foreach ($existingVariants as $key => $variant) {
            if ($variant['name'] === $selectedVariant) {
                $useVariant = $existingVariants[$key];
            }
        }

        if (! $useVariant) {
            throw new InvalidVariantException();
        }

        return $useVariant;
    }

    protected static function isEncrypted(string $fullPath): bool
    {
        $pathinfo = pathinfo($fullPath);
        $extension = config('filestore.vault.extension');
        $extension = str_replace('.', '', $extension);
        if (isset($pathinfo['extension']) && ($pathinfo['extension'] === $extension)) {
            return true;
        }

        return false;
    }

    private static function downloadPlain(string $disk, string $fullPath, string $downloadAs): StreamedResponse
    {
        return Storage::disk($disk)->download($fullPath, $downloadAs);
    }

    private static function displayPlain(string $disk, string $fullPath, string $mime)
    {
        //return Storage::disk($disk)->response($fullPath);
        $imagedata = Storage::disk($disk)->get($fullPath);

        return self::convertBase64($imagedata, $mime);
    }

    private static function downloadEncrypted(string $disk, string $fullPath, string $downloadAs, string $encryptionKey = null)
    {
        return response()->streamDownload(function () use ($disk, $fullPath, $encryptionKey) {
            FileVault::disk($disk)->streamDecrypt($fullPath, $encryptionKey);
        }, $downloadAs);
    }

    private static function displayEncrypted(string $disk, string $fullPath, string $mime, string $encryptionKey = null)
    {
        // $mimeType = Storage::disk($disk)->mimeType($fullPath); // stream
        //        $x =  response()->stream(function () use ($disk, $fullPath, $mimeType) {
        //            FileVault::disk($disk)->streamDecrypt($fullPath);
        //        }, 200, ["Content-Type" => $mimeType]);

        ob_start();
        FileVault::disk($disk)->streamDecrypt($fullPath, $encryptionKey);
        $imagedata = ob_get_contents();
        ob_end_clean();

        return self::convertBase64($imagedata, $mime);
    }

    private static function convertBase64(string $imagedata, string $mime): string
    {
        $prefix = "data:$mime;base64,";

        return $prefix.base64_encode($imagedata);
    }

    private static function getLogData(Request $request): array
    {
        $ipResolverClass = config('filestore.resolvers.ip');
        $ip = $ipResolverClass::get($request);

        $useragentResolverClass = config('filestore.resolvers.useragent');
        $useragent = $useragentResolverClass::get($request);

        $userResolverClass = config('filestore.resolvers.user');
        $user = $userResolverClass::get($request);

        $data = [
            'ip' => $ip,
            'useragent' => $useragent,
            'user_id' => $user?->id,
            'user_type' => $user ? get_class($user) : null,
        ];

        return $data;
    }
}
