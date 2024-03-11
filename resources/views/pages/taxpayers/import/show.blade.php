<x-default-layout>

@section('title')
    {{ __('taxpayers information') }}
@endsection
    @section('breadcrumbs')
        {{ Breadcrumbs::render('taxpayers.index') }}
    @endsection
    <div class="card">
        <!--begin::Card body-->
        <div class="card-body">
            <!--begin::Heading-->
            <div class="card-px text-center pt-15 pb-15">
                <!--begin::Title-->
                <h2 class="fs-2x fw-bold mb-0">Upload news taxaplays with exel</h2>
                <form action="{{ route('import.process') }}" method="POST" enctype="multipart/form-data">
                <p class="text-gray-500 fs-4 fw-semibold py-7">
                    Click on the below buttons to launch <br>a new target example.            </p>
                @csrf
                <input type="file" name="file" accept=".xlsx, .xls" class="fs-4 form-control form-control-solid" />
                <button   type="submit" class="btn btn-primary er fs-6 px-8 py-4" data-bs-toggle="modal" >
                    Importer
                </button>
                </form>
            </div>
        </div>
    </div>
</x-default-layout>
