<?php

namespace Botble\DevTool\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand('cms:locale:remove', 'Remove a locale')]
class LocaleRemoveCommand extends Command
{
    public function handle(): int
    {
        if (! $this->components->confirm('Are you sure you want to permanently delete?', true)) {
            return self::FAILURE;
        }

        if (! preg_match('/^[a-z0-9\-]+$/i', $this->argument('locale'))) {
            $this->components->error('Only alphabetic characters are allowed.');

            return self::FAILURE;
        }

        $defaultLocale = lang_path($this->argument('locale'));
        if (File::exists($defaultLocale)) {
            File::deleteDirectory($defaultLocale);
            $this->components->info('Deleted: ' . $defaultLocale);
        }

        File::delete(lang_path($this->argument('locale')) . '.json');
        $this->removeLocaleInPath(lang_path('vendor/core'));
        $this->removeLocaleInPath(lang_path('vendor/packages'));
        $this->removeLocaleInPath(lang_path('vendor/plugins'));

        $this->components->info('Removed locale "' . $this->argument('locale') . '" successfully!');

        return self::SUCCESS;
    }

    protected function removeLocaleInPath(string $path): int
    {
        if (! File::isDirectory($path)) {
            return self::SUCCESS;
        }

        $folders = File::directories($path);

        foreach ($folders as $module) {
            foreach (File::directories($module) as $locale) {
                if (File::name($locale) == $this->argument('locale')) {
                    File::deleteDirectory($locale);
                    $this->components->info('Deleted: ' . $locale);
                }
            }
        }

        return count($folders);
    }

    protected function configure(): void
    {
        $this->addArgument('locale', InputArgument::REQUIRED, 'The locale name that you want to remove');
    }
}
