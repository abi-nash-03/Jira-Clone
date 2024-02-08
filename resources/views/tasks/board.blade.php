@extends('layouts.app')

@section('content')
<style>

</style>



<div class="d-flex justify-content-between align-items-center mt-3">
    <h2 class="ml-5 board_title">Board</h2>
    <form class="d-flex mr-5" method="get" action="/search">
        @csrf
        <input class="form-control mr-sm-2" list="homepage_search" type="search" name ="search_key" placeholder="Search" aria-label="Search">
        <datalist id="homepage_search">
            <option value="">abinash</option>
        </datalist>
        <button class="btn btn-outline-success my-2 my-sm-0"  type="submit">Search</button>
    </form>
    
</div>
<div class="container">
    <div class='users d-flex '>
        <form action="#" id = "userEmails" >
            @csrf
            <div class="form-check">
                @foreach ($users as $user)
                <input class="form-check-input" type="checkbox" id="flexCheckChecked" name='{{$user['email']}}' value='{{$user['id']}}'>
                <label class="form-check-label mr-5" for="flexCheckChecked">{{$user['email']}}</label>
                @endforeach
                {{-- <input type="submit" value="apply" id="formsubmit" class="btn btn-primary btn-sm ml-4"> --}}
            </div>
        </form>
        <button id="sub_btn" class="btn btn-primary btn-sm">apply</button>
    </div>
</div>
<div class="main_container" >
    <div class="box1">
        <h3 class="title">Todo</h3>
        <div id="todo">
            @foreach ($tasks as $task)
                @if ($task["status"] == 'todo')
                    <div class="card card_custom mt-1">
                        <div class="card-body hvr-fade">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex">
                                    <span class="mr-1">#JD{{$task['id']}}</span>
                                    <a href="#" id="edit_task_modal_{{$task['id']}}" data-taskid="/api/getTaskDetails/{{$task['id']}}"
                                    data-url="/api/getUserDetails/{{$task['created_by']}}" data-toggle="modal" data-target="#edit_task"
                                    class='card-title' onclick="getId('edit_task_modal_{{$task['id']}}')" >
                                    {{$task["title"]}}
                                    </a>
                                </div>
                                <form action="task/destroy/{{$task["id"]}}" id="delete_task_form_{{$task['id']}}" method="POST" >
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <div class="d-flex justify-content-end">
                                    @if ($task['due_at'] < date('Y-m-d'))
                                        <span class="badge badge-danger" id="expired">Expired</span>
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
    </div>
    <div class="box2">
        <h3 class="title">In Progress</h3>
        <div id="inprogress">
            @foreach ($tasks as $task)
                @if ($task["status"] == 'inprogress')
                <div class="card card_custom mt-1">
                    <div class="card-body hvr-fade">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex">
                                <span class="mr-1">#JD{{$task['id']}}</span>
                                <a href="#" id="edit_task_modal_{{$task['id']}}" data-taskid="/api/getTaskDetails/{{$task['id']}}"
                                data-url="/api/getUserDetails/{{$task['created_by']}}" data-toggle="modal" data-target="#edit_task"
                                class="card-title" onclick="getId('edit_task_modal_{{$task['id']}}')" >
                                {{$task["title"]}}
                                </a>    
                            </div>
                            <form action="task/destroy/{{$task["id"]}}" id="delete_task_form_{{$task['id']}}" method="POST" >
                                @csrf
                                @method('DELETE')
                            </form>
                            <div class="d-flex justify-content-end">
                                @if ($task['due_at'] < date('Y-m-d'))
                                    <span class="badge badge-danger" id="expired">Expired</span>
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
    </div>
    <div class="box3">
        <h3 class="title">Completed</h3>
        <div id="completed">
            @foreach ($tasks as $task)
                @if ($task["status"] == 'completed')
                    <div class="card card_custom mt-1">
                        <div class="card-body hvr-fade">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex">
                                    <span class="mr-1 ">#JD{{$task['id']}}</span>
                                    <a href="#" id="edit_task_modal_{{$task['id']}}" data-taskid="/api/getTaskDetails/{{$task['id']}}"
                                    data-url="/api/getUserDetails/{{$task['created_by']}}" data-toggle="modal" data-target="#edit_task"
                                    class="card-title" onclick="getId('edit_task_modal_{{$task['id']}}')" >
                                    {{$task["title"]}}
                                    </a>
                                </div>
                                <form action="task/destroy/{{$task["id"]}}" id="delete_task_form_{{$task['id']}}" method="POST" >
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <div class="d-flex justify-content-end">
                                    @if ($task['due_at'] < date('Y-m-d'))
                                        <span class="badge badge-danger" id="expired">Expired</span>
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
    </div>
</div>



