@if(!empty($navs))
    <div class="span2" id="sidemenubar">
    	<button onclick="w3_close()" class="w3-bar-item w3-large">Close &times;</button>
	      <div class="well sidebar-nav">
			<ul class="nav nav-list">
				@foreach($navs as $nav)
			 	<li><a href="{{ $nav->link }}">{{ $nav->displayname }}</a></li>
			 	@endforeach
			</ul>
	      </div>
	    </div>
    <button class="w3-button w3-teal w3-xlarge" onclick="w3_open()">☰</button>
@endif
			  <!-- <li class="active"><a href="#">Link</a></li>
			  <li class="nav-header">Sidebar</li>
			  <li class="nav-header">Sidebar</li>
			  <li><a href="#">Link</a></li>
			  <li><a href="#">Link</a></li> -->