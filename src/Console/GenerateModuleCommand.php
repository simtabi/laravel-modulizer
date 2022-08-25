<?php

namespace Simtabi\Modulizer\Console;

use Illuminate\Contracts\Validation\Validator as ValidatorInterface;
use Illuminate\Support\Facades\Validator;
use Simtabi\Modulizer\Support\Conveyor;
use Simtabi\Modulizer\Support\Wrapper;
use Simtabi\Modulizer\Validation\ClassNameRule;

/**
 * Create a brand-new package.
 *
 * @author JeroenG
 **/
class GenerateModuleCommand extends BaseCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'modulizer:module:generate {vendor?} {name?} {--i} {--skeleton=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new package.';

    /**
     * Packages roll off of the conveyor.
     *
     * @var object \Simtabi\Modulizer\Support\Conveyor
     */
    protected $conveyor;

    /**
     * Packages are packed in wrappings to personalise them.
     *
     * @var object \Simtabi\Modulizer\Support\Wrapping
     */
    protected $wrapper;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Conveyor $conveyor, Wrapper $wrapper)
    {
        parent::__construct();

        $this->conveyor = $conveyor;
        $this->wrapper  = $wrapper;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Start the progress bar
        $this->startProgressBar(6);

        $vendor = $this->argument('vendor') ?? 'vendor-name';
        $name   = $this->argument('name') ?? 'package-name';

        if (strstr($vendor, '/')) {
            [$vendor, $name] = explode('/', $vendor);
        }

        // Defining vendor/package, optionally defined interactively
        if ($this->option('i')) {
            $this->conveyor->vendor($this->ask('What will be the vendor name?', $vendor));
            $this->conveyor->package($this->ask('What will be the package name?', $name));
        } else {
            $this->conveyor->vendor($vendor);
            $this->conveyor->package($name);
        }

        // Validate the vendor and package names
        $validator = $this->validateInput($this->conveyor->vendor(), $this->conveyor->package());

        if ($validator->fails()) {
            $this->showErrors($validator);

            return 1;
        }

        // Start creating the package
        $this->info('Creating package '.$this->conveyor->vendor().'\\'.$this->conveyor->package().'...');
        $this->conveyor->checkIfPackageExists();
        $this->makeProgress();

        // Create the package directory
        $this->info('Creating packages directory...');
        $this->conveyor->makeDir($this->conveyor->packagesPath());
        $this->makeProgress();

        // Create the vendor directory
        $this->info('Creating vendor...');
        $this->conveyor->makeDir($this->conveyor->vendorPath());
        $this->makeProgress();

        // Get the modulizer package skeleton
        $this->info('Downloading skeleton...');
        if ($this->option('i')) {
            $this->conveyor->downloadSkeleton($this->ask('What package skeleton would you like to use?', $this->option('skeleton') ?? config('modulizer.skeleton')));
        } else {
            $this->conveyor->downloadSkeleton($this->option('skeleton') ?? null);
        }
        $manifest = (file_exists($this->conveyor->packagePath().'/rewriteRules.php')) ? $this->conveyor->packagePath().'/rewriteRules.php' : null;
        $this->conveyor->renameFiles($manifest);
        $this->makeProgress();

        // Replacing skeleton placeholders
        $this->info('Replacing skeleton placeholders...');
        $this->wrapper->replace([
            ':uc:vendor',
            ':uc:package',
            ':lc:vendor',
            ':lc:package',
            ':kc:vendor',
            ':kc:package',
        ], [
            $this->conveyor->vendorStudly(),
            $this->conveyor->packageStudly(),
            strtolower($this->conveyor->vendor()),
            strtolower($this->conveyor->package()),
            $this->conveyor->vendorKebab(),
            $this->conveyor->packageKebab(),
        ]);

        if ($this->option('i')) {
            $this->interactiveReplace();
        } else {
            $this->wrapper->replace([
                ':author_name',
                ':author_email',
                ':author_homepage',
                ':author_role',
                ':license',
            ], [
                config('modulizer.author_name'),
                config('modulizer.author_email'),
                config('modulizer.author_homepage'),
                config('modulizer.author_role'),
                config('modulizer.license'),
            ]);
        }

        // Fill all placeholders in all files with the replacements.
        $this->wrapper->fill($this->conveyor->packagePath());

        // Make sure to remove the rule files to avoid clutter.
        if ($manifest !== null) {
            $this->conveyor->cleanUpRules();
        }

        $this->makeProgress();

        // Add path repository to composer.json and install package
        $this->info('Installing package...');
        $this->conveyor->installPackage();

        $this->makeProgress();

        // Finished creating the package, end of the progress bar
        $this->finishProgress('Package created successfully!');
    }

    /**
     * Use the interactive CLI to replace certain placeholders.
     *
     * @return void
     */
    protected function interactiveReplace()
    {
        $author         = $this->ask('Who is the author?', config('modulizer.author_name'));
        $authorEmail    = $this->ask('What is the author\'s e-mail?', config('modulizer.author_email'));
        $authorHomepage = $this->ask('What is the author\'s website?', config('modulizer.author_homepage'));
        $authorRole     = $this->ask('What is the author\'s role?', config('modulizer.author_role'));
        $description    = $this->ask('How would you describe the package?');
        $license        = $this->ask('Under which license will it be released?', config('modulizer.license'));

        $this->wrapper->replace([
            ':author_name',
            ':author_email',
            ':author_homepage',
            ':author_role',
            ':package_description',
            ':license',
        ], [
            $author,
            $authorEmail,
            $authorHomepage,
            $authorRole,
            $description,
            $license,
        ]);
    }

    private function validateInput(string $vendor, string $name)
    {
        return Validator::make(compact('vendor', 'name'), [
            'vendor' => new ClassNameRule,
            'name'   => new ClassNameRule,
        ]);
    }

    private function showErrors(ValidatorInterface $validator)
    {
        $this->info('Package was not created. Please choose a valid name.');

        foreach ($validator->errors()->all() as $error) {
            $this->error($error);
        }
    }
}