@include('modal.delete_alert');
@include('modal.editTask')
{{-- @yield('modal.script') --}}         
<script>
    
    var taskId;
    // var tagName;
    $(document).ready(function(){
        window.getId = function(id){

            taskId = "#"+id;
            var curr_task_id = id.substring(16);
            var url = $(taskId).attr('data-url');
            var task_url = $(taskId).attr('data-taskid');
            //var tag_name_url = "api/getTagName/";
            var tag_name_url = "getTagName/";
            //var task_emails = "api/getAssigneesEmailOfTask/"+curr_task_id;
            var task_emails = "getAssigneesEmailOfTask/"+curr_task_id;
            //var getUsers = "api/getUsers";
            var getUsers = "/getUsers";
            
            assignTaskIdToShare();
            //get user details
            $.get(url, function(data){
                console.log("url = "+url);
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

            $('#userEmails').on('submit', function(e){
                e.preventDefault();
                alert("hhi");   
            });

            
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

        //Ajax call to get the tasks of filtered users
        $("#sub_btn").click(function() { 
            var x = $("#userEmails").serializeArray();
            var users_id = [];
            for(var i=1;i<x.length;i++){
                users_id.push(x[i]['value']);
            }
            var user_data = {
                'users_id' : users_id
            }
            var x = $.ajax({
                type: "POST",
                url: '/getUsersTasks',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: JSON.stringify(user_data),
                contentType: "application/json; charset=utf-8",
                crossDomain: true,
                dataType: "json",
            });

            x.done(function(data){
                $('#todo').empty();
                $('#inprogress').empty();
                $('#completed').empty();
                var keys = Object.keys(data['tasks']);
                console.log(data);
                for(var i=0;i<keys.length;i++){
                    var status = data['tasks'][keys[i]]['status'];
                    if(status == 'todo'){
                        var todo_sel = $('#todo');
                        todo_sel.append(
                            getCard(data['tasks'][keys[i]], data['user_id_with_name'], data['assignees'], data['user_id_with_profile'], data['user_id_with_profile'])
                        );
                        todo_sel.hide();
                        todo_sel.show(300);
                        // todo_sel.slideDown(500);
                    }
                    else if(status == 'inprogress'){
                        var inprogress_sel = $('#inprogress');
                        inprogress_sel.append(
                            getCard(data['tasks'][keys[i]], data['user_id_with_name'], data['assignees'], data['user_id_with_profile'], data['user_id_with_profile'])
                        );
                        inprogress_sel.hide();
                        inprogress_sel.show(300);
                    }
                    else if(status == 'completed'){
                        var completed_sel = $('#completed')
                        completed_sel.append(
                            getCard(data['tasks'][keys[i]], data['user_id_with_name'], data['assignees'], data['user_id_with_profile'], data['user_id_with_profile'])
                        );
                        completed_sel.hide();
                        completed_sel.show(300);
                    }
                }
                
            });
        }); 
        
    });


    function deleteTask(id){
        $('#delete_alert_modal').modal('show');
        $('#delete_task_form').attr('action', 'task/destroy/'+id);
    }

    function getCard(data, user_id_with_name, assignees, user_id_with_profile){
        var card = "<div class='card card_custom mt-2'><div class='card-body hvr-fade'>";
        var upper_body = 
                    "<div class='d-flex justify-content-between'>"+
                        "<div class='d-flex'>"+
                            "<span class='mr-1'>#JD"+data.id+"</span>"+
                            "<a href='#' id=edit_task_modal_"+data.id+" data-taskid=/api/getTaskDetails/"+data.id+" data-url=/api/getUserDetails/"+data.created_by+" data-toggle=modal data-target=#edit_task class=card-title onclick=getId('edit_task_modal_"+data.id+"')>"+data.title+"</a>"+
                        "</div>"+
                        "<form action=task/destroy/"+data.id+" id=delete_task_form_"+data.id+" method=POST>"+
                        "<input type=hidden name=_method value=DELETE>"+
                        "<input type=hidden name=_csrf value=getCSRF()>"+
                        "</form>"+
                        "<div class='d-flex justify-content-end'>"+
                            getExpired(data.due_at)+
                            "<a class='ml-5' onclick=deleteTask("+data.id+") >&times;</a>"+
                        "</div>"+
                    "</div>";
        card = card+upper_body;
        card = card + getlowerPart(data.created_by, user_id_with_name,data.id, assignees, user_id_with_profile);
        card = card + "</div></div>";
        return card;
    }

    function getlowerPart(user_id, user_id_with_name, task_id, assignees, user_id_with_profile){
        var lower_part = "<div class='row d-flex'>";
        lower_part = lower_part + getCreatedBy(user_id, user_id_with_name);
        lower_part += assigneesPrifile(task_id, assignees, user_id_with_profile);
        lower_part = lower_part + "</div>";
        return lower_part;
    }

    function getCreatedBy(user_id, user_id_with_name){
        var created_by = 
            "<div class='col-6 d-flex justify-content-start'>"+
                "<span class='pl-1 pr-1' id=created_by>"+user_id_with_name[user_id]+"</span>"+
            "</div>";

        return created_by;
    }

    function assigneesPrifile(task_id, assignees, user_id_with_profile){
        var assignees_profile = "<div class='col-6 d-flex justify-content-end'>";
        console.log(Object.keys(assignees[task_id]));
        var n = Object.keys(assignees[task_id]).length;
        for(var i=0;i<n;i++){
            if(i > 2){
                var rem = n-i;
                assignees_profile += "<div class='image rounded-circle' id=remaining_profiles><b>+"+rem+"</b></div>";
                break;
            }
            var img =  "<img src=http://localhost:8000/storage/profile/";
                img = img + user_id_with_profile[assignees[task_id][i]['user_id']]+" class='image rounded-circle' id=card_images alt=http://localhost:8000/storage/profile/user.png>";
            assignees_profile += img;
        }
        assignees_profile = assignees_profile + "</div>";
        return assignees_profile;
    }

    function getExpired(date){
        var date = new Date(date);
        var expired_pill = "";
        console.log(date+" "+new Date());
        console.log(date < new Date());
        if(date < new Date()){
            expired_pill = "<span class='badge badge-danger' id=expired>Expired</span>"
        }
        return expired_pill;
    }

    function getCSRF(){
        return $('meta[name="csrf-token"]').attr('content');
    }


</script>

@endsection