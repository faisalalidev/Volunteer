<?php

namespace Botble\DevTool\Commands\Abstracts;

use BaseHelper;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use League\Flysystem\MountManager;

abstract class BaseMakeCommand extends Command
{
    public function searchAndReplaceInFiles(string $pattern, string $location, $stub = null): bool
    {
        $replacements = $this->replacements($pattern);

        if (File::isFile($location)) {
            if (! $stub) {
                $stub = File::get($this->getStub());
            }

            $replace = $this->getReplacements($pattern) + $this->baseReplacements($pattern);

            $content = str_replace(array_keys($replace), $replace, $stub);

            File::put($location, $content);

            return true;
        }

        $manager = new MountManager([
            'directory' => new Filesystem(new LocalFilesystemAdapter($location)),
        ]);

        foreach ($manager->listContents('directory://', true) as $file) {
            if ($file['type'] === 'file') {
                $content = str_replace(
                    array_keys($replacements),
                    array_values($replacements),
                    $manager->read($file['path'])
                );

                $manager->write($file['path'], $content);
            }
        }

        return true;
    }

    public function replacements(string $replaceText): array
    {
        return array_merge($this->baseReplacements($replaceText), $this->getReplacements($replaceText));
    }

    public function baseReplacements(string $replaceText): array
    {
        return [
            '{-name}' => strtolower($replaceText),
            '{name}' => Str::snake(str_replace('-', '_', $replaceText)),
            '{+name}' => Str::camel($replaceText),
            '{++name}' => str_replace('_', ' ', Str::snake(str_replace('-', '_', $replaceText))),
            '{names}' => Str::plural(Str::snake(str_replace('-', '_', $replaceText))),
            '{Names}' => ucfirst(Str::plural(Str::snake(str_replace('-', '_', $replaceText)))),
            '{++Names}' => str_replace(
                '_',
                ' ',
                ucfirst(Str::plural(Str::snake(str_replace('-', '_', $replaceText))))
            ),
            '{-names}' => Str::plural($replaceText),
            '{NAME}' => strtoupper(Str::snake(str_replace('-', '_', $replaceText))),
            '{Name}' => ucfirst(Str::camel($replaceText)),
            '.stub' => '.php',
            '{migrate_date}' => Carbon::now()->format('Y_m_d_His'),
            '{type}' => 'package',
            '{types}' => 'packages',
        ];
    }

    abstract public function getReplacements(string $replaceText): array;

    abstract public function getStub(): string;

    public function renameFiles(string $pattern, string $location): bool
    {
        $paths = BaseHelper::scanFolder($location);

        if (empty($paths)) {
            return false;
        }

        foreach ($paths as $path) {
            $path = $location . DIRECTORY_SEPARATOR . $path;

            $newPath = $this->transformFileName($pattern, $path);
            rename($path, $newPath);

            $this->renameFiles($pattern, $newPath);
        }

        return true;
    }

    public function transformFileName(string $pattern, string $path): string
    {
        $replacements = $this->replacements($pattern);

        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $path
        );
    }

    protected function publishStubs(string $from, string $to): void
    {
        $this->createParentDirectory(File::dirname($to));

        if (File::isDirectory($from)) {
            $this->publishDirectory($from, $to);
        } else {
            File::copy($from, $to);
        }
    }

    protected function createParentDirectory(string $path): void
    {
        if (! File::isDirectory($path) && ! File::isFile($path)) {
            File::makeDirectory($path, 0755, true);
        }
    }

    protected function publishDirectory(string $from, string $to): void
    {
        File::copyDirectory($from, $to);
    }
}
