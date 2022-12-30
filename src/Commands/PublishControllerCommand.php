<?php

namespace SocialMedia\Poster\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;

class PublishControllerCommand extends Command
{
    use ConfirmableTrait;

    protected $signature = 'social-media-poster:publish-controller';

    protected $description = 'Publish SocialMediaAuthController for customization';

    public function handle()
    {
        if (!$this->confirmToProceed())
        {
            return 1;
        }

        if (!is_dir($stubsPath = $this->laravel->basePath('app/Http/Controllers')))
        {
            (new Filesystem())->makeDirectory($stubsPath);
        }

        if (!File::exists(base_path('app/Http/Controllers/SocialMediaAuthController.php')))
        {
            $controllerStub = $this->replaceNameSpace(File::get(__DIR__ . '/../../stubs/SocialMediaAuthController.stub'));
            File::put(base_path('app/Http/Controllers/SocialMediaAuthController.php'), $controllerStub);
        }

        $this->info('SocialMediaAuthController stub published.');
    }

    private function replaceNameSpace($file)
    {
        return str_replace('{{namespace}}', 'App\Http\Controllers', $file);
    }
}
