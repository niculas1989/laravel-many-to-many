@extends('layouts.app')

@section('content')
<div class="container">
    <header>
        <h1>Modifica Post</h1>
    </header>
    <hr>
    @include('includes.posts.form')
    @endsection

    @section('additional-scripts')
    <script>
        const placeholder = "http://www.asdalcione.it/wp-content/uploads/2016/08/jk-placeholder-image-1.jpg";

        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('preview');

        imageInput.addEventListener('change', e => {
            const preview = imageInput.value ?? placeholder;
            imagePreview.setAttribute('src', preview);
        })
    </script>
    @endsection