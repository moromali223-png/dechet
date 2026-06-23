@extends('layouts.app')

@section('title', 'Dashboard - Exemple')

@section('content')
  @include('components.navbar')

  <div class="mt-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-900">Tableau de bord</h1>
        <x-button-primary>Nouvelle action</x-button-primary>
      </div>

      <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <x-card header="Revenus" :sub="'Dernière période'">
          <div class="text-2xl font-semibold text-gray-900">€12,340</div>
          <div class="mt-2 text-sm text-gray-500">+8% comparé à la semaine dernière</div>
        </x-card>

        <x-card header="Nouveaux abonnés" :sub="'30 dernières jours'">
          <div class="text-2xl font-semibold text-gray-900">124</div>
          <div class="mt-2 text-sm text-gray-500">Taux de conversion 3.2%</div>
        </x-card>

        <x-card header="Collectes prévues" :sub="'Aujourd'hui'">
          <div class="text-2xl font-semibold text-gray-900">18</div>
          <div class="mt-2 text-sm text-gray-500">Agents disponibles: 6</div>
        </x-card>
      </div>

      <div class="mt-6 grid gap-6 lg:grid-cols-2">
        <x-card header="Détails récents">
          <ul class="divide-y divide-gray-100">
            <li class="py-3 flex justify-between items-center"><span>Abonnement #234</span><span class="text-sm text-gray-500">2h ago</span></li>
            <li class="py-3 flex justify-between items-center"><span>Client: Jean Dupont</span><span class="text-sm text-gray-500">5h ago</span></li>
            <li class="py-3 flex justify-between items-center"><span>Paiement reçu</span><span class="text-sm text-gray-500">1 day</span></li>
          </ul>
        </x-card>

        <x-card header="Alertes" :sub="'Système'">
          <div class="space-y-3">
            <div class="p-3 bg-yellow-50 rounded">Stock faible pour Produit X</div>
            <div class="p-3 bg-red-50 rounded">Erreur d'envoi email</div>
          </div>
        </x-card>
      </div>
    </div>
  </div>

  <x-toast title="Succès">Exemple: opération terminée avec succès.</x-toast>

@endsection
