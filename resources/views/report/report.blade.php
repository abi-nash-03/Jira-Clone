
@extends('layouts.app')

@section('content')
    <div class="container">
        <title>Hii {{$user['name']}}, Your report from JIDO</title>
        <div class="d-flex justify-content-start">
        </div>
        <div class="pt-3 pb-4 d-flex justify-content-between">
            <div>
                <h4 class="d-inline">Hii,</h4>
                <h2 class="d-inline">{{auth()->user()->name}}</h2><br>
                <span>Here is your JIDO report</span><br>
                Generated at: {{$date}}
            </div>
        </div>

        {{-- Todo --}}
        <h3>Todo</h3>
        <div class="todo m-3">
            @if (count($tasks['todo']) == 0)
                <span>No Todo's</span>
            @else
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Task ID</th>
                            <th scope="col">Title</th>
                            <th scope="col">Description</th>
                            <th scope="col">Due at</th>
                            <th scope="col">Tag</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tasks['todo'] as $task)
                            @if ($task['status']=='todo')
                                <tr>
                                    <th scope="row">#JD{{$task['id']}}</th>
                                    <td>{{$task['title']}}</td>
                                    <td>{{$task['body']}}</td>
                                    <td>{{$task['due_at']}}</td>
                                    <td>{{$tag_with_id[$task['tag_id']]}}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        {{-- Inprogress --}}
        <h3>Inprogress</h3>
        <div class="inprogress m-3">
            @if (count($tasks['inprogress']) == 0)
                <span>No Inprogress</span>
            @else
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Task ID</th>
                            <th scope="col">Title</th>
                            <th scope="col">Description</th>
                            <th scope="col">Due at</th>
                            <th scope="col">Tag</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tasks['inprogress'] as $task)
                            @if ($task['status']=='inprogress')
                                <tr>
                                    <th scope="row">#JD{{$task['id']}}</th>
                                    <td>{{$task['title']}}</td>
                                    <td>{{$task['body']}}</td>
                                    <td>{{$task['due_at']}}</td>
                                    <td>{{$tag_with_id[$task['tag_id']]}}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        {{-- Completed --}}
        <h3>Completed</h3>
        <div class="completed m-3">
            @if (count($tasks['completed']) == 0)
                <span>No Completed</span>
            @else
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Task ID</th>
                            <th scope="col">Title</th>
                            <th scope="col">Description</th>
                            <th scope="col">Due at</th>
                            <th scope="col">Tag</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tasks['completed'] as $task)
                            @if ($task['status']=='completed')
                                <tr>
                                    <th scope="row">#JD{{$task['id']}}</th>
                                    <td>{{$task['title']}}</td>
                                    <td>{{$task['body']}}</td>
                                    <td>{{$task['due_at']}}</td>
                                    <td>{{$tag_with_id[$task['tag_id']]}}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection