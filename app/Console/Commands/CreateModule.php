<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class CreateModule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:moduleStructure {module_folder_name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Module Structure.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {

            Artisan::call('make:module', [
                'name' => $this->argument('module_folder_name'),
            ]);

            $module_folder_name = $this->argument('module_folder_name');
            if (!file_exists("app/Modules/$module_folder_name"))
                throw new \Exception("This module doesn't exist in {app/Modules/} path.");

            // Controller file
            $module_controller_path = "app/Modules/$module_folder_name/Http/Controllers";
            if (!file_exists($module_controller_path)) mkdir($module_controller_path);
            file_put_contents("$module_controller_path/$module_folder_name" . 'Controller.php', $this->getControllerContent($module_folder_name));

            // Services file
            $module_services_path = "app/Modules/$module_folder_name/Services";
            if (!file_exists($module_services_path)) mkdir($module_services_path);
            file_put_contents("$module_services_path/$module_folder_name" . 'AddonForm.php', $this->getAddonContent($module_folder_name));
            file_put_contents("$module_services_path/$module_folder_name" . 'ProcessService.php', $this->getProcessContent($module_folder_name));

            // Blade file
            $module_blade_path = "app/Modules/$module_folder_name/Resources/views";
            if (!file_exists($module_blade_path)) mkdir($module_blade_path);
            foreach ($this->getBladeContent() as $fileName => $content) {
                file_put_contents("$module_blade_path/$fileName", $content);
            }
            $this->info('Module file structure created.');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * Get controller content
     * @param string $module_folder_name Module folder name
     * @return string
     */
    public function getControllerContent($module_folder_name): string
    {
        return
            "<?php

namespace App\Modules\\$module_folder_name\Http\Controllers;

use App\Core\ProcessEngine\Handlers\ApplicationFormHandler;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class $module_folder_name" . 'Controller' . " extends ApplicationFormHandler
{
    /**
     * This method will be used by the framework. This will render form
     * @param Request \$request
     * @param \$processTypeId
     * @param \$applicationId
     * @return string
     */
    public function create(): string
    {
         return (string) view('$module_folder_name::form');
    }

    /**
     * This method will be used by the framework. This will render form edit page
     * @param Request \$request
     * @param \$processTypeId
     * @param \$applicationId
     * @return string
     */
    public function edit(\$processTypeId, \$applicationId): string
    {
        return (string) view('$module_folder_name::form');
    }

    /**
     * This method will be used by the framework. This will render view page
     * @param Request \$request
     * @param \$processTypeId
     * @param \$applicationId
     * @return string
     */
    public function view(\$processTypeId, \$applicationId): string
    {
        return (string) view('$module_folder_name::form');
    }
}
        ";
    }

    /**
     * Get addon form content
     * @param string $module_folder_name Module folder name
     * @return string
     */
    public function getAddonContent($module_folder_name): string
    {
        return
            "<?php

namespace App\Modules\\$module_folder_name\Services;

use App\Core\ProcessEngine\Handlers\AddonFormHandler;


class $module_folder_name" . 'AddonForm' . " extends AddonFormHandler
{
    /**
     * prepareData addon form data. This will automatically available in addon blade
     * @return array
     */
    public function prepareData(): array
    {
        \$data = [];
        switch (\$this->statusId) {
            case 1 :
                //TODO:: prepare data
                break;
        }
        return \$data;
    }
}

        ";
    }

    /**
     * Get process content
     * @param string $module_folder_name Module folder name
     * @return string
     */
    public function getProcessContent($module_folder_name): string
    {
        return
            "<?php

namespace App\Modules\\$module_folder_name\Services;

use App\Core\ProcessEngine\Handlers\ProcessCallbackInterface;

class $module_folder_name" . 'ProcessService' . " implements ProcessCallbackInterface {

    /**
     * When application will process this method will be executed every time.
     * @param \$processTypeId
     * @param \$statusId
     * @param \$deskId
     * @param \$request
     * @return string
     */
    public function processCallback(\$processTypeId, \$statusId, \$deskId, \$request):bool
    {
        switch (\$statusId) {
            case 1:
                //TODO:: execute operations
                break;
        }
        return true;
    }

}

        ";
    }

    /**
     * Get blade content
     * @return string[]
     */
    public function getBladeContent(): array
    {
        return [
            'form.blade.php' => "@includeIf('FormBuilder::form-builder.pages.form.layouts.index')",
        ];
    }
}
