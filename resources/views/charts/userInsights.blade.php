@extends('layouts.app')

@section('content')
    <style>
        .selectMonth{
            padding: 5px;
            border-radius: 20px;
            border: none;
        }
    </style>



    <div class="container">
        <div class="pt-3 pb-4">
            <h4 class="d-inline mt-1">Hii,</h4>
            <h2 class="d-inline mt-1">{{auth()->user()->name}}</h2><br>
            <h5 class="mt-1">Your Insights are here</h5>
        </div>

        <div class="d-felx mt-3 ">
            <div>
                <b>Average Daily Tasks</b>
                <small class="ml-2">(avg of 1 month)</small>
                <select name="month" id="avgDailyTaskSpecificMonth" onchange='demo()' class='selectMonth'>
                    <option value="#">Choose</option>
                    <option value="January">January</option>
                    <option value="February">February</option>
                    <option value="March">March</option>
                    <option value="April">April</option>
                    <option value="May">May</option>
                    <option value="June">June</option>
                    <option value="July">July</option>
                    <option value="August">August</option>
                    <option value="September">September</option>
                    <option value="October">October</option>
                    <option value="November">November</option>
                    <option value="December">December</option>
                </select>
            </div>
        </div>

        <div         class="">
            <canvas height=100px width=400px id='avgDailyTask' aria-label="Average Daily Tasks" ></canvas>
        </div>


        <div class="d-flex mt-3">
            <b>Average Monthly Tasks</b>
            <small class="ml-2">(avg of 12 months)</small>
        </div>
        <div>
            <canvas height=100px width=400px id='avgMonthlyTask' aria-label='Average Monthly Tasks'></canvas>
        </div>


        <div class="d-flex mt-3">
            <b>Average Yearly Tasks</b>
            <small class="ml-2">(avg of 10 years)</small>
        </div>
        <div>
            <canvas height=100px width=400px id='avgYearlyTask' aria-label='Average Yearly Tasks'></canvas>
        </div>
    </div>



    <script>
        $(document).ready(function(){
            // alert("hii");
            getAvgDailyTasks();  
            // alert("Hello");
            getAvgMonthlyTasks();

            getAvgYearlyTasks();
            // alert("hii");
        });


        function getAvgDailyTasks(){
            var x = $.ajax({
                type: "get",
                url: '/getAvgDailyTasks',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                contentType: "application/json; charset=utf-8",
                crossDomain: true,
                dataType: "json",
            });
            plotGraph(x, 'avgDailyTask');
        }

        function getAvgMonthlyTasks(){
            var request = $.ajax({
                type: 'get',
                url: 'getAvgMonthlyTasks',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                contentType: "application/json; charset=utf-8",
                crossDomain: true,
                dataType: "json"
            });

            plotGraph(request, 'avgMonthlyTask');
        }

        function  getAvgYearlyTasks(){
            var request = $.ajax({
                type: 'get',
                url: 'getAvgYearlyTasks',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                contentType: "application/json; charset=utf-8",
                crossDomain: true,
                dataType: "json"
            });

            plotGraph(request, 'avgYearlyTask');
        }

        function demo(){
            var month = $('#avgDailyTaskSpecificMonth').val();
            // alert(e.value);
            var request = $.ajax({
                type: 'post',
                url: 'avgDailyTaskSpecificMonth',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                contentType: "application/json; charset=utf-8",
                data: JSON.stringify(month),
                crossDomain: true,
                dataType: "json"
            });
        }
        
        function plotGraph(request, id){
            request.done(function(data){
                console.log(data);
                const ctx = document.getElementById(id);
                var todo = data.todo;
                var inprogress = data.inprogress;
                var completed = data.completed;
                // console.log(data);
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Todo', 'Inprogress', 'Completed'],
                        datasets: [{
                        label: 'Avg Yearly Tasks',
                        data: [todo, inprogress, completed],
                        borderWidth: 3,
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.5)',
                            'rgba(54, 162, 235, 0.5)',
                            'rgba(153, 102, 255, 0.5)',
                        ],
                        }],
                    },
                    options: {
                        // borderColor: '#ff3b3b',
                        backgroundColor: '#ffffff',
                        // scales: {
                        // y: {beginAtZero: true}
                        // }
                        // layout: {
                        //     padding: 20
                        // }
                    },
                    plugins: []
                });
            });
        }

    </script>
@endsection