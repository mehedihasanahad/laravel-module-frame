<?php

namespace App\Core\FormBuilder\Services;

use App\Core\FormBuilder\Interfaces\FormServiceInterface;
use App\Core\FormBuilder\Models\Form;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 *
 */
class FormService implements FormServiceInterface
{
    /**
     * @param $processTypeId
     * @param $formType
     * @param $app_id
     * @return mixed
     */
    public static function formatFormData($processTypeId, $formType, $app_id = null): mixed
    {
        $form = Form::query()
            ->where([
                'process_type_id' => $processTypeId,
                'form_type' => $formType
            ])
            ->with(['components' => function ($query) {
                return $query->where('status', 1)
                    ->orderBy('order', 'ASC')
                    ->with(['inputs' => function ($q) {
                        return $q->where('status', 1)
                            ->orderBy('order', 'ASC')
                            ->with('group:id,label,value');
                    }]);
            }])
            ->first();

        abort_if($form === null, 404, 'You have to create form for this process type.<br>');

        abort_if(!in_array(strtoupper($form->method),
            ['POST', 'PUT', 'PATCH', 'DELETE']),
            403, 'Form method is not supported');

        if (!empty($form->form_data_json)) {
            $queryArray = $form->form_data_json;

            foreach ($queryArray as $key => $query) {
                if (Str::contains($query, '{$app_id}') && $app_id !== null)
                    $query = Str::replace('{$app_id}', $app_id, $query);
                $results = DB::select($query);
                $queryArray[$key] = $results;
            }
            $form->form_data_json = $queryArray;
        }
        $form->formatted = self::formatComponents($form->components);
        return $form;
    }

    /**
     * @param $data
     * @param null $parentId
     * @return array
     */
    public static function formatComponents($data, $parentId = 0): array
    {
        $formattedComponents = [];

        foreach ($data as $component) {
            if ($component['parent_id'] === $parentId) {
                $formattedComponent = [
                    'id' => $component['id'],
                    'form_id' => $component['form_id'],
                    'parent_id' => $component['parent_id'],
                    'title' => $component['title'],
                    'is_loop' => $component['is_loop'],
                    'loop_data' => $component['loop_data'],
                    'template_type' => $component['template_type'],
                    'order' => $component['order'],
                    'step_no' => $component['step_no'],
                    'status' => $component['status'],
                    'inputs' => $component['inputs']->count() > 0 ? self::formatInputs($component['inputs']) : null,
                ];
                $childComponents = self::formatComponents($data, $component['id']);
                if (!empty($childComponents))
                    $formattedComponent['components'] = $childComponents;

                $formattedComponents[] = $formattedComponent;
            }
        }
        return $formattedComponents;
    }

    /**
     * @param $inputs
     * @return array
     */
    public static function formatInputs($inputs): array
    {
        $formattedInputs = [];
        $groupedInputs = [];
        foreach ($inputs as $input) {
            if ($input->group_id !== null && $input->group_id === $input?->group?->id) {
                $groupId = $input?->group?->id;
                $groupedInputs[$groupId]['input_groups'][] = [
                    'id' => $input->id,
                    'form_id' => $input->form_id,
                    'component_id' => $input->component_id,
                    'label' => $input->label,
                    'label_position' => $input->label_position,
                    'width' => $input->width,
                    'input_tag_name' => $input->input_tag_name,
                    'is_loop' => $input->is_loop,
                    'loop_data' => $input->loop_data,
                    'input_id' => $input->input_id,
                    'name' => $input->name,
                    'attribute_bag' => $input->attribute_bag,
                    'attributes' => $input->attribute_bag,
                    'validation' => $input->validation,
                    'model_namespace' => $input->model_namespace,
                    'column_name' => $input->column_name,
                    'order' => $input->order,
                    'status' => $input->status,
                ];
                $groupedInputs[$groupId]['group_id'] = $input?->group?->id;
                $groupedInputs[$groupId]['column_name'] = $input?->group?->column_name;
                $groupedInputs[$groupId]['group_label'] = $input?->group?->label;
                $groupedInputs[$groupId]['value'] = $input?->group?->value;
                $groupedInputs[$groupId]['order'] = $input->order;
                $groupedInputs[$groupId]['width'] = $input->width;
            } else {
                $formattedInputs[] = [
                    'id' => $input->id,
                    'form_id' => $input->form_id,
                    'component_id' => $input->component_id,
                    'label' => $input->label,
                    'label_position' => $input->label_position,
                    'width' => $input->width,
                    'input_tag_name' => $input->input_tag_name,
                    'is_loop' => $input->is_loop,
                    'loop_data' => $input->loop_data ?: null,
                    'input_id' => $input->input_id,
                    'name' => $input->name,
                    'attribute_bag' => $input->attribute_bag,
                    'attributes' => $input->attribute_bag,
                    'validation' => $input->validation,
                    'model_namespace' => $input->model_namespace,
                    'column_name' => $input->column_name,
                    'order' => $input->order,
                    'status' => $input->status,
                ];
            }
        }

        foreach ($groupedInputs as $inputs) {
            $formattedInputs[] = [
                'input_tag_name' => 'group_inputs',
                'attribute_bag' => [],
                'group_id' => $inputs['group_id'],
                'group_label' => $inputs['group_label'],
                'column_name' => $inputs['column_name'],
                'grouped_inputs' => $inputs['input_groups'],
                'value' => $inputs['value'],
                'order' => $inputs['order'],
                'width' => $inputs['width'],
            ];
        }

        usort($formattedInputs, function ($a, $b) {
            if ($a['order'] > $b['order']) {
                return 1;
            } elseif ($a['order'] < $b['order']) {
                return -1;
            }
            return 0;
        });
        return $formattedInputs;
    }

    /**
     * @param $processTypeId
     * @param $app_id
     * @param $formType
     * @return array
     */
    public static function formWithData($processTypeId, $app_id, $formType): array
    {
        $form = Form::query()
            ->where([
                'process_type_id' => $processTypeId,
                'form_type' => $formType
            ])
            ->with(['components' => function ($query) {
                return $query->where('status', 1)
                    ->orderBy('order', 'ASC')
                    ->with(['inputs' => function ($q) {
                        return $q->where('status', 1)
                            ->orderBy('order', 'ASC')
                            ->with('group:id,label');
                    }]);
            }])
            ->first();

        $form->formatted = self::formatComponents($form->components);

        $inputs = self::relatedDataModels($form->components);

        $modelNameSpaces = array_keys($inputs);
        $modelNameSpaces = collect($modelNameSpaces)
            ->filter(fn($nameSpace) => $nameSpace !== $form->app_model_namespace);

        $mainModelData = $form->app_model_namespace::firstWhere('id', $app_id);

        $relatedModelData = $modelNameSpaces->mapWithKeys(fn($nameSpace) => [
            $nameSpace => $nameSpace::Where($form->app_id, $app_id)->get(),
        ]);

        return [
            'form' => $form,
            'mainModelData' => $mainModelData,
            'relatedModelData' => collect($relatedModelData)->toArray(),
        ];
    }

    /**
     * @param $components
     * @return array
     */
    public static function relatedDataModels($components): array
    {
        return collect($components)
            ->map(fn($component) => $component->inputs)
            ->collapse()
            ->filter(fn($inputs) => !empty($inputs))
            ->groupBy('model_namespace')
            ->toArray();
    }



}
