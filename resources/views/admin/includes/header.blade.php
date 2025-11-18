    <div class="brand clearfix">
    	<a href="{{ route('admin.dashboard') }}" style="font-size: 25px;">Car Rental Portal | Admin Panel</a>
		<span class="menu-btn"><i class="fa fa-bars"></i></span>
		<ul class="ts-profile-nav">
			
				<li class="ts-account">
					<a href="#"><img src="{{ asset('legacy/admin/img/ts-avatar.jpg') }}" class="ts-avatar hidden-side" alt=""> Account <i class="fa fa-angle-down hidden-side"></i></a>
					<ul>
						<li><a href="{{ route('admin.change-password') }}">Change Password</a></li>
						<li><a href="{{ route('admin.logout') }}">Logout</a></li>
					</ul>
				</li>
		</ul>
	</div>