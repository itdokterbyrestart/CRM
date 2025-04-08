<!-- BEGIN: Footer-->
<footer class="footer {{($configData['footerType']=== 'footer-hidden') ? 'd-none':''}} footer-light">
	<p class="clearfix mb-0">
		<span class="float-md-left d-block d-md-inline-block mt-25">Copyright &copy; {{ date('Y') }}<a class="ml-25" href="{{ config('app.url') }}" target="_blank">{{ config('app.name') }}</a>
		</span>
	</p>
</footer>
<button class="btn btn-primary btn-icon scroll-top" type="button"><i class="fas fa-arrow-up"></i></button>
<!-- END: Footer-->
