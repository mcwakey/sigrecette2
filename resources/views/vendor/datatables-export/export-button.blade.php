<div class="card-toolbar flex-row-fluid justify-content-end gap-5">
    <button type="button" class="btn btn-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
        <i class="ki-duotone ki-exit-down fs-2"><span class="path1"></span><span class="path2"></span></i>
        Export Report
    </button>
    <div id="kt_datatable_example_export_menu" class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4" data-kt-menu="true">

        <div class="menu-item px-3">
            <a href="#" class="menu-link px-3" data-kt-export="pdf">
                {{__('Export')."en PDF"}}
            </a>
        </div>
        <div class="menu-item px-3" x-data>

            <form class="mr-2" x-on:submit.prevent="
                $refs.exportBtn.disabled = true;
                var oTable = LaravelDataTables['{{ $tableId }}'];
                var baseUrl = oTable.ajax.url() === '' ? window.location.toString() : oTable.ajax.url();

                var params = new URLSearchParams({
                    action: 'exportQueue',
                    exportType: '{{$fileType}}',
                    sheetName: '{{$sheetName}}',
                    emailTo: '{{urlencode($emailTo)}}',
                });

                $.get(baseUrl + '?' + params.toString() + '&' + $.param(oTable.ajax.params())).then(function(exportId) {
                    $wire.export(exportId);
                }).catch(function(error) {
                    $wire.set('exportFinished', true);
                    $wire.set('exporting', false);
                    $wire.set('exportFailed', true);
                });
              ">

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-link  btn-lg" x-ref="exportBtn" :disabled="$wire.exporting">
                        <span class="menu-link px-3">
                        {{__('Export')."en XLSX"}}
                        </span>
                    </button>
                   </div>



            </form>

            @if($exporting && $emailTo)
                <div class="d-inline">Export will be emailed to {{ $emailTo }}.</div>
            @endif

            @if($exporting && !$exportFinished)
                <div class="d-inline" wire:poll="updateExportProgress">{{__('Exporting...please wait.')}}</div>
            @endif

            @if($exportFinished && !$exportFailed && !$autoDownload)
                <span>{{__('Done. Download file')}} <a href="#" class="text-primary" wire:click.prevent="downloadExport">{{__('Here')}}</a></span>
            @endif

            @if($exportFinished && !$exportFailed && $autoDownload && $downloaded)
                <span>{{__('Done. File has been downloaded.')}}</span>
            @endif

            @if($exportFailed)
                <span>{{__('Export failed, please try again later.')}}</span>
            @endif
        </div>

    </div>
</div>
