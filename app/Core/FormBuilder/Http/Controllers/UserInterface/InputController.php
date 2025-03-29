<?php

namespace App\Core\FormBuilder\Http\Controllers\UserInterface;

use App\Core\FormBuilder\Actions\InputAction;
use App\Core\FormBuilder\Http\Requests\StoreInputRequest;
use App\Core\FormBuilder\Models\Component;
use App\Core\FormBuilder\Models\Form;
use App\Core\FormBuilder\Models\Input;
use App\Core\FormBuilder\Models\InputGroup;
use App\Core\FormBuilder\Services\InputService;
use App\Http\Controllers\Controller;
use App\Libraries\Encryption;
use App\Traits\ApiResponse;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class InputController extends Controller
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
            $inputs = Input::query()->with(['form:id,title', 'component:id,title'])->get();
            return DataTables::of($inputs)
                ->addColumn('action', function ($row) {
                    return
                        '<a href="' . route('input.edit', Encryption::encodeId($row->id)) . ' " class="btn btn-sm btn-secondary">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form style="display: inline-block" action="' . route('input.destroy', Encryption::encodeId($row->id)) . '" method="POST">
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
        return view('FormBuilder::input.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $forms = Form::query()->active()->get();
        return view('FormBuilder::input.add', compact('forms'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreInputRequest $request
     * @param Input $input
     * @return JsonResponse
     */
    public function store(StoreInputRequest $request, Input $input)
    {
        try {
            InputAction::save($request, $input);
            Session::flash('success', 'Input Created Successfully');
            return $this->successResponse($input, 'Input Created Successfully');
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
            return $this->errorResponse($e->getMessage());
        }
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
        $input = Input::query()->findOrFail(Encryption::decodeId($id));
        $components = Component::query()
            ->active()
            ->where('form_id', $input->form_id)
            ->get();

        $inputGroups = InputGroup::query()
            ->where('form_id', $input->form_id)
            ->where('component_id', $input->component_id)
            ->get();

        $data = InputService::formatData($input);
        return view('FormBuilder::input.edit', compact('data', 'forms', 'components', 'inputGroups'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreInputRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function update(StoreInputRequest $request, $id)
    {
        try {
            $input = InputAction::update($request, $id);
            Session::flash('success', 'Input Updated Successfully');
            return $this->successResponse($input, 'Input Updated Successfully');
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
