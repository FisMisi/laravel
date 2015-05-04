<h1>Modify {{ $helperDataJson['model']->fullname }} </h1>

{{ Form::model($helperDataJson['model'], array('route' => array('/administrator/models/modifyModel', $helperDataJson['model']->id), 'files'=>true, 'method'=>'put', 'class' => 'col-sm-10 form-horizontal')) }}
         @include('helper.admin.models.form');
{{ Form::close() }}



