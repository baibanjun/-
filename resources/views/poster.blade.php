@extends('../layouts.main')

@section('content')

<div id="poster">
    <img style="max-width:100%;display: block;" :src="data.name|cosPic(750)">
</div>

@endsection

@section('javascript')

<script src="{{statics('js/ints/poster.js')}}"></script>

@endsection