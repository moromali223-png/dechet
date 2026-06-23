@extends('layouts.app')

@section('title', $produit->nom)

@section('content')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap');

  :root {
    --clr-bg: #f4f7fb;
    --clr-surface: #ffffff;
    --clr-card: #ffffff;
    --clr-card-soft: #f8fafc;
    --clr-border: rgba(15, 23, 42, 0.08);
    --clr-accent: #0f766e;
    --clr-accent-soft: #d1fae5;
    --clr-text: #0f172a;
    --clr-muted: #E19A0B;
    --clr-success: #16a34a;
    --clr-warning: #f59e0b;
    --radius: 24px;
  }

  .produit-page {
    font-family: 'DM Sans', sans-serif;
    background: var(--clr-bg);
    min-height: 100vh;
    padding: 40px 0 80px;
    color: var(--clr-text);
  }

  .breadcrumb {
    color: var(--clr-muted);
    font-size: 13px;
    margin-bottom: 24px;
  }
  .breadcrumb a {
    color: var(--clr-muted);
    text-decoration: none;
  }
  .breadcrumb a:hover {
    color: var(--clr-accent);
  }

  .produit-grid {
    display: grid;
    grid-template-columns: 1.1fr 0.9fr;
    gap: 44px;
    align-items: start;
  }
  @media(max-width: 992px) {
    .produit-grid {
      grid-template-columns: 1fr;
      gap: 32px;
    }
  }

  .produit-img-wrap {
    border-radius: 28px;
    overflow: hidden;
    background: var(--clr-card);
    border: 1px solid var(--clr-border);
    box-shadow: 0 20px 50px rgba(15, 23, 42, 0.08);
    position: relative;
  }
  .produit-img-wrap img {
    width: 100%;
    height: auto;
    display: block;
    transition: transform 0.5s ease;
  }
  .produit-img-wrap:hover img {
    transform: scale(1.02);
  }

  .produit-placeholder {
    min-height: 500px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #7b8794;
    background: linear-gradient(180deg, rgba(239, 246, 255, 0.9), rgba(241, 245, 249, 0.95));
  }
  .produit-placeholder i {
    font-size: 96px;
    opacity: 0.28;
  }

  .produit-info {
    display: flex;
    flex-direction: column;
    gap: 28px;
  }

  .produit-category {
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 1.7px;
    text-transform: uppercase;
    color: var(--clr-accent);
  }

  .produit-name {
    font-family: 'Syne', sans-serif;
    font-size: clamp(32px, 4vw, 48px);
    font-weight: 900;
    line-height: 1.05;
    margin: 0;
    letter-spacing: -0.04em;
  }

  .produit-price {
    font-family: 'Syne', sans-serif;
    font-size: 40px;
    font-weight: 800;
    color: var(--clr-accent);
  }

  .produit-description {
    font-size: 16px;
    line-height: 1.8;
    color: var(--clr-muted);
    max-width: 720px;
  }

  .stock-status {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 10px 16px;
    border-radius: 999px;
    background: rgba(22, 163, 74, 0.12);
    color: var(--clr-success);
    font-size: 14px;
    font-weight: 600;
    border: 1px solid rgba(22, 163, 74, 0.16);
    width: fit-content;
  }

  .commander-block {
    background: var(--clr-card);
    border: 1px solid var(--clr-border);
    border-radius: 28px;
    padding: 32px;
    box-shadow: 0 24px 40px rgba(15, 23, 42, 0.08);
  }

  .commander-block h5 {
    font-family: 'Syne', sans-serif;
    margin-bottom: 22px;
    color: var(--clr-text);
  }

  .qty-row {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 12px;
    margin-bottom: 22px;
  }

  .qty-btn {
    width: 100%;
    min-height: 56px;
    background: var(--clr-card-soft);
    border: 1px solid var(--clr-border);
    border-radius: 16px;
    color: var(--clr-text);
    font-size: 24px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
  }
  .qty-btn:hover {
    border-color: var(--clr-accent);
    background: rgba(15, 118, 110, 0.08);
  }

  .qty-input {
    width: 100%;
    text-align: center;
    background: var(--clr-card-soft);
    border: 1px solid var(--clr-border);
    border-radius: 16px;
    padding: 16px;
    color: var(--clr-text);
    font-family: 'Syne', sans-serif;
    font-size: 20px;
    font-weight: 700;
  }

  .total-line {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    background: #f8fafc;
    padding: 18px 22px;
    border-radius: 18px;
    margin-bottom: 24px;
    border: 1px solid rgba(15, 23, 42, 0.06);
    font-size: 15px;
    color: var(--clr-text);
  }

  .btn-commander {
    width: 100%;
    background: var(--clr-accent);
    color: #ffffff;
    border: none;
    border-radius: 16px;
    padding: 18px;
    font-family: 'Syne', sans-serif;
    font-weight: 800;
    font-size: 16px;
    cursor: pointer;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }
  .btn-commander:hover {
    background: #115e59;
    transform: translateY(-1px);
    box-shadow: 0 12px 24px rgba(15, 118, 110, 0.18);
  }

  .meta-info {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px;
    margin-top: 20px;
  }
  .meta-info div {
    font-size: 14px;
    color: var(--clr-muted);
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 14px;
    border-radius: 16px;
    background: #f8fafc;
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
          <div class="produit-placeholder">
            <i class="bx bx-package"></i>
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