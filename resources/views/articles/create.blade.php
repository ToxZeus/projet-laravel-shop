<h1>Créer un produit</h1>

<form action="/articles/create" method="post">
    @csrf
    Titre :
    </br>
    <input type="text" name="title" />
    </br>
    Description :
    </br>
    <textarea name="description"></textarea>
    </br>
    Catégorie :
    </br>
    <select name="category_id">
        @foreach($categories as $category)
            <option value="{{ $category->id_category }}">{{ $category->name }}</option>
        @endforeach
    </select>
    </br>
    Quantité :
    </br>
    <input type="number" name="quantity" />
    </br>
    Prix :
    </br>
    <input type="number" name="price" step="0.01" />
    </br>
    <button type="submit">Créer</button>
</form>
