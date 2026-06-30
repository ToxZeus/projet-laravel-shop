/* =====================================================================
   CONTENU DU COURS — 4 fonctionnalités suivies à travers tout le MVC.
   Chaque étape = un fichier. Chaque ligne = code + explication simple.
   couche ∈ Vue | Route | Contrôleur | Modèle | Migration | BDD
   ===================================================================== */

const FEATURES = [

/* ============================ 1. CRÉER UN ARTICLE ============================ */
{
  id: "creer-article",
  icone: "📝",
  titre: "Créer un article",
  sousTitre: "Le CRUD (Create) — le cœur de la consigne du prof",
  resume: "L'admin ouvre un formulaire, on valide les champs, on insère l'article en base, on revient au tableau de bord.",
  etapes: [
    {
      couche: "Vue", dossier: "resources/views", fichier: "dashboard.blade.php", lang: "blade",
      role: "Affiche le bouton « Créer un article » (uniquement si l'utilisateur est admin).",
      oral: "Le bouton n'apparaît que pour l'admin grâce à @if(role=='admin'). Mais ce n'est que visuel : la vraie sécurité est dans le contrôleur.",
      lignes: [
        { code: "@if(auth()->user()->role == 'admin')", exp: "Directive Blade <b>@if</b> : on n'affiche le bloc que si une condition est vraie. <span class='tok'>auth()->user()</span> = l'utilisateur connecté, <span class='tok'>->role</span> = sa colonne <span class='tok'>role</span>. <span class='tok'>==</span> compare." },
        { code: "    <a href=\"/articles/create\">Créer un article</a>", exp: "Un lien HTML. Cliquer envoie une requête <b>GET</b> vers l'URL <span class='tok'>/articles/create</span>." },
        { code: "@endif", exp: "Ferme le <b>@if</b>. ⭐ En Blade, chaque <span class='tok'>@if</span> a son <span class='tok'>@endif</span>." }
      ]
    },
    {
      couche: "Route", dossier: "routes", fichier: "web.php", lang: "php",
      role: "L'annuaire : associe l'URL /articles/create aux méthodes du contrôleur.",
      oral: "Tout est dans un groupe middleware('auth') : impossible d'accéder sans être connecté. Même URL, GET affiche / POST enregistre.",
      lignes: [
        { code: "Route::middleware('auth')->group(function () {", exp: "On regroupe des routes derrière le <b>middleware <span class='tok'>auth</span></b> : si on n'est pas connecté, Laravel redirige vers <span class='tok'>/login</span>." },
        { code: "    Route::get('/articles/create', [ArticlesController::class, 'create'])->name('articles.create');", exp: "Route <b>GET</b> (afficher) : l'URL <span class='tok'>/articles/create</span> appelle <span class='tok'>ArticlesController->create()</span>. <span class='tok'>->name()</span> donne un nom à la route." },
        { code: "    Route::post('/articles/create', [ArticlesController::class, 'post'])->name('articles.post');", exp: "Route <b>POST</b> (enregistrer) : même URL, mais le verbe POST appelle la méthode <span class='tok'>post()</span>. ⭐ Verbe différent → méthode différente." },
        { code: "});", exp: "Ferme le groupe de routes." }
      ]
    },
    {
      couche: "Contrôleur", dossier: "app/Http/Controllers", fichier: "ArticlesController.php", lang: "php",
      role: "Méthode create() : vérifie le rôle admin, récupère les catégories, renvoie le formulaire.",
      oral: "Le contrôleur est le chef d'orchestre : il sécurise, va chercher les données via le modèle, puis choisit la vue.",
      lignes: [
        { code: "public function create()", exp: "Méthode <b>publique</b> que Laravel peut appeler. <span class='tok'>create</span> = son nom, <span class='tok'>()</span> = aucun paramètre." },
        { code: "{", exp: "Début du corps de la méthode." },
        { code: "    if (Auth::user()->role !== 'admin') {", exp: "⭐ <b>Vraie sécurité</b> : <span class='tok'>!==</span> = « strictement différent ». « Si l'utilisateur n'est PAS admin… »" },
        { code: "        return redirect('/dashboard')", exp: "…on <b>arrête</b> (return) et on <b>redirige</b> vers le tableau de bord." },
        { code: "        ->with('info', 'Vous ne pouvez pas créer de produit.');", exp: "On attache un <b>message flash</b> (clé <span class='tok'>info</span>) affiché sur la page suivante." },
        { code: "    }", exp: "Fin du bloc if." },
        { code: "    $categories = Category::all();", exp: "<span class='tok'>$categories</span> = variable. <span class='tok'>Category::all()</span> demande au <b>modèle</b> toutes les catégories (pour la liste déroulante)." },
        { code: "    return view('articles.create', ['categories' => $categories]);", exp: "Renvoie la vue <span class='tok'>articles/create.blade.php</span> en lui passant les catégories (clé <span class='tok'>categories</span> → variable <span class='tok'>$categories</span> dans la vue)." },
        { code: "}", exp: "Fin de la méthode." }
      ]
    },
    {
      couche: "Vue", dossier: "resources/views/articles", fichier: "create.blade.php", lang: "blade",
      role: "Le formulaire HTML que l'admin remplit.",
      oral: "À l'envoi, le navigateur fait un POST vers /articles/create avec chaque champ (identifié par son name) + le jeton @csrf.",
      lignes: [
        { code: "@extends('layouts.app')", exp: "Cette vue <b>hérite</b> du gabarit commun <span class='tok'>layouts/app.blade.php</span> (en-tête, navigation)." },
        { code: "@section('content')", exp: "Définit le bloc « content » qui sera inséré dans le gabarit." },
        { code: "    @if($errors->any())", exp: "<span class='tok'>$errors</span> existe toujours dans les vues. <span class='tok'>->any()</span> : « y a-t-il des erreurs de validation ? »." },
        { code: "        @foreach($errors->all() as $error)", exp: "Boucle <b>@foreach</b> sur chaque erreur ; <span class='tok'>as $error</span> = la variable de boucle." },
        { code: "            <li>{{ $error }}</li>", exp: "⭐ Les <b>doubles accolades</b> <span class='tok'>{{ }}</span> affichent une variable en <b>échappant le HTML</b> (protection XSS)." },
        { code: "    <form action=\"/articles/create\" method=\"post\">", exp: "Le formulaire. <span class='tok'>action</span> = URL d'envoi ; <span class='tok'>method=\"post\"</span> = verbe POST (on envoie des données)." },
        { code: "        @csrf", exp: "⭐ <b>Obligatoire.</b> Génère un champ caché avec un <b>jeton anti-CSRF</b>. Sans lui, Laravel refuse le POST (erreur 419)." },
        { code: "        <input type=\"text\" name=\"title\" value=\"{{ old('title') }}\" />", exp: "⭐ L'attribut <span class='tok'>name</span> est la clé reçue côté serveur (<span class='tok'>$request->title</span>). <span class='tok'>old('title')</span> re-remplit le champ si la validation a échoué." },
        { code: "        <select name=\"category_id\">", exp: "Liste déroulante des catégories ; sa valeur sera envoyée sous le nom <span class='tok'>category_id</span>." },
        { code: "            @foreach($categories as $category)", exp: "Affiche une <span class='tok'>&lt;option&gt;</span> par catégorie (les <span class='tok'>$categories</span> viennent du contrôleur)." },
        { code: "                <option value=\"{{ $category->id_category }}\">{{ $category->name }}</option>", exp: "La <b>valeur envoyée</b> = l'id de la catégorie ; le texte affiché = son nom." },
        { code: "        <input type=\"number\" name=\"price\" step=\"0.01\" value=\"{{ old('price', 0) }}\" />", exp: "Champ numérique. <span class='tok'>step=\"0.01\"</span> autorise 2 décimales (le prix)." },
        { code: "        <button type=\"submit\">Créer</button>", exp: "Le bouton qui <b>envoie</b> le formulaire (déclenche le POST)." }
      ]
    },
    {
      couche: "Contrôleur", dossier: "app/Http/Controllers", fichier: "ArticlesController.php", lang: "php",
      role: "Méthode post() : valide les données reçues puis crée l'article en base.",
      oral: "Je valide d'abord. Si une règle échoue, Laravel renvoie au formulaire. Sinon, $validated ne contient que des champs sûrs, et Article::create les insère.",
      lignes: [
        { code: "public function post(Request $request){", exp: "⭐ <span class='tok'>Request $request</span> = <b>injection automatique</b> : Laravel fournit l'objet requête, qui contient tous les champs du formulaire." },
        { code: "    $validated = $request->validate([", exp: "⭐ Lance la <b>validation</b>. Si elle échoue → retour au formulaire avec les erreurs (la suite n'est pas exécutée)." },
        { code: "        'title' => 'required|string|max:255',", exp: "Le titre est <b>obligatoire</b>, de type <b>chaîne</b>, <b>255 car. max</b>. Le <span class='tok'>|</span> sépare les règles." },
        { code: "        'description' => 'nullable|string',", exp: "<span class='tok'>nullable</span> = le champ <b>peut être vide</b>." },
        { code: "        'image' => 'nullable|url',", exp: "Si fournie, doit être une <b>URL valide</b>." },
        { code: "        'category_id' => 'required|integer|exists:categories,id_category',", exp: "⭐ <span class='tok'>exists:categories,id_category</span> vérifie que la catégorie <b>existe vraiment</b> en base → cohérence référentielle." },
        { code: "        'price' => 'required|numeric|min:0',", exp: "Prix : <b>nombre</b> ≥ 0." },
        { code: "        'quantity' => 'required|integer|min:0',", exp: "Stock : <b>entier</b> ≥ 0." },
        { code: "    ]);", exp: "Fin des règles. <span class='tok'>$validated</span> = tableau des champs validés uniquement." },
        { code: "    Article::create($validated);", exp: "⭐ Crée l'article. <b>Mass assignment</b> : remplit toutes les colonnes d'un coup. Possible grâce au <span class='tok'>$fillable</span> du modèle." },
        { code: "    return redirect('/dashboard')->with('status', 'Article créé.');", exp: "Redirige vers le dashboard avec un <b>message de succès</b> (clé <span class='tok'>status</span>, affiché en vert)." }
      ]
    },
    {
      couche: "Modèle", dossier: "app/Models", fichier: "Article.php", lang: "php",
      role: "Représente la table 'articles' ; traduit Article::create() en SQL (INSERT).",
      oral: "Le modèle est le traducteur PHP↔SQL. $fillable autorise l'insertion, belongsTo relie l'article à sa catégorie.",
      lignes: [
        { code: "class Article extends Model", exp: "<span class='tok'>Article</span> <b>hérite</b> de <span class='tok'>Model</span> (Eloquent) → il sait parler à la base sans SQL écrit à la main." },
        { code: "    protected $primaryKey = 'id_article';", exp: "⭐ On précise que la <b>clé primaire</b> s'appelle <span class='tok'>id_article</span> (et pas <span class='tok'>id</span> par défaut)." },
        { code: "    protected $fillable = [", exp: "⭐ <b>Liste blanche</b> des colonnes remplissables en masse. C'est elle qui <b>autorise</b> <span class='tok'>Article::create($validated)</span>." },
        { code: "        'title', 'description', 'image', 'category_id', 'price', 'quantity',", exp: "Les colonnes qu'on a le droit de remplir via <span class='tok'>create()</span>/<span class='tok'>update()</span>." },
        { code: "    ];", exp: "Fin de la liste." },
        { code: "    public function category(): BelongsTo", exp: "Déclare la <b>relation</b> : un article <b>appartient à</b> une catégorie. <span class='tok'>: BelongsTo</span> = type de retour." },
        { code: "        return $this->belongsTo(Category::class, 'category_id');", exp: "⭐ Lien via la colonne <span class='tok'>category_id</span>. Permet d'écrire <span class='tok'>$article->category->name</span> dans les vues." }
      ]
    },
    {
      couche: "Migration", dossier: "database/migrations", fichier: "..._create_articles_table.php", lang: "php",
      role: "A créé la structure de la table 'articles' (exécutée par artisan migrate).",
      oral: "La migration crée les colonnes, le modèle les remplit, la validation les contrôle. Les trois doivent être cohérents.",
      lignes: [
        { code: "Schema::create('articles', function (Blueprint $table) {", exp: "Crée la table <span class='tok'>articles</span>." },
        { code: "    $table->id('id_article');", exp: "Colonne <b>clé primaire</b> auto-incrémentée nommée <span class='tok'>id_article</span>." },
        { code: "    $table->foreignId('category_id')->constrained('categories', 'id_category')->onDelete('cascade');", exp: "⭐ <b>Clé étrangère</b> : <span class='tok'>category_id</span> doit exister dans <span class='tok'>categories.id_category</span> → le modèle relationnel. <span class='tok'>onDelete('cascade')</span> : si la catégorie part, ses articles aussi." },
        { code: "    $table->string('title');", exp: "Colonne texte courte (VARCHAR) pour le titre." },
        { code: "    $table->text('description');", exp: "Colonne texte longue." },
        { code: "    $table->decimal('price', 10, 2);", exp: "⭐ Prix en <b>decimal</b> (précision exacte) — <b>jamais float</b> pour de l'argent." },
        { code: "    $table->integer('quantity');", exp: "Le stock (entier)." },
        { code: "    $table->timestamps();", exp: "Ajoute <span class='tok'>created_at</span> et <span class='tok'>updated_at</span> (gérés automatiquement)." }
      ]
    }
  ],
  quiz: [
    { q: "Pourquoi DEUX routes pour /articles/create ?", r: "Une en GET (afficher le formulaire → create()), une en POST (enregistrer → post()). Même URL, verbe différent." },
    { q: "Que se passe-t-il si le titre est vide ?", r: "La règle 'required' échoue, validate() interrompt et renvoie au formulaire avec l'erreur ; old() garde les autres champs." },
    { q: "C'est quoi Article::create($validated) ?", r: "Du mass assignment : Eloquent insère une ligne en remplissant les colonnes du $fillable, et génère le INSERT SQL." },
    { q: "Un non-admin tape l'URL /articles/create à la main ?", r: "Le test if(role !== 'admin') dans le contrôleur le redirige. Masquer le bouton ne suffit pas : sécurité = contrôleur." },
    { q: "Comment garantir que la catégorie existe ?", r: "Double sécurité : règle exists:categories,id_category (appli) + clé étrangère constrained() (base)." }
  ]
},

/* ========================== 2. PASSER UNE COMMANDE ========================== */
{
  id: "passer-commande",
  icone: "📦",
  titre: "Passer une commande",
  sousTitre: "La logique métier qui impressionne : stock décrémenté + prix figé",
  resume: "Depuis le panier, on valide : on crée la commande, une ligne par produit (prix figé), on baisse le stock, on vide le panier.",
  etapes: [
    {
      couche: "Vue", dossier: "resources/views/cart", fichier: "index.blade.php", lang: "blade",
      role: "Affiche le panier et le bouton « Commander ».",
      oral: "Le bouton Commander est un formulaire POST vers orders.store, protégé par @csrf.",
      lignes: [
        { code: "    <form method=\"POST\" action=\"{{ route('orders.store') }}\" class=\"mt-4\">", exp: "Formulaire <b>POST</b> vers la route nommée <span class='tok'>orders.store</span>. <span class='tok'>route()</span> génère l'URL à partir du nom." },
        { code: "        @csrf", exp: "Jeton anti-CSRF obligatoire pour tout POST." },
        { code: "        <button type=\"submit\">Commander</button>", exp: "Envoie le panier au serveur pour le transformer en commande." },
        { code: "    </form>", exp: "Fin du formulaire." }
      ]
    },
    {
      couche: "Route", dossier: "routes", fichier: "web.php", lang: "php",
      role: "Relie POST /orders à OrderController@store.",
      oral: "Lire les commandes = GET ; créer une commande = POST. Tout est sous middleware auth.",
      lignes: [
        { code: "    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');", exp: "Liste des commandes (GET)." },
        { code: "    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');", exp: "⭐ <b>Valider le panier</b> (POST) → <span class='tok'>store()</span>." },
        { code: "    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');", exp: "Détail d'une commande. <span class='tok'>{id}</span> = paramètre dynamique passé à <span class='tok'>show($id)</span>." }
      ]
    },
    {
      couche: "Contrôleur", dossier: "app/Http/Controllers", fichier: "OrderController.php", lang: "php",
      role: "Méthode store() : LE cœur métier. Crée la commande, fige le prix, baisse le stock, vide le panier.",
      oral: "Je récupère le panier, je refuse s'il est vide, je revérifie le stock, je crée la commande + une ligne par produit en figeant le prix, je décrémente le stock, je vide le panier.",
      lignes: [
        { code: "public function store()", exp: "Méthode appelée par POST /orders." },
        { code: "    $user = Auth::user();", exp: "L'utilisateur connecté (propriétaire du panier et de la future commande)." },
        { code: "    $items = Cart::with('article')->where('user_id', $user->id)->get();", exp: "⭐ <b>Eager loading</b> : charge les lignes du panier <b>avec</b> leur article en une fois (évite le problème N+1). Filtré sur l'utilisateur." },
        { code: "    if ($items->isEmpty()) {", exp: "Si le panier est <b>vide</b>…" },
        { code: "        return redirect()->route('cart.index')->with('info', 'Votre panier est vide.');", exp: "…on s'arrête et on retourne au panier avec un message." },
        { code: "    foreach ($items as $item) {", exp: "On parcourt chaque ligne du panier pour <b>revérifier le stock</b>." },
        { code: "        if ($item->quantity > $item->article->quantity) {", exp: "Si la quantité voulue dépasse le <b>stock dispo</b> de l'article…" },
        { code: "            return redirect()->route('cart.index')->with('info', 'Stock insuffisant pour « '.$item->article->title.' ».');", exp: "…on refuse la commande. Le <span class='tok'>.</span> (point) <b>concatène</b> les chaînes." },
        { code: "    $total = $items->sum(fn ($item) => $item->article->price * $item->quantity);", exp: "⭐ Calcule le <b>total</b>. <span class='tok'>sum()</span> additionne ; <span class='tok'>fn ($item) => ...</span> est une fonction fléchée (prix × quantité)." },
        { code: "    $order = Order::create([", exp: "Crée la <b>commande</b> (table orders)." },
        { code: "        'user_id' => $user->id,", exp: "À qui appartient la commande." },
        { code: "        'total' => $total,", exp: "Le montant total calculé juste avant." },
        { code: "        'status' => 'validée',", exp: "Statut initial de la commande." },
        { code: "    ]);", exp: "<span class='tok'>$order</span> contient maintenant la commande créée (avec son <span class='tok'>id_order</span>)." },
        { code: "    foreach ($items as $item) {", exp: "On reparcourt le panier pour créer le <b>détail</b> de la commande." },
        { code: "        OrderItem::create([", exp: "Crée une <b>ligne de commande</b> (un produit acheté)." },
        { code: "            'order_id' => $order->id_order,", exp: "Rattache la ligne à la commande créée." },
        { code: "            'article_id' => $item->article_id,", exp: "Quel article a été acheté." },
        { code: "            'quantity' => $item->quantity,", exp: "Combien d'unités." },
        { code: "            'price' => $item->article->price,", exp: "⭐⭐ <b>PRIX FIGÉ</b> : on copie le prix au moment de l'achat. Si le prix change demain, la commande garde le prix payé (historique)." },
        { code: "        $item->article->quantity -= $item->quantity;", exp: "⭐ <b>Décrémente le stock</b> : <span class='tok'>-=</span> retire la quantité achetée du stock de l'article." },
        { code: "        $item->article->save();", exp: "Sauvegarde le nouveau stock en base (UPDATE)." },
        { code: "    Cart::where('user_id', $user->id)->delete();", exp: "⭐ <b>Vide le panier</b> de l'utilisateur (la commande est validée)." },
        { code: "    return redirect()->route('orders.show', $order->id_order)->with('status', 'Commande validée.');", exp: "Redirige vers le <b>détail</b> de la commande, avec un message de succès." }
      ]
    },
    {
      couche: "Modèle", dossier: "app/Models", fichier: "Order.php", lang: "php",
      role: "La commande : appartient à un user, possède plusieurs lignes (items).",
      oral: "Order belongsTo User (qui a commandé) et hasMany OrderItem (le détail).",
      lignes: [
        { code: "    protected $primaryKey = 'id_order';", exp: "Clé primaire personnalisée." },
        { code: "    protected $fillable = ['user_id', 'total', 'status'];", exp: "Colonnes remplissables via <span class='tok'>create()</span>." },
        { code: "    public function user(): BelongsTo", exp: "Relation : la commande <b>appartient à</b> un utilisateur." },
        { code: "        return $this->belongsTo(User::class, 'user_id');", exp: "Lien via <span class='tok'>user_id</span>." },
        { code: "    public function items(): HasMany", exp: "Relation : une commande <b>a plusieurs</b> lignes." },
        { code: "        return $this->hasMany(OrderItem::class, 'order_id');", exp: "Permet <span class='tok'>$order->items</span> (toutes les lignes de la commande)." }
      ]
    },
    {
      couche: "Modèle", dossier: "app/Models", fichier: "OrderItem.php", lang: "php",
      role: "Une ligne de commande = un produit acheté, avec son prix figé.",
      oral: "OrderItem relie une commande à un article et stocke quantité + prix payé.",
      lignes: [
        { code: "    protected $primaryKey = 'id_order_item';", exp: "Clé primaire de la ligne." },
        { code: "    protected $fillable = ['order_id', 'article_id', 'quantity', 'price'];", exp: "Colonnes remplissables, dont le <b>prix figé</b>." },
        { code: "    public function order(): BelongsTo", exp: "La ligne appartient à une commande." },
        { code: "        return $this->belongsTo(Order::class, 'order_id');", exp: "Lien via <span class='tok'>order_id</span>." },
        { code: "    public function article(): BelongsTo", exp: "La ligne référence un article." },
        { code: "        return $this->belongsTo(Article::class, 'article_id');", exp: "Permet <span class='tok'>$item->article->title</span>." }
      ]
    },
    {
      couche: "Vue", dossier: "resources/views/orders", fichier: "show.blade.php", lang: "blade",
      role: "Affiche le détail de la commande (prix figés stockés).",
      oral: "On boucle sur $order->items ; le prix affiché est celui stocké dans order_items (figé), pas le prix actuel de l'article.",
      lignes: [
        { code: "    @foreach($order->items as $item)", exp: "Boucle sur chaque <b>ligne</b> de la commande (relation items)." },
        { code: "        <td>{{ $item->article->title }}</td>", exp: "Nom de l'article (via la relation <span class='tok'>article</span>)." },
        { code: "        <td>{{ $item->quantity }}</td>", exp: "Quantité achetée." },
        { code: "        <td>{{ number_format($item->price, 2, ',', ' ') }} €</td>", exp: "⭐ Prix <b>figé</b> (depuis <span class='tok'>order_items</span>). <span class='tok'>number_format</span> formate « 12,50 »." },
        { code: "    <td>{{ number_format($order->total, 2, ',', ' ') }} €</td>", exp: "Le total de la commande." }
      ]
    }
  ],
  quiz: [
    { q: "Pourquoi figer le prix dans order_items ?", r: "Pour l'historique : si le prix de l'article change après, la commande conserve le prix réellement payé." },
    { q: "Pourquoi décrémenter le stock à la commande et pas à l'ajout au panier ?", r: "Un article au panier n'est pas vendu. Le stock ne baisse qu'à l'achat réel, sinon un panier abandonné bloquerait du stock." },
    { q: "C'est quoi l'eager loading (with) ?", r: "Cart::with('article') charge les articles liés en une requête au lieu d'une par ligne → évite le problème N+1." },
    { q: "Deux clients commandent le dernier article en même temps ?", r: "Cas de concurrence. Je revérifie le stock ; la version robuste = DB::transaction + verrou (lockForUpdate). Je connais la parade." },
    { q: "Quelle est la limite de ton store() ?", r: "Pas de DB::transaction : si une erreur survient au milieu, la base peut être incohérente. Je l'envelopperais dans une transaction." }
  ]
},

/* ============================ 3. SE CONNECTER ============================ */
{
  id: "se-connecter",
  icone: "🔐",
  titre: "Se connecter",
  sousTitre: "Authentification Breeze : formulaire → validation → session",
  resume: "L'utilisateur saisit email + mot de passe ; on valide, on vérifie les identifiants (anti-force brute), on ouvre la session.",
  etapes: [
    {
      couche: "Vue", dossier: "resources/views/auth", fichier: "login.blade.php", lang: "blade",
      role: "Le formulaire de connexion (composants Breeze).",
      oral: "Formulaire POST vers la route login, avec @csrf, et affichage des erreurs via x-input-error.",
      lignes: [
        { code: "    <form method=\"POST\" action=\"{{ route('login') }}\">", exp: "Formulaire <b>POST</b> vers la route nommée <span class='tok'>login</span>." },
        { code: "        @csrf", exp: "Jeton anti-CSRF obligatoire." },
        { code: "        <x-text-input id=\"email\" type=\"email\" name=\"email\" :value=\"old('email')\" required autofocus />", exp: "Champ email (composant Blade <span class='tok'>x-text-input</span>). <span class='tok'>name=\"email\"</span> = clé envoyée ; <span class='tok'>old('email')</span> le re-remplit après erreur." },
        { code: "        <x-input-error :messages=\"$errors->get('email')\" />", exp: "Affiche les erreurs de validation du champ email." },
        { code: "        <x-text-input id=\"password\" type=\"password\" name=\"password\" required />", exp: "Champ mot de passe (masqué). <span class='tok'>name=\"password\"</span>." },
        { code: "        <input type=\"checkbox\" name=\"remember\">", exp: "Case « se souvenir de moi » → envoyée sous le nom <span class='tok'>remember</span>." },
        { code: "        <x-primary-button>{{ __('Log in') }}</x-primary-button>", exp: "Bouton d'envoi. <span class='tok'>__()</span> = texte traduisible." }
      ]
    },
    {
      couche: "Route", dossier: "routes", fichier: "auth.php", lang: "php",
      role: "Routes d'authentification générées par Breeze.",
      oral: "GET login affiche le formulaire, POST login le traite. Le middleware guest = réservé aux NON connectés.",
      lignes: [
        { code: "Route::middleware('guest')->group(function () {", exp: "<b>guest</b> = l'inverse de auth : accessible seulement si on n'est PAS connecté (inutile de se connecter si on l'est déjà)." },
        { code: "    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');", exp: "Affiche le formulaire (GET) → <span class='tok'>create()</span>." },
        { code: "    Route::post('login', [AuthenticatedSessionController::class, 'store']);", exp: "Traite la connexion (POST) → <span class='tok'>store()</span>." },
        { code: "});", exp: "Fin du groupe guest." }
      ]
    },
    {
      couche: "Contrôleur", dossier: "app/Http/Controllers/Auth", fichier: "AuthenticatedSessionController.php", lang: "php",
      role: "Méthode store() : déclenche l'authentification puis régénère la session.",
      oral: "Le contrôleur est mince : il délègue la validation et l'authentification au LoginRequest, puis sécurise la session.",
      lignes: [
        { code: "public function store(LoginRequest $request): RedirectResponse", exp: "⭐ Le type <span class='tok'>LoginRequest</span> déclenche <b>automatiquement</b> la validation + l'autorisation AVANT d'entrer ici." },
        { code: "    $request->authenticate();", exp: "Appelle la méthode du LoginRequest qui vérifie les identifiants (voir étape suivante)." },
        { code: "    $request->session()->regenerate();", exp: "⭐ <b>Régénère l'ID de session</b> après connexion → protège contre la « fixation de session »." },
        { code: "    return redirect()->intended(route('dashboard', absolute: false));", exp: "Redirige vers la page voulue avant le login, sinon le <span class='tok'>dashboard</span>." }
      ]
    },
    {
      couche: "Contrôleur", dossier: "app/Http/Requests/Auth", fichier: "LoginRequest.php", lang: "php",
      role: "FormRequest : valide le formulaire ET authentifie avec protection anti-force brute.",
      oral: "Le LoginRequest regroupe la validation et la logique d'authentification, pour décharger le contrôleur. Il vérifie le quota d'essais, compare les identifiants via Auth::attempt, et bloque après 5 échecs.",
      lignes: [
        { code: "    public function authorize(): bool", exp: "Qui a le droit d'envoyer cette requête ?" },
        { code: "        return true;", exp: "Tout le monde : un visiteur doit pouvoir tenter de se connecter." },
        { code: "    public function rules(): array", exp: "Les règles de <b>validation</b> du formulaire." },
        { code: "        'email' => ['required', 'string', 'email'],", exp: "Email <b>obligatoire</b>, chaîne, format email." },
        { code: "        'password' => ['required', 'string'],", exp: "Mot de passe obligatoire (on vérifie juste la <b>forme</b>, pas encore s'il est correct)." },
        { code: "    public function authenticate(): void", exp: "Tente la connexion. <span class='tok'>void</span> = ne retourne rien (agit ou lance une exception)." },
        { code: "        $this->ensureIsNotRateLimited();", exp: "Vérifie d'abord qu'on n'a pas dépassé le nombre d'essais (anti-force brute)." },
        { code: "        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {", exp: "⭐ <span class='tok'>Auth::attempt</span> cherche l'email en base et <b>compare le mot de passe haché</b>. <span class='tok'>!</span> = « si la connexion échoue »." },
        { code: "            RateLimiter::hit($this->throttleKey());", exp: "Enregistre une <b>tentative échouée</b> (incrémente le compteur)." },
        { code: "            throw ValidationException::withMessages([", exp: "Lance une erreur de validation rattachée à l'email…" },
        { code: "                'email' => trans('auth.failed'),", exp: "…avec le message traduit « identifiants incorrects »." },
        { code: "        RateLimiter::clear($this->throttleKey());", exp: "Connexion réussie → <b>remet le compteur d'essais à zéro</b>." },
        { code: "    public function ensureIsNotRateLimited(): void", exp: "La protection <b>anti-force brute</b>." },
        { code: "        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {", exp: "Moins de <b>5 tentatives</b> ? Alors tout va bien…" },
        { code: "            return;", exp: "…on sort de la méthode (rien à bloquer)." },
        { code: "        event(new Lockout($this));", exp: "Trop d'essais → on déclenche l'<b>événement Lockout</b> (un listener peut journaliser/alerter)." },
        { code: "        $seconds = RateLimiter::availableIn($this->throttleKey());", exp: "Nombre de secondes à attendre avant de réessayer." },
        { code: "    public function throttleKey(): string", exp: "La <b>clé</b> qui identifie le tentateur." },
        { code: "        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());", exp: "Clé = email (minuscules) <span class='tok'>.</span> '|' <span class='tok'>.</span> adresse IP → compteur <b>par email ET par IP</b>." }
      ]
    }
  ],
  quiz: [
    { q: "Qui appelle le LoginRequest ?", r: "AuthenticatedSessionController@store(LoginRequest $request) : Laravel l'injecte, exécute authorize() et rules() avant la méthode, puis on appelle $request->authenticate()." },
    { q: "rules() vérifie-t-il que le mot de passe est correct ?", r: "Non, juste la forme (présent, type). La vraie vérification se fait dans authenticate() via Auth::attempt()." },
    { q: "Comment es-tu protégé contre la force brute ?", r: "Le RateLimiter bloque après 5 essais par couple email+IP, avec un délai d'attente, et déclenche l'événement Lockout." },
    { q: "Pourquoi session()->regenerate() ?", r: "Pour changer l'ID de session après connexion → protection contre la fixation de session." },
    { q: "Où sont stockés les mots de passe ?", r: "Hachés en bcrypt en base, jamais en clair. Auth::attempt compare le hash." }
  ]
},

/* =========================== 4. AJOUTER AU PANIER =========================== */
{
  id: "ajouter-panier",
  icone: "🛒",
  titre: "Ajouter au panier",
  sousTitre: "Validation + contrôle du stock disponible",
  resume: "Depuis la fiche produit, on ajoute une quantité : on valide, on vérifie le stock, on incrémente la ligne existante ou on en crée une.",
  etapes: [
    {
      couche: "Vue", dossier: "resources/views/cart", fichier: "_add_form.blade.php", lang: "blade",
      role: "Petit formulaire réutilisable « Ajouter au panier » (inclus dans la fiche produit).",
      oral: "C'est un partial inclus dans articles/show.blade.php via @include('cart._add_form') → réutilisation (DRY).",
      lignes: [
        { code: "<form method=\"POST\" action=\"{{ route('cart.add') }}\">", exp: "Formulaire <b>POST</b> vers la route <span class='tok'>cart.add</span>." },
        { code: "    @csrf", exp: "Jeton anti-CSRF." },
        { code: "    <input type=\"hidden\" name=\"article_id\" value=\"{{ $article->id_article }}\">", exp: "Champ <b>caché</b> : l'id de l'article à ajouter (envoyé sous <span class='tok'>article_id</span>)." },
        { code: "    <input id=\"quantity\" name=\"quantity\" type=\"number\" value=\"1\" min=\"1\">", exp: "La quantité voulue (minimum 1)." },
        { code: "    <button type=\"submit\">Ajouter au panier</button>", exp: "Envoie le formulaire (POST)." }
      ]
    },
    {
      couche: "Route", dossier: "routes", fichier: "web.php", lang: "php",
      role: "Relie POST /cart/add à CartController@add.",
      oral: "Voir le panier = GET ; ajouter/retirer = POST. Sous middleware auth.",
      lignes: [
        { code: "    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');", exp: "Affiche le panier (GET)." },
        { code: "    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');", exp: "⭐ Ajoute un article (POST) → <span class='tok'>add()</span>." },
        { code: "    Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');", exp: "Retire une ligne du panier (POST). <span class='tok'>{id}</span> = l'id de la ligne." }
      ]
    },
    {
      couche: "Contrôleur", dossier: "app/Http/Controllers", fichier: "CartController.php", lang: "php",
      role: "Méthode add() : valide, contrôle le stock, incrémente ou crée la ligne de panier.",
      oral: "Je valide, je vérifie que (déjà au panier + demandé) ne dépasse pas le stock, puis j'incrémente la ligne existante ou j'en crée une nouvelle.",
      lignes: [
        { code: "public function add(Request $request)", exp: "Reçoit la requête (injectée par Laravel)." },
        { code: "    $request->validate([", exp: "<b>Validation</b> des données reçues." },
        { code: "        'article_id' => 'required|integer|exists:articles,id_article',", exp: "⭐ L'article doit <b>exister</b> (exists). Sécurité référentielle." },
        { code: "        'quantity' => 'required|integer|min:1',", exp: "Quantité : entier ≥ 1." },
        { code: "    $user = Auth::user();", exp: "L'utilisateur connecté (propriétaire du panier)." },
        { code: "    $article = Article::findOrFail($request->article_id);", exp: "Récupère l'article. <span class='tok'>findOrFail</span> → erreur 404 s'il n'existe pas." },
        { code: "    $cart = Cart::where('user_id', $user->id)->where('article_id', $request->article_id)->first();", exp: "Cherche si cet article est <b>déjà</b> dans le panier de l'utilisateur. <span class='tok'>first()</span> = la 1re ligne ou null." },
        { code: "    $current = $cart ? $cart->quantity : 0;", exp: "Quantité déjà présente (ternaire : si <span class='tok'>$cart</span> existe → sa quantité, sinon 0)." },
        { code: "    if ($current + $request->quantity > $article->quantity) {", exp: "⭐ <b>Contrôle de stock</b> : si (déjà au panier + demandé) dépasse le stock dispo…" },
        { code: "        return redirect()->route('cart.index')->with('info', 'Stock insuffisant : ...');", exp: "…on refuse et on prévient l'utilisateur." },
        { code: "    if ($cart) {", exp: "Si l'article est <b>déjà</b> dans le panier…" },
        { code: "        $cart->quantity += $request->quantity;", exp: "…on <b>incrémente</b> la quantité (<span class='tok'>+=</span>)." },
        { code: "        $cart->save();", exp: "On sauvegarde (UPDATE)." },
        { code: "    } else {", exp: "Sinon (pas encore au panier)…" },
        { code: "        Cart::create([", exp: "…on <b>crée</b> une nouvelle ligne de panier." },
        { code: "            'user_id' => $user->id,", exp: "Pour quel utilisateur." },
        { code: "            'article_id' => $request->article_id,", exp: "Quel article." },
        { code: "            'quantity' => $request->quantity,", exp: "Quelle quantité." },
        { code: "    return redirect()->route('cart.index')->with('status', 'Article ajouté au panier.');", exp: "Retour au panier avec un message de succès." }
      ]
    },
    {
      couche: "Modèle", dossier: "app/Models", fichier: "Cart.php", lang: "php",
      role: "Une ligne de panier : un user veut N fois un article.",
      oral: "Attention : la table s'appelle 'cart' au singulier, donc je dois préciser $table. Cart belongsTo User et belongsTo Article.",
      lignes: [
        { code: "    protected $table = 'cart';", exp: "⭐ <b>Indispensable</b> : la table est <span class='tok'>cart</span> (singulier). Sans ça, Eloquent chercherait <span class='tok'>carts</span> et planterait." },
        { code: "    protected $primaryKey = 'id_cart';", exp: "Clé primaire personnalisée." },
        { code: "    protected $fillable = ['user_id', 'article_id', 'quantity'];", exp: "Colonnes remplissables via <span class='tok'>create()</span>." },
        { code: "    public function user(): BelongsTo", exp: "La ligne appartient à un utilisateur." },
        { code: "        return $this->belongsTo(User::class, 'user_id');", exp: "Lien via <span class='tok'>user_id</span>." },
        { code: "    public function article(): BelongsTo", exp: "La ligne référence un article." },
        { code: "        return $this->belongsTo(Article::class, 'article_id');", exp: "Permet <span class='tok'>$item->article->title</span> et <span class='tok'>->price</span>." }
      ]
    }
  ],
  quiz: [
    { q: "Comment marche le contrôle de stock à l'ajout ?", r: "On vérifie que (quantité déjà au panier + quantité demandée) ne dépasse pas le stock de l'article ; sinon on refuse." },
    { q: "Pourquoi $table = 'cart' dans le modèle ?", r: "Parce que la table s'appelle 'cart' (singulier). Par convention Eloquent chercherait 'carts'. Sans cette ligne, ça plante." },
    { q: "Que fait findOrFail() ?", r: "Cherche par id ; si rien n'est trouvé, renvoie automatiquement une erreur 404 (plus sûr que find())." },
    { q: "Pourquoi filtrer sur user_id ?", r: "Pour qu'un utilisateur ne touche que SON panier (sécurité), même s'il devine un id." }
  ]
},

/* ============================ 5. MODIFIER MON PROFIL ============================ */
{
  id: "profil",
  icone: "👤",
  titre: "Modifier mon profil",
  sousTitre: "ProfileController — le CRUD appliqué à son propre compte (Breeze)",
  resume: "L'utilisateur ouvre son profil, modifie nom/email (validés et uniques), enregistre ; il peut aussi supprimer son compte en confirmant par mot de passe.",
  etapes: [
    {
      couche: "Vue", dossier: "resources/views/profile/partials", fichier: "update-profile-information-form.blade.php", lang: "blade",
      role: "Le formulaire de modification du profil (nom + email), pré-rempli.",
      oral: "C'est un formulaire POST « transformé » en PATCH grâce à @method('patch'). Les valeurs actuelles sont pré-remplies via old('name', $user->name).",
      lignes: [
        { code: "<form method=\"post\" action=\"{{ route('profile.update') }}\" class=\"mt-6 space-y-6\">", exp: "Formulaire <b>POST</b> vers la route nommée <span class='tok'>profile.update</span>." },
        { code: "    @csrf", exp: "Jeton anti-CSRF obligatoire pour tout POST." },
        { code: "    @method('patch')", exp: "⭐ <b>Method spoofing</b> : le HTML ne sait envoyer que GET/POST. <span class='tok'>@method('patch')</span> ajoute un champ caché <span class='tok'>_method=PATCH</span> pour que Laravel route vers <span class='tok'>update()</span> (PATCH = modification partielle)." },
        { code: "    <x-input-label for=\"name\" :value=\"__('Name')\" />", exp: "L'étiquette du champ (composant Blade Breeze). <span class='tok'>__()</span> = texte traduisible." },
        { code: "    <x-text-input id=\"name\" name=\"name\" type=\"text\" :value=\"old('name', $user->name)\" required autofocus />", exp: "⭐ Champ nom. <span class='tok'>name=\"name\"</span> = clé envoyée. <span class='tok'>old('name', $user->name)</span> : valeur précédente après erreur, sinon le nom <b>actuel</b>." },
        { code: "    <x-input-error :messages=\"$errors->get('name')\" />", exp: "Affiche les erreurs de validation du champ <span class='tok'>name</span>." },
        { code: "    <x-text-input id=\"email\" name=\"email\" type=\"email\" :value=\"old('email', $user->email)\" required />", exp: "Champ email, pré-rempli de la même façon." },
        { code: "    <x-primary-button>{{ __('Save') }}</x-primary-button>", exp: "Le bouton qui <b>envoie</b> le formulaire (déclenche le PATCH)." }
      ]
    },
    {
      couche: "Route", dossier: "routes", fichier: "web.php", lang: "php",
      role: "Les 3 routes du profil : afficher, modifier, supprimer — toutes derrière auth.",
      oral: "Trois verbes pour trois actions : GET pour afficher, PATCH pour modifier, DELETE pour supprimer. Le même mini-CRUD que le prof demande, ici sur l'utilisateur lui-même.",
      lignes: [
        { code: "Route::middleware('auth')->group(function () {", exp: "Groupe réservé aux <b>connectés</b> (middleware <span class='tok'>auth</span>)." },
        { code: "    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');", exp: "<b>GET</b> → affiche le formulaire (<span class='tok'>edit()</span>)." },
        { code: "    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');", exp: "⭐ <b>PATCH</b> → enregistre les modifications (<span class='tok'>update()</span>)." },
        { code: "    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');", exp: "<b>DELETE</b> → supprime le compte (<span class='tok'>destroy()</span>)." },
        { code: "});", exp: "Fin du groupe." }
      ]
    },
    {
      couche: "Contrôleur", dossier: "app/Http/Controllers", fichier: "ProfileController.php", lang: "php",
      role: "edit() renvoie le formulaire ; update() valide, applique, et invalide l'email s'il change.",
      oral: "Je récupère l'utilisateur via $request->user(). Pour update, le ProfileUpdateRequest valide AVANT d'entrer. Je remplis l'objet ; si l'email change je remets email_verified_at à null pour forcer une revérification.",
      lignes: [
        { code: "public function edit(Request $request): View", exp: "Méthode d'affichage. <span class='tok'>: View</span> = type de retour (une vue). <span class='tok'>Request</span> injectée par Laravel." },
        { code: "    return view('profile.edit', [", exp: "Renvoie la vue <span class='tok'>profile/edit.blade.php</span>…" },
        { code: "        'user' => $request->user(),", exp: "…en lui passant l'utilisateur connecté. <span class='tok'>$request->user()</span> = raccourci vers <span class='tok'>Auth::user()</span>." },
        { code: "    ]);", exp: "Fin." },
        { code: "public function update(ProfileUpdateRequest $request): RedirectResponse", exp: "⭐ Le type <span class='tok'>ProfileUpdateRequest</span> déclenche <b>automatiquement</b> la validation avant le corps. Retour = une redirection." },
        { code: "    $request->user()->fill($request->validated());", exp: "⭐ <span class='tok'>fill()</span> remplit l'objet utilisateur avec les <b>seuls champs validés</b> (<span class='tok'>$request->validated()</span>). Rien n'est encore écrit en base." },
        { code: "    if ($request->user()->isDirty('email')) {", exp: "⭐ <span class='tok'>isDirty('email')</span> = « l'email a-t-il été modifié (différent de la base) ? »." },
        { code: "        $request->user()->email_verified_at = null;", exp: "Si oui, on <b>annule la vérification</b> → l'utilisateur devra reconfirmer sa nouvelle adresse." },
        { code: "    }", exp: "Fin du if." },
        { code: "    $request->user()->save();", exp: "⭐ <span class='tok'>save()</span> écrit réellement en base (UPDATE). Eloquent ne met à jour que les colonnes modifiées." },
        { code: "    return Redirect::route('profile.edit')->with('status', 'profile-updated');", exp: "Redirige vers le profil avec un statut (la vue affiche « Saved. »)." }
      ]
    },
    {
      couche: "Contrôleur", dossier: "app/Http/Requests", fichier: "ProfileUpdateRequest.php", lang: "php",
      role: "Classe de validation dédiée (FormRequest) : nom requis, email valide ET unique (sauf le sien).",
      oral: "Externaliser la validation dans un FormRequest allège le contrôleur. La règle Rule::unique()->ignore() garantit l'unicité de l'email sans bloquer l'utilisateur sur sa propre adresse.",
      lignes: [
        { code: "class ProfileUpdateRequest extends FormRequest", exp: "⭐ <b>FormRequest</b> : Laravel l'instancie, valide, puis l'injecte dans le contrôleur." },
        { code: "    public function rules(): array", exp: "Retourne le tableau des <b>règles de validation</b>." },
        { code: "        'name' => ['required', 'string', 'max:255'],", exp: "Nom <b>obligatoire</b>, chaîne, 255 car. max." },
        { code: "        'email' => [", exp: "Règles de l'email (sous forme de tableau)…" },
        { code: "            'required', 'string', 'lowercase', 'email', 'max:255',", exp: "Obligatoire, <b>minuscules</b>, format email, 255 max." },
        { code: "            Rule::unique(User::class)->ignore($this->user()->id),", exp: "⭐ <b>Unicité</b> en base SAUF pour la ligne de l'utilisateur courant (<span class='tok'>->ignore(id)</span>) — sinon il ne pourrait pas resauvegarder son propre email." },
        { code: "        ],", exp: "Fin des règles email." }
      ]
    },
    {
      couche: "Contrôleur", dossier: "app/Http/Controllers", fichier: "ProfileController.php", lang: "php",
      role: "destroy() : supprime le compte après re-confirmation du mot de passe, puis nettoie la session.",
      oral: "Action destructrice : je revérifie le mot de passe courant côté serveur (current_password), je déconnecte, je supprime l'utilisateur, puis j'invalide la session et régénère le jeton pour ne laisser aucune trace exploitable.",
      lignes: [
        { code: "public function destroy(Request $request): RedirectResponse", exp: "Méthode de <b>suppression</b> du compte." },
        { code: "    $request->validateWithBag('userDeletion', [", exp: "⭐ Valide en rangeant les erreurs dans un <b>sac dédié</b> <span class='tok'>userDeletion</span> (pour ne pas polluer les autres formulaires de la page)." },
        { code: "        'password' => ['required', 'current_password'],", exp: "⭐ <span class='tok'>current_password</span> = règle qui vérifie que le mot de passe saisi correspond bien à celui du <b>compte connecté</b>." },
        { code: "    ]);", exp: "Fin." },
        { code: "    $user = $request->user();", exp: "On garde une référence à l'utilisateur AVANT de le déconnecter." },
        { code: "    Auth::logout();", exp: "Déconnecte (ferme la session d'authentification)." },
        { code: "    $user->delete();", exp: "⭐ Supprime la ligne en base (DELETE)." },
        { code: "    $request->session()->invalidate();", exp: "Invalide toute la session." },
        { code: "    $request->session()->regenerateToken();", exp: "Régénère le jeton CSRF → sécurité." },
        { code: "    return Redirect::to('/');", exp: "Redirige vers la page d'accueil." }
      ]
    }
  ],
  quiz: [
    { q: "Pourquoi @method('patch') / @method('delete') ?", r: "Le HTML ne gère que GET et POST. @method ajoute un champ caché _method pour que Laravel comprenne PATCH/DELETE et route vers la bonne méthode." },
    { q: "À quoi sert Rule::unique()->ignore() ?", r: "À garantir un email unique tout en autorisant l'utilisateur à resauvegarder SON propre email (on ignore sa propre ligne)." },
    { q: "Que fait isDirty('email') ?", r: "Il indique si l'email a été modifié par rapport à la base. Si oui, on remet email_verified_at à null pour forcer une revérification." },
    { q: "Comment sécurises-tu la suppression de compte ?", r: "La règle current_password revérifie le mot de passe, puis logout + delete + session invalidée et token régénéré." },
    { q: "Différence entre fill() et save() ?", r: "fill() remplit l'objet en mémoire (sans écrire) ; save() écrit ensuite en base (UPDATE)." }
  ]
},

/* ========================= 6. GÉRER LES CATÉGORIES (CRUD) ========================= */
{
  id: "categories",
  icone: "🗂️",
  titre: "Gérer les catégories",
  sousTitre: "CategoryController — le CRUD complet (Read/Update/Delete) réservé admin",
  resume: "L'admin liste les catégories (avec leur nombre d'articles), en crée, en modifie, en supprime — chaque action protégée par un contrôle admin.",
  etapes: [
    {
      couche: "Contrôleur", dossier: "app/Http/Controllers", fichier: "CategoryController.php", lang: "php",
      role: "adminOnly() centralise le contrôle de rôle ; index() liste les catégories avec withCount.",
      oral: "Je factorise la sécurité dans une méthode privée adminOnly() appelée au début de chaque action. index() utilise withCount('articles') pour afficher le nombre d'articles sans charger chaque article.",
      lignes: [
        { code: "private function adminOnly()", exp: "⭐ Méthode <b>privée</b> réutilisée : la garde de sécurité, écrite une seule fois (principe <b>DRY</b>)." },
        { code: "    if (Auth::user()->role !== 'admin') {", exp: "Si l'utilisateur n'est <b>pas</b> admin…" },
        { code: "        return redirect('/dashboard')->with('info', 'Accès refusé.');", exp: "…on renvoie une <b>redirection</b> (qui sera exécutée par l'appelant)." },
        { code: "    return null;", exp: "Admin → on renvoie <span class='tok'>null</span> : « rien à bloquer, laisse passer »." },
        { code: "public function index()", exp: "Liste des catégories." },
        { code: "    if ($redirect = $this->adminOnly()) return $redirect;", exp: "⭐ Si <span class='tok'>adminOnly()</span> renvoie une redirection, on l'exécute et on <b>stoppe</b>. Sinon <span class='tok'>$redirect</span> = null → on continue." },
        { code: "    $categories = Category::withCount('articles')->get();", exp: "⭐ <span class='tok'>withCount('articles')</span> ajoute une colonne virtuelle <span class='tok'>articles_count</span> (un <b>COUNT SQL</b>) sans charger les articles → performant." },
        { code: "    return view('categories.index', compact('categories'));", exp: "Renvoie la vue. <span class='tok'>compact('categories')</span> = <span class='tok'>['categories' => $categories]</span>." }
      ]
    },
    {
      couche: "Vue", dossier: "resources/views/categories", fichier: "index.blade.php", lang: "blade",
      role: "Le tableau des catégories + liens Modifier / Supprimer.",
      oral: "Chaque ligne affiche articles_count (issu de withCount). Le lien Supprimer demande une confirmation JS avant de partir.",
      lignes: [
        { code: "@foreach($categories as $category)", exp: "Boucle sur chaque catégorie (passées par le contrôleur)." },
        { code: "    <td class=\"py-2\">{{ $category->name }}</td>", exp: "Affiche le nom (échappé par <span class='tok'>{{ }}</span> → anti-XSS)." },
        { code: "    <td class=\"py-2\">{{ $category->articles_count }}</td>", exp: "⭐ <span class='tok'>articles_count</span> vient de <span class='tok'>withCount('articles')</span> — pas une vraie colonne." },
        { code: "    <a href=\"{{ route('categories.edit', $category->id_category) }}\">Modifier</a>", exp: "Lien <b>GET</b> vers le formulaire d'édition, avec l'<span class='tok'>id_category</span> en paramètre." },
        { code: "    <a href=\"{{ route('categories.destroy', $category->id_category) }}\" onclick=\"return confirm('Supprimer cette catégorie ?')\">Supprimer</a>", exp: "⚠️ Lien <b>GET</b> de suppression + <span class='tok'>confirm()</span> JS. (À l'oral : idéalement un formulaire <b>DELETE</b> — je connais le point d'amélioration.)" },
        { code: "@endforeach", exp: "Fin de la boucle." }
      ]
    },
    {
      couche: "Contrôleur", dossier: "app/Http/Controllers", fichier: "CategoryController.php", lang: "php",
      role: "store() valide et insère (Create) ; update() valide (avec exists) et modifie (Update).",
      oral: "Même schéma partout : adminOnly(), validate(), puis create/update. Pour la modification, l'id transite par un champ caché et est validé par exists:categories,id_category.",
      lignes: [
        { code: "public function store(Request $request)", exp: "<b>Création</b> (POST)." },
        { code: "    if ($redirect = $this->adminOnly()) return $redirect;", exp: "Garde admin." },
        { code: "    $validated = $request->validate([", exp: "Lance la <b>validation</b>." },
        { code: "        'name' => 'required|string|max:255',", exp: "Nom obligatoire, chaîne, ≤ 255 caractères." },
        { code: "    ]);", exp: "Fin des règles." },
        { code: "    Category::create(['name' => $validated['name']]);", exp: "⭐ Insère la catégorie (INSERT) — mass assignment via <span class='tok'>$fillable</span>." },
        { code: "    return redirect()->route('categories.index')->with('status', 'Catégorie créée.');", exp: "Retour à la liste avec un message de succès." },
        { code: "public function update(Request $request)", exp: "<b>Modification</b> (Update)." },
        { code: "        'id'   => 'required|integer|exists:categories,id_category',", exp: "⭐ L'id doit <b>exister</b> en base (<span class='tok'>exists</span>) → on ne modifie pas une catégorie fantôme." },
        { code: "    $category = Category::findOrFail($validated['id']);", exp: "Récupère la catégorie (404 si introuvable)." },
        { code: "    $category->update(['name' => $validated['name']]);", exp: "⭐ <b>UPDATE</b> de la colonne <span class='tok'>name</span>." }
      ]
    },
    {
      couche: "Contrôleur", dossier: "app/Http/Controllers", fichier: "CategoryController.php", lang: "php",
      role: "destroy() : supprime une catégorie après contrôle admin (Delete).",
      oral: "findOrFail puis delete. Grâce au onDelete('cascade') de la migration, supprimer une catégorie supprime aussi ses articles liés.",
      lignes: [
        { code: "public function destroy($id)", exp: "Reçoit l'<span class='tok'>id</span> depuis l'URL." },
        { code: "    if ($redirect = $this->adminOnly()) return $redirect;", exp: "Garde admin." },
        { code: "    $category = Category::findOrFail($id);", exp: "Récupère la catégorie ou renvoie <b>404</b>." },
        { code: "    $category->delete();", exp: "⭐ <b>DELETE</b> en base. La clé étrangère <span class='tok'>onDelete('cascade')</span> supprime les articles liés." },
        { code: "    return redirect()->route('categories.index')->with('status', 'Catégorie supprimée.');", exp: "Retour à la liste." }
      ]
    },
    {
      couche: "Modèle", dossier: "app/Models", fichier: "Category.php", lang: "php",
      role: "La table 'categories' ; relation hasMany vers les articles.",
      oral: "Une catégorie a plusieurs articles (hasMany). C'est ce lien que withCount('articles') et $category->articles exploitent.",
      lignes: [
        { code: "    protected $primaryKey = 'id_category';", exp: "Clé primaire personnalisée." },
        { code: "    protected $fillable = ['name'];", exp: "Seule colonne remplissable en masse." },
        { code: "    public function articles(): HasMany", exp: "⭐ Une catégorie <b>a plusieurs</b> articles." },
        { code: "        return $this->hasMany(Article::class, 'category_id');", exp: "Lien via <span class='tok'>articles.category_id</span>. Permet <span class='tok'>$category->articles</span> et <span class='tok'>withCount('articles')</span>." }
      ]
    }
  ],
  quiz: [
    { q: "À quoi sert withCount('articles') ?", r: "À récupérer le nombre d'articles par catégorie (colonne articles_count) via un COUNT SQL, sans charger les articles eux-mêmes (performant)." },
    { q: "Comment est centralisé le contrôle admin ?", r: "Une méthode privée adminOnly() appelée en tête de chaque action : if ($r = $this->adminOnly()) return $r;." },
    { q: "Que se passe-t-il en supprimant une catégorie qui a des articles ?", r: "La clé étrangère onDelete('cascade') supprime automatiquement les articles liés." },
    { q: "Comment l'id arrive-t-il dans update() ?", r: "Par un champ caché <input name='id'> dans le formulaire, validé par exists:categories,id_category." },
    { q: "Quelle faiblesse vois-tu dans la suppression ?", r: "Elle passe par un lien GET ; idéalement un formulaire POST/DELETE avec @method('delete'). Je sais comment la corriger." }
  ]
},

/* ===================== 7. ADMINISTRER LES UTILISATEURS (RÔLES) ===================== */
{
  id: "admin-users",
  icone: "🛡️",
  titre: "Administrer les utilisateurs",
  sousTitre: "UserController — gestion des rôles (bonus du prof) + garde-fous",
  resume: "L'admin liste les utilisateurs, change leur rôle (user/admin) ou les supprime — avec des garde-fous pour ne pas se rétrograder ni se supprimer soi-même.",
  etapes: [
    {
      couche: "Migration", dossier: "database/migrations", fichier: "..._add_role_user_table.php", lang: "php",
      role: "Ajoute la colonne 'role' à la table users (défaut 'user').",
      oral: "La gestion de rôles repose sur une simple colonne role avec valeur par défaut 'user'. Tout nouvel inscrit est donc 'user' ; on promeut en 'admin' depuis l'interface.",
      lignes: [
        { code: "Schema::table('users', function (Blueprint $table) {", exp: "⭐ <span class='tok'>table()</span> (et non <span class='tok'>create()</span>) = on <b>MODIFIE</b> une table existante." },
        { code: "    $table->string('role')->default('user');", exp: "⭐ Ajoute une colonne texte <span class='tok'>role</span> avec la valeur <b>par défaut</b> <span class='tok'>'user'</span>." },
        { code: "});", exp: "Fin." }
      ]
    },
    {
      couche: "Vue", dossier: "resources/views/admin/users", fichier: "index.blade.php", lang: "blade",
      role: "Tableau des utilisateurs avec formulaire de rôle et bouton supprimer.",
      oral: "Chaque ligne a son mini-formulaire PATCH pour le rôle. La colonne Commandes vient de withCount('orders'). On masque la suppression de soi-même.",
      lignes: [
        { code: "@foreach($users as $user)", exp: "Boucle sur les utilisateurs." },
        { code: "    <form method=\"POST\" action=\"{{ route('admin.users.updateRole', $user->id) }}\">", exp: "Formulaire de changement de rôle (POST, avec spoof PATCH ci-dessous)." },
        { code: "        @csrf", exp: "Jeton anti-CSRF." },
        { code: "        @method('PATCH')", exp: "Spoofe le verbe <b>PATCH</b> (modification)." },
        { code: "        <select name=\"role\">", exp: "Liste déroulante du rôle (envoyée sous <span class='tok'>role</span>)." },
        { code: "            <option value=\"user\" @selected($user->role === 'user')>Utilisateur</option>", exp: "⭐ <span class='tok'>@selected(cond)</span> ajoute l'attribut <span class='tok'>selected</span> si la condition est vraie → affiche le rôle courant." },
        { code: "            <option value=\"admin\" @selected($user->role === 'admin')>Admin</option>", exp: "Idem pour le rôle admin." },
        { code: "    <td class=\"py-2\">{{ $user->orders_count }}</td>", exp: "⭐ Nombre de commandes (<span class='tok'>withCount('orders')</span>)." },
        { code: "    @if($user->id !== auth()->id())", exp: "⭐ On n'affiche le bouton <b>Supprimer</b> QUE si ce n'est pas soi-même." },
        { code: "        <form method=\"POST\" action=\"{{ route('admin.users.destroy', $user->id) }}\">", exp: "Formulaire de suppression (POST + DELETE)." },
        { code: "            @method('DELETE')", exp: "Spoofe le verbe <b>DELETE</b>." },
        { code: "    @else", exp: "Sinon (c'est moi)…" },
        { code: "        <span class=\"text-gray-400 text-sm\">Vous</span>", exp: "…on affiche « Vous » au lieu du bouton." }
      ]
    },
    {
      couche: "Route", dossier: "routes", fichier: "web.php", lang: "php",
      role: "Les routes admin des utilisateurs (préfixe /admin/users).",
      oral: "GET pour lister, PATCH pour le rôle, DELETE pour supprimer. Le préfixe /admin/users les regroupe logiquement.",
      lignes: [
        { code: "    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');", exp: "Liste des utilisateurs (GET)." },
        { code: "    Route::patch('/admin/users/{id}/role', [UserController::class, 'updateRole'])->name('admin.users.updateRole');", exp: "⭐ Modifier le rôle (PATCH). <span class='tok'>{id}</span> = l'utilisateur ciblé." },
        { code: "    Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');", exp: "Supprimer un utilisateur (DELETE)." }
      ]
    },
    {
      couche: "Contrôleur", dossier: "app/Http/Controllers", fichier: "UserController.php", lang: "php",
      role: "index / updateRole / destroy, avec garde admin et garde-fous anti-auto-sabotage.",
      oral: "Garde admin partout. Deux garde-fous essentiels : je ne peux ni changer MON rôle, ni supprimer MON compte depuis l'admin — sinon un admin pourrait se verrouiller dehors.",
      lignes: [
        { code: "    $users = User::withCount('orders')->orderBy('name')->get();", exp: "Liste <b>triée par nom</b> + compteur de commandes (<span class='tok'>orders_count</span>)." },
        { code: "public function updateRole(int $id)", exp: "Changement de rôle d'un utilisateur." },
        { code: "    request()->validate(['role' => 'required|in:user,admin']);", exp: "⭐ <span class='tok'>in:user,admin</span> = le rôle ne peut être QUE <span class='tok'>'user'</span> ou <span class='tok'>'admin'</span> (liste <b>fermée</b>)." },
        { code: "    $user = User::findOrFail($id);", exp: "L'utilisateur ciblé (404 sinon)." },
        { code: "    if ($user->id === Auth::id()) {", exp: "⭐ <b>Garde-fou</b> : est-ce moi-même ?" },
        { code: "        return back()->with('info', 'Vous ne pouvez pas modifier votre propre rôle.');", exp: "On refuse → évite de se rétrograder par erreur." },
        { code: "    $user->role = request()->input('role');", exp: "Affecte le nouveau rôle." },
        { code: "    $user->save();", exp: "UPDATE en base." },
        { code: "public function destroy(int $id)", exp: "Suppression d'un utilisateur." },
        { code: "    if ($user->id === Auth::id()) {", exp: "⭐ Même garde-fou : <b>pas d'auto-suppression</b>." },
        { code: "    $user->delete();", exp: "DELETE en base." }
      ]
    },
    {
      couche: "Modèle", dossier: "app/Models", fichier: "User.php", lang: "php",
      role: "L'utilisateur : relation hasMany vers ses commandes ; rôle lu via $user->role.",
      oral: "User hasMany Order : c'est ce lien que withCount('orders') et $user->orders exploitent. La colonne role se lit directement.",
      lignes: [
        { code: "class User extends Authenticatable", exp: "Hérite d'<span class='tok'>Authenticatable</span> (gère l'auth, le hachage du mot de passe, la session)." },
        { code: "    public function orders()", exp: "⭐ Relation : un utilisateur <b>a plusieurs</b> commandes." },
        { code: "        return $this->hasMany(\\App\\Models\\Order::class);", exp: "Lien via <span class='tok'>orders.user_id</span> (convention). Permet <span class='tok'>$user->orders</span> et <span class='tok'>withCount('orders')</span>." }
      ]
    }
  ],
  quiz: [
    { q: "Comment gères-tu les rôles ?", r: "Une colonne role (défaut 'user') sur la table users, ajoutée par migration. On teste Auth::user()->role === 'admin' pour autoriser." },
    { q: "Pourquoi in:user,admin dans la validation ?", r: "Pour fermer la liste des valeurs possibles : impossible d'injecter un rôle inventé comme 'superadmin'." },
    { q: "Quels garde-fous as-tu mis ?", r: "On ne peut ni modifier son propre rôle ni se supprimer soi-même (comparaison $user->id === Auth::id()), pour éviter qu'un admin se verrouille dehors." },
    { q: "D'où vient orders_count ?", r: "De withCount('orders'), qui ajoute un COUNT SQL des commandes liées via la relation hasMany." },
    { q: "Masquer le bouton Supprimer pour soi suffit-il ?", r: "Non, c'est cosmétique. La vraie protection est le test côté contrôleur ($user->id === Auth::id())." }
  ]
},

/* ======================== 8. PAYER AVEC STRIPE (CASHIER) ======================== */
{
  id: "paiement",
  icone: "💳",
  titre: "Payer avec Stripe",
  sousTitre: "PaymentController + Laravel Cashier — le package bonus de l'écosystème",
  resume: "Au paiement, on construit les lignes Stripe depuis le panier, on redirige vers la page Stripe ; au retour « success », on VÉRIFIE le paiement puis on crée la commande et décrémente le stock.",
  etapes: [
    {
      couche: "Modèle", dossier: "app/Models", fichier: "User.php", lang: "php",
      role: "Le trait Billable de Cashier branche Stripe sur l'utilisateur.",
      oral: "Cashier est un package officiel de l'écosystème Laravel. Le trait Billable ajoute au modèle User des méthodes comme checkout() et la gestion du client Stripe — sans réécrire l'intégration.",
      lignes: [
        { code: "use Laravel\\Cashier\\Billable;", exp: "⭐ Importe le <b>trait</b> du package <b>Cashier</b>." },
        { code: "class User extends Authenticatable", exp: "La classe utilisateur." },
        { code: "    use HasFactory, Notifiable, Billable;", exp: "⭐ On « branche » <span class='tok'>Billable</span> : l'utilisateur sait maintenant dialoguer avec Stripe (<span class='tok'>$user->checkout(...)</span>)." }
      ]
    },
    {
      couche: "Route", dossier: "routes", fichier: "web.php", lang: "php",
      role: "Les routes du tunnel de paiement.",
      oral: "checkout lance le paiement (POST) ; success et cancel sont les pages de retour depuis Stripe (GET).",
      lignes: [
        { code: "    Route::post('/payment/checkout', [PaymentController::class, 'checkout'])->name('payment.checkout');", exp: "⭐ Démarre le paiement (POST)." },
        { code: "    Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');", exp: "Page de retour si paiement OK (Stripe nous y renvoie)." },
        { code: "    Route::get('/payment/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');", exp: "Page de retour si annulation." }
      ]
    },
    {
      couche: "Contrôleur", dossier: "app/Http/Controllers", fichier: "PaymentController.php", lang: "php",
      role: "checkout() : construit les lignes Stripe depuis le panier et redirige vers le paiement.",
      oral: "Je revérifie le panier et le stock, je transforme chaque ligne en line_item Stripe (prix en centimes), puis $user->checkout() (fourni par Cashier) crée la session Stripe et me renvoie son URL.",
      lignes: [
        { code: "    $items = Cart::with('article')->where('user_id', $user->id)->get();", exp: "Le panier de l'utilisateur (<b>eager loading</b> des articles)." },
        { code: "    $lineItems = $items->map(fn($item) => [", exp: "⭐ <span class='tok'>map()</span> transforme chaque ligne du panier en ligne Stripe. <span class='tok'>fn(...) =></span> = fonction fléchée." },
        { code: "        'price_data' => [", exp: "Les données de prix de cette ligne." },
        { code: "            'currency' => 'eur',", exp: "Devise : euro." },
        { code: "            'product_data' => ['name' => $item->article->title],", exp: "Nom du produit affiché sur Stripe." },
        { code: "            'unit_amount' => (int) round($item->article->price * 100),", exp: "⭐ Stripe attend des <b>CENTIMES entiers</b> : prix × 100, arrondi, casté en <span class='tok'>int</span>." },
        { code: "        'quantity' => $item->quantity,", exp: "Quantité de cette ligne." },
        { code: "    ])->values()->all();", exp: "<span class='tok'>values()->all()</span> = retransforme la collection en simple tableau indexé (attendu par Stripe)." },
        { code: "    $checkout = $user->checkout([], [", exp: "⭐ Méthode de <b>Cashier</b> (trait Billable) : crée une <b>session de paiement</b> Stripe." },
        { code: "        'line_items' => $lineItems,", exp: "Les lignes construites au-dessus." },
        { code: "        'mode' => 'payment',", exp: "Paiement <b>unique</b> (pas un abonnement)." },
        { code: "        'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',", exp: "⭐ URL de retour si succès ; Stripe remplace <span class='tok'>{CHECKOUT_SESSION_ID}</span> par l'id réel." },
        { code: "        'cancel_url' => route('payment.cancel'),", exp: "URL de retour si annulation." },
        { code: "    return redirect($checkout->url);", exp: "⭐ Redirige le navigateur vers la page de paiement <b>hébergée par Stripe</b>." }
      ]
    },
    {
      couche: "Contrôleur", dossier: "app/Http/Controllers", fichier: "PaymentController.php", lang: "php",
      role: "success() : au retour de Stripe, on VÉRIFIE le paiement avant de créer la commande.",
      oral: "Point clé de sécurité : je ne crois pas le navigateur. Je récupère la session Stripe par son id et je vérifie payment_status === 'paid' AVANT de créer la commande et décrémenter le stock.",
      lignes: [
        { code: "    $sessionId = request()->query('session_id');", exp: "Récupère l'id de session passé dans l'URL de retour." },
        { code: "    if (!$sessionId) {", exp: "Pas d'id → session invalide, on sort." },
        { code: "    $session = Cashier::stripe()->checkout->sessions->retrieve($sessionId);", exp: "⭐ Interroge l'<b>API Stripe</b> pour récupérer la vraie session de paiement." },
        { code: "    if ($session->payment_status !== 'paid') {", exp: "⭐⭐ On vérifie <b>côté serveur</b> que le paiement est bien « paid ». On ne fait PAS confiance à l'URL." },
        { code: "        return redirect()->route('cart.index')->with('info', 'Paiement non confirmé.');", exp: "Sinon on refuse." },
        { code: "    $order = Order::create([...]);", exp: "Paiement OK → on crée la commande (comme dans <span class='tok'>store()</span>)." },
        { code: "    $item->article->quantity -= $item->quantity; $item->article->save();", exp: "On <b>décrémente le stock</b> de chaque article." },
        { code: "    Cart::where('user_id', $user->id)->delete();", exp: "On <b>vide le panier</b>." }
      ]
    }
  ],
  quiz: [
    { q: "C'est quoi Laravel Cashier ?", r: "Un package officiel de l'écosystème Laravel qui simplifie l'intégration de Stripe (paiements, abonnements). On l'active via le trait Billable sur User." },
    { q: "Pourquoi multiplier le prix par 100 ?", r: "Stripe raisonne en centimes (plus petite unité). 12,50 € → 1250. On arrondit puis on caste en entier." },
    { q: "Pourquoi vérifier payment_status dans success() ?", r: "Parce que l'URL de retour peut être falsifiée/rejouée. On interroge l'API Stripe pour confirmer 'paid' avant de valider la commande — la confiance est côté serveur." },
    { q: "Que fait $user->checkout() ?", r: "C'est une méthode du trait Billable : elle crée une session Stripe Checkout avec nos line_items et renvoie un objet contenant l'URL de paiement." },
    { q: "Quel est le risque le jour de la démo ?", r: "Cashier exige les clés Stripe + internet. Plan B : la route orders.store crée la commande sans paiement." }
  ]
},

/* ============================== 9. S'INSCRIRE ============================== */
{
  id: "inscription",
  icone: "✍️",
  titre: "S'inscrire",
  sousTitre: "RegisteredUserController — création de compte (Breeze) + hachage",
  resume: "Le visiteur remplit le formulaire ; on valide (email unique, mot de passe confirmé), on crée l'utilisateur avec mot de passe haché, on déclenche l'événement Registered et on le connecte.",
  etapes: [
    {
      couche: "Route", dossier: "routes", fichier: "auth.php", lang: "php",
      role: "Routes d'inscription (réservées aux invités).",
      oral: "GET register affiche le formulaire, POST register le traite. Sous middleware guest : inutile de s'inscrire si on est déjà connecté.",
      lignes: [
        { code: "Route::middleware('guest')->group(function () {", exp: "<b>guest</b> = réservé aux <b>NON connectés</b> (l'inverse de auth)." },
        { code: "    Route::get('register', [RegisteredUserController::class, 'create']);", exp: "Affiche le formulaire (<span class='tok'>create()</span>)." },
        { code: "    Route::post('register', [RegisteredUserController::class, 'store']);", exp: "Traite l'inscription (<span class='tok'>store()</span>)." }
      ]
    },
    {
      couche: "Vue", dossier: "resources/views/auth", fichier: "register.blade.php", lang: "blade",
      role: "Le formulaire d'inscription (nom, email, mot de passe + confirmation).",
      oral: "Le champ password_confirmation est la clé attendue par la règle 'confirmed' : Laravel vérifie automatiquement password === password_confirmation.",
      lignes: [
        { code: "<form method=\"POST\" action=\"{{ route('register') }}\">", exp: "Formulaire <b>POST</b> vers la route <span class='tok'>register</span>." },
        { code: "    @csrf", exp: "Jeton anti-CSRF." },
        { code: "    <x-text-input id=\"name\" name=\"name\" :value=\"old('name')\" required autofocus />", exp: "Champ nom. <span class='tok'>old('name')</span> le re-remplit après erreur." },
        { code: "    <x-text-input id=\"email\" type=\"email\" name=\"email\" :value=\"old('email')\" required />", exp: "Champ email." },
        { code: "    <x-text-input id=\"password\" type=\"password\" name=\"password\" required />", exp: "Mot de passe (masqué)." },
        { code: "    <x-text-input id=\"password_confirmation\" type=\"password\" name=\"password_confirmation\" required />", exp: "⭐ Confirmation : son <span class='tok'>name</span> DOIT être <span class='tok'>password_confirmation</span> pour que la règle <span class='tok'>confirmed</span> fonctionne." },
        { code: "    <x-primary-button>{{ __('Register') }}</x-primary-button>", exp: "Le bouton d'envoi." }
      ]
    },
    {
      couche: "Contrôleur", dossier: "app/Http/Controllers/Auth", fichier: "RegisteredUserController.php", lang: "php",
      role: "store() : valide, crée l'utilisateur (mot de passe haché), déclenche l'événement, connecte.",
      oral: "Je valide (email unique, password 'confirmed' + règles de robustesse). Je hache le mot de passe avec Hash::make — jamais en clair. Je déclenche l'événement Registered (email de vérification) puis je connecte le nouvel inscrit.",
      lignes: [
        { code: "    $request->validate([", exp: "Lance la <b>validation</b>." },
        { code: "        'name' => ['required', 'string', 'max:255'],", exp: "Nom obligatoire." },
        { code: "        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],", exp: "⭐ Email obligatoire, minuscules, valide, et <b>UNIQUE</b> en base (<span class='tok'>unique:users</span>)." },
        { code: "        'password' => ['required', 'confirmed', Rules\\Password::defaults()],", exp: "⭐ <span class='tok'>confirmed</span> = doit correspondre à <span class='tok'>password_confirmation</span>. <span class='tok'>Password::defaults()</span> = règles de robustesse (longueur, etc.)." },
        { code: "    ]);", exp: "Fin des règles." },
        { code: "    $user = User::create([", exp: "Crée l'utilisateur." },
        { code: "        'name' => $request->name,", exp: "Le nom saisi." },
        { code: "        'email' => $request->email,", exp: "L'email saisi." },
        { code: "        'password' => Hash::make($request->password),", exp: "⭐⭐ <span class='tok'>Hash::make</span> = <b>hachage bcrypt</b>. On ne stocke JAMAIS le mot de passe en clair." },
        { code: "    ]);", exp: "Fin." },
        { code: "    event(new Registered($user));", exp: "⭐ Déclenche l'<b>événement</b> <span class='tok'>Registered</span> → un listener envoie l'email de vérification (découplage)." },
        { code: "    Auth::login($user);", exp: "⭐ Connecte automatiquement le nouvel inscrit (ouvre la session)." },
        { code: "    return redirect(route('dashboard', absolute: false));", exp: "Redirige vers le tableau de bord." }
      ]
    }
  ],
  quiz: [
    { q: "Comment marche la confirmation du mot de passe ?", r: "La règle 'confirmed' compare le champ password au champ password_confirmation (convention de nommage). S'ils diffèrent → erreur." },
    { q: "Le mot de passe est-il stocké en clair ?", r: "Jamais. Hash::make() le hache en bcrypt ; le cast 'hashed' du modèle renforce. À la connexion, Auth::attempt compare les empreintes." },
    { q: "À quoi sert event(new Registered($user)) ?", r: "À déclencher l'événement d'inscription, dont un listener envoie l'email de vérification — un découplage via le système d'événements." },
    { q: "Pourquoi le middleware guest sur register ?", r: "Parce qu'un utilisateur déjà connecté n'a pas à s'inscrire ; guest est l'inverse de auth." },
    { q: "Que fait Rules\\Password::defaults() ?", r: "Applique les règles de robustesse par défaut du mot de passe (longueur minimale, etc.), configurables au niveau de l'application." }
  ]
}

];

