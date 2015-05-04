<h1 class="col-sm-offset-3 col-sm-9"><b> {{ $helperDataJson['config']->title }} </b></h1>

{{ Form::model($helperDataJson['config'], array('route' => array('/administrator/configs/update', $helperDataJson['config']->id), 'method'=>'put', 'class' => 'col-sm-10 form-horizontal')) }}
         @include('helper.admin.configs.form')
{{ Form::close() }}


