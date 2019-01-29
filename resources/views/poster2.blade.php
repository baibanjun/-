@extends('../layouts.main')

@section('content')

<div id="poster2">
    <img style="max-width:100%;display: block;" :src="data.name|cosPic(750)">
    <div style="position: absolute;left: 6.2rem;top: 17.1rem;color: #ffffff;">长按海报保存到本地</div>
</div>

@endsection

@section('javascript')

<script src="{{statics('js/ints/poster2.js')}}"></script>

@endsection