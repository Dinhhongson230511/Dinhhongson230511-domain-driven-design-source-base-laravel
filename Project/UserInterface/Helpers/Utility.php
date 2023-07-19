<?php

namespace Project\UserInterface\Helpers;

use Project\UserInterface\Interfaces\LinkHandlerRepositoryInterface;
use Carbon\Carbon;
use Exception;
use finfo;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Image;

class Utility
{
    public const S_KEY = 'Web@OPTIMIZATION03022016';
    public const S_IV = 'ems@best00key!!';
    public const S_METHOD = 'AES-256-CBC';

    /**
     * safeb64Encode()
     * This function is used to encode into base64.
     *
     * @param string $string : String which you wan to encode.
     * @return mixed|string
     */
    public static function safeBase64Encode($string)
    {
        $data = base64_encode($string);
        $data = str_replace([
            '+',
            '/',
            '='
        ], [
            '-',
            '_',
            ''
        ], $data);

        return $data;
    }

    /**
     * safeb64Decode()
     * This function is used to decode b64 safely.
     *
     * @param string $string String which you want to decode
     * @return bool|string
     */
    public static function safeBase64Decode($string)
    {
        $data = str_replace([
            '-',
            '_'
        ], [
            '+',
            '/'
        ], $string);

        $mod4 = strlen($data) % 4;

        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

    /**
     * This method will encrypt string
     *
     * @param string $value
     * @return boolean|string
     */
    public static function encode(string $value): string
    {
        return self::safeBase64Encode(
            openssl_encrypt(
                $value,
                self::S_METHOD,
                hash(
                    'sha256',
                    self::S_KEY
                ),
                0,
                substr(
                    hash(
                        'sha256',
                        self::S_IV
                    ),
                    0,
                    16
                )
            )
        );
    }

    /**
     * This method will decrypt string
     *
     * @param string $value
     * @return boolean|string
     */
    public static function decode(string $value): string
    {
        return openssl_decrypt(
            self::safeBase64Decode($value),
            self::S_METHOD,
            hash(
                'sha256',
                self::S_KEY
            ),
            0,
            substr(
                hash(
                    'sha256',
                    self::S_IV
                ),
                0,
                16
            )
        );
    }

    /**
     *  Get response after verification
     *
     * @param int $status
     * @param string $message
     * @param array $data
     * @return array
     */
    public static function getStructuredResponse(int $status = Response::HTTP_OK, string $message = '', array $data = [])
    {
        return [
            'status' => $status,
            'message' => $message,
            'data' => $data
        ];
    }

    /**
     * @param str $url
     * @param string $urlShortener class (as string) of url shortener to use
     * @return string
     */
    public static function shortenUrl(string $url): string
    {
        $linkHandlerRepository = resolve(LinkHandlerRepositoryInterface::class);

        $shortUrl = Cache::remember($url, config('cache.expireSecond'), function () use ($url, $linkHandlerRepository) {
            return $linkHandlerRepository->shortenUrl($url);
        });

        return $shortUrl ?? $url;
    }

    /**
     * get image url
     *
     * @param string $filename
     * @param string $filepath
     * @return string
     */
    public static function getFileLink(?string $filename, string $filepath): string
    {
        if (empty($filename)) return '';
        if (!App::environment('production') && env('FAKE_CURRENT_TIME')) {
            $testNow = Carbon::now()->toDateTimeString();
            Carbon::setTestNow();
            $expire = Carbon::now()->addMinute(5)->timestamp;
            Carbon::setTestNow($testNow);
        } else {
            $expire = Carbon::now()->addMinute(5)->timestamp;
        }
        if (config('filesystems.default') === 's3') {
            return Storage::temporaryUrl($filepath . $filename, $expire);
        }
        return Storage::url($filepath . $filename);
    }

    /**
     * wait till function return true or limit
     *
     * @param callable $var
     * @param integer $limit
     * @return mixed
     */
    public static function wait(callable $var, int $limit = 100): mixed
    {
        $count = 0;
        $result = false;
        while (!$result) {
            $count++;
            $result = $var();
            if ($count > $limit) return $result;
            usleep(100000);
        }
        return $result;
    }

    /**
     * Store Image and get filename
     *
     * @param UploadedFile $file
     * @param string $storagePath
     * @param string|null $fileName
     * @return string
     */
    public static function storeFile(UploadedFile $file, string $storagePath, ?string $fileName = null): string
    {
        $fileName = $fileName ?? $file->getClientOriginalName();

        $checkSumSHA256 = hash_file("sha256", $file);
        $metaData = [
            "ContentSHA256" => $checkSumSHA256
        ];

        Storage::putFileAs($storagePath, $file, $fileName, $metaData);

        if (!empty(self::getFileLink($fileName, $storagePath)))
            return $fileName;

        return '';
    }

    /**
     * coping file from url to s3
     *
     * @param string|null $fileName
     * @param $folderBucketPath
     * @param $url
     * @return string
     */
    public static function copyingFileFromUrlToS3($fileName, $folderBucketPath, $url)
    {
        $tempFilePath = '/tmp/' . $fileName;
        $fileContents = file_get_contents($url);
        file_put_contents($tempFilePath, $fileContents);
        $fileInfo = new finfo(FILEINFO_MIME_TYPE);
        $uploadFile = new uploadedfile(
            $tempFilePath,
            $fileName,
            $fileInfo->file($tempFilePath),
            filesize($tempFilePath),
            0,
            false
        );
        return utility::storefile($uploadFile, $folderBucketPath, $fileName);
    }

    public static function chunk(callable $fetchChunk, callable $perChunk, $pageSize = 800)
    {
        $page = 1;

        do {
            $results = $fetchChunk($page, $pageSize);
            $countResults = $results->count();
            if ($countResults == 0) {
                break;
            }

            $perChunk($results);

            unset($results);

            $page++;
        } while ($countResults == $pageSize);
    }

    /**
     * @param Request $request
     * @return string
     */
    public static function getGuard(Request $request): string
    {
        if (Str::contains($request->url(), 'api'))
            return 'api';

        if (Str::contains($request->url(), 'admin'))
            return 'admin';

        return 'web';
    }

    /**
     * @param array|null $value
     * @return string|null
     */
    public static function convertArrayToString(?array $value = null): ?string
    {
        if (!$value || !implode(',', $value)) {
            return null;
        }
        return implode(',', $value);
    }

    /**
     * @param string $filePath
     * @param int $width
     * @return mixed
     */
    public static function resizeImage(string $filePath, int $width): mixed
    {
        $img = Image::make($filePath);
        if ($img->width() > $width) {
            $img->resize($width, $width);
        }

        return $img->response()->content();
    }
}
