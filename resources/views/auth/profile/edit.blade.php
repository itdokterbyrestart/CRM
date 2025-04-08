@extends('layouts/contentLayoutMaster')

@section('title', 'Profiel aanpassen')

@section('vendor-style')
	{{-- Vendor Css files --}}
	<link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
	<link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection

@section('page-style')
	{{-- Page Css files --}}
	<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
	<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
	<link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-user.css')) }}">
@endsection

@section('content')
<!-- users edit start -->
<section class="app-user-edit">
	<div class="card">
		<div class="card-body">
			<ul class="nav nav-pills" role="tablist">
				<li class="nav-item">
					<a
						class="nav-link d-flex align-items-center active"
						id="account-tab"
						data-toggle="tab"
						href="#account"
						aria-controls="account"
						role="tab"
						aria-selected="true"
					>
						<i class="fas fa-user"></i><span class="d-none d-sm-block">Account</span>
					</a>
				</li>
			</ul>
			<div class="tab-content">
                <!-- users edit account form start -->
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        @error('name')
                            <div class="col-12">
                                {{ $message }}
                            </div>
                        @enderror
                        @error('email')
                            <div class="col-12">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Naam*</label>
                                <input
                                    type="text"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Name"
                                    value="{{ old('name', $user->name) }}"
                                    name="name"
                                    id="name"
                                    required
                                />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">E-mail*</label>
                                <input
                                    type="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="Email"
                                    value="{{ old('email', $user->email) }}"
                                    name="email"
                                    id="email"
                                    required
                                />
                            </div>
                        </div>
                        
                        @error('password')
                            <div class="col-12">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Nieuw wachtwoord</label>
                                <input
                                    type="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Wachtwoord"
                                    name="password"
                                    id="password"
                                />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirmation">Nieuw wachtwoord herhalen</label>
                                <input
                                    type="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Wachtwoord herhalen"
                                    name="password_confirmation"
                                    id="password_confirmation"
                                />
                            </div>
                        </div>
                        
                        <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                            <button type="submit" class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1">Opslaan</button>
                            <a class="btn btn-outline-secondary" href="{{ route('profile.show') }}">Annuleer</a>
                        </div>
                    </div>
                </form>
                <!-- users edit account form ends -->
			</div>
		</div>
	</div>
</section>
<!-- users edit ends -->
@endsection

@section('vendor-script')
	{{-- Vendor js files --}}
	<script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
	<script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
	<script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
@endsection

@section('page-script')
	{{-- Page js files --}}
	<script src="{{ asset(mix('js/scripts/pages/app-user-edit.js')) }}"></script>
	<script src="{{ asset(mix('js/scripts/components/components-navs.js')) }}"></script>
@endsection
