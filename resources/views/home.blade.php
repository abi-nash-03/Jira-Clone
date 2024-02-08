@extends('layouts.app')

{{-- @include('modal.delete_alert'); --}}
@section('content')
    <style>

        /* #assignessInHome{
            background-color: #dcdcdc;
        } */
        /* a:link {
            color: green;
            background-color: transparent;
            text-decoration: none;
        } */
    </style>

    <div class="container pt-3 ">
        <div class="pt-3 pb-4">
            <h4 class="d-inline">Welcome,</h4>
            <h2 class="d-inline">{{auth()->user()->name}}</h2>
        </div>
        
        <div class="d-flex justify-content-around">
            <div class="card text-white bg-secondary mb-10 d-inline-flex" style="max-width: 18rem;">
                <h5><div class="card-header">Todo's</div></h5>
                <div class="card-body">
                    <h4 class="card-title">Total: {{$todo}}</h4>
                    <p class="card-text">Count of Tasks that need to be done</p>
                </div>
            </div>
                
            <div class="card bg-light mb-10 d-inline-flex " style="max-width: 18rem;">
                <h5><div class="card-header">Inprogress</div></h5>
                <div class="card-body">
                    <h4 class="card-title">Total: {{$inprogress}}</h4>
                    <p class="card-text">Count of Tasks you've been working on</p>
                </div>
            </div>
            
            <div class="card text-white bg-success mb-10 d-inline-flex" style="max-width: 18rem;">
                <h5><div class="card-header">Completed</div></h5>
                <div class="card-body">
                    <h4 class="card-title">Total: {{$completed}}</h4>
                    <p class="card-text">Count of Tasks you've completed</p>
                </div>
            </div>

        </div>
        
        <div class="mt-5">
            <div class="bar d-flex justify-content-between">
                <h3 class="">Tasks</h3>
                <a class="btn btn-primary mb-3" href="/task/create" role="button">Create</a>
            </div>
            <ol class="list-group list-group-numbered">
                @if ($tasks == null)
                <p>No tasks to list :)</p >
                @endif
                @foreach ($all_tasks as $task)
                {{-- {{$task->created_by}} --}}
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        {{-- <div class="col-8 d-flex hvr-fade" >
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">
                                    #JD{{$task['id']}} 
                                    <b>{{$task['title']}}</b>
                                </div>
                            </div>
                            <div>
                                <span class="badge badge-secondary  ml-3">{{$task['status']}}</span>
                            </div>
                            <a href="#" id="edit_task_modal_{{$task['id']}}" data-taskid="/api/getTaskDetails/{{$task['id']}}"
                            data-url="/api/getUserDetails/{{$task['created_by']}}" data-toggle="modal" data-target="#edit_task"
                            onclick="getId('edit_task_modal_{{$task['id']}}')" >
                            <img class="image ml-3" id="edit_task_card_images" src="http://localhost:8000/storage/img/edit1.png" alt="">
                            click
                            </a>
                        </div> --}}
                        <a href="#" style="color:#000000;" class="col-8 d-flex home" id="edit_task_modal_{{$task['id']}}" data-taskid="/api/getTaskDetails/{{$task['id']}}"
                            data-url="/api/getUserDetails/{{$task['created_by']}}" data-toggle="modal" data-target="#edit_task"
                            onclick="getId('edit_task_modal_{{$task['id']}}')" >
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">
                                    #JD{{$task['id']}} 
                                    <b>{{$task['title']}}</b>
                                </div>
                            </div>
                            <div>
                                <span class="badge badge-secondary  ml-3">{{$task['status']}}</span>
                            </div>
                        </a>
                        <div class="col-4 ml-5 d-flex"id='assignessInHome'>
                            <div class="ml-2 " >
                                @if ($task_id_with_assignees[$task['id']] == null)
                                    <img src="http://localhost:8000/storage/profile/unassigned1.png" class="image rounded-circle"id="card_images">
                                @else
                                    @foreach ($task_id_with_assignees[$task['id']] as $assinee_id)
                                        <img src="http://localhost:8000/storage/profile/{{$user_with_profile[$assinee_id]}}" class="image rounded-circle"id="card_images" alt="http://localhost:8000/storage/profile/unassigned1.png">
                                    @endforeach
                                    
                                @endif
                            </div>
                        </div>
                    </li>
                @endforeach
            </ol>
                    
            <div class="pb-10 w-4/5">
                {{$all_tasks->links()}}
            </div>
                    
        </div>
                
                
    </div>
    @include('modal.editTask')  
    {{-- @stack('modal_script') --}}
    <script>
        var taskId;
        // var tagName;
        $(document).ready(function(){
            // automatically called when the winodw is loaded
            window.getId = function(id){
                // alert("id = "+id);
                taskId = "#"+id;
                var curr_task_id = id.substring(16);
                var url = $(taskId).attr('data-url');
                var task_url = $(taskId).attr('data-taskid');
                var tag_name_url = "api/getTagName/";
                var task_emails = "api/getAssigneesEmailOfTask/"+curr_task_id;
                var getUsers = "api/getUsers";
                // tag_name_url = tag_name_url + $('#edit_tag_id').attr('data-tagid');
                
                assignTaskIdToShare();
                //get user details
                $.get(url, function(data){
                    console.log(data);
                    $('#owner_name').text(data.name);
                    $('#owner_profile').attr('src', 'http://localhost:8000/storage/profile/'+data.profile);
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
                    // $('#task_emails').append("<b>Shared Wit  h</b><br>");
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
            
            $("#task").hover(function(){
                $(this).css('background-color', 'yellow');
            },function(){
                $(this).css('background-color', 'white');
            });
            
        });
    </script>
@endsection
