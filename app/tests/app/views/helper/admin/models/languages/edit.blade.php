<h1>Modify {{ $helperDataJson['language']->name }}</h1>

{{ Form::model($helperDataJson['language'], array('route' => array('/administrator/modelslanguages/update/{id}', $helperDataJson['language']->id),'method'=>'put', 'class' => 'col-sm-10 form-horizontal')) }}
         @include('helper.admin.models.languages.form');
{{ Form::close() }}