/* ===================== GLOSSAIRE GLOBAL (termes à connaître) ===================== */
const GLOSSAIRE = [
  { term: "MVC", def: "Modèle-Vue-Contrôleur : l'organisation du code. La Vue affiche, la Route oriente, le Contrôleur décide, le Modèle parle à la base." },
  { term: "Route", def: "Une URL + un verbe HTTP associés à une action (méthode de contrôleur). Définies dans routes/web.php." },
  { term: "Middleware", def: "Un filtre exécuté AVANT le contrôleur. 'auth' bloque les non-connectés ; 'guest' fait l'inverse." },
  { term: "Contrôleur", def: "La classe qui reçoit la requête, valide, appelle le modèle et renvoie une vue. Dans app/Http/Controllers." },
  { term: "Modèle (Eloquent)", def: "Une classe = une table. Permet de manipuler la base en objets PHP (Article::all()) sans écrire de SQL." },
  { term: "Eloquent ORM", def: "L'ORM de Laravel : il traduit le PHP objet en requêtes SQL automatiquement." },
  { term: "Migration", def: "La structure d'une table décrite en PHP, versionnée. 'php artisan migrate' crée les tables." },
  { term: "belongsTo / hasMany", def: "Les deux côtés d'une relation 1-N. Le côté qui porte la clé étrangère utilise belongsTo ; le parent utilise hasMany." },
  { term: "Clé étrangère (FK)", def: "Une colonne qui pointe vers la clé primaire d'une autre table (ex: articles.category_id → categories.id_category)." },
  { term: "Mass assignment", def: "Remplir plusieurs colonnes d'un coup avec create([...]). Encadré par $fillable (liste blanche des colonnes autorisées)." },
  { term: "$fillable", def: "La liste des colonnes qu'on autorise à remplir en masse. Sécurité : on ne peut pas injecter un champ non prévu." },
  { term: "Validation", def: "$request->validate([...]) vérifie les données. Si une règle échoue, on revient au formulaire avec les erreurs." },
  { term: "exists:table,colonne", def: "Règle de validation qui vérifie que la valeur existe vraiment dans une autre table → cohérence référentielle." },
  { term: "CSRF / @csrf", def: "Protection contre la falsification de requête. @csrf met un jeton caché dans chaque formulaire, vérifié à chaque POST." },
  { term: "XSS / {{ }}", def: "Les doubles accolades échappent le HTML → empêchent l'injection de scripts (XSS)." },
  { term: "Eager loading (with)", def: "Charger les relations en une requête (Cart::with('article')) pour éviter le problème des N+1 requêtes." },
  { term: "Message flash", def: "Un message stocké en session pour la requête suivante (->with('status', '...')). Affiché une seule fois." },
  { term: "old()", def: "Re-remplit un champ de formulaire avec la valeur précédente après une erreur de validation." },
  { term: "Route nommée", def: "->name('cart.index') ; on génère l'URL via route('cart.index'). Si l'URL change, le nom reste." },
  { term: "Breeze", def: "Le starter kit d'authentification de Laravel : inscription, connexion, reset mot de passe, vérification email." },
  { term: "Rate limiting", def: "Limiter le nombre de tentatives (ex: 5 essais de login) pour contrer la force brute." },
  { term: "Prix figé", def: "Copier le prix au moment de l'achat dans order_items, pour garder l'historique même si le prix change." },
  { term: "decimal vs float", def: "On utilise decimal pour l'argent : précision exacte, pas d'erreur d'arrondi (contrairement à float)." },
  { term: "FormRequest", def: "Une classe de validation dédiée (app/Http/Requests). Laravel exécute authorize() + rules() AVANT le contrôleur, qu'on allège ainsi. Ex : ProfileUpdateRequest, LoginRequest." },
  { term: "@method (spoofing)", def: "Le HTML ne sait envoyer que GET/POST. @method('patch'|'delete') ajoute un champ caché _method pour simuler les verbes PATCH/PUT/DELETE." },
  { term: "withCount", def: "Ajoute un compteur de relation (ex : articles_count, orders_count) via un COUNT SQL, sans charger les éléments liés. Plus léger que tout charger." },
  { term: "Hachage / Hash::make", def: "Transformer le mot de passe en empreinte irréversible (bcrypt). On ne stocke jamais le mot de passe en clair ; à la connexion on compare les empreintes." },
  { term: "current_password", def: "Règle de validation qui vérifie qu'un mot de passe saisi correspond à celui de l'utilisateur connecté (re-confirmation d'identité, ex : suppression de compte)." },
  { term: "confirmed", def: "Règle de validation : le champ X doit être identique au champ X_confirmation (ex : password / password_confirmation)." },
  { term: "Rule::unique()->ignore()", def: "Garantit l'unicité d'une valeur en base ; ->ignore(id) exclut une ligne (la sienne) pour permettre la resauvegarde de son propre email." },
  { term: "Cashier / Billable", def: "Package officiel de l'écosystème Laravel pour Stripe. Le trait Billable ajoute checkout() et la gestion client/abonnements au modèle User." },
  { term: "Stripe Checkout", def: "Page de paiement hébergée par Stripe. On y redirige le client, et on vérifie le paiement au retour (payment_status === 'paid') côté serveur." },
  { term: "Événement (event/listener)", def: "Mécanisme de découplage : event(new X) déclenche des listeners (ex : Registered → envoi de l'email de vérification)." },
  { term: "isDirty()", def: "Indique si un attribut a été modifié par rapport à la valeur en base (avant save()). Ex : si l'email change, on annule sa vérification." },
  { term: "Bag d'erreurs", def: "Un « sac » nommé pour ranger des erreurs de validation séparément (ex : userDeletion), afin de ne pas mélanger plusieurs formulaires d'une même page." },
  { term: "@selected", def: "Directive Blade qui ajoute l'attribut HTML selected à une <option> si la condition est vraie (ré-affiche le choix courant)." }
];
