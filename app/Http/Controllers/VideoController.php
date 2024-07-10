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

        // اجرای فرمان دانلود ویدیو با استفاده از yt-dlp
        $command = "yt-dlp -o 'storage/app/public/%(title)s.%(ext)s' $url";
        exec($command, $output, $return_var);

        if ($return_var != 0) {
            return response()->json(['error' => 'Failed to download video', 'output' => $output, 'return_var' => $return_var], 500);
        }

        // بازیابی اطلاعات ویدیو
        $videoInfo = json_decode(shell_exec("yt-dlp -j $url"), true);

        // ذخیره اطلاعات ویدیو در دیتابیس
        $video = new Video();
        $video->title = $videoInfo['title'];
        $video->description = $videoInfo['description'];
        $video->url = Storage::url($videoInfo['title'] . '.' . $videoInfo['ext']);
        $video->save();

        return response()->json(['message' => 'Video downloaded successfully', 'video' => $video], 200);
    }

}
