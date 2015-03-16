@extends('layouts.admin')

@section('admincontent')
<div class="row"> 
    <div class="col-sm-12">
        <a class="btn btn-info" href="{{ route('admin.categories.type.create') }}"> New Category Type</a>
        <a class="btn btn-link" href="{{ route('admin.categories.index') }}"> All category type</a>
        @if(count($categoryTypes))
            @foreach($categoryTypes as $category)
                <a class="btn btn-link" href="{{ route('/admin/categories/{id}',array('id'=>$category->id)) }}"> {{ $category->title }} </a>
            @endforeach
        @endif
    </div>    
</div>    
    
@if(count($categoryTypes))
  <p class="bg-primary flashmsg">CATEGORY TYPES</p>
   <table class="table table-striped">
    <tr>
        <th>Id</th>
        <th>Title</th>
        <th>Category Name</th>
        <th>Statusz</th>
        <th>Actions</th>
    </tr>
    @foreach($categoryTypes as $categoryType)
    <tr>
        <td>{{ $categoryType->id }}</td>
        <td>{{ $categoryType->title }}</td>
        <td>{{ $categoryType->name }}</td>
        <td>@if($categoryType->active == 1) Active @else Inactive @endif</td>
        <td>
            {{ Form::open(array('route' => array('admin.categories.type.editStatusz',$categoryType->id), 'role' => 'form')) }} 
            <a class="btn btn-warning" href="{{ route('admin.categories.type.edit', $categoryType->id) }}">View</a>
            
            <button type="submit" name="admin.categories.type.editStatusz" class="btn btn-danger btn-sm">
                @if($categoryType->active == 1)
                    Inactivated
                @else
                    Activated
                @endif
            </button>
        </td>
    </tr>
    @endforeach
   </table>
  {{ $categoryTypes->links()  }}
  @else
  <h2><i>Nincs megjelenítendő elem</i></h2>
  @endif
  
@stop