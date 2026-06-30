<form method="POST" action="{{ route('cart.add') }}" class="flex items-end gap-3">
    @csrf
    <input type="hidden" name="article_id" value="{{ $article->id_article }}">

    <div>
        <label for="quantity" class="field-label">Quantité</label>
        <input id="quantity" name="quantity" type="number" value="1" min="1"
               max="{{ $article->quantity }}" {{ $article->quantity === 0 ? 'disabled' : '' }}
               class="field-input w-24">
    </div>

    <button type="submit" class="btn-primary" {{ $article->quantity === 0 ? 'disabled' : '' }}>
        <x-icon name="cart" class="w-4 h-4" /> Ajouter au panier
    </button>
</form>
