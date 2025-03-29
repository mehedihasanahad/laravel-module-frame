<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class createAddonForm extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:addonForm {module_folder_name} {status_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $status_id = $this->argument('status_id');
            $module_folder_name = $this->argument('module_folder_name');
            if (!file_exists("app/Modules/$module_folder_name"))
                throw new \Exception("This module doesn't exist in {app/Modules/} path.");

            // addon file
            $module_addon_path = "app/Modules/$module_folder_name/resources/views/addons";
            if(!file_exists($module_addon_path)) mkdir($module_addon_path);
            file_put_contents("$module_addon_path/addon-$status_id.".'blade.php', '<h1>Addon Form</h1>');

            $this->info('Addon form file created.');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
