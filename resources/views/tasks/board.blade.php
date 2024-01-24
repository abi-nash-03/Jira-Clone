@extends('layouts.app')

@section('content')
<style>
    .hvr-fade {
        display: inline-block;
        vertical-align: middle;
        -webkit-transform: perspective(1px) translateZ(0);
        transform: perspective(1px) translateZ(0);
        box-shadow: 0 0 1px rgba(0, 0, 0, 0);
        overflow: hidden;
        -webkit-transition-duration: 0.3s;
        transition-duration: 0.3s;
        -webkit-transition-property: color, background-color;
        transition-property: color, background-color;
    }
    .hvr-fade:hover, .hvr-fade:focus, .hvr-fade:active {
        background-color: #e1e1e1;
        color: #373737;
    }
    .box1::-webkit-scrollbar {
        display: none;
    }

    .box2::-webkit-scrollbar {
        display: none;
    }

    .box3::-webkit-scrollbar {
        display: none;
    }

    .title {
        /* position: unset; */
    }

    .main_container {
        display: flex;
        width: 100%;
        height: 100%;
        padding: 50px;
        justify-content: space-around;
        /* background-color: aquamarine; */
    }

    .box1 {
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        width: 400px;
        height: 600px;
        /* box-shadow: 0.7px 0.7px rgb(217, 217, 217), -0.7px -0.7px rgb(179, 172, 172); */
        box-shadow: 1px 1px 5px rgb(173, 173, 173);
        background-color: #f4f5f7;
        overflow-y: scroll;
        scroll-behavior: smooth;
        margin-right: 5px;
        border-radius: 3px;


    }

    .box2 {
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        width: 400px;
        height: 600px;
        /* box-shadow: 0.7px 0.7px rgb(183, 183, 183), -0.7px -0.7px rgb(179, 172, 172); */
        box-shadow: 1px 1px 5px rgb(173, 173, 173);
        background-color: #F4F5F7;
        overflow-y: scroll;
        scroll-behavior: smooth;
        margin-right: 5px;
        border-radius: 3px;

    }

    .box3 {
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        width: 400px;
        height: 600px;
        box-shadow: 1px 1px 5px rgb(173, 173, 173);
        background-color: #F4F5F7;
        overflow-y: scroll;
        scroll-behavior: smooth;
        border-radius: 3px;

    }

    .card_custom {
        margin: 10px;
        box-shadow: 1px 1px 5px rgb(173, 173, 173);
    }

    .board_title {
        position: relative;
    }

    .buttons {
        display: flex;
        justify-content: end;
        margin-right: 90px;
    }


    .col1{
        background-color: rgb(255, 255, 255);
    }
    .col2row1{
        background-color: rgb(255, 255, 255);
    }
    .col2row2{
        background-color: rgb(255, 255, 255);
    }
    #card_images{
        height: 25px;
        width: 25px;
    }
    #shared_email{
        /* background-color: black */
        /* border: 2px solid; */
        border-radius: 10px;
        padding:3px;
        background-color: rgb(209, 244, 255)
    }
    /* .shared_emails{
        background-color: lightblue;
    } */
    #remaining_profiles{
        height: 20px;
        width: 20px;
        background-color: aliceblue;
    }
    .card-custom{
        /* box-shadow: 1px 1px 1px rgb(173, 173, 173); */
    }   
</style>



<div class="d-flex justify-content-between align-items-center mt-3">
    <h2 class="ml-5 board_title">Board</h2>
    <form class="d-flex mr-5" method="get" action="/search">
        @csrf
        <input class="form-control mr-sm-2" type="search" name ="search_key" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success my-2 my-sm-0"  type="submit">Search</button>
    </form>
    
</div>
<div class="container">
    <div class='users d-flex '>
        <form action="/user/board" method="post">
            @csrf
            <div class="form-check">
                @foreach ($users as $user)
                <input class="form-check-input" type="checkbox" id="flexCheckChecked" name='{{$user['id']}}' value='{{$user['id']}}'>
                <label class="form-check-label mr-5" for="flexCheckChecked">{{$user['email']}}</label>
                @endforeach
                <input type="submit" value="apply" class="btn btn-primary btn-sm ml-4">
            </div>
        </form>
    </div>
