<!--begin::Logo-->
<div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
	<!--begin::Logo image-->
	<a href="{{ route('dashboard') }}">
		<div>
			<img alt="Logo" src="{{ image('logos/sig-long.png')  }}" class="h-25px app-sidebar-logo-default" />
		</div>
		<img alt="Logo" src="{{ image('logos/logo.png')  }}" class="h-30px app-sidebar-logo-minimize" />
	</a>

	<div id="kt_app_sidebar_toggle" class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary h-30px w-30px position-absolute top-50 start-100 translate-middle rotate" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="app-sidebar-minimize">{!! getIcon('black-left-line', 'fs-3 rotate-180 ms-1') !!}</div>
	<script type="text/javascript">
		let sidebar_toggle = document.getElementById("kt_app_sidebar_toggle");  // Get the sidebar toggle button element
		@if (isset($_COOKIE["sidebar_minimize_state"]) && $_COOKIE["sidebar_minimize_state"] === "on") 
			document.body.setAttribute("data-kt-app-sidebar-minimize", "on");  // Set the 'data-kt-app-sidebar-minimize' attribute for the body tag
			sidebar_toggle.setAttribute("data-kt-toggle-state", "active");  // Set the 'data-kt-toggle-state' attribute for the sidebar toggle button
			sidebar_toggle.classList.add("active");  // Add the 'active' class to the sidebar toggle button
		@endif
	</script>
	<!--end::Sidebar toggle-->
</div>
<!--end::Logo-->
