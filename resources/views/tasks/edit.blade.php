@extends('layouts.app')

@section('content')
    <div class="container">
        <h3 class="mt-4 mb-3">Create Task</h3>
        {{-- {{env('APP_URL')}} --}}
        <form action="/task/{{$task["id"]}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Title</label>
                <input type="text" class="form-control" id="exampleFormControlInput1" name="title" value="{{$task["title"]}}">
            </div>
            <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">Description</label>
                @if ($task['body'] == null)
                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="body">No description :)</textarea>
                @else
                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="body">{{$task["body"]}}</textarea>
                @endif
                
            </div>
            <div class="mb-4">
                <label class="form-label">Due at</label>
                <input type="date" class="form-control" name="due_at" value="{{$task["due_at"]}}">
            </div>

            <div class="input-group mb-4">
                <label class="input-group-text" for="inputGroupSelect01">Status</label>
                <select class="form-select form-control" for="inputGroupSelect01" aria-label="Default select example" name="status">
                <option selected value="todo">{{$task['status']}}</option>
                <option value="inprogress">In Progress</option>
                <option value="completed">Completed</option>
                </select>
            </div>

            <div class="input-group mb-4">
                <label class="input-group-text" for="inputGroupSelect01">Tag</label>
                {{-- {{$curr_tag_id = $tag}} --}}
                <select class="form-select form-control" for="inputGroupSelect01" aria-label="Default select example" name="tag_id">
                <option selected value="{{$task['tag_id']}}">{{$tag_name}}</option>
                @foreach ($tags as $tag)
                    <option value="{{$tag->id}}">{{$tag->name}}</option>
                @endforeach
                </select>
            </div>
            <input type="submit" class="btn btn-outline-success" onclick="hide()">
        </form>
    </div>
@endsection