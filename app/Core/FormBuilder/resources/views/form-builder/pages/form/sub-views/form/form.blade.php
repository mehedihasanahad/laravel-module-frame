@inject('encryption','\App\Libraries\Encryption')
@php
    $action = $form->action;
    if (Str::contains($action,'$app_id'))
            $action=Str::replace('$app_id', $decodedAppId, $action);
    eval("\$action = \"$action\";");
@endphp

{{--@if($errors->any())--}}
{{--    @dump($errors->messages())--}}
{{--    <div class="alert alert-danger">--}}
{{--        <ul class="mb-0">--}}
{{--            @foreach($errors->all() as $error)--}}
{{--                <li> {{$error}} </li>--}}
{{--            @endforeach--}}
{{--        </ul>--}}
{{--    </div>--}}
{{--@endif--}}

<section class="content">
    <div class="container-fluid">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">{{$form->title}}</h3>
            </div>
            <div class="card-body">
                <form method="post" action="{{ url($action) }}" id="{{$form->form_id}}"
                      autocomplete="{{$form->autocomplete}}" enctype="{{$form->enctype}}">
                    @csrf
                    @method(strtoupper($form->method))
                    @if( $form->template_type === 2 )
                        @foreach(explode(',', $form->steps_name) as $key => $name)
                            <h3> {{$name}} </h3>
                            <fieldset>
                                <div>
                                    @include('FormBuilder::form-builder.pages.form.sub-views.component.main-component',['current_step'=>$loop->iteration])
                                </div>
                            </fieldset>
                        @endforeach
                    @else
                        <div>
                            @include('FormBuilder::form-builder.pages.form.sub-views.component.main-component',['current_step'=>null])
                        </div>
                    @endif
                    <div style="padding-top: 10px">
                        <button type="submit" class="btn btn-info btn-md cancel mt-2 ml-3" value="draft"
                                name="actionBtn"
                                id="save_as_draft"> Draft
                        </button>
                        <button type="submit" id="submitForm"
                                style="margin-right:{{ $form->template_type === 1 ? '0px':'100px' }}; display: {{ $form->template_type === 1 ? 'block':'none' }}; position: relative; z-index: 10;top:8px;"
                                class="float-right btn btn-success btn-md"
                                value="submit" name="actionBtn"
                                onclick="CommonFunction.validForm('{{$form->form_id}}')"
                        > Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
