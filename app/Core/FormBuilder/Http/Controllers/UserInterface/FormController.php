<?php

namespace App\Core\FormBuilder\Http\Controllers\UserInterface;

use App\Core\FormBuilder\Http\Requests\StoreFormRequest;
use App\Core\FormBuilder\Models\Form;
use App\Core\ProcessEngine\Models\ProcessType;
use App\Http\Controllers\Controller;
use App\Libraries\Encryption;
use App\Traits\ApiResponse;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class FormController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     * @throws \Exception
     */
    public function index()
    {
        if (request()->ajax()) {
            $forms = Form::query()->with(['process:id,name'])->get();
            return DataTables::of($forms)
                ->addColumn('action', function ($row) {
                    return
                        '<a href="' . route('form.edit', Encryption::encodeId($row->id)) . ' " class="btn btn-sm btn-secondary">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form style="display: inline-block" action="' . route('form.destroy', Encryption::encodeId($row->id)) . '" method="POST">
                        <input type="hidden" name="_token" value="' . csrf_token() . '"/>
                        <input type="hidden" name="_method" value="DELETE"/>
                        <button class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                    ';
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('FormBuilder::form.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $processTypes = ProcessType::query()->active()->get();
        return view('FormBuilder::form.add', compact('processTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreFormRequest $request
     * @return JsonResponse
     */
    public function store(StoreFormRequest $request)
    {
        try {
            $form = new Form();
            $data = $request->validated();
            $step_names = collect($data['steps_name'])->pluck('name')->implode(',');
            $queries = collect($data['form_data_json'])->mapWithKeys(fn($value) => [$value['key'] => $value['value']]);
            $data['steps_name'] = $step_names;
            $data['form_data_json'] = $queries;
            $form->fill($data)->save();
            Session::flash('success', 'Form Created Successfully');
            return $this->successResponse($form, 'Form Created Successfully');
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        $processTypes = ProcessType::query()->active()->get();
        $form = Form::query()->findOrFail(Encryption::decodeId($id));
        $stepNames = empty($form->steps_name)? [] : collect(explode(',', $form->steps_name))
            ->map(fn($value) => ['name' => $value])->toArray();

        $formDataJson = collect($form->form_data_json)->map(fn($collection, $key) => [
            'key' => $key,
            'value' => $collection
        ])->when(!$form->form_data_json, function () {
            return collect([['key' => '', 'value' => '']]);
        })->values()->toArray();
        $data = [
            'id' => Encryption::encodeId($form->id),
            'process_type_id' => $form->process_type_id,
            'form_type' => $form->form_type,
            'template_type' => $form->template_type,
            'title' => $form->title,
            'form_id' => $form->form_id,
            'steps' => $form->steps ?? 0,
            'steps_name' => $stepNames,
            'app_model_namespace' => $form->app_model_namespace,
            'app_id' => $form->app_id,
            'form_data_json' => $formDataJson,
            'method' => $form->method,
            'action' => $form->action,
            'autocomplete' => $form->autocomplete,
            'enctype' => $form->enctype,
            'status' => $form->status,
        ];

        return view('FormBuilder::form.edit', compact('data', 'processTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreFormRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function update(StoreFormRequest $request, $id)
    {
        try {
            $form = Form::query()->findOrFail(Encryption::decodeId($id));
            $data = $request->validated();
            $step_names = collect($data['steps_name'])->pluck('name')->implode(',');
            $queries = collect($data['form_data_json'])->mapWithKeys(fn($value) => [$value['key'] => $value['value']]);
            $data['steps_name'] = $step_names;
            $data['form_data_json'] = $queries;
            $form->fill($data)->save();
            Session::flash('success', 'Form Updated Successfully');
            return $this->successResponse($form, 'Form Updated Successfully');
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        Form::query()->findOrFail(Encryption::decodeId($id))->delete();
        return redirect()->back()->with('success', 'Form Deleted successfully');
    }

}
