<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $quality = $request->input('quality', 'best'); // اگر کیفیت مشخص نشد، به صورت پیش‌فرض "best" در نظر گرفته می‌شود

        // اجرای فرمان دانلود ویدیو با استفاده از shell_exec برای دریافت خروجی دقیق‌تر
        $command = "yt-dlp -f $quality -o 'storage/app/public/%(title)s.%(ext)s' $url 2>&1";
        $output = shell_exec($command);

        // بررسی خطا
        if (strpos($output, 'ERROR') !== false) {
            return response()->json(['error' => 'Failed to download video', 'output' => $output], 500);
        }

        // بازیابی اطلاعات ویدیو
        $videoInfo = json_decode(shell_exec("yt-dlp -j $url"), true);

        // بررسی اینکه آیا ویدیو با کیفیت مورد نظر دانلود شده است یا خیر
        $filePath = storage_path('app/public/' . $videoInfo['title'] . '.' . $videoInfo['ext']);
        if (!file_exists($filePath)) {
            return response()->json(['error' => 'Failed to download video with the specified quality', 'output' => $output], 500);
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
