<h1 class="col-sm-offset-1"> View {{ $helperDataJson['videos'][0]->artist_name }} {{ $helperDataJson['videos'][0]->videoTypeTitle }} </h1>

{{ Form::model($helperDataJson['videos'][0], array('route' => array('/administrator/video_storage/update', $helperDataJson['videoId']), 'files'=>true, 'method'=>'put', 'class' => 'col-sm-10 form-horizontal')) }}
         @include('helper.admin.videostorage.form')
{{ Form::close() }}



