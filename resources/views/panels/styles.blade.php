<link rel="stylesheet" href="{{ asset(mix('vendors/css/vendors.min.css')) }}" />
<link rel="stylesheet" href="{{ asset(mix('vendors/css/ui/prism.min.css')) }}" />
<link rel="stylesheet" href="{{ asset(mix('fonts/font-awesome/css/font-awesome.min.css')) }}" />
{{-- Vendor Styles --}}
@yield('vendor-style')
{{-- Theme Styles --}}

<link rel="stylesheet" href="{{ asset(mix('css/core.css')) }}" />

{{-- {!! Helper::applClasses() !!} --}}
@php $configData = Helper::applClasses(); @endphp

{{-- Page Styles --}}
@if($configData['mainLayoutType'] === 'horizontal')
<link rel="stylesheet" href="{{ asset(mix('css/base/core/menu/menu-types/horizontal-menu.css')) }}" />
@endif
<link rel="stylesheet" href="{{ asset(mix('css/base/core/menu/menu-types/vertical-menu.css')) }}" />
<!-- <link rel="stylesheet" href="{{ asset(mix('css/base/core/colors/palette-gradient.css')) }}"> -->
@livewireStyles

{{-- Page Styles --}}
@yield('page-style')

{{-- Laravel Style --}}
<link rel="stylesheet" href="{{ asset(mix('css/overrides.css')) }}" />

{{-- Custom RTL Styles --}}

@if($configData['direction'] === 'rtl' && isset($configData['direction']))
<link rel="stylesheet" href="{{ asset(mix('css-rtl/custom-rtl.css')) }}" />
<link rel="stylesheet" href="{{ asset(mix('css-rtl/style-rtl.css')) }}" />
@endif

{{-- user custom styles --}}
<link rel="stylesheet" href="{{ asset(mix('css/style.css')) }}" />

{{-- Vendor styles --}}
<link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">