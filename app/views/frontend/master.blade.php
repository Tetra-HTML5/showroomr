<!DOCTYPE HTML>
<html>
<head>

	<meta charset="UTF-8">
	<title>Showroomr</title>
	<meta name="viewport" content="initial-scale=1, maximum-scale=1">
	<link rel="stylesheet" href="{{ asset('assets/jquery-mobile/themes/flat-ui/css/jquery.mobile.flatui.css') }}" />
	<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
	<script type="text/javascript" src="{{ asset('assets/jquery-mobile/themes/flat-ui/js/jquery.js')}}"></script>
	<script type="text/javascript">
	$(document).bind("mobileinit", function(){
		$.mobile.ajaxEnabled = false;
	});
	</script>
	<script type="text/javascript" src="{{ asset('assets/jquery-mobile/themes/flat-ui/js/jquery.mobile-1.4.0.js')}}"></script>
	@yield('head')
</head>
{{-- Base URL contains the URL to root. This is usefull for some external JS files --}}
<baseurl>{{ url('/') }}</baseurl>
<body>
	<div data-role="page" data-theme="d">
    	@yield('page')
	</div>
</body>
</html>
