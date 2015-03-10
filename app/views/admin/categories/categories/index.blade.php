@extends('layouts.admin')

@section('admincontent')
<div class="row"> 
    <div class="col-sm-12">
        <a class="btn btn-info" href="{{ route('admin.categories.cat.create') }}"> New Category </a>
        <a class="btn btn-link" href="{{ route('admin.categories.index') }}"> All category </a>
        @if(count($categories))
            @foreach($categories as $category)
                <a class="btn btn-link" href="{{ route('admin.categories.cat',array('id'=>$category->id)) }}"> {{ $category->name }} </a>
            @endforeach
        @endif
    </div>    
</div>    
    
@if(!empty($categoryTypes))
    <a class="btn btn-link" href="{{ route('admin.categories.type.create',array('id'=>$categoriy_id)) }}"> New Type </a>
 @if(count($categoryTypes))
  <p class="bg-primary flashmsg">CATEGORY TYPES</p>
   <table class="table table-striped">
    <tr>
        <th>Category Name</th>
    </tr>
    @foreach($categoryTypes as $categoryType)
    <tr>
        <td>{{ $categoryType->categoryTypeName }}</td>
    </tr>
    @endforeach
   </table>
  {{ $categoryTypes->links()  }}
  @else
  <h2><i>Nincs megjelenítendő elem</i></h2>
  @endif
 @endif
@stop
