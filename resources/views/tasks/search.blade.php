@extends('layouts.app')

@section('content')
<style>
    #search_pill{
        height: 17px;
    }
    #expired{
        height: 17px;
        /* width: 20px; */
    }
</style>
<div class="d-flex justify-content-sm-around mt-3">
    <a href="/board" class="btn btn-primary">Back</a>
    <form class="d-flex mr-5" method="get" action="/search">
        @csrf
        <input class="form-control mr-sm-2" type="search" name ="search_key" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success my-2 my-sm-0"  type="submit">Search</button>
    </form>
</div>
<div class="ml-5 mt-2 d-flex justify-content-center">
    <h2>
        Search results
    </h2>
</div>
<div class="container">
    <form action="">
        <div class="row">
            <div class="col-md-3">
                <label for="">Filter by date</label>
                <input type="date" name='date' value="{{date('Y-m-d')}}" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="">Select status</label>
                <select name="status" class="form-control">
                    <option value="todo">Todo</option>
                    <option value="inprogress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            <input type="hidden" name="search_key" value="{{$search_key}}">
            <div class="col-md-6">
                <br>
                <input type="submit" class="btn btn-primary">
            </div>
        </div>
    </form>
        <hr>
        @if ($tasks == null)
            No Matching tasks
        @else
            @foreach ($tasks as $task)
            <li class="list-group-item d-flex justify-content-between align-items-start">
                <div class="mt-2 mb-2 d-flex">
                    <div class="ms-2 me-auto">
                        <div class="fw-bold">{{$task['title']}}</div>
                    </div>
                    <span class="badge badge-light ml-3" id="search_pill">{{$task['status']}}</span>
                    @if ($task['due_at'] < date('Y-m-d'))
                        <span class=" ml-2 badge badge-danger" id="expired">Expired</span>
                    @endif
                </div>
            </li>
            @endforeach
        @endif
    </div>
@endsection