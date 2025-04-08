@php
$configData = Helper::applClasses();
@endphp
<div class="main-menu menu-fixed {{(($configData['theme'] === 'dark') || ($configData['theme'] === 'semi-dark')) ? 'menu-dark' : 'menu-light'}} menu-accordion menu-shadow" data-scroll-to-active="true">
	<div class="navbar-header">
		<ul class="nav navbar-nav flex-row">
			<li class="nav-item mr-auto">
				<a class="navbar-brand" href="{{ route('dashboard') }}">
					<span class="brand-logo">
						<img src="{{ asset('images/logo/logo.png') }}" class="img-fluid" alt="Brand logo">
					</span>
					<h2 class="brand-text">{{ config('app.name') }}</h2>
				</a>
			</li>
			<li class="nav-item nav-toggle">
				<a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse">
					<i class="fas fa-times d-block d-xl-none text-primary toggle-icon font-medium-4"></i>
					<i class="fas fa-fot-circle d-none d-xl-block collapse-toggle-icon font-medium-4 text-primary"></i>
				</a>
			</li>
		</ul>
	</div>
	<div class="shadow-bottom"></div>
	<div class="main-menu-content">
		<ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
			{{-- Foreach menu item starts --}}
			@if(isset($menuData[0]))
				@foreach($menuData[0]->menu as $menu)
					@if(isset($menu->navheader))
						<li class="navigation-header">
							<span>{{ __('locale.'.$menu->navheader) }}</span>
							<i class="fas fa-ellipsis-h"></i>
						</li>
					@else
						@can(isset($menu->permission) ? $menu->permission : 'show menu item')
							{{-- Add Custom Class with nav-item --}}
							@php
								$custom_classes = "";
								if(isset($menu->classlist)) {
									$custom_classes = $menu->classlist;
								}
							@endphp
							<li class="nav-item {{ Route::currentRouteName() === $menu->slug ? 'active' : '' }} {{ $custom_classes }}">
								<a href="{{ isset($menu->route)? route($menu->route):'javascript:void(0)' }}" class="d-flex align-items-center" target="{{ isset($menu->newTab) ? '_blank':'_self' }}">
									<i class="fas fa-{{ $menu->icon }} text-center"></i>
									<span class="menu-title text-truncate">{{ $menu->name }}</span>
									@if (isset($menu->badge))
										<?php $badgeClasses = "badge badge-pill badge-light-primary ml-auto mr-1" ?>
										<span class="{{ isset($menu->badgeClass) ? $menu->badgeClass : $badgeClasses }} ">{{$menu->badge}}</span>
									@endif
								</a>
								@if(isset($menu->submenu))
									@include('panels/submenu', ['menu' => $menu->submenu])
								@endif
							</li>
						@endcan
					@endif
				@endforeach
			@endif
			{{-- Foreach menu item ends --}}
		</ul>
	</div>
</div>
<!-- END: Main Menu-->
