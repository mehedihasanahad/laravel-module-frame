<?php

namespace App\Core\FormBuilder\Interfaces;

use App\Core\FormBuilder\Http\Requests\FormStoreRequest;
use Illuminate\Http\RedirectResponse;

interface FormControllerInterface
{
    /**
     * @param FormStoreRequest $request
     * @return RedirectResponse
     */
    public function store(FormStoreRequest $request): RedirectResponse;

    /**
     * @param FormStoreRequest $request
     * @return RedirectResponse
     */
    public function update(FormStoreRequest $request): RedirectResponse;

}
