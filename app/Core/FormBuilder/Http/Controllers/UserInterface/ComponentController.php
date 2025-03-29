<?php

namespace App\Core\FormBuilder\Http\Controllers\UserInterface;

use App\Core\FormBuilder\Http\Requests\StoreComponentRequest;
use App\Core\FormBuilder\Models\Component;
use App\Core\FormBuilder\Models\Form;
use App\Http\Controllers\Controller;
use App\Libraries\Encryption;
use App\Traits\ApiResponse;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Yajra\DataTables\Facades\DataTables;

class ComponentController extends Controller
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
            $components = Component::query()->with('form')->get();
            return DataTables::of($components)
                ->addColumn('action', function ($row) {
                    return
                        '<a href="' . route('component.edit', Encryption::encodeId($row->id)) . ' " class="btn btn-sm btn-secondary">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form style="display: inline-block" action="' . route('component.destroy', Encryption::encodeId($row->id)) . '" method="POST">
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

        return view('FormBuilder::component.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $forms = Form::query()->active()->get();
        return view('FormBuilder::component.add', compact('forms'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreComponentRequest $request
     * @return RedirectResponse
     */
    public function store(StoreComponentRequest $request)
    {
//        dd($request->validated());
        $component = Component::query()->create($request->validated());
        return redirect()->route('component.index')->with('success', 'Component created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        try {
            $component = Component::query()->where('form_id', $id)->get();
            return $this->successResponse($component, 'Component fetched successfully');
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
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
        $component = Component::query()->findOrFail(Encryption::decodeId($id));
        $parentComponents = Component::query()->where('form_id', $component->form_id)->get();
        return view('FormBuilder::component.edit', compact('forms', 'component', 'parentComponents'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreComponentRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(StoreComponentRequest $request, $id)
    {
        $component = Component::query()->findOrFail(Encryption::decodeId($id));
        $component->update($request->validated());
        return redirect()->route('component.index')->with('success', 'Component updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        Component::query()->findOrFail(Encryption::decodeId($id))->delete();
        return redirect()->route('component.index')->with('success', 'Component deleted successfully');
    }
}
