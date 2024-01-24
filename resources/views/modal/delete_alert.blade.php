{{-- model for deleteing a Task --}}

<div id="delete_alert" class="modal fade" role="dialog">
    <div class="modal-dialog ">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="d-flex justify-content-between">
                <button type="button" class="close ml-3 mt-2" data-dismiss="modal">&times;</button>
                <h4 class="modal-title mr-3 mt-2">Delete a Task</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure ? </p>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-outline-success mr-2" data-dismiss="modal">Not sure</button>
                    @if (@isset($task))
                        <form action="task/destroy/{{$task["id"]}}" id="delete_task_form" method="POST" >
                            @csrf
                            @method('DELETE')
                            <input type="submit" value="Sure" class="btn btn-outline-danger" onclick="hide()">
                        </form>

                    @endisset
                </div>
            </div>
        </div>

    </div>
</div>


{{-- <script>
    $(document).ready({
        alert($(this).val());
        alert("hhi");
    });
</script> --}}

{{-- <div id="delete_task" class="modal">

</div> --}}