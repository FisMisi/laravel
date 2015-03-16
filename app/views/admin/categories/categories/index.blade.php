@extends('layouts.admin')

@section('admincontent')
<div class="row"> 
    <div class="col-sm-12">
        <a class="btn btn-info" href="{{ route('admin.categories.type.create') }}"> New Category </a>
        <a class="btn btn-link" href="{{ route('admin.categories.index') }}"> All category </a>
        @if(count($categoryTypes))
            @foreach($categoryTypes as $category)
                <a class="btn btn-link" href="{{ route('/admin/categories/{id}',array('id'=>$category->id)) }}"> {{ $category->title }} </a>
            @endforeach
        @endif
    </div>    
</div>    
    
 <a class="btn btn-link" href="{{ route('admin.categories.cat.create',array('id'=>$categoryTypeId)) }}"> New subcategory </a>
 @if(count($categories))
  <p class="bg-primary flashmsg">CATEGORY TYPES</p>
   <table class="table table-striped">
    <tr>
        <th>Title</th>
        <th>Name</th>
        <th>Statusz</th>
        <th>Actions</th>
    </tr>
    @foreach($categories as $category)
    <tr>
        <td>{{ $category->title }}</td>
        <td>{{ $category->name }}</td>
        <td>@if($category->active == 1) Active @else Inactive @endif</td>
        <td>
            {{ Form::open(array('route' => array('admin.categories.cat.editCatStatusz',$category->id), 'role' => 'form')) }} 
            <a class="btn btn-warning" href="{{ route('admin.categories.cat.edit', array('type_id'=>$categoryTypeId, 'id'=>$category->id)) }}">View</a>
            
            <button type="submit" name="admin.categories.cat.editCatStatusz" class="btn btn-danger btn-sm">
                @if($category->active == 1)
                    Inactivated
                @else
                    Activated
                @endif
            </button>
            
        </td>
    </tr>
    @endforeach
   </table>
  {{ $categories->links()  }}
  @else
  <h2><i>Nincs megjelenítendő elem</i></h2>
  @endif
  
@stop
