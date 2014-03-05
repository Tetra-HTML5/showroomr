@if ($positioningAllowed)
<li class="dropdown">
    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
    	<span class="glyphicon glyphicon-map-marker"></span> Positionering
    </a>
    <ul class="dropdown-menu">
    	<li><a href="{{ url('admin/positioning/grid') }}">Raster</a></li>
    	<li><a href="{{ url('admin/positioning/products') }}">Producten</a></li>
    </ul>
</li>
@endif