@extends('frontend.master')
	@section('page')
    <div data-role="header" data-position="fixed">
        <h1>showroomr</h1>
        <a href="#left-panel" data-icon="flat-menu" data-role="button" data-iconpos="notext">Menu</a>
    </div><!-- /header -->
    <div data-role="content">
        @yield('content')
    </div><!-- /content -->
    <div data-role="panel" id="left-panel"  style="background-color:#C1392B;" data-display="push">
        <ul data-role="listview">
            <li data-icon="delete"><a href="#" data-rel="close">Sluit</a></li>
            <li data-role="list-divider">Menu</li>
            <li data-icon="home"><a href="{{ url('/') }}">Vestiging</a></li>
            <li data-icon="shop"><a data-ajax="false" href="{{ url('/products') }}">Producten</a></li>
            <li data-icon="star"><a  href="{{ url('/wishlist') }}">Verlanglijst</a></li>
            <li data-icon="location"><a  data-ajax="false" href="{{ url('/establishmentMap') }}">Plattegrond</a></li>
            <li data-icon="info"><a href="{{ url('/faq') }}">Helpdesk</a></li>
            <li data-icon="power"><a href="{{ url('/logout')}}"> Uitloggen</a></li>
        </ul>
    </div><!-- /panel -->
    @yield('footer')

    @endsection