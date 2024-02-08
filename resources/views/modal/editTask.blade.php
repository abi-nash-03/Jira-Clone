
<style>
    .user_details{
        background-color: rgb(247, 247, 252);
    }
    .modal-xl {
        width: 90%;
        max-width:1200px;
    }

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
    input{
        cursor: pointer;
    }
    select{
            cursor: pointer;
        }
</style>




{{-- Modal for Task edit --}}
<div id="edit_task" class="modal fade" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="d-flex modal-header justify-content-between">
                <h4 class="modal-title">Edit task</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-7">
                            <form action="/task/{{$task["id"]}}" id="editForm" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label" >Title</label>
                                    <input type="text" class="form-control" id="taskTitle" name="title" value="{{$task["title"]}}">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlTextarea1" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" rows="3" name="body">No description :)</textarea>
                                    
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">Due at</label>
                                    <input type="date" class="form-control" id="due_at" name="due_at" value="{{$task["due_at"]}}">
                                </div>
                    
                                <div class="input-group mb-4">
                                    <label class="input-group-text" for="inputGroupSelect01">Status</label>
                                    <select class="form-select form-control" for="inputGroupSelect01" aria-label="Default select example" name="status" id = "status">
                                    <option selected value="todo">{{$task['status']}}</option>
                                    <option value="todo">Todo</option>
                                    <option value="inprogress">In Progress</option>
                                    <option value="completed">Completed</option>
                                    </select>
                                </div>
                                {{-- tag --}}
                                <div class="input-group mb-4 tasktag">
                                    <label class="input-group-text" for="inputGroupSelect01">Tag</label>
                                    <select class="form-select form-control" for="inputGroupSelect01" aria-label="Default select example" name="tag_id" id="edit_tag_id" data-tagid = "0">
                                        <option selectted value="" id="tagoption"></option>
                                    @foreach ($tags as $tag)
                                        <option value="{{$tag->id}}">{{$tag->name}}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <input type="submit" class="btn btn-outline-success" onclick="hide()">
                            </form>
                        </div>
                        <div class="col-5 user_details overflow-scroll">    
                            <div>
                                <b>Created By</b>
                                <div class="mt-2 user_details flex-column ">
                                    <img class="image rounded-circle mr-2" id="owner_profile" src="{{asset('/storage/profile/user.png')}}" alt="" style="width: 50px;height: 50px; margin-bottom: 2px;">
                                    <b id="owner_name">Enna therilaya</b>
                                    <div class="">
                                        <small>Created at:</small>
                                        <small id="created_at"></small>
                                        <small>Updated at:</small>
                                        <small id="updated_at"></small>
                                    </div>
                                    {{-- shared task emails --}}
                                    <div class="mt-2">
                                        <b class>Shared With</b>
                                    </div>
                                    <div class="d-flex flex-wrap overflow-scroll" id="task_emails">
                                    </div>
                                    {{-- <p id="updated_at"></p> --}}
                                </div>
                            </div>

                            {{-- share with colleague modal --}}
                            <div class="mt-5">
                                <div class="d-flex justify-content-between">
                                    <b class="modal-title mr-3 mt-2">Share with colleague</b>
                                </div>
                                <div class="modal-body">
                                    <form action="/share" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <label for="exampleFormControlInput1" class="form-label">Recipient</label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="basic-addon1">@</span>
                                            <input type="text" class="form-control" id="email_sugession" name="email">
                                            <input type="hidden" name='task_id' value="" id="task_id">
                                            {{-- <input type="text" name="country" id="autocomplete"/> --}}
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <input type="submit" value="share" class="btn btn-outline-success" onclick="hide()">
                                        </div>
                                    </form>
                                </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" class="close" data-dismiss="modal"   data-bs-toggle="modal" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
