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
        if (!file_exists(public_path('uploads/big_buck_bunny_720p_1mb.mp4'))) {
            echo "File not found!";
        } else {
            echo public_path('uploads/big_buck_bunny_720p_1mb.mp4') . PHP_EOL;
            
            $lowBitrate  = (new X264)->setKiloBitrate(1000);
            $midBitrate = (new X264)->setKiloBitrate(2500);
            $highBitrate = (new X264)->setKiloBitrate(5000);

            $this->info('Converting big_buck_bunny_720p_1mb.mp4');

            try {
                FFMpeg::fromDisk('uploads')
                    ->open('big_buck_bunny_720p_1mb.mp4')
                    ->exportForHLS()
                    // ->addFormat($lowBitrate)
                    // ->addFormat($midBitrate)
                    ->withRotatingEncryptionKey(function($filename, $content){
                        Storage::disk('secrets')->put($filename, $content);
                    })
                    ->addFormat($highBitrate)
                    ->onProgress(function ($progress) {
                        $this->info("Progress: {$progress}%");
                    })
                    ->toDisk('secrets')
                    ->save('big_buck_bunny_720p_1mb.m3u8');

                $this->info('Done!');
            } catch (\Exception $e) {
                $this->error('Error: ' . $e->getMessage());
            }
        }
    }
}

