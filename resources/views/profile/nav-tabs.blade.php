<ul class="nav nav-tabs mb-4 gap-2 gap-lg-0" role="tablist">
    <li class="nav-item">
        <a class="nav-link {{ Request::routeIs('web.profile') ? 'active' : '' }}" role="tab"
            href="{{ Route('web.profile') }}?id={{ request()->get('id') }}">
            <i class="mdi mdi-account-outline mdi-20px me-1"></i>Personal Info
        </a>
    </li>
    {{-- <li class="nav-item">
        <a class="nav-link {{ Request::routeIs('address') ? 'active' : '' }}"
            href="{{ Route('address') }}?id={{ request()->get('id') }}">
            <i class="mdi mdi-map-marker-radius-outline mdi-20px me-1"></i>Address Info
        </a>
    </li> --}}
</ul>
