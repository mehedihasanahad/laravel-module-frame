<style>
    .table-bordered td, .table-bordered th {
        border: 1px solid #dee2e6;
        height: 100%;
        text-align: center;
        vertical-align: middle;
    }
</style>
<table class="table table-bordered table-responsive">
    <thead>
    <tr>
        <th>#</th>
        @foreach ($component['inputs'] as $input)
            <th>{{$input['label']}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @if( $component['template_type'] === 3 && count($relatedModelData[$input['model_namespace']]) > 0 )
        @foreach($relatedModelData[$input['model_namespace']] as $value)
            <tr>
                <td>{{$loop->iteration}}</td>
                @foreach ($component['inputs'] as $input)
                    <td>{{$value[$input['column_name']]}}</td>
                @endforeach
            </tr>
        @endforeach
    @endif
    </tbody>
</table>
