<div class="wrapper group">
        <div class="video-title-wrapper">
            <h1>Filter for your favourite model types</h1>	
        </div>
        
        <div class="content-wrapper">
            
            @foreach(ModelCategoryType::getCategoryTypes() as $type)
            <div style="margin-bottom: 10px;font-size: 17px;">
                <span style="font-size: 18px; margin-right:70px;"><b>{{ $type['title'] }}</b></span>
                    <?php $del=""; $del2 = "  |  "; ?>
                    @foreach(ModelCategory::getCategories($type['id']) as $category)
                        {{ $del }}
                            <a href="#{{ $category['id'] }}"> {{ $category['title'] }} </a> 
                        <?php $del = $del2;  ?>
                    @endforeach
            </div>
            @endforeach 
           
        </div>    
</div>

