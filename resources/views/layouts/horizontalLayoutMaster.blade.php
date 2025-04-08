
<body class="horizontal-layout horizontal-menu {{$configData['horizontalMenuType']}} {{ $configData['showMenu'] === true ? '' : '1-column' }}
{{ $configData['blankPageClass'] }} {{ $configData['bodyClass'] }}
{{ $configData['footerType'] }}" data-menu="horizontal-menu" data-col="{{ $configData['showMenu'] === true ? '' : '1-column' }}" data-open="hover" data-layout="{{ ($configData['theme'] === 'light') ? '' : $configData['layoutTheme'] }}" style="{{ $configData['bodyStyle'] }}" data-framework="laravel" data-asset-path="{{ asset('/')}}">

  <!-- BEGIN: Header-->
  {{-- Include Navbar --}}
  @include('panels.navbar')

  {{-- Include Sidebar --}}
  @if((isset($configData['showMenu']) && $configData['showMenu'] === true))
  @include('panels.horizontalMenu')
  @endif

  <!-- BEGIN: Content-->
  <div class="app-content content {{ $configData['pageClass'] }}" style="background-image: linear-gradient(150deg, {{ config('app.PRIMARY_GRADIENT_COLOR') ?? '#0f70b7' }} 10%, {{ config('app.SECONDARY_GRADIENT_COLOR') ?? '#28a9e1' }} 100%) !important">
    <div class="content-overlay"></div>
    {{-- <div class="header-navbar-shadow"></div> --}}
    @if(($configData['contentLayout']!=='default') && isset($configData['contentLayout']))
    <div class="content-area-wrapper {{ $configData['layoutWidth'] === 'boxed' ? 'container-xxl p-0' : '' }}">
      <div class="{{ $configData['sidebarPositionClass'] }}">
        <div class="sidebar">
          {{-- Include Sidebar Content --}}
          @yield('content-sidebar')
        </div>
      </div>
      <div class="{{ $configData['contentsidebarClass'] }}">
        <div class="content-wrapper">
          <div class="content-body">
            {{-- Include Page Content --}}
            @yield('content')
          </div>
        </div>
      </div>
    </div>
    @else
    <div class="content-wrapper {{ $configData['layoutWidth'] === 'boxed' ? 'container-xxl p-0' : '' }}">
      {{-- Include Breadcrumb --}}
      @if($configData['pageHeader'] == true)
      @include('panels.breadcrumb')
      @endif

      <div class="content-body">

        {{-- Include Page Content --}}
        @yield('content')

      </div>
    </div>
    @endif

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
