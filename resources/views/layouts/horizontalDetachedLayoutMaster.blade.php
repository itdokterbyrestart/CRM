<body class="horizontal-layout horizontal-menu {{$configData['horizontalMenuType']}} {{ $configData['blankPageClass'] }}
{{ $configData['bodyClass'] }} {{ $configData['verticalMenuNavbarType'] }} {{ $configData['footerType'] }}" data-menu="horizontal-menu" data-col="{{ $configData['showMenu'] === true ? '' : '1-column' }}" data-open="hover" data-layout="{{ ($configData['theme'] === 'light') ? '' : $configData['layoutTheme'] }}" style="{{ $configData['bodyStyle'] }}" data-framework="laravel" data-asset-path="{{ asset('/')}}">

  {{-- Include Sidebar --}}
  @if((isset($configData['showMenu']) && $configData['showMenu'] === true))
  @include('panels.sidebar')
  @endif

  <!-- BEGIN: Header-->
  {{-- Include Navbar --}}
  @include('panels.navbar')

  {{-- Include Sidebar --}}
  @include('panels.horizontalMenu')

  <!-- BEGIN: Content-->
  <div class="app-content content {{ $configData['pageClass'] }}" style="background-image: linear-gradient(150deg, {{ config('app.PRIMARY_GRADIENT_COLOR') ?? '#0f70b7' }} 10%, {{ config('app.SECONDARY_GRADIENT_COLOR') ?? '#28a9e1' }} 100%) !important">
    <!-- BEGIN: Header-->
    <div class="content-overlay"></div>
    {{-- <div class="header-navbar-shadow"></div> --}}

    <div class="content-wrapper {{ $configData['layoutWidth'] === 'boxed' ? 'container-xxl p-0' : '' }}">
      {{-- Include Breadcrumb --}}
      @if($configData['pageHeader'] == true && isset($configData['pageHeader']))
      @include('panels.breadcrumb')
      @endif
      <div class="{{ $configData['sidebarPositionClass'] }}">
        <div class="sidebar">
          {{-- Include Sidebar Content --}}
          @yield('content-sidebar')
        </div>
      </div>
      <div class="{{ $configData['contentsidebarClass'] }}">
        <div class="content-body">
          {{-- Include Page Content --}}
          @yield('content')
        </div>
      </div>
    </div>
  </div>
  <!-- End: Content-->

  <div class="sidenav-overlay"></div>
  <div class="drag-target"></div>

  {{-- include footer --}}
  @include('panels.footer')

  {{-- include default scripts --}}
  @include('panels.scripts')
</body>

</html>
