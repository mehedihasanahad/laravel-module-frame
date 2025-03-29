<?php

namespace App\Providers;


use App\Core\ProcessEngine\Handlers\AddonFormHandler;
use App\Core\ProcessEngine\Handlers\ApplicationFormHandler;
use App\Core\ProcessEngine\Handlers\ProcessCallbackInterface;
use App\Core\ProcessEngine\Models\ProcessList;
use App\Core\ProcessEngine\Models\ProcessType;
use App\Libraries\Encryption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class ModuleProcessServiceProvider extends ServiceProvider
{
    private string $moduleFolderName = '';

    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $requestInstance = app()->make('request');
        try {
            # Bind ApplicationFormHandler to a callback function
            $this->app->bind(ApplicationFormHandler::class, function () use ($requestInstance) {
                $encodedProcessTypeId = $requestInstance->segment(3);
                $processTypeId = Encryption::decodeId($encodedProcessTypeId);

                # Retrieve the module folder name for the given process type ID
                $this->moduleFolderName = ProcessType::whereId($processTypeId)->value('module_folder_name');
                if (empty($this->moduleFolderName)) {
                    abort(500, 'Module folder name not found. Please check the "module_folder_name" value for the given "process_types" table.');
                }

                # Define directory and get all the files in the directory
                $Controllers = app_path("Modules/$this->moduleFolderName/Http/Controllers");
                $files = File::allFiles($Controllers);

                $instance = [];

                foreach ($files as $file) {
                    # Get the relative path of the file and remove .php extension
                    $relativePath = $file->getRelativePathName();
                    $className = str_replace('.php', '', $relativePath);
                    $className = str_replace('/', '\\', $className);

                    # Build the full class name with the module folder name and create a ReflectionClass instance
                    $fullClassName = "App\\Modules\\$this->moduleFolderName\\Http\\Controllers\\$className";
                    $reflectionClass = new \ReflectionClass($fullClassName);

                    # Check if the class is a subclass of ApplicationFormHandler
                    if ($reflectionClass->isSubclassOf(ApplicationFormHandler::class)) {
                        # Create an instance
                        $instance[] = app()->make($fullClassName);
                    }
                }

                # validate class instance
                $this->validateClassInstances($instance, "BaseController", 'ApplicationFormHandler', $Controllers,"[MPSP-63]");

                # Return the first instance of a class
                return $instance[0];

            });

            # Bind ProcessCallbackInterface to a callback function
            $this->app->bind(ProcessCallbackInterface::class, function () use ($requestInstance) {
                $encodedProcessTypeId = $requestInstance->get('process_type_id');
                $processTypeId = Encryption::decodeId($encodedProcessTypeId);

                # Retrieve the module folder name for the given process type ID
                if (empty($this->moduleFolderName)) {
                    $this->moduleFolderName = ProcessType::whereId($processTypeId)->value('module_folder_name');
                }

                # Define directory and get all the files in the directory
                $directory = app_path("Modules/$this->moduleFolderName/Services");
                $files = File::allFiles($directory);

                $instance = [];
                foreach ($files as $file) {
                    # Get the relative path of the file and remove .php extension
                    $relativePath = $file->getRelativePathName();
                    $className = str_replace('.php', '', $relativePath);
                    # Build the full class name with the module folder name and create a ReflectionClass instance
                    $fullClassName = "App\\Modules\\$this->moduleFolderName\\Services\\$className";
                    $reflectionClass = new \ReflectionClass($fullClassName);

                    # Check if the class is a subclass of ProcessCallbackInterface
                    if ($reflectionClass->isSubclassOf(ProcessCallbackInterface::class)) {
                        # Create an instance
                        $instance[] = $this->app->make($fullClassName);
                    }
                }

                # validate class instance
                $this->validateClassInstances($instance, "ProcessService", 'ProcessCallbackInterface', $directory,"[MPSP-101]");

                # Return the first instance of a class
                return $instance[0];

            });

            # Bind AddonFormHandler to a callback function
            $this->app->bind(AddonFormHandler::class, function () use ($requestInstance) {
                $encodedProcessListId = $requestInstance->get('process_list_id');
                $processListId = Encryption::decodeId($encodedProcessListId);

                $encodedProcessTypeId = $requestInstance->get('process_type_id');
                $processTypeId = Encryption::decodeId($encodedProcessTypeId);

                $statusId = trim($requestInstance->get('statusId'));

                # Retrieve the module folder name for the given process type ID
                if (empty($this->moduleFolderName)) {
                    $this->moduleFolderName = ProcessType::whereId($processTypeId)->value('module_folder_name');
                }

                $applicationId = ProcessList::where('id', $processListId)->value('ref_id');

                # Define directory and get all the files in the directory
                $directory = app_path("Modules/$this->moduleFolderName/Services");
                $files = File::allFiles($directory);
                $instance = [];
                foreach ($files as $file) {
                    # Get the relative path of the file and remove .php extension
                    $relativePath = $file->getRelativePathName();
                    $className = str_replace('.php', '', $relativePath);
                    # Build the full class name with the module folder name and create a ReflectionClass instance
                    $fullClassName = "App\\Modules\\$this->moduleFolderName\\Services\\$className";
                    $reflectionClass = new \ReflectionClass($fullClassName);

                    # Check if the class is a subclass of ProcessCallbackInterface
                    if ($reflectionClass->isSubclassOf(AddonFormHandler::class)) {
                        # Create an instance
                        $instance[] = $reflectionClass->newInstanceArgs([
                            'processTypeId' => $processTypeId,
                            'applicationId' => $applicationId,
                            'statusId' => $statusId,
                        ]);
                    }
                }
                # validate class instance
                $this->validateClassInstances($instance, "AddonForm", 'AddonFormHandler', $directory,"[MPSP-148]");

                # Return the first instance of a class
                return $instance[0];

            });

        } catch (\Exception $exception) {
            # Abort with an error message if an exception occurs
            abort(500, $exception->getMessage());
        }
    }

    /**
     * validated class instances
     *
     * @param array $instances
     * @param string $subClassName
     * @param string $baseClassName
     * @param $subClassDirectory
     * @param $errorCode
     */
    private function validateClassInstances(array $instances, string $subClassName, string $baseClassName, $subClassDirectory,$errorCode): void
    {
        if (count($instances) == 0) {
            $errorMessage = "No {$subClassName} class found in the specified - [$subClassDirectory] folder. Make sure to extend or implement the {$baseClassName} in your corresponding {$subClassName} class.$errorCode";
            abort(500, $errorMessage);
        } elseif (count($instances) != 1) {
            $errorMessage = "Only one {$subClassName} class should extend or implement the {$baseClassName}.$errorCode";
            abort(500, $errorMessage);
        }
    }

}