</div>
<div class="main_container">
    <div class="box1">
        <h3 class="title">Todo</h3>
        @foreach ($tasks as $task)
        @if ($task["status"] == 'todo')
        <div class="card card-custom">
            <div class="card-body hvr-fade">
                <div class="d-flex justify-content-between">
                    <div class="d-flex">
                        <span class="mr-1">#JD{{$task['id']}}</span>
                        <a href="#" id="edit_task_modal_{{$task['id']}}" data-taskid="/api/getTaskDetails/{{$task['id']}}"
                        data-url="/api/getUserDetails/{{$task['created_by']}}" data-toggle="modal" data-target="#edit_task"
                        class="card-title" onclick="getId('edit_task_modal_{{$task['id']}}')" >
                        <h5>{{$task["title"]}}</h5>
                    </div>
                    <form action="task/destroy/{{$task["id"]}}" id="delete_task_form_{{$task['id']}}" method="POST" >
                        @csrf
                        @method('DELETE')
                    </form>
                    <div class="d-flex justify-content-end">
                        @if ($task['due_at'] < date('Y-m-d'))
                            <span class="badge badge-danger">Expired</span>
                        @endif
                        <a class="ml-5" href="#" onclick="deleteTask({{$task['id']}})">&times;</a>
                    </div>
                </div>
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
        @endif
    @endforeach
    </div>
    <div class="box2">
        <h3 class="title">In Progress</h3>
        @foreach ($tasks as $task)
            @if ($task["status"] == 'inprogress')
            <div class="card card-custom">
                <div class="card-body hvr-fade">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex">
                            <span class="mr-1">#JD{{$task['id']}}</span>
                            <a href="#" id="edit_task_modal_{{$task['id']}}" data-taskid="/api/getTaskDetails/{{$task['id']}}"
                            data-url="/api/getUserDetails/{{$task['created_by']}}" data-toggle="modal" data-target="#edit_task"
                            class="card-title" onclick="getId('edit_task_modal_{{$task['id']}}')" >
                            <h5>{{$task["title"]}}</h5>
                        </div>
                        <form action="task/destroy/{{$task["id"]}}" id="delete_task_form_{{$task['id']}}" method="POST" >
                            @csrf
                            @method('DELETE')
                        </form>
                        <div class="d-flex justify-content-end">
                            @if ($task['due_at'] < date('Y-m-d'))
                                <span class="badge badge-danger">Expired</span>
                            @endif
                            <a class="ml-5" href="#" onclick="deleteTask({{$task['id']}})">&times;</a>
                        </div>
                    </div>
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
            @endif
        @endforeach
    </div>
    <div class="box3">
        <h3 class="title">Completed</h3>
        @foreach ($tasks as $task)
        @if ($task["status"] == 'completed')
        <a href="/home" style="">
            <div class="card card-custom">
                <div class="card-body hvr-fade">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex">
                            <span class="mr-1">#JD{{$task['id']}}</span>
                            {{-- <a href="#" id="edit_task_modal_{{$task['id']}}" data-taskid="/api/getTaskDetails/{{$task['id']}}"
                            data-url="/api/getUserDetails/{{$task['created_by']}}" data-toggle="modal" data-target="#edit_task"
                            class="card-title" onclick="getId('edit_task_modal_{{$task['id']}}')" >
                            <h5>{{$task["title"]}}</h5> --}}
                        </div>
                        <form action="task/destroy/{{$task["id"]}}" id="delete_task_form_{{$task['id']}}" method="POST" >
                            @csrf
                            @method('DELETE')
                        </form>
                        <div class="d-flex justify-content-end">
                            @if ($task['due_at'] < date('Y-m-d'))
                                <span class="badge badge-danger">Expired</span>
                            @endif
                            <a class="ml-5" href="#" onclick="deleteTask({{$task['id']}})">&times;</a>
                        </div>
                    </div>
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
        </a>
        @endif
    @endforeach
    </div>
</div>



@include('modal.delete_alert');
@include('modal.editTask')
{{-- @yield('modal.script') --}}         
<script>
    
    var taskId;
    // var tagName;
    $(document).ready(function(){
        // automatically called when the winodw is loaded
        window.getId = function(id){            // alert("id = "+id);

            taskId = "#"+id;
            var curr_task_id = id.substring(16);
            var url = $(taskId).attr('data-url');
            var task_url = $(taskId).attr('data-taskid');
            var tag_name_url = "api/getTagName/";
            var task_emails = "api/getAssigneesEmailOfTask/"+curr_task_id;
            var getUsers = "api/getUsers";
            
            assignTaskIdToShare();
            //get user details
            $.get(url, function(data){
                console.log(data);
                $('#owner_name').text(data.name);
                $('#owner_profile').attr('src', 'http://localhost:8000/storage/profile/'+data.profile);
                // $("#created_at").text($.datepicker.format('dd M yy', data.created_at));
                $('#created_at').text(data.created_at);
                $('#updated_at').text(data.updated_at);
            });
            
            $.get(task_url, function(data){
                console.log(data);
                $('#taskTitle').val(data.title);
                $('#description').val(data.body);
                $('#due_at').val(data.due_at);
                $('#status').val(data.status);
                $("#edit_tag_id").val(data.tag_id).change();
                $("#editForm").attr('action','/task/'+data.id);
            });

            $.get(task_emails, function(data){
                console.log(data);
                $('#task_emails').empty();
                if(data.length == 0){
                        $('#task_emails').append("<span>Not shared with anyone.</span>");
                    }
                var count = 0;
                for(var i=0;i<data.length;i++){
                    if(count > 5){
                        $('#task_emails').append("<span>...</span>");
                        break;
                    }
                    $('#task_emails').append("<div id=shared_email class=mr-2>"+data[i].email+" </div>");
                    count++;
                }
            });
            function assignTaskIdToShare(){
                $('#email_sugession').val("");
                $('#task_id').val(curr_task_id);
            }

            
            // autocomplete
            
            // $.get(getUsers, function(data){
            //     var sugg = [];
            //     for(var i=0;i<data.length;i++){
            //         sugg.push(data[i].email);
            //         // console.log("data = "+data[i].email);
            //     }
            //     var arr = ['1','2','3'  ];
            //     console.log(sugg);
            //     $("#email_sugession").autocomplete({});  
            // });

            
        }

        
    });
    function deleteTask(id){
        var res = confirm("Are you sure");
        if(res){
            $("#delete_task_form_"+id).submit();
        }
    }
</script>

@endsection