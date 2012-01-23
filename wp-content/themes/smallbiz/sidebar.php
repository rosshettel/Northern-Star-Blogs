	</div>
	
	<div id="sidebar">
		<ul>
			<?php 	/* Widgetized sidebar, if you have the plugin installed. */
					if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Default Sidebar') ) : ?>
			<li class="box">
				<h3>Connect With Us: </h3>
				
				<p class="center"><a href="#" target="_blank"><img src="http://members.expand2web.com/E2W-theme-images/TW_icon2.png" class="frame" alt="Expand2Web Twitter Feed"/></a></p>
				
				<p class="center"><a href="#" target="_blank"><img src="http://members.expand2web.com/E2W-theme-images/YT_icon2.png" class="frame" alt="Expand2Web YouTube Link"/></a></p>
				<p class="center"><a href="#" target="_blank"><img src="http://members.expand2web.com/E2W-theme-images/FB_icon2.png" class="frame" alt="Expand2Web Facebook Link"/></a></p>
				
				<p>This demo sidebar widget will disappear as soon as you add your own Widget(s)</p>
				<p>1) Log into WordPress</p>
				<p>2) "Appearance" -> "Widgets"</p>
				<p>3) Select a widget and drag it into the "Default Sidebar" area. </p>
					
					<p>The Contact, Find Us and Blog page have their own sidebar areas. Duplicate your "Default" widget(s) or add different widgets.</p>
					
					<p>Not sure what to put yet or want it empty - just add a text widget and leave it blank. </p>
			</li>
			<?php endif; ?>
		</ul>
	</div>
