<h1>Modifier le produit</h1>

<form action="{{ route('articles.update') }}" method="post">
    @csrf
    Titre :
    </br>
    <input type="text" name="title" value="{{ $article->title }}" />
    </br>
    Description :
    </br>
    <textarea name="description">{{ $article->description }}</textarea>
    </br>
    Catégorie :
    </br>
    <select name="category_id">
        @foreach($categories as $category)
            <option value="{{ $category->id_category }}" {{ $article->category_id == $category->id_category ? 'selected' : '' }}>{{ $category->name }}</option>
        @endforeach
    </select>
    </br>
    Quantité :
    </br>
    <input type="number" name="quantity" value="{{ $article->quantity }}" />
    </br>
    Prix :
    </br>
    <input type="number" name="price" step="0.01" value="{{ $article->price }}" />
    </br>
    <input type="hidden" name="id" value="{{ $article->id_article }}" />
    <button type="submit">Update</button>
</form>
