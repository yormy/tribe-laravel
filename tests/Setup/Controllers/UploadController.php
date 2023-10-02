<?php

declare(strict_types=1);

namespace Yormy\TribeLaravel\Tests\Setup\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Yormy\TribeLaravel\Domain\Upload\Services\UploadFileService;

class UploadController
{
    public function uploadUserEncryption(Request $request)
    {
        $file = $request->file('file');

        $user = auth::user();
        //dd($user);
        $xid = UploadFileService::make($file)
            ->sanitize()
            ->memberId(6)
            ->userEncryption()
            ->saveEncryptedToLocal('myid');

        return [
            'xids' => [$xid],
        ];
    }

    public function upload(Request $request)
    {
        $file = $request->file('file');

        $xid = UploadFileService::make($file)
            ->sanitize()
            ->memberId(6)
            //->saveEncryptedToLocal('myid', 'key:sadasfar3451235r);
            ->saveEncryptedToLocal('myid');
        //->saveEncryptedToPersistent('myid');

        return [
            'xids' => [$xid],
        ];
    }

    public function uploadLargeFiles(Request $request)
    {
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));
        if (! $receiver->isUploaded()) {
            // file not uploaded
        }

        $fileReceived = $receiver->receive(); // receive file
        if ($fileReceived->isFinished()) { // file uploading is complete / all chunks are uploaded
            $file = $fileReceived->getFile(); // get file
            $extension = $file->getClientOriginalExtension();
            $fileName = str_replace('.'.$extension, '', $file->getClientOriginalName()); //file name without extenstion
            $fileName .= '_'.md5('oooo').'.'.$extension; // a unique file name

            $disk = Storage::disk(config('filesystems.default'));
            $path = $disk->putFileAs('videos', $file, $fileName);

            // delete chunked file
            unlink($file->getPathname());

            return [
                'path' => asset('storage/'.$path),
                'filename' => $fileName,
            ];
        }

        // otherwise return percentage information
        $handler = $fileReceived->handler();

        return [
            'done' => $handler->getPercentageDone(),
            'status' => true,
        ];
    }
}
