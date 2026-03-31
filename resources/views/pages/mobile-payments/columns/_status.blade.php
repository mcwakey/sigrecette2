@php
    $statusMap = [
        'pending' => ['class' => 'badge-light-warning', 'label' => 'En attente'],
        'verifying' => ['class' => 'badge-light-info', 'label' => 'Vérification'],
        'success' => ['class' => 'badge-light-success', 'label' => 'Succès'],
        'failed' => ['class' => 'badge-light-danger', 'label' => 'Échoué'],
        'expired' => ['class' => 'badge-light-dark', 'label' => 'Expiré'],
    ];
    $st = $statusMap[$transaction->status] ?? ['class' => 'badge-light', 'label' => $transaction->status];
@endphp
<span class="badge {{ $st['class'] }}">{{ $st['label'] }}</span>
