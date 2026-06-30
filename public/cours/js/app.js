/* =====================================================================
   MOTEUR DU COURS INTERACTIF — vanilla JS, zéro dépendance.
   ===================================================================== */

const LAYER = {
  "Vue":         { slug: "vue" },
  "Route":       { slug: "route" },
  "Contrôleur":  { slug: "ctrl" },
  "Modèle":      { slug: "model" },
  "Migration":   { slug: "mig" },
  "BDD":         { slug: "bdd" }
};

const state = { f: 0, step: 0, tab: "code" };

/* ---------- Coloration syntaxique (PHP / Blade) ---------- */
function esc(s) {
  return s.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
}
function highlight(code) {
  code = esc(code);
  const re = /(\{\{--[\s\S]*?--\}\}|\/\/[^\n]*|\/\*[\s\S]*?\*\/)|('(?:\\.|[^'\\])*'|"(?:\\.|[^"\\])*")|(\{\{.*?\}\}|@[a-zA-Z]+)|(\$[a-zA-Z_]\w*)|(-&gt;|::)|(\b(?:public|private|protected|function|return|if|else|elseif|foreach|for|as|new|class|extends|implements|use|namespace|throw|echo|true|false|null|void|bool|int|string|array|static|this|abstract)\b)/g;
  return code.replace(re, (m, com, str, blade, varr, op, kw) => {
    if (com)   return `<span class="t-com">${com}</span>`;
    if (str)   return `<span class="t-str">${str}</span>`;
    if (blade) return `<span class="t-blade">${blade}</span>`;
    if (varr)  return `<span class="t-var">${varr}</span>`;
    if (op)    return `<span class="t-op">${m}</span>`;
    if (kw)    return `<span class="t-kw">${kw}</span>`;
    return m;
  });
}

/* ---------- Les pastilles de fonctionnalités ---------- */
function renderPills() {
  const box = document.getElementById("featurePills");
  box.innerHTML = FEATURES.map((f, i) =>
    `<button class="pill ${i === state.f ? "active" : ""}" data-f="${i}">
       <span>${f.icone}</span> ${f.titre}
     </button>`
  ).join("");
  box.querySelectorAll(".pill").forEach(p =>
    p.onclick = () => { state.f = +p.dataset.f; state.step = 0; renderAll(); }
  );
}

/* ---------- Arborescence ---------- */
function buildTree(steps) {
  const root = { dirs: {}, files: [] };
  steps.forEach((s, i) => {
    const parts = s.dossier.split("/");
    let node = root;
    parts.forEach(p => {
      node.dirs[p] = node.dirs[p] || { dirs: {}, files: [] };
      node = node.dirs[p];
    });
    node.files.push({ label: s.fichier, i, layer: s.couche });
  });
  return root;
}
function renderTreeNode(node, depth) {
  let html = "";
  for (const name in node.dirs) {
    html += `<div class="folder" style="padding-left:${depth * 12}px">📁 ${name}</div>`;
    html += renderTreeNode(node.dirs[name], depth + 1);
  }
  node.files.forEach(f => {
    const active = f.i === state.step ? "active" : "";
    html += `<span class="file ${active}" data-i="${f.i}" style="padding-left:${depth * 12 + 6}px"
                 title="Étape ${f.i + 1} — ${f.layer}">📄 ${f.label}</span>`;
  });
  return html;
}
function renderTree() {
  const steps = FEATURES[state.f].etapes;
  const tree = document.getElementById("tree");
  tree.innerHTML = `<div class="folder">📦 projet-laravel-shop</div>` +
                   renderTreeNode(buildTree(steps), 1);
  tree.querySelectorAll(".file").forEach(el =>
    el.onclick = () => { state.step = +el.dataset.i; state.tab = "code"; renderAll(); }
  );
}

/* ---------- Onglet CODE : code + explication synchronisés ---------- */
function renderStep() {
  const f = FEATURES[state.f];
  const step = f.etapes[state.step];
  const slug = (LAYER[step.couche] || {}).slug || "route";

  const badge = document.getElementById("stepLayer");
  badge.textContent = step.couche;
  badge.className = "layer-badge lc-" + slug;

  document.getElementById("stepPath").textContent = step.dossier + "/" + step.fichier;
  document.getElementById("stepRole").textContent = step.role;
  document.getElementById("stepCount").textContent = `Étape ${state.step + 1} / ${f.etapes.length}`;
  document.getElementById("prevBtn").disabled = state.step === 0;
  document.getElementById("nextBtn").disabled = state.step === f.etapes.length - 1;

  // code
  document.getElementById("code").innerHTML = step.lignes.map((l, i) =>
    `<div class="cline" data-i="${i}"><span class="ln">${i + 1}</span><code>${highlight(l.code)}</code></div>`
  ).join("");

  // explications
  document.getElementById("exp").innerHTML = step.lignes.map((l, i) =>
    l.exp ? `<div class="cexp" data-i="${i}"><b>Ligne ${i + 1}</b> — ${l.exp}</div>` : ""
  ).join("");

  // à dire à l'oral
  document.getElementById("oral").innerHTML = step.oral
    ? `<b>💬 À dire à l'oral —</b> ${step.oral}` : "";

  // synchronisation survol code ↔ explication
  const sync = (on) => (e) => {
    const i = e.currentTarget.dataset.i;
    document.querySelectorAll(`.cline[data-i="${i}"], .cexp[data-i="${i}"]`)
      .forEach(el => el.classList.toggle("active", on));
  };
  document.querySelectorAll(".cline, .cexp").forEach(el => {
    el.addEventListener("mouseenter", sync(true));
    el.addEventListener("mouseleave", sync(false));
  });

  applySearch();
}

