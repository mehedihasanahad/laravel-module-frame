<?php

namespace App\Core\ProcessEngine\Handlers;

use App\Core\ProcessEngine\Models\ProcessType;

abstract class AddonFormHandler
{
    protected int $statusId;
    protected int $applicationId;
    protected int $processTypeId;
    protected string $addonsModuleFolderName;
    protected string $addonsDirectory;

    public function __construct($processTypeId, $applicationId, $statusId)
    {
        $this->processTypeId = $processTypeId;
        $this->applicationId = $applicationId;
        $this->statusId = $statusId;
        $this->addonsModuleFolderName = ProcessType::find($this->processTypeId)->module_folder_name;
        $this->addonsDirectory = app_path("/Modules/$this->addonsModuleFolderName/resources/views/addons");
    }

    /**
     * get addon file name based on the statusId
     *
     * @return bool|string
     */
    public function getAddonForm(): bool|string
    {
        $addonBladeFileName = "addon-$this->statusId.blade.php";
        if (!file_exists("$this->addonsDirectory/$addonBladeFileName")) {
            return false;
        }

        return "$this->addonsModuleFolderName::addons.addon-$this->statusId";
    }

    /**
     * Prepare data based on the statusId.
     *
     * @return array The prepared data.
     */
    public abstract function prepareData(): array;

}
