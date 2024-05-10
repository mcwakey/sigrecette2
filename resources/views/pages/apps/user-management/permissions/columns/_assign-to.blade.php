@foreach($roles as $role)
    @if($role->name!='administrateur_system')
    <a href="{{ route('user-management.roles.show', $role) }}" class="badge fs-7 m-1 {{ app(\App\Actions\GetThemeType::class)->handle('badge-light-?', $role->name) }}">
        {{ __($role->name) }}
    </a>
    @endif
@endforeach
