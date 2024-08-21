<x-default-layout>

@section('title')
    {{ __("Page de téléchargement des sauvegardes du logiciel") }}
@endsection
    @section('breadcrumbs')
        {{ Breadcrumbs::render('export_backup') }}
    @endsection
    <div class="card">
        <!--begin::Card body-->
        <div class="card-body">
            <!--begin::Heading-->
            <div class="card-px text-center pt-15 pb-15">
                <!--begin::Title-->
                <h2 class="fs-2x fw-bold mb-0">{{ __('Veuillez cliquez sur le bouton Télécharger pour télécharger la dernière  sauvegarde du logiciel.') }}</h2>
                <form action="{{ route('backupdownload') }}" method="GET">
                    <p class="text-gray-500 fs-4 fw-semibold py-7">
                        {{  __("Il est recommandé de récupérer les sauvegardes à la fin de chaque journée, ou à la fin du service. Assurez-vous de conserver cette sauvegarde en lieu sûr, car en cas de panne informatique, c'est elle qui permettra la restauration des données.") }}<br>.
                     </p>
                     @csrf

                     <div class="row justify-content-center mt-3">
                         <div class="col-md-6">
                             <button type="submit" class="btn btn-primary er fs-6 px-8 py-4" data-bs-toggle="modal">
                                 {{__('Télécharger')}}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-default-layout>
