<div class="wrapper group" id="catselect">
    <div class="catsel-type">
        @foreach($helperDataJson['typeArray'] as $typeArray)
            <span class="type">{{$typeArray['title']}}</span>
            <div class='divcat'>
            @foreach($helperDataJson['catArray'][$typeArray['type_id']] as $catArray) 
                @if($catArray['linkType'] == 0)
                    <span class="cattitle0">
                        <a html="{{$helperDataJson['baseLink'].$catArray['link']}}">
                            {{$catArray['titpe']}}
                        </a>
                    </span>
                @else
                    <span class="cattitle1">
                        {{$catArray['titpe']}}
                        <a html="{{$helperDataJson['baseLink'].$catArray['link']}}">
                            X
                        </a>
                    </span>
                @endif
            @endforeach
            </div>
        @endforeach
    </div>  
</div>