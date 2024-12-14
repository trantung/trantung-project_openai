<?php

namespace App\Http\Controllers;

use FFMpeg\Format\Video\X264;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function showForm()
    {
        return view('upload');
    }

    public function convert12345(Request $request)
    {
        $request->validate([
            'video' => 'required|mimes:mp4|max:20480', // 20MB Max
        ]);
    
        // Lưu file vào thư mục 'uploads' trên disk 'uploads'
        $videoPath = $request->file('video')->store('', 'uploads');
        
        dd($videoPath);
    
        $videoName = pathinfo($request->file('video')->getClientOriginalName(), PATHINFO_FILENAME);
        $hlsPath = "{$videoName}.m3u8";
    
        try {
            // Đọc file từ disk 'uploads'
            FFMpeg::fromDisk('uploads')
                ->open($videoPath)
                ->exportForHLS()
                ->addFormat(new X264('aac', 'libx264'))
                ->toDisk('secrets') // Lưu file HLS vào disk 'secrets'
                ->save($hlsPath);
    
            $hlsUrl = Storage::disk('secrets')->url($hlsPath); // Lấy URL cho file HLS
    
            return redirect()->back()->with('success', 'Video converted successfully!')->with('hlsUrl', $hlsUrl);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

}
