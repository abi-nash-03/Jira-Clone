<script>
    function hide(){
        $('.alert').slideUp();
    }
    setTimeout(hide, 1000)
</script>

@if (count($errors) > 0)
    @foreach ($errors->all() as $error)
        <div class="alert alert-danger">
            {{$error}}
        </div>
    @endforeach
    
@endif

@if (session('success'))
    <div class="alert alert-success">
        {{session('success')}}
        {{-- {{hide()}} --}}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{session('error')}}
    </div>
@endif