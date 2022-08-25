<?php declare(strict_types=1);

namespace Simtabi\Modulizer\Console;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Simtabi\Modulizer\Helpers\ModuleHelpers;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;
use Symfony\Component\Finder\Finder;

class GenerateNewModuleCommand extends BaseCommand
{
    protected const BASE_PATH = __DIR__ . '/../../../';
    protected $signature      = 'modulizer:module:generate';
    protected $description    = 'Create starter module from a template';
    protected $caseTypes      = [
        'module' => 'strtolower',
        'Module' => 'ucwords',
        'model'  => 'strtolower',
        'Model'  => 'ucwords',
    ];

    public function handle()
    {
        $this->container['name'] = ucwords($this->ask('Please enter a name'));
        $modulesPath             = ModuleHelpers::getModulesPath($this->container['name']);
        $stubsPath               = ModuleHelpers::getStubsPath();

        // Start the progress bar
        $this->startProgressBar(2);

        if (strlen($this->container['name']) == 0) {
            $this->error("\nName cannot be empty.");
            return $this->handle();
        } else {
            if (File::exists($modulesPath)) {
                $this->error("\n{$this->container['name']} Module already exists.");
                return true;
            }
        }

        $this->container['folder'] =  config('modulizer.stubs_path');
        if (!File::exists($stubsPath)) {
            $this->error("\nModulizer stubs path does not exist.");
            return true;
        }

        $this->generate($this->container['name']);

        $this->info('Starter '.$this->container['name'].' module generated successfully.');

        // Finished removing the package, end of the progress bar
        $this->finishProgress('Package disabled successfully!');
    }

    protected function generate(string $moduleName)
    {
        $tempFolderPath = storage_path('modulizer-temp');
        $modulePath     = ModuleHelpers::getModulesPath(Str::lower($moduleName));

        //ensure directory does not exist
        $this->delete($tempFolderPath);

        $this->container['folder'] = ModuleHelpers::getStubsPath();
        $folder                    = $this->container['folder'];

        $this->copy($folder, $tempFolderPath);

        $finder     = new Finder();
        $finder->files()->in($tempFolderPath);

        $this->renameFiles($finder);
        $this->updateFilesContent($finder);

        ModuleHelpers::ensureFolderExists($modulePath);

        $this->copy($tempFolderPath, $modulePath);

        $this->delete($tempFolderPath);

        $this->makeProgress();
    }

    protected function updateFilesContent($finder)
    {
        foreach ($finder as $file) {
            $sourceFile = $file->getPath().'/'.$file->getFilename();
            $this->replaceInFile($sourceFile);
        }
    }

    protected function renameFiles($finder)
    {
        foreach ($finder as $file) {
            $type       = Str::endsWith($file->getPath(), ['migrations', 'Migrations']) ? 'migration' : '';
            $sourceFile = $file->getPath().'/'.$file->getFilename();
            $this->alterFilename($sourceFile, $type);
        }
    }

    protected function alterFilename($sourceFile, $type = '')
    {
        $name  = ucwords($this->container['name']);
        $model = Str::singular($name);

        $targetFile = $sourceFile;
        $targetFile = str_replace('Module', $name, $targetFile);
        $targetFile = str_replace('module', strtolower($name), $targetFile);
        $targetFile = str_replace('Model', $model, $targetFile);
        $targetFile = str_replace('model', strtolower($model), $targetFile);

        if (in_array(basename($sourceFile), config('modulizer.ignore_files'))) {
            $targetFile = dirname($targetFile).'/'.basename($sourceFile);
        }

        //hack to ensure Models exists
        $targetFile = str_replace("Entities", "Models", $targetFile);

        //hack to ensure modules if used does not get replaced
        if (Str::contains($targetFile, $name.'s')) {
            $targetFile = str_replace($name.'s', "Modules", $targetFile);
        }

        if (!is_dir(dirname($targetFile))) {
            mkdir(dirname($targetFile), 0777, true);
        }

        $this->rename($sourceFile, $targetFile, $type);
    }

