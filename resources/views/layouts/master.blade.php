<!doctype html>
<html lang="en">

<head>
	<title>GIC Clients | @yield('title') </title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<!-- VENDOR CSS -->
	<link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('vendor/font-awesome/css/font-awesome.min.css') }}">
	<link rel="stylesheet" href="{{ asset('vendor/linearicons/style.css') }}">
	<link rel="stylesheet" href="{{ asset('vendor/chartist/css/chartist-custom.css') }}">
	<!-- MAIN CSS -->
	<link rel="stylesheet" href="{{ asset('css/main.css') }}">
	<!-- GOOGLE FONTS -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
	<!-- ICONS -->
	<link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/apple-icon.png') }}">
	<link rel="icon" type="image/png" sizes="96x96" href="{{ asset('img/favicon.png') }}">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	@yield('header_scripts')
</head>

<body>
	<!-- WRAPPER -->
	<div id="wrapper">

		@include('layouts.top-nav')

		<!-- LEFT SIDEBAR -->
		@include('layouts.left-nav')

		<!-- MAIN -->
		<div class="main">
			<!-- MAIN CONTENT -->
			<div class="main-content">
				@yield('content')
			</div>
			<!-- END MAIN CONTENT -->
		</div>
		<!-- END MAIN -->
		<div class="clearfix"></div>
		<footer>
			<div class="container-fluid">
				<p class="copyright">&copy; {{ Carbon\Carbon::now()->format('Y') }} GIC Clients. All Rights Reserved.</p>
			</div>
		</footer>
	</div>
	@yield('footer_scripts')
	<!-- END WRAPPER -->
	<!-- Javascript -->
	<!-- <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script> -->
	<script src="{{ asset('vendor/bootstrap/js/bootstrap.min.js') }}"></script>
	<script src="{{ asset('vendor/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
	<script src="{{ asset('vendor/jquery.easy-pie-chart/jquery.easypiechart.min.js') }}"></script>
	<script src="{{ asset('vendor/chartist/js/chartist.min.js') }}"></script>
	<script src="{{ asset('scripts/klorofil-common.js') }}"></script>

</body>
</html>
