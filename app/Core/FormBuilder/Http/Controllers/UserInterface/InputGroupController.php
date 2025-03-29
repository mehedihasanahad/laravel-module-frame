<?php

namespace App\Core\FormBuilder\Http\Controllers\UserInterface;

use App\Core\FormBuilder\Http\Requests\StoreInputGroupRequest;
use App\Core\FormBuilder\Models\Component;
use App\Core\FormBuilder\Models\Form;
use App\Core\FormBuilder\Models\InputGroup;
use App\Http\Controllers\Controller;
use App\Libraries\Encryption;
use App\Traits\ApiResponse;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Yajra\DataTables\Facades\DataTables;

class InputGroupController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|JsonResponse
     * @throws \Exception
     */
    public function index()
    {
        if (request()->ajax()) {
            $groupInputs = InputGroup::query()->with(['form', 'component'])->get();
            return DataTables::of($groupInputs)
                ->addColumn('action', function ($row) {
                    return
                        '<a href="' . route('input-group.edit', Encryption::encodeId($row->id)) . ' " class="btn btn-sm btn-secondary">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form style="display: inline-block" action="' . route('input-group.destroy', Encryption::encodeId($row->id)) . '" method="POST">
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

        return view('FormBuilder::input-group.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $forms = Form::query()->active()->get();
        return view('FormBuilder::input-group.add', compact('forms'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreInputGroupRequest $request
     * @return RedirectResponse
     */
    public function store(StoreInputGroupRequest $request)
    {
        InputGroup::query()->create($request->validated());
        return redirect()->route('input-group.index')->with('success', 'Input Group created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
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
        $forms = Form::query()->active()->get();
        $inputGroup = InputGroup::query()->findOrFail(Encryption::decodeId($id));
        $components = Component::query()->where('form_id', $inputGroup->form_id)->get();
        return view('FormBuilder::input-group.edit', compact('forms', 'inputGroup', 'components'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreInputGroupRequest $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(StoreInputGroupRequest $request, $id)
    {
        InputGroup::query()->findOrFail(Encryption::decodeId($id))->update($request->validated());
        return redirect()->route('input-group.index')->with('success', 'Input Group updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        InputGroup::query()->findOrFail(Encryption::decodeId($id))->delete();
        return redirect()->route('input-group.index')->with('success', 'Input Group deleted successfully');
    }

    public function inputGroups($formId, $componentId)
    {
        try {
            $component = InputGroup::query()->where('form_id', $formId)->where('component_id', $componentId)->get();
            return $this->successResponse($component, 'Input Group fetched successfully');
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }
}
