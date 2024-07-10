<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Video;
use GuzzleHttp\Client;

class VideoController extends Controller
{
//    public function download(Request $request)
//    {
//        $url = $request->input('url');
//
//        // اجرای فرمان دانلود ویدیو با استفاده از youtube-dl یا yt-dlp
//        $command = "youtube-dl -o 'storage/app/public/%(title)s.%(ext)s' $url";
//        exec($command, $output, $return_var);
//
//        if ($return_var != 0) {
//            return response()->json(['error' => 'Failed to download video'], 500);
//        }
//
//        // بازیابی اطلاعات ویدیو
//        $videoInfo = json_decode(shell_exec("youtube-dl -j $url"), true);
//
//        // ذخیره اطلاعات ویدیو در دیتابیس
//        $video = new Video();
//        $video->title = $videoInfo['title'];
//        $video->description = $videoInfo['description'];
//        $video->url = Storage::url($videoInfo['title'] . '.' . $videoInfo['ext']);
//        $video->save();
//
//        return response()->json(['message' => 'Video downloaded successfully', 'video' => $video], 200);
//    }

    public function download(Request $request)
    {
        $url = $request->input('url');
        $quality = $request->input('quality', 'best'); // دریافت کیفیت انتخاب شده توسط کاربر، پیش‌فرض 'best'

        // درخواست برای دریافت اطلاعات ویدیو به صورت JSON
        $client = new Client();
        $response = $client->request('GET', "https://api.yt-dlp.com/info?url=$url");
        $videoInfo = json_decode($response->getBody(), true);

        // بررسی وجود کیفیت انتخاب شده در لیست کیفیت‌های موجود
        $availableQualities = array_column($videoInfo['formats'], 'format_id');
        if (!in_array($quality, $availableQualities)) {
            return response()->json(['error' => 'Selected quality is not available'], 400);
        }

        // فرمان دانلود ویدیو با استفاده از yt-dlp برای کیفیت انتخاب شده
        $command = "yt-dlp -f $quality -o 'storage/app/public/%(title)s.%(ext)s' $url";
        exec($command, $output, $return_var);

        if ($return_var != 0) {
            return response()->json(['error' => 'Failed to download video', 'output' => $output, 'return_var' => $return_var], 500);
        }

        // ذخیره اطلاعات ویدیو در دیتابیس
        $video = new Video();
        $video->title = $videoInfo['title'];
        $video->description = $videoInfo['description'];
        $video->url = Storage::url($videoInfo['title'] . '.' . $videoInfo['ext']);
        $video->save();

        return response()->json(['message' => 'Video downloaded successfully', 'video' => $video], 200);
    }

}
