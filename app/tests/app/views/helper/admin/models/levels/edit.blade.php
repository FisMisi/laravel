<h1>UPDATE {{ $helperDataJson['level']->title }}</h1>

{{ Form::model($helperDataJson['level'], array('route' => array('/administrator/model_levels/update', $helperDataJson['level']->id),'method'=>'put', 'class' => 'col-sm-10 form-horizontal')) }}
         @include('helper.admin.models.levels.form')
{{ Form::close() }}



