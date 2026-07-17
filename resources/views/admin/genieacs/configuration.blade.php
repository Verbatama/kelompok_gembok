@extends('layouts.app')
@vite([ 'resources/js/app.js', 'resources/css/app.css', 'resources/js/vue.js' ])
@section('title', 'IP Monitor Alerts')

@section('content')
@if(session('jwt_token'))
<script>
    localStorage.setItem('jwt_token', @json(session('jwt_token')));
</script>
@endif
<div class="min-h-screen">

    @include('admin.partials.sidebar')

    <main class="lg:ml-64 min-h-screen bg-gray-100 p-6">
        <div data-vue="ConfigurationPage"></div>
    </main>

</div>

@endsection
<script>
    window.APP = {
        jwt: @json(session('jwt_token'))
    };

</script>
@vite([ 'resources/js/app.js', 'resources/css/app.css',  'resources/js/vue.js' ])