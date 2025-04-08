{{-- For submenu --}}
<ul class="menu-content">
	@if(isset($menu))
		@foreach($menu as $submenu)
			@can(isset($submenu->permission) ? $submenu->permission : 'show menu item')
				<li class="{{ $submenu->slug === Route::currentRouteName() ? 'active' : '' }}">
					<a href="{{isset($submenu->route) ? route($submenu->route):'javascript:void(0)'}}" class="d-flex align-items-center" target="{{isset($submenu->newTab) && $submenu->newTab === true  ? '_blank':'_self'}}">
						@if(isset($submenu->icon))
						<i class="fas fa-{{ $submenu->icon }} text-center"></i>
						@endif
						<span class="menu-item text-truncate">{{ $submenu->name }}</span>
					</a>
					@if (isset($submenu->submenu))
						@include('panels/submenu', ['menu' => $submenu->submenu])
					@endif
				</li>
			@endcan
		@endforeach
	@endif
</ul>
