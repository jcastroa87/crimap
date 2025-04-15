{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<!-- CriMap Main Menu -->
<x-backpack::menu-dropdown title="Crime Management" icon="la la-map-marked">
    <x-backpack::menu-dropdown-item title="Crime Types" icon="la la-list" :link="backpack_url('crime-type')" />
    <x-backpack::menu-dropdown-item title="Crime Reports" icon="la la-exclamation-triangle" :link="backpack_url('crime-report')" />
</x-backpack::menu-dropdown>

<!-- API & Subscription Management -->
<x-backpack::menu-dropdown title="API & Subscriptions" icon="la la-key">
    <x-backpack::menu-dropdown-item title="API Keys" icon="la la-key" :link="backpack_url('api-key')" />
    <x-backpack::menu-dropdown-item title="Subscriptions" icon="la la-credit-card" :link="backpack_url('subscription')" />
</x-backpack::menu-dropdown>

<!-- User Management -->
<x-backpack::menu-dropdown title="User Management" icon="la la-users">
    <x-backpack::menu-dropdown-item title="Users" icon="la la-user" :link="backpack_url('user')" />
</x-backpack::menu-dropdown>
