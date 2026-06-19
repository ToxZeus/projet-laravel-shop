<form method="POST" action="{{ route('cart.add') }}">
    @csrf
    <input type="hidden" name="article_id" value="{{ $article->id_article }}">

    <label for="quantity">Quantité</label>
    <input id="quantity" name="quantity" type="number" value="1" min="1" class="border rounded px-2 py-1 text-gray-900 w-24">

    <button type="submit" class="ml-2 bg-blue-600 text-white px-3 py-1 rounded">Ajouter au panier</button>
</form>
