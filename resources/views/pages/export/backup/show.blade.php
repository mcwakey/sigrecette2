<x-default-layout>

@section('title')
    {{ __("Page de téléchargement des sauvegardes du logiciel") }}
@endsection
    @section('breadcrumbs')
        {{ Breadcrumbs::render('export_backup') }}
    @endsection
    <div class="card shadow-sm border-0">
        <div class="card-body p-5 text-center">
            <div class="text-center mb-5">
                <h2 class="fs-1 fw-bold text-primary mb-4">
                    {{ __('Télécharger la dernière sauvegarde') }}
                </h2>
                <p class="text-muted fs-5">
                    {{ __('Cliquez sur le bouton ci-dessous pour récupérer la dernière sauvegarde du logiciel.') }}
                </p>
            </div>

            @if($backup)
                <div class="d-flex justify-content-center align-items-center mb-4">
                    <a href="{{ route('backupdownload', [ 'created_at' => $backup['created_at']]) }}"
                       class="btn btn-success btn-lg px-5 py-3 d-flex align-items-center gap-2" target="_blank">
                        <i class="bi bi-cloud-download fs-4"></i>
                        {{ __('Télécharger le dernier backup') }} <span class="badge bg-light text-dark fs-6 ms-2">{{ $backup['created_at'] }}</span>
                    </a>
                </div>
            @else
                <p class="text-danger fs-5">
                    {{ __('Aucune sauvegarde disponible pour le moment.') }}
                </p>
            @endif

            <div class="bg-light p-4 rounded mt-5">
                <h4 class="text-dark mb-3">
                    {{ __('Recommandations') }}
                </h4>
                <p class="text-gray-700 fs-6">
                    {{ __('Il est conseillé de récupérer les sauvegardes quotidiennement, surtout après une journée de travail.
                    Stockez-les dans un endroit sécurisé. En cas de problème technique, cette sauvegarde permettra de restaurer vos données.') }}
                </p>

                <form action="{{ route('backupdownload') }}" method="GET" class="mt-4">
                    @csrf
                    <button type="submit" class="btn btn-primary px-5 py-3 fw-semibold fs-5">
                        <i class="bi bi-download me-2"></i> {{ __('Télécharger maintenant') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

</x-default-layout>
