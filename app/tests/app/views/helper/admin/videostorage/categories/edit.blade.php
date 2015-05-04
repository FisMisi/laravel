<h1 class="col-sm-offset-1"><b>EDIT {{ $helperDataJson['category']->title }}</b></h1>

{{ Form::model($helperDataJson['category'], array('route' => array('/administrator/video_storaged_categories/update/{id}', $helperDataJson['category']->id),'method'=>'put', 'class' => 'col-sm-10 form-horizontal')) }}
         @include('helper.admin.videostorage.categories.form')
{{ Form::close() }}



