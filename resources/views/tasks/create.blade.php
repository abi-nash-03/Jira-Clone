@extends('layouts.app')

@section('content')

    <style>
        .form-control input {
            border: none;
            box-sizing: border-box;
            outline: 0;
            padding: .75rem;
            position: relative;
            width: 100%;
        }

        /* input[type="date"]::-webkit-calendar-picker-indicator {
            background: transparent;
            bottom: 0;  
            color: transparent;
            cursor: pointer;
            height: auto;
            left: 0;
            position: absolute;
            right: 0;
            top: 0;
            width: auto;
        } */
        
        select{
            cursor: pointer;
        }
        input{
            cursor: pointer;
        }
    </style>

    <div class="container">
        <h3 class="mt-4 mb-3">Create Task</h3>
        {{-- {{env('APP_URL')}} --}}
        <form action="/task/create" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Title</label>
                <input type="text" class="form-control" id="exampleFormControlInput1" name="title">
            </div>
            <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">Description</label>
                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="body"></textarea>
            </div>
            <div class="mb-4">
                <label class="form-label">Due at</label>
                <input type="date" class="form-control" name="due_at">
            </div>
            
            <div class="input-group mb-4">
                <label class="input-group-text" for="inputGroupSelect01">Status</label>
                <select class="form-select form-control" for="inputGroupSelect01" aria-label="Default select example" name="status">
                <option selected value="TODO">Todo</option>
                <option value="INPROGRESS">In Progress</option>
                <option value="COMPLETED">Completed</option>
                </select>
            </div>
        
            <div class="input-group mb-4">
                <label class="input-group-text" for="inputGroupSelect01">Tag</label>
                <select class="form-select form-control" for="inputGroupSelect01" aria-label="Default select example" name="tag_id">
                    <option selected>Choose...</option>
                    @foreach ($tags as $tag)
                    <option value="{{$tag->id}}">{{$tag->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="d-flex justify-content-between">
                <a href="/home" class="btn btn-primary">back</a>
                <input type="submit" class="btn btn-outline-success">
            </div>
        </form>
    </div>
@endsection