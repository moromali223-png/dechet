@extends('layouts.app')

@section('title', $produit->nom)

@section('content')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap');

  :root {
    --clr-bg: #2A4533;
    --clr-surface: #1a202c;
    --clr-card: #4D6BA0;
    --clr-border: rgba(255,255,255,0.08);
    --clr-accent: #3b82f6;
    --clr-accent-hover: #AABADD;
    --clr-text: #f8fafc;
    --clr-muted: #94a3b8;
    --clr-success: #10b981;
    --radius: 16px;
  }

  .produit-page {
    font-family: 'DM Sans', sans-serif;
    background: var(--clr-bg);
    min-height: 100vh;
    padding: 40px 0 100px;
    color: var(--clr-text);
  }

  .breadcrumb {
    color: var(--clr-muted);
    font-size: 13px;
    margin-bottom: 20px;
  }
  .breadcrumb a { color: var(--clr-muted); text-decoration: none; }
  .breadcrumb a:hover { color: var(--clr-accent); }

  .produit-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 48px;
    align-items: start;
  }
  @media(max-width: 992px) {
    .produit-grid { grid-template-columns: 1fr; gap: 32px; }
  }

  /* Image Section */
  .produit-img-wrap {
    border-radius: 20px;
    overflow: hidden;
    background: #1e2937;
    border: 1px solid var(--clr-border);
    position: relative;
  }
  .produit-img-wrap img {
    width: 100%;
    height: auto;
    display: block;
    transition: transform 0.5s ease;
  }
  .produit-img-wrap:hover img {
    transform: scale(1.03);
  }

  /* Info Section */
  .produit-info {
    display: flex;
    flex-direction: column;
    gap: 24px;
  }

  .produit-category {
    font-size: 12px;
    font-weight: 500;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: var(--clr-accent);
  }

  .produit-name {
    font-family: 'Syne', sans-serif;
    font-size: clamp(28px, 4vw, 42px);
    font-weight: 800;
    line-height: 1.1;
    margin: 0;
  }

  .produit-price {
    font-family: 'Syne', sans-serif;
    font-size: 38px;
    font-weight: 800;
    color: var(--clr-accent);
  }

  .produit-description {
    font-size: 15.5px;
    line-height: 1.75;
    color: var(--clr-muted);
  }

  /* Commander Block - Style Amazon Premium */
  .commander-block {
    background: var(--clr-card);
    border: 1px solid var(--clr-border);
    border-radius: var(--radius);
    padding: 28px;
  }

  .commander-block h5 {
    font-family: 'Syne', sans-serif;
    margin-bottom: 20px;
    color: var(--clr-text);
  }

  .stock-status {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: var(--clr-success);
    font-size: 14px;
    margin-bottom: 20px;
  }

  .qty-row {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
  }

  .qty-btn {
    width: 48px;
    height: 48px;
    background: var(--clr-bg);
    border: 1px solid var(--clr-border);
    border-radius: 12px;
    color: var(--clr-text);
    font-size: 22px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
  }
  .qty-btn:hover {
    border-color: var(--clr-accent);
    background: rgba(59, 130, 246, 0.1);
  }

  .qty-input {
    width: 90px;
    text-align: center;
    background: var(--clr-bg);
    border: 1px solid var(--clr-border);
    border-radius: 12px;
    padding: 12px;
    color: var(--clr-text);
    font-family: 'Syne', sans-serif;
    font-size: 20px;
    font-weight: 700;
  }

  .total-line {
    background: rgba(0,0,0,0.2);
    padding: 14px 18px;
    border-radius: 12px;
    margin-bottom: 24px;
    font-size: 15px;
  }

  .btn-commander {
    width: 100%;
    background: var(--clr-accent);
    color: #0d0f14;
    border: none;
    border-radius: 12px;
    padding: 16px;
    font-family: 'Syne', sans-serif;
    font-weight: 700;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.2s;
  }
  .btn-commander:hover {
    background: #60a5fa;
    transform: translateY(-2px);
  }

  .meta-info {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 16px;
  }
  .meta-info div {
    font-size: 13px;
    color: var(--clr-muted);
  }
</style>

<div class="produit-page">
  <div class="container-xxl">

    {{-- Breadcrumb --}}
    <div class="breadcrumb">
      <a href="{{ route('client.produits.index') }}">Catalogue</a> 
      &rsaquo; <span>{{ $produit->nom }}</span>
    </div>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="produit-grid">

      <!-- Image -->
      <div class="produit-img-wrap">
        @if($produit->photo)
          <img src="{{ asset('storage/' . $produit->photo) }}" alt="{{ $produit->nom }}" loading="lazy">
        @else
          <div style="height: 500px; display:flex; align-items:center; justify-content:center; color:#64748b;">
            <i class="bx bx-package" style="font-size: 120px; opacity: 0.3;"></i>
          </div>
        @endif
      </div>

      <!-- Informations + Commande -->
      <div class="produit-info">

        @if($produit->categorie)
          <div class="produit-category">{{ $produit->categorie }}</div>
        @endif

        <h1 class="produit-name">{{ $produit->nom }}</h1>

        <div class="produit-price">
          {{ number_format($produit->prix_unitaire, 2) }} <small style="font-size:18px;">FCFA / unité</small>
        </div>

        <div class="stock-status">
          <i class="bx bx-check-circle"></i>
          <strong>En stock • Livraison rapide</strong>
        </div>

        @if($produit->description)
          <p class="produit-description">{{ $produit->description }}</p>
        @endif

        <!-- Bloc Commande -->
        <div class="commander-block">
          <h5>Passer votre commande</h5>

          <form method="POST" action="{{ route('client.produits.commander', $produit) }}">
          @csrf
            <div class="qty-row">
              <button type="button" class="qty-btn" onclick="adjustQty(-1)">−</button>
              <input type="number" name="quantite" id="qty" class="qty-input" value="1" min="1" required>
              <button type="button" class="qty-btn" onclick="adjustQty(1)">+</button>
            </div>

            <div class="total-line">
              Total estimé : 
              <strong id="totalDisplay">{{ number_format($produit->prix_unitaire, 2) }} FCFA</strong>
            </div>

            <button type="submit" class="btn-commander">
              <i class="bx bx-cart me-2"></i> Confirmer la commande
            </button>
          </form>

          <div class="meta-info">
            <div><i class="bx bx-shield"></i> Paiement sécurisé</div>
            <div><i class="bx bx-time"></i> Commande validée sous 24h</div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<script>
  const unitPrice = {{ $produit->prix_unitaire }};

  function adjustQty(delta) {
    const input = document.getElementById('qty');
    let value = parseInt(input.value) || 1;
    value = Math.max(1, value + delta);
    input.value = value;
    updateTotal();
  }

  function updateTotal() {
    const qty = parseInt(document.getElementById('qty').value) || 1;
    const total = (qty * unitPrice).toFixed(2);
    document.getElementById('totalDisplay').textContent = total + ' FCFA';
  }

  document.getElementById('qty').addEventListener('input', updateTotal);
</script>
@endsection