@if(in_array($transaction->status, ['failed', 'expired', 'pending', 'verifying']))
    <button
        class="btn btn-sm btn-icon btn-light-warning"
        title="Relancer la vérification"
        onclick="Livewire.dispatch('retryMobileVerification', { id: {{ $transaction->id }} })"
    >
        {!! getIcon('arrows-circle', 'fs-4') !!}
    </button>
@endif