    protected function rename($sourceFile, $targetFile, $type = '')
    {
        $filesystem = new SymfonyFilesystem;
        if ($filesystem->exists($sourceFile)) {
            if ($type == 'migration') {
                $targetFile = $this->appendTimestamp($targetFile);
            }
            $filesystem->rename($sourceFile, $targetFile, true);
        }
    }

    protected function appendTimestamp($sourceFile)
    {
        $timestamp = date('Y_m_d_his_');
        $file      = basename($sourceFile);
        return str_replace($file, $timestamp.$file, $sourceFile);
    }

    protected function copy($sourceFile, $target)
    {
        $filesystem = new SymfonyFilesystem;
        if ($filesystem->exists($sourceFile)) {
            $filesystem->mirror($sourceFile, $target);
        }
    }

    protected function replaceInFile($sourceFile)
    {
        $name  = ucwords($this->container['name']);
        $model = Str::singular($name);
        $types = [
            '{Module_}'  => null,
            '{module_}'  => null,
            '{module-}'  => null,
            '{Module-}'  => null,
            '{Module}'   => $name,
            '{module}'   => strtolower($name),
            '{module }'  => trim(preg_replace('/(?<!\ )[A-Z]/', ' $0', strtolower($name))),
            '{Model-}'   => null,
            '{model-}'   => null,
            '{Model_}'   => null,
            '{model_}'   => null,
            '{Model}'    => $model,
            '{model}'    => strtolower($model),
            '{model }'   => trim(preg_replace('/(?<!\ )[A-Z]/', ' $0', strtolower($model)))
        ];

        foreach ($types as $key => $value) {
            if (File::exists($sourceFile)) {
                if ($key == '{Module_}') {
                    $parts = preg_split('/(?=[A-Z])/', $name, -1, PREG_SPLIT_NO_EMPTY);
                    $value = implode('_', $parts);
                }

                if ($key == '{module_}') {
                    $parts = preg_split('/(?=[A-Z])/', $name, -1, PREG_SPLIT_NO_EMPTY);
                    $parts = array_map('strtolower', $parts);
                    $value = implode('_', $parts);
                }

                if ($key == '{Module-}') {
                    $parts = preg_split('/(?=[A-Z])/', $name, -1, PREG_SPLIT_NO_EMPTY);
                    $value = implode('-', $parts);
                }

                if ($key == '{module-}') {
                    $parts = preg_split('/(?=[A-Z])/', $model, -1, PREG_SPLIT_NO_EMPTY);
                    $parts = array_map('strtolower', $parts);
                    $value = implode('-', $parts);
                }

                if ($key == '{model_}') {
                    $parts = preg_split('/(?=[A-Z])/', $model, -1, PREG_SPLIT_NO_EMPTY);
                    $parts = array_map('strtolower', $parts);
                    $value = implode('_', $parts);
                }

                if ($key == '{Model_}') {
                    $parts = preg_split('/(?=[A-Z])/', $model, -1, PREG_SPLIT_NO_EMPTY);
                    $value = implode('_', $parts);
                }

                if ($key == '{model-}') {
                    $parts = preg_split('/(?=[A-Z])/', $model, -1, PREG_SPLIT_NO_EMPTY);
                    $parts = array_map('strtolower', $parts);
                    $value = implode('-', $parts);
                }

                if ($key == '{Model-}') {
                    $parts = preg_split('/(?=[A-Z])/', $model, -1, PREG_SPLIT_NO_EMPTY);
                    $value = implode('-', $parts);
                }

                file_put_contents($sourceFile, str_replace($key, $value, file_get_contents($sourceFile)));
            }
        }
    }

    public function append($sourceFile, $content)
    {
        $filesystem = new SymfonyFilesystem;
        if ($filesystem->exists($sourceFile)) {
            $filesystem->appendToFile($sourceFile, $content);
        }
    }

    public function delete($sourceFile)
    {
        $filesystem = new SymfonyFilesystem;
        if ($filesystem->exists($sourceFile)) {
            $filesystem->remove($sourceFile);
        }
    }
}
