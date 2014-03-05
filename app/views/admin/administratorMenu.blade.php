@include('admin.customMenu', array('positioningAllowed' => $positioningAllowed))
<li class="dropdown">
    <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="glyphicon glyphicon-briefcase"></i> <span>Management</span></a></a>
    <ul class="dropdown-menu">
		@foreach ($menu as $k => $subitem)
				<?php echo View::make("administrator::partials.menu_item", array(
					'item' => $subitem,
					'key' => $k,
					'settingsPrefix' => $settingsPrefix,
					'pagePrefix' => $pagePrefix
				))?>
		@endforeach
    </ul>
</li>