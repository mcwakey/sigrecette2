<x-default-layout>

    @section('title')
        {{ __('dashboard') }}
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('dashboard') }}
    @endsection

    <style>
        .grid-2 {
            margin-bottom: 12px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            /* Crée une grille avec 4 colonnes égales */
            gap: 10px;
            /* Ajoute un espace entre les éléments de la grille */
            width: 100%;
            height: 100%;
        }

        .widget-container {

            /* Exemple de style pour voir les éléments plus facilement */
            padding: 10px;
            /* Ajoute un padding pour l'espacement interne */
            box-sizing: border-box;
            /* Inclut le padding et la bordure dans la largeur et la hauteur */
            border-radius: 6px;
            /* Hauteur pa défaut de la div */
            height: 100%;
        }
    </style>

    <div class="widget-container">
        @include('pages.dashboards._widget_invoices_stats')
    </div>
    <div class="grid-2">
        <div class="widget-container">
            @include('pages.dashboards.widget_notifications')
        </div>
        <div class="widget-container">
            @include('pages.dashboards.widget_taxpayer_with_gender')
        </div>
    </div>

    <div>
        <div style="margin-bottom: 20px;">
            @include('pages.dashboards.widget_root')
        </div>

        <div style="margin-bottom: 20px;">
            @include('pages.dashboards.widget_taxpayer_taxable')
        </div>
        <div style="margin-bottom: 20px;">
            @include('pages.dashboards.widget_taxpayer_category')
        </div>
        <div style="margin-bottom: 20px;">
            @include('pages.dashboards.widget_taxpayer_activity')
        </div>

        <div style="margin-bottom: 20px;">
            @include('pages.dashboards.widget_taxpayer_state')
        </div>
    </div>
</x-default-layout>
