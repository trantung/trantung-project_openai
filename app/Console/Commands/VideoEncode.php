<?php

namespace App\Console\Commands;

use FFMpeg\Format\Video\X264;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class VideoEncode extends Command
{
    protected $signature = 'app:video-encode';
    protected $description = 'Command description';

    public function handle()
    {
        if (!file_exists(public_path('uploads/video1.mp4'))) {
            echo "File not found!";
        } else {
            echo public_path('uploads/video1.mp4') . PHP_EOL;
            
            $this->info('Converting video1.mp4');

            try {
                FFMpeg::fromDisk('uploads')
                    ->open('video1.mp4')
                    ->exportForHLS()
                    ->addFormat(new X264('aac', 'libx264'))
                    ->onProgress(function ($progress) {
                        $this->info("Progress: {$progress}%");
                    })
                    ->toDisk('secrets')
                    ->save('video1.m3u8');

                $this->info('Done!');
            } catch (\Exception $e) {
                $this->error('Error: ' . $e->getMessage());
            }
        }
    }
}
