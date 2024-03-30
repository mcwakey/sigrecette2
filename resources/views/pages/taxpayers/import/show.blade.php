<x-default-layout>

@section('title')
    {{ __('import-view') }}
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
                <h2 class="fs-2x fw-bold mb-0">{{ __('upload_news_taxpayers_with_excel') }}</h2>
                <form action="{{ route('import.process') }}" method="POST" enctype="multipart/form-data">
                    <p class="text-gray-500 fs-4 fw-semibold py-7">
                        {{  __('Load your excel after Click on the below button to load new taxable') }}<br>.
                     </p>
                     @csrf
                     <div class="row justify-content-center">
                         <div class="col-md-4">
                             <input type="file" name="file" accept=".xlsx, .xls" class="fs-6 form-control form-control-solid justify-content-center" />
                         </div>
                     </div>
                     <div class="row justify-content-center mt-3">
                         <div class="col-md-6">
                             <button type="submit" class="btn btn-primary er fs-6 px-8 py-4" data-bs-toggle="modal">
                                 {{__('import')}}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-default-layout>
