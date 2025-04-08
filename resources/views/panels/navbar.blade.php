@if($configData["mainLayoutType"] === 'horizontal' && isset($configData["mainLayoutType"]))
<nav class="header-navbar navbar-expand-lg navbar navbar-fixed align-items-center navbar-shadow navbar-brand-center {{ $configData['navbarColor'] }}" data-nav="brand-center">
	<div class="navbar-header d-xl-block d-none">
		<ul class="nav navbar-nav">
			<li class="nav-item">
				<a class="navbar-brand" href="{{url('/')}}">
					<span class="brand-logo">
						<img src="{{ asset('images/logo/logo.png') }}" class="img-fluid" alt="Brand logo">
					</span>
					<h2 class="brand-text mb-0">{{ config('app.name') }}</h2>
				</a>
			</li>
		</ul>
	</div>
	@else
	<nav class="header-navbar navbar navbar-expand-lg align-items-center {{ $configData['navbarClass'] }} navbar-light navbar-shadow {{ $configData['navbarColor'] }} {{ ($configData['layoutWidth'] === 'boxed' && $configData['verticalMenuNavbarType']  === 'navbar-floating') ? 'container-xxl p-0' : '' }}">
		@endif
		<div class="navbar-container d-flex content">
			<div class="bookmark-wrapper d-flex align-items-center">
				<ul class="nav navbar-nav d-xl-none">
					<li class="nav-item"><a class="nav-link menu-toggle" href="javascript:void(0);"><i class="ficon fas fa-bars"></i></a></li>
				</ul>
				<ul class="nav navbar-nav">
					<li class="nav-item d-none d-lg-block">
						<a class="nav-link nav-link-style">
							<i class="ficon fas fa-{{($configData['theme'] === 'dark') ? 'sun' : 'moon' }}"></i>
						</a>
					</li>
				</ul>
			</div>
			<ul class="nav navbar-nav align-items-center ml-auto">
				<li class="nav-item dropdown dropdown-user">
					<a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<div class="user-nav d-sm-flex d-none">
							<span class="user-name font-weight-bolder">{{ Auth::user()->name }}</span>
							<span class="user-status">{{ config('app.name') }}</span>
						</div>
						<span class="avatar">
							<img class="round" src="{{ asset('images/logo/logo.png') }}" alt="avatar" height="40" width="40">
							<span class="avatar-status-online"></span>
						</span>
					</a>
					<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-user">
						<a class="dropdown-item" href="{{ route('profile.show') }}">
							<i class="mr-50 fas fa-user"></i> Profiel
						</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
							<i class="mr-50 fas fa-power-off"></i> Uitloggen
						</a>
						<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
							{{ csrf_field() }}
						</form>
					</div>
				</li>
			</ul>
		</div>
	</nav>

	{{-- Search Start Here --}}
	<ul class="main-search-list-defaultlist d-none">
		<li class="d-flex align-items-center">
			<a href="javascript:void(0);">
				<h6 class="section-label mt-75 mb-0">Files</h6>
			</a>
		</li>
		<li class="auto-suggestion">
			<a class="d-flex align-items-center justify-content-between w-100" href="{{url('app/file-manager')}}">
				<div class="d-flex">
					<div class="mr-75">
						<img src="{{asset('images/icons/xls.png')}}" alt="png" height="32">
					</div>
					<div class="search-data">
						<p class="search-data-title mb-0">Two new item submitted</p>
						<small class="text-muted">Marketing Manager</small>
					</div>
				</div>
				<small class="search-data-size mr-50 text-muted">&apos;17kb</small>
			</a>
		</li>
		<li class="auto-suggestion">
			<a class="d-flex align-items-center justify-content-between w-100" href="{{url('app/file-manager')}}">
				<div class="d-flex">
					<div class="mr-75">
						<img src="{{asset('images/icons/jpg.png')}}" alt="png" height="32">
					</div>
					<div class="search-data">
						<p class="search-data-title mb-0">52 JPG file Generated</p>
						<small class="text-muted">FontEnd Developer</small>
					</div>
				</div>
				<small class="search-data-size mr-50 text-muted">&apos;11kb</small>
			</a>
		</li>
		<li class="auto-suggestion">
			<a class="d-flex align-items-center justify-content-between w-100" href="{{url('app/file-manager')}}">
				<div class="d-flex">
					<div class="mr-75">
						<img src="{{asset('images/icons/pdf.png')}}" alt="png" height="32">
					</div>
					<div class="search-data">
						<p class="search-data-title mb-0">25 PDF File Uploaded</p>
						<small class="text-muted">Digital Marketing Manager</small>
					</div>
				</div>
				<small class="search-data-size mr-50 text-muted">&apos;150kb</small>
			</a>
		</li>
		<li class="auto-suggestion">
			<a class="d-flex align-items-center justify-content-between w-100" href="{{url('app/file-manager')}}">
				<div class="d-flex">
					<div class="mr-75">
						<img src="{{asset('images/icons/doc.png')}}" alt="png" height="32"></div>
					<div class="search-data">
						<p class="search-data-title mb-0">Anna_Strong.doc</p>
						<small class="text-muted">Web Designer</small>
					</div>
				</div>
				<small class="search-data-size mr-50 text-muted">&apos;256kb</small>
			</a>
		</li>
		<li class="d-flex align-items-center">
			<a href="javascript:void(0);">
				<h6 class="section-label mt-75 mb-0">Members</h6>
			</a>
		</li>
		<li class="auto-suggestion">
			<a class="d-flex align-items-center justify-content-between py-50 w-100" href="{{url('app/user/view')}}">
				<div class="d-flex align-items-center">
					<div class="avatar mr-75">
						<img src="{{asset('images/portrait/small/avatar-s-8.jpg')}}" alt="png" height="32">
					</div>
					<div class="search-data">
						<p class="search-data-title mb-0">John Doe</p>
						<small class="text-muted">UI designer</small>
					</div>
				</div>
			</a>
		</li>
		<li class="auto-suggestion">
			<a class="d-flex align-items-center justify-content-between py-50 w-100" href="{{url('app/user/view')}}">
				<div class="d-flex align-items-center">
					<div class="avatar mr-75">
						<img src="{{asset('images/portrait/small/avatar-s-1.jpg')}}" alt="png" height="32">
					</div>
					<div class="search-data">
						<p class="search-data-title mb-0">Michal Clark</p>
						<small class="text-muted">FontEnd Developer</small>
					</div>
				</div>
			</a>
		</li>
		<li class="auto-suggestion">
			<a class="d-flex align-items-center justify-content-between py-50 w-100" href="{{url('app/user/view')}}">
				<div class="d-flex align-items-center">
					<div class="avatar mr-75">
						<img src="{{asset('images/portrait/small/avatar-s-14.jpg')}}" alt="png" height="32">
					</div>
					<div class="search-data">
						<p class="search-data-title mb-0">Milena Gibson</p>
						<small class="text-muted">Digital Marketing Manager</small>
					</div>
				</div>
			</a>
		</li>
		<li class="auto-suggestion">
			<a class="d-flex align-items-center justify-content-between py-50 w-100" href="{{url('app/user/view')}}">
				<div class="d-flex align-items-center">
					<div class="avatar mr-75">
						<img src="{{asset('images/portrait/small/avatar-s-6.jpg')}}" alt="png" height="32">
					</div>
					<div class="search-data">
						<p class="search-data-title mb-0">Anna Strong</p>
						<small class="text-muted">Web Designer</small>
					</div>
				</div>
			</a>
		</li>
	</ul>

	{{-- if main search not found! --}}
	<ul class="main-search-list-defaultlist-other-list d-none">
		<li class="auto-suggestion justify-content-between">
			<a class="d-flex align-items-center justify-content-between w-100 py-50">
				<div class="d-flex justify-content-start">
					<span class="mr-75 fas fa-exclamation-circle"></span>
					<span>No results found.</span>
				</div>
			</a>
		</li>
	</ul>
	{{-- Search Ends --}}
	<!-- END: Header-->