/* ---------- Onglet SCHÉMA MVC ---------- */
function renderSchema() {
  const f = FEATURES[state.f];
  document.getElementById("schemaTitle").textContent = `🗺️ ${f.titre} — le parcours MVC`;
  let html = `<p class="hint">${f.resume}</p><div class="flow">`;
  f.etapes.forEach((s, i) => {
    const slug = (LAYER[s.couche] || {}).slug || "route";
    html += `
      <div class="flow-card bc-${slug}">
        <div class="fc-head">
          <span class="fc-num lc-${slug}">${i + 1}</span>
          <span class="fc-file">${s.couche} · ${s.fichier}</span>
        </div>
        <div class="fc-role">${s.role}</div>
      </div>`;
    if (i < f.etapes.length - 1) html += `<div class="flow-arrow">▼</div>`;
  });
  html += `</div>`;
  html += `<div class="legend">
    <span><i class="dot lc-vue"></i> Vue</span>
    <span><i class="dot lc-route"></i> Route</span>
    <span><i class="dot lc-ctrl"></i> Contrôleur</span>
    <span><i class="dot lc-model"></i> Modèle</span>
    <span><i class="dot lc-mig"></i> Migration</span>
    <span><i class="dot lc-bdd"></i> Base de données</span>
  </div>`;
  html += `<div class="mantra">🧠 <b>Mantra MVC</b> : « La Vue demande, la Route oriente, le Contrôleur décide, le Modèle parle à la Base. »</div>`;
  document.getElementById("schema").innerHTML = html;
}

/* ---------- Onglet QUIZ ---------- */
function renderQuiz() {
  const f = FEATURES[state.f];
  document.getElementById("quiz").innerHTML = f.quiz.map(q =>
    `<div class="qcard">
       <div class="q">❓ ${q.q}</div>
       <div class="a">✅ ${q.r}</div>
       <div class="tap">👆 clique pour révéler la réponse</div>
     </div>`
  ).join("");
  document.querySelectorAll(".qcard").forEach(c =>
    c.onclick = () => c.classList.toggle("open")
  );
}

/* ---------- Onglet GLOSSAIRE ---------- */
function renderGlossaire() {
  document.getElementById("glossaire").innerHTML = GLOSSAIRE.map(g =>
    `<div class="gitem"><div class="term">${g.term}</div><div class="def">${g.def}</div></div>`
  ).join("");
}

/* ---------- Recherche (surlignage) ---------- */
function applySearch() {
  const q = document.getElementById("search").value.trim().toLowerCase();
  const targets = document.querySelectorAll(".cexp, .gitem, .qcard");
  targets.forEach(el => {
    // retire les anciennes marques
    el.innerHTML = el.innerHTML.replace(/<mark>(.*?)<\/mark>/gi, "$1");
    if (!q) { el.style.display = ""; return; }
    const txt = el.textContent.toLowerCase();
    const match = txt.includes(q);
    if (el.classList.contains("cexp")) {
      el.style.display = "";
      if (match) highlightTextNodes(el, q);
    } else {
      el.style.display = match ? "" : "none";
      if (match) highlightTextNodes(el, q);
    }
  });
}
function highlightTextNodes(root, q) {
  const walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT);
  const nodes = [];
  while (walker.nextNode()) nodes.push(walker.currentNode);
  nodes.forEach(n => {
    const idx = n.textContent.toLowerCase().indexOf(q);
    if (idx === -1) return;
    const span = document.createElement("span");
    const before = n.textContent.slice(0, idx);
    const hit = n.textContent.slice(idx, idx + q.length);
    const after = n.textContent.slice(idx + q.length);
    span.innerHTML = esc(before) + "<mark>" + esc(hit) + "</mark>" + esc(after);
    n.replaceWith(span);
  });
}

/* ---------- Onglets ---------- */
function setTab(tab) {
  state.tab = tab;
  document.querySelectorAll(".tab").forEach(t => t.classList.toggle("active", t.dataset.tab === tab));
  document.querySelectorAll(".panel").forEach(p => p.classList.remove("active"));
  document.getElementById("panel-" + tab).classList.add("active");
}

/* ---------- Rendu global ---------- */
function renderAll() {
  renderPills();
  renderTree();
  renderStep();
  renderSchema();
  renderQuiz();
  renderGlossaire();
  setTab(state.tab);
}

/* ---------- Initialisation ---------- */
document.addEventListener("DOMContentLoaded", () => {
  renderGlossaire();
  renderAll();

  document.querySelectorAll(".tab").forEach(t => t.onclick = () => setTab(t.dataset.tab));
  document.getElementById("prevBtn").onclick = () => { if (state.step > 0) { state.step--; renderAll(); } };
  document.getElementById("nextBtn").onclick = () => {
    if (state.step < FEATURES[state.f].etapes.length - 1) { state.step++; renderAll(); }
  };
  document.getElementById("search").addEventListener("input", applySearch);

  document.getElementById("themeBtn").onclick = () => {
    document.body.classList.toggle("light");
    document.getElementById("themeBtn").textContent = document.body.classList.contains("light") ? "☀️" : "🌙";
  };
  document.getElementById("printBtn").onclick = () => window.print();

  // navigation clavier ← →
  document.addEventListener("keydown", (e) => {
    if (e.target.id === "search") return;
    if (e.key === "ArrowRight") document.getElementById("nextBtn").click();
    if (e.key === "ArrowLeft") document.getElementById("prevBtn").click();
  });
});
