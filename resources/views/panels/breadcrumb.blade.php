<div class="content-header">
	<div class="content-header-left mb-2 card">
		<div class="row breadcrumbs-top card-header">
			<div class="col-12">
				<h2 class="content-header-title float-left mb-0">@yield('title')</h2>
				<div class="breadcrumb-wrapper">
					@if(@isset($breadcrumbs))
					<ol class="breadcrumb">
							{{-- this will load breadcrumbs dynamically from controller --}}
							@foreach ($breadcrumbs as $breadcrumb)
							<li class="breadcrumb-item">
									@if(isset($breadcrumb['link']))
									<a href="{{ $breadcrumb['link'] == 'javascript:void(0)' ? $breadcrumb['link']:url($breadcrumb['link']) }}">
											@endif
											{{$breadcrumb['name']}}
											@if(isset($breadcrumb['link']))
									</a>
									@endif
							</li>
							@endforeach
					</ol>
					@endisset
				</div>
			</div>
		</div>
	</div>
	{{-- <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
		<div class="form-group breadcrumb-right">
			<div class="dropdown">
				<button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-border-all"></i></button>
				<div class="dropdown-menu dropdown-menu-right">
					<a class="dropdown-item" href="javascript:void(0)">
						<i class="fas fa-check-square mr-1"></i>
						<span class="align-middle">Todo</span>
					</a>
					<a class="dropdown-item" href="javascript:void(0)">
						<i class="fas fa-comments mr-1"></i>
						<span class="align-middle">Chat</span>
					</a>
					<a class="dropdown-item" href="javascript:void(0)">
						<i class="fas fa-envelope mr-1"></i>
						<span class="align-middle">Email</span>
					</a>
					<a class="dropdown-item" href="javascript:void(0)">
						<i class="fas fa-calendar mr-1"></i>
						<span class="align-middle">Calendar</span>
					</a>
				</div>
			</div>
		</div>
	</div> --}}
</div>
