<body class="vertical-layout vertical-menu-modern {{ $configData['showMenu'] === true ? '2-columns' : '1-column' }} {{$configData['blankPageClass']}} {{ $configData['bodyClass'] }}
{{$configData['verticalMenuNavbarType']}} {{ $configData['sidebarClass'] }} {{ $configData['footerType'] }}" data-layout="{{ ($configData['theme'] === 'light') ? '' : $configData['layoutTheme'] }}" data-menu="vertical-menu-modern" data-col="{{ $configData['showMenu'] === true ? '2-columns' : '1-column' }}" style="{{ $configData['bodyStyle'] }}" data-framework="laravel" data-asset-path="{{ asset('/')}}">

  {{-- Include Sidebar --}}
  @if((isset($configData['showMenu']) && $configData['showMenu'] === true)) @include('panels.sidebar') @endif

  {{-- Include Navbar --}}
  @include('panels.navbar')

  <!-- BEGIN: Content-->
  <div class="app-content content {{ $configData['pageClass'] }}" style="background-image: linear-gradient(150deg, {{ config('app.PRIMARY_GRADIENT_COLOR') ?? '#0f70b7' }} 10%, {{ config('app.SECONDARY_GRADIENT_COLOR') ?? '#28a9e1' }} 100%) !important">
    <!-- BEGIN: Header-->
    <div class="content-overlay"></div>
    {{-- <div class="header-navbar-shadow"></div> --}}

    <div class="content-wrapper {{ $configData['layoutWidth'] === 'boxed' ? 'container-xxl p-0' : '' }}">
      {{-- Include Breadcrumb --}}
      @if($configData['pageHeader'] == true) @include('panels.breadcrumb') @endif

      <div class="{{ $configData['contentsidebarClass'] }}">
        <div class="content-body">
          {{-- Include Page Content --}}
          @yield('content')
        </div>
      </div>

      <div class="{{ $configData['sidebarPositionClass'] }}">
        <div class="sidebar">
          {{-- Include Sidebar Content --}}
          @yield('content-sidebar')
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
