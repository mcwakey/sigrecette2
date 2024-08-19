<x-default-layout>

@section('title')
    {{ __("Page de téléchargement du backup du logiciel") }}
@endsection
    @section('breadcrumbs')
        {{ Breadcrumbs::render('import-view') }}
    @endsection
    <div class="card">
        <!--begin::Card body-->
        <div class="card-body">
            <!--begin::Heading-->
            <div class="card-px text-center pt-15 pb-15">
                <!--begin::Title-->
                <h2 class="fs-2x fw-bold mb-0">{{ __('Veillez au moins chaque matin telecharger une sauvegarde du logiciel') }}</h2>
                <form action="{{ route('backupdownload') }}" method="GET">
                    <p class="text-gray-500 fs-4 fw-semibold py-7">
                        {{  __("") }}<br>.
                     </p>
                     @csrf

                     <div class="row justify-content-center mt-3">
                         <div class="col-md-6">
                             <button type="submit" class="btn btn-primary er fs-6 px-8 py-4" data-bs-toggle="modal">
                                 {{__('Télécharger la sauvegarde de la base de données')}}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-default-layout>
