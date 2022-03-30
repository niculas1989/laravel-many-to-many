@if($errors->any())
<div class="alert-danger">
    <ul>
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
@if($post->exists)
<form action="{{ route('admin.posts.update', $post->id) }}" method="POST" novalidate>
    @method('PUT')
    @else
    <form action="{{ route('admin.posts.store') }}" method="POST" novalidate>
        @endif
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="form-group @error('title') is-invalid @enderror">
                    <label for="title" class="text-white">Titolo Post</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $post->title) }}" required>
                    @error('title')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
            <div class="col-12">
                <div class="form-group @error('content') is-invalid @enderror">
                    <label for=" content" class="text-white">Post</label>
                    <textarea class="form-control" id="content" rows="5" name="content">
                    {{ old('content', $post->content) }}
                    </textarea>
                </div>
                @error('title')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="col-10">
                <div class="form-group @error('image') is-invalid @enderror">
                    <label for="image" class="text-white">Immagine Post</label>
                    <input type="url" class="form-control" id="image" placeholder="immagine" name="image">
                    @error('title')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
            <div class="col 2">
                <img src="{{ old('image', $post->image) ?? 'http://www.asdalcione.it/wp-content/uploads/2016/08/jk-placeholder-image-1.jpg'}}" alt="placeholder" width=50; class="img-fluid" id="preview">
            </div>
            <div class="col-12 d-flex justify-content-between">
                <div class="form-group">
                    <label for="category" class="text-white">Seleziona la Categoria</label>
                    <select class="form-control w-100" id="category" name="category_id">
                        <option value="">--</option>
                        @foreach($categories as $c)
                        <option @if(old('category_id', $post->category_id)==$c->id) selected @endif value="{{ $c->id }}">{{ $c->label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="is_published" id="is-published" {{ old('is_published', $post->is_published) ? 'checked' : ''}} />
                    <label class="form-check-label text-white" for="is-published">Pubblicato</label>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-success"><i class="fa-solid fa-check mr-2"></i>Conferma</button>
        </div>
    </form>