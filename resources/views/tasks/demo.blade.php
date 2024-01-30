@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body hvr-fade">
        <div class="row d-flex">
            <div class="col-6 d-flex justify-content-start">
                <span class="pl-1 pr-1" id="created_by">Abinash</span>
            </div>
            <div class="col-6 d-flex justify-content-end">
                <img src="http://localhost:8000/storage/profile/Abinash.jpg" class="image rounded-circle" id="card_images" 
                alt="http://localhost:8000/storage/profile/user.png">
                <img src="http://localhost:8000/storage/profile/Abinash.jpg" class="image rounded-circle" id="card_images" 
                alt="http://localhost:8000/storage/profile/user.png">
                <img src="http://localhost:8000/storage/profile/user.png" class="image rounded-circle" id="card_images" 
                alt="http://localhost:8000/storage/profile/user.png">
                <div class="image rounded-circle" id="remaining_profiles"><b>+2</b>
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="card card-custom">
        <div class="card-body hvr-fade">
            <div class=" row d-flex">
                <div class="col-6 d-flex justify-content-start">
                    <span class="pl-1 pr-1" id="created_by">{{$user_id_with_name[$task['created_by']]}}</span>
                </div>
                <div class="col-6 d-flex justify-content-end">
                    @foreach ($assignees as $tasks_id)
                        @foreach ($tasks_id as $id)
                            @if ($id['task_id'] == $task['id'])
                                @if ($loop->index >=3)
                                    <div class="image rounded-circle" id="remaining_profiles"><b>+{{$loop->remaining+1}}</b></div>
                                    @break
                                @endif
                                <img src="http://localhost:8000/storage/profile/{{$user_with_profile[$id['user_id']]}}" class="image rounded-circle"id="card_images" alt="http://localhost:8000/storage/profile/user.png">
                            @endif
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection