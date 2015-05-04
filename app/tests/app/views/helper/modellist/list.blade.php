<div class="wrapper group" id="no_ez_qrva_mind1">
@if(count($helperDataJson['models']))
    @foreach($helperDataJson['models'] as $model)
        <div class="row content-block-model">
           <hr /> 
            <div style=" width: 200px; height:200px">
                {{ HTML::image($model['img_path'],$model['img_path']) }}
                <h2>{{ $model['artist_name'] }}</h2>
                {{ $model['country_name'] }}
            </div>
            <div style='display:inline;'>
                <h3>She Catgeories</h3>
                <ul>
                    @foreach($model['mct'] as $cat1)
                     <li>{{$cat1}}</li>
                    @endforeach
                </ul>
                
                <h3>Offered Show Catgeories</h3>
                <ul>
                    @foreach($model['vct'] as $cat2)
                     <li>{{$cat2}}</li>
                    @endforeach
                </ul>
                
                <span>
                    <a href="/order/{{$model['id']}}">
                        Go to order page
                    </a>
                </span>
                
                
            </div>    
        </div>
    @endforeach
@else
<h3><i>No Result</i></h3>
@endif
</div>
