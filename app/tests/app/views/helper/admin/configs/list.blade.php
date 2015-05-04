<div class="coll-sm-10" style="color: #151515">    
    
<h1>
   CONFIGS
</h1>

  @if (Session::has('errors'))
        <div class="row">
            <ul>
              @if ($errors->has())
                <div class="col-sm-offset-2 col-sm-8 alert alert-success">
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br>        
                    @endforeach
                </div>
               @endif
            </ul>
        </div>    
    @endif
    
    @if (Session::has('message'))
    <div class="row">
         <div class="col-sm-offset-2 col-sm-6 alert alert-success">
          {{Session::get('message')}}                  
         </div>
    </div>    
    @endif
    
<div class="table-responsive">
    @if (count($helperDataJson['configs']))
        <table id="orders" style="background-color: #8f785c;color: #404" class="table table-striped">
            <tr>
                <th>#Id</th>
                <th>Title</th>
                <th>Name</th>
                <th>Value</th>
                <th>Created</th>
                <th>Updated</th>
                <th></th>       
            </tr>
            @foreach($helperDataJson['configs'] as $config)
                @if (isset($config))
                        <tr>
                                <td>{{$config->id}}</td>
                                <td>{{$config->title}}</td>
                                {{ Form::model($config, array('route' => array('/administrator/configs/update', $config->id), 'method'=>'put')) }}
                                <td>{{$config->name}}</td>
                                <td>{{ Form::text('value',null,array('size'=>'4')) }}</td>
                                <td>{{$config->created_at}}</td>
                                <td>{{$config->updated_at}}</td>
                                <td>
                                 {{ Form::submit('Save',array('class' => 'btn btn-success')) }}
                                </td>           
                        </tr>
                @endif
            @endforeach

        </table>
    @else
        <h3>Nincs találat</h3>
    @endif
    
    {{ $helperDataJson['configs']->links() }}
</div>
   



