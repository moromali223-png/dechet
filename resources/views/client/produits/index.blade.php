@extends('layouts.app')

@section('title', 'Nos Produits')

@section('content')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap');

  :root {
    --clr-bg: #E9F0FE;
    --clr-surface: #FCFCF8;
    --clr-card: #3B5D3D;
    --clr-border: rgba(255, 255, 255, 0.07);
    --clr-accent: #e8b84b;
    --clr-text: #FFFFFF;
    --clr-muted: #FDFEFF;
    --radius: 16px;
  }

  .shop-wrapper {
    font-family: 'DM Sans', sans-serif;
    background: var(--clr-bg);
    min-height: 100vh;
    padding: 40px 0 80px;
    color: var(--clr-text);
  }

  /* ── HEADER ── */
  .shop-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    margin-bottom: 40px;
    gap: 16px;
    flex-wrap: wrap;
  }
  .shop-title {
    font-family: 'Syne', sans-serif;
    font-size: clamp(28px, 4vw, 44px);
    font-weight: 800;
    line-height: 1.1;
    letter-spacing: -1px;
    color: var(--clr-text);
    margin: 0;
  }
  .shop-title span { color: var(--clr-accent); }
  .shop-subtitle {
    font-size: 14px;
    color: #DE0C0C;
    margin-top: 6px;
  }

  /* ── SEARCH BAR ── */
  .search-bar {
    position: relative;
    flex: 1;
    max-width: 340px;
  }
  .search-bar input {
    width: 100%;
    background: #C2D8EF;
    border: 1px solid var(--clr-border);
    border-radius: 50px;
    padding: 12px 20px 12px 46px;
    color: var(--clr-text);
    font-family: 'DM Sans', sans-serif;
    font-size: 14px;
    outline: none;
    transition: border-color .2s;
  }
  .search-bar input:focus { border-color: var(--clr-accent); }
  .search-bar .icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--clr-muted);
    font-size: 18px;
    pointer-events: none;
  }

  /* ── PRODUCT GRID ── */
  .products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 24px;
  }

  /* ── PRODUCT CARD ── */
  .product-card {
    background: var(--clr-card);
    border: 1px solid var(--clr-border);
    border-radius: var(--radius);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
    text-decoration: none;
    color: inherit;
  }
  .product-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 50px rgba(0,0,0,0.5);
    border-color: rgba(232,184,75,0.35);
  }

  /* Image zone */
  .product-image-wrap {
    position: relative;
    width: 100%;
    padding-top: 68%;
    background: #0f111a;
    overflow: hidden;
  }
  .product-image-wrap img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .4s ease;
  }
  .product-card:hover .product-image-wrap img { transform: scale(1.05); }

  .product-image-placeholder {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 8px;
    color: var(--clr-muted);
  }
  .product-image-placeholder i { font-size: 48px; opacity: .4; }

  /* Badge stock */
  .badge-stock {
    position: absolute;
    top: 12px;
    left: 12px;
    background: rgba(13,15,20,.75);
    backdrop-filter: blur(6px);
    border: 1px solid var(--clr-border);
    border-radius: 50px;
    padding: 4px 12px;
    font-size: 11px;
    font-weight: 500;
    color: #6ddf9d;
    display: flex;
    align-items: center;
    gap: 5px;
  }
  .badge-stock::before {
    content: '';
    width: 6px; height: 6px;
    background: #6ddf9d;
    border-radius: 50%;
    animation: pulse 1.5s infinite;
  }
  @keyframes pulse {
    0%,100% { opacity:1; } 50% { opacity:.3; }
  }

  /* Quick order overlay */
  .quick-order-overlay {
    position: absolute;
    bottom: 12px;
    right: 12px;
    opacity: 0;
    transform: translateY(8px);
    transition: opacity .25s, transform .25s;
  }
  .product-card:hover .quick-order-overlay {
    opacity: 1;
    transform: translateY(0);
  }
  .btn-quick-order {
    background: var(--clr-accent);
    color: #0d0f14;
    border: none;
    border-radius: 50px;
    padding: 9px 18px;
    font-family: 'Syne', sans-serif;
    font-weight: 700;
    font-size: 12px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
    transition: background .2s;
  }
  .btn-quick-order:hover { background: #f5ca6a; }

  /* Info zone */
  .product-info {
    padding: 18px 20px 20px;
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 10px;
  }
  .product-category {
    font-size: 11px;
    font-weight: 500;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: var(--clr-accent);
  }
  .product-name {
    font-family: 'Syne', sans-serif;
    font-size: 16px;
    font-weight: 700;
    line-height: 1.3;
    color: var(--clr-text);
    margin: 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
  .product-description {
    font-size: 13px;
    color: var(--clr-muted);
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin: 0;
    flex: 1;
  }

  .product-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 4px;
    gap: 8px;
    flex-wrap: wrap;
  }
  .product-price {
    font-family: 'Syne', sans-serif;
    font-size: 22px;
    font-weight: 800;
    color: var(--clr-accent);
    line-height: 1;
  }
  .product-price small {
    font-size: 13px;
    font-weight: 400;
    color: var(--clr-muted);
    font-family: 'DM Sans', sans-serif;
  }
  .btn-detail {
    background: transparent;
    border: 1px solid var(--clr-border);
    border-radius: 50px;
    padding: 7px 16px;
    color: var(--clr-muted);
    font-size: 12px;
    font-family: 'DM Sans', sans-serif;
    cursor: pointer;
    transition: all .2s;
  }
  /* ── QUICK ORDER MODAL ── */
.qo-modal-backdrop {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.6);
    backdrop-filter: blur(4px);
    z-index: 9999;
    align-items: center;
    justify-content: center;
}
.qo-modal-backdrop.open {
    display: flex;
}
.qo-modal {
    background: #1e2937;
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 20px;
    padding: 32px;
    width: 100%;
    max-width: 440px;
    position: relative;
    color: #f8fafc;
}
.qo-modal h5 {
    font-family: 'Syne', sans-serif;
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 4px;
}
.qo-modal p {
    color: #94a3b8;
    font-size: 13px;
    margin-bottom: 20px;
}
.qo-modal-close {
    position: absolute;
    top: 16px; right: 16px;
    background: rgba(255,255,255,.07);
    border: none;
    border-radius: 8px;
    width: 36px; height: 36px;
    color: #f8fafc;
    font-size: 20px;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
}
.qo-product-preview {
    display: flex;
    align-items: center;
    gap: 14px;
    background: rgba(0,0,0,.2);
    border-radius: 12px;
    padding: 14px;
    margin-bottom: 20px;
}
.qo-product-preview strong { display: block; font-size: 15px; }
.qo-product-preview span   { font-size: 13px; color: #e8b84b; }
.qo-label {
    font-size: 13px;
    color: #94a3b8;
    display: block;
    margin-bottom: 8px;
}
.qo-qty-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 16px;
}
.qo-qty-btn {
    width: 44px; height: 44px;
    background: rgba(255,255,255,.07);
    border: 1px solid rgba(255,255,255,.08);
    border-radius: 10px;
    color: #f8fafc;
    font-size: 22px;
    cursor: pointer;
    transition: background .2s;
}
.qo-qty-btn:hover { background: rgba(232,184,75,.15); }
.qo-qty-input {
    width: 80px;
    text-align: center;
    background: rgba(255,255,255,.07);
    border: 1px solid rgba(255,255,255,.08);
    border-radius: 10px;
    padding: 10px;
    color: #f8fafc;
    font-size: 18px;
    font-weight: 700;
}
.qo-total {
    background: rgba(0,0,0,.2);
    border-radius: 10px;
    padding: 12px 16px;
    font-size: 14px;
    color: #94a3b8;
    margin-bottom: 20px;
}
.qo-total strong { color: #e8b84b; }
.btn-confirm-order {
    width: 100%;
    background: #e8b84b;
    color: #0d0f14;
    border: none;
    border-radius: 12px;
    padding: 15px;
    font-family: 'Syne', sans-serif;
    font-weight: 700;
    font-size: 15px;
    cursor: pointer;
    transition: background .2s, transform .2s;
}
.btn-confirm-order:hover {
    background: #f5ca6a;
    transform: translateY(-2px);
}
  .btn-detail:hover { border-color: var(--clr-accent); color: var(--clr-accent); }
</style>

<div class="shop-wrapper">
  <div class="container-xxl">

    @if(session('success'))
      <div class="alert-success d-flex align-items-center gap-2">
        <i class="bx bx-check-circle"></i> {{ session('success') }}
      </div>
    @endif

    {{-- HEADER --}}
    <div class="shop-header">
      <div>
        <h1 class="shop-title">Notre <span>Catalogue</span></h1>
        <p class="shop-subtitle">{{ $produits->total() }} produits disponibles</p>
      </div>
      <div class="search-bar">
        <i class="bx bx-search icon"></i>
        <input type="text" id="searchInput" placeholder="Rechercher un produit…">
      </div>
    </div>

    {{-- GRID --}}
    @if($produits->isEmpty())
      <div class="empty-state">
        <i class="bx bx-package"></i>
        <h5>Aucun produit disponible</h5>
        <p>Revenez bientôt, de nouveaux produits arrivent.</p>
      </div>
    @else
      <div class="products-grid" id="productsGrid">
        @foreach($produits as $produit)
          <a href="{{ route('client.produits.show', $produit) }}" 
             class="product-card" 
             data-name="{{ strtolower($produit->nom) }}">

            {{-- Image --}}
            <div class="product-image-wrap">
              @if($produit->photo)
                <img src="{{ asset('storage/' . $produit->photo) }}" 
                     alt="{{ $produit->nom }}" 
                     loading="lazy">
              @else
                <div class="product-image-placeholder">
                  <i class="bx bx-image-alt"></i>
                  <span style="font-size:12px;">Aucune image</span>
                </div>
              @endif

              <div class="badge-stock">En stock</div>

              {{-- Quick Order Button --}}
              {{-- Quick Order Button --}}
<div class="quick-order-overlay">
    <button 
        class="btn-quick-order"
        data-produit-id="{{ $produit->id }}"
        data-nom="{{ $produit->nom }}"
        data-prix="{{ $produit->prix_unitaire }}"
        data-photo="{{ $produit->photo ? asset('storage/' . $produit->photo) : '' }}"
        data-url="{{ route('client.produits.commander', $produit) }}"
    >
        <i class="bx bx-cart-add"></i> Commander
    </button>
</div>
            </div>

            {{-- Info --}}
            <div class="product-info">
              @if($produit->categorie ?? false)
                <div class="product-category">{{ $produit->categorie }}</div>
              @endif
              <h3 class="product-name">{{ $produit->nom }}</h3>
              <p class="product-description">{{ $produit->description }}</p>

              <div class="product-footer">
                <div class="product-price">
                  {{ number_format($produit->prix_unitaire, 0, ',', ' ') }} 
                  <small>FCFA / unité</small>
                </div>
                <span class="btn-detail">
                  <i class="bx bx-info-circle"></i> Détails
                </span>
              </div>
            </div>
          </a>
        @endforeach
      </div>

      {{-- Pagination --}}
      <div class="d-flex justify-content-center mt-5">
        {{ $produits->links() }}
      </div>
    @endif

  </div>
</div>

{{-- QUICK ORDER MODAL --}}
<div class="qo-modal-backdrop" id="qoBackdrop">
  <div class="qo-modal">
    <button class="qo-modal-close" onclick="closeQuickOrder()">
      <i class="bx bx-x"></i>
    </button>
    <h5>Passer une commande</h5>
    <p>Choisissez la quantité souhaitée</p>

    <div class="qo-product-preview">
      <div id="qoImg"></div>
      <div>
        <strong id="qoName">—</strong>
        <span id="qoPrice">—</span>
      </div>
    </div>

    <form id="qoForm" method="POST" action="">
      @csrf
      <label class="qo-label">Quantité</label>
      <div class="qo-qty-row">
        <button type="button" class="qo-qty-btn" onclick="adjustQty(-1)">−</button>
        <input type="number" name="quantite" id="qoQty" class="qo-qty-input" value="1" min="1">
        <button type="button" class="qo-qty-btn" onclick="adjustQty(1)">+</button>
      </div>

      <div class="qo-total">
        Total estimé : <strong id="qoTotal">—</strong>
      </div>

      <button type="submit" class="btn-confirm-order">
        <i class="bx bx-check-circle"></i> Confirmer la commande
      </button>
    </form>
  </div>
</div>

<script>
let currentPrice = 0;

// Ouvre la modal quick order
function openQuickOrder(button) {
    currentPrice = parseFloat(button.dataset.prix);

    document.getElementById('qoName').textContent   = button.dataset.nom;
    document.getElementById('qoPrice').textContent  = Number(currentPrice).toLocaleString('fr-FR') + ' FCFA';
    document.getElementById('qoForm').action        = button.dataset.url;
    document.getElementById('qoQty').value          = 1;

    const imgEl = document.getElementById('qoImg');
    const imgUrl = button.dataset.photo;
    imgEl.innerHTML = imgUrl
        ? `<img src="${imgUrl}" alt="${button.dataset.nom}" style="width:64px;height:64px;object-fit:cover;border-radius:8px;">`
        : `<div style="width:64px;height:64px;background:#1e2937;border-radius:8px;display:flex;align-items:center;justify-content:center;"><i class='bx bx-package' style='font-size:28px;opacity:.4'></i></div>`;

    updateTotal();
    document.getElementById('qoBackdrop').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeQuickOrder() {
    document.getElementById('qoBackdrop').classList.remove('open');
    document.body.style.overflow = '';
}

document.getElementById('qoBackdrop').addEventListener('click', function(e) {
    if (e.target === this) closeQuickOrder();
});

function adjustQty(delta) {
    const inp = document.getElementById('qoQty');
    inp.value = Math.max(1, parseInt(inp.value || 1) + delta);
    updateTotal();
}

function updateTotal() {
    const qty   = parseInt(document.getElementById('qoQty').value) || 1;
    const total = (qty * currentPrice).toLocaleString('fr-FR');
    document.getElementById('qoTotal').textContent = total + ' FCFA';
}

document.getElementById('qoQty').addEventListener('input', updateTotal);

// Recherche en temps réel
document.getElementById('searchInput').addEventListener('input', function() {
    const q = this.value.toLowerCase().trim();
    document.querySelectorAll('.product-card').forEach(card => {
        card.style.display = card.dataset.name.includes(q) ? '' : 'none';
    });
});

// Attacher les boutons commander
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.btn-quick-order').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation(); // empêche le clic de propager vers le lien parent
            openQuickOrder(this);
        });
    });
});
</script>
@endsection