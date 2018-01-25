@if(!empty($navs))
	<button type="button" id="sidebarCollapse" class="btn btn-info navbar-btn">
        <i class="glyphicon glyphicon-align-left"></i>
        Toggle Sidebar
    </button>
    <div class="span2" id="sidemenubar">
      <div class="well sidebar-nav">
		<ul class="nav nav-list">
			@foreach($navs as $nav)
		 	<li><a href="{{ $nav->link }}">{{ $nav->displayname }}</a></li>
		 	@endforeach
		</ul>
      </div>
    </div>
@endif
			  <!-- <li class="active"><a href="#">Link</a></li>
			  <li class="nav-header">Sidebar</li>
			  <li class="nav-header">Sidebar</li>
			  <li><a href="#">Link</a></li>
			  <li><a href="#">Link</a></li> -->