<?php

namespace Botble\DevTool\Commands;

use Illuminate\Support\Facades\File;
use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand('cms:locale:create', 'Create a new locale')]
class LocaleCreateCommand extends Command
{
    public function handle(): int
    {
        if (! preg_match('/^[a-z0-9\-]+$/i', $this->argument('locale'))) {
            $this->components->error('Only alphabetic characters are allowed.');

            return self::FAILURE;
        }

        $defaultLocale = lang_path('en');
        if (File::exists($defaultLocale)) {
            File::copyDirectory($defaultLocale, lang_path($this->argument('locale')));
            $this->components->info('Created: ' . lang_path($this->argument('locale')));
        }

        $this->createLocaleInPath(lang_path('vendor/core'));
        $this->createLocaleInPath(lang_path('vendor/packages'));
        $this->createLocaleInPath(lang_path('vendor/plugins'));

        return self::SUCCESS;
    }

    protected function createLocaleInPath(string $path): int
    {
        if (! File::isDirectory($path)) {
            return self::SUCCESS;
        }

        $folders = File::directories($path);

        foreach ($folders as $module) {
            foreach (File::directories($module) as $locale) {
                if (File::name($locale) == 'en') {
                    File::copyDirectory($locale, $module . '/' . $this->argument('locale'));
                    $this->components->info('Created: ' . $module . '/' . $this->argument('locale'));
                }
            }
        }

        return count($folders);
    }

    protected function configure(): void
    {
        $this->addArgument('locale', InputArgument::REQUIRED, 'The locale name that you want to create');
    }
}
