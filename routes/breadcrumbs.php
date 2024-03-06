<?php

use App\Models\Activity;
use App\Models\Canton;
use App\Models\Category;
use App\Models\User;
use App\Models\Taxpayer;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
use Spatie\Permission\Models\Role;

// Home
Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push(__('home'), route('dashboard'));
});

// Home > Dashboard
Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push(__('dashboard'), route('dashboard'));
});

// Home > Dashboard > User Management
Breadcrumbs::for('user-management.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('User Management', route('user-management.users.index'));
});

// Home > Dashboard > User Management > Users
Breadcrumbs::for('user-management.users.index', function (BreadcrumbTrail $trail) {
    $trail->parent('user-management.index');
    $trail->push('Users', route('user-management.users.index'));
});

// Home > Dashboard > User Management > Users > [User]
Breadcrumbs::for('user-management.users.show', function (BreadcrumbTrail $trail, User $user) {
    $trail->parent('user-management.users.index');
    $trail->push(ucwords($user->name), route('user-management.users.show', $user));
});

// Home > Dashboard > User Management > Roles
Breadcrumbs::for('user-management.roles.index', function (BreadcrumbTrail $trail) {
    $trail->parent('user-management.index');
    $trail->push('Roles', route('user-management.roles.index'));
});

// Home > Dashboard > User Management > Roles > [Role]
Breadcrumbs::for('user-management.roles.show', function (BreadcrumbTrail $trail, Role $role) {
    $trail->parent('user-management.roles.index');
    $trail->push(ucwords($role->name), route('user-management.roles.show', $role));
});

// Home > Dashboard > User Management > Permission
Breadcrumbs::for('user-management.permissions.index', function (BreadcrumbTrail $trail) {
    $trail->parent('user-management.index');
    $trail->push('Permissions', route('user-management.permissions.index'));
});


// Home > Dashboard > Taxpayers
Breadcrumbs::for('taxpayers.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('taxpayers'), route('taxpayers.index'));
});

Breadcrumbs::for('towns.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('towns'), route('settings.towns.index'));
});
Breadcrumbs::for('taxpayers.show', function (BreadcrumbTrail $trail, Taxpayer $taxpayer) {
    $trail->parent('taxpayers.index');
    $trail->push(ucwords($taxpayer->name), route('taxpayers.show', $taxpayer));
});
Breadcrumbs::for('cantons.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('cantons'), route('settings.cantons.index'));
});
Breadcrumbs::for('ereas.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('ereas'), route('settings.ereas.index'));
});
Breadcrumbs::for('zones.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('zones'), route('settings.zones.index'));
});
Breadcrumbs::for('cantons.show', function (BreadcrumbTrail $trail, Canton $canton) {
    $trail->parent('cantons.index');
    $trail->push(ucwords($canton->id), route(' cantons.show', $canton));
});


Breadcrumbs::for('categories.show', function (BreadcrumbTrail $trail, Category $category) {
    $trail->parent('categories.index');
    $trail->push(ucwords($category->id), route(' categories.show', $category));
});
Breadcrumbs::for('categories.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('categories'), route('settings.categories.index'));
});


Breadcrumbs::for('activities.show', function (BreadcrumbTrail $trail, Activity $activity) {
    $trail->parent('activities.index');
    $trail->push(ucwords($activity->id), route(' activities.show', $activity));
});
Breadcrumbs::for('activities.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('activities'), route('settings.activities.index'));
});
