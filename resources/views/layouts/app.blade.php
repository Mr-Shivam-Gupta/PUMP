@include('layouts.header')
<div id="layout-wrapper">
    @include('layouts.navbar')
    @include('layouts.sidebar')
    <div class="vertical-overlay"></div>
    <div class="main-content">
        @yield('content')
        @include('layouts.copyrigth')
    </div>
</div>
@include('layouts.footer')
