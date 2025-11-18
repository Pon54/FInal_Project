<nav class="ts-sidebar">
	<ul class="ts-sidebar-menu">

		<li class="ts-label">MAIN</li>
		<li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>

		<li><a href="#"><i class="fa fa-tag"></i> Brands</a>
			<ul>
				<li><a href="{{ route('admin.brands.create') }}">Create Brand</a></li>
				<li><a href="{{ route('admin.brands.index') }}">Manage Brands</a></li>
			</ul>
		</li>

		<li><a href="#"><i class="fa fa-car"></i> Vehicles</a>
			<ul>
				<li><a href="{{ route('admin.vehicles.create') }}">Post a Vehicle</a></li>
				<li><a href="{{ route('admin.vehicles.index') }}">Manage Vehicles</a></li>
			</ul>
		</li>

		<li><a href="#"><i class="fa fa-calendar"></i> Bookings</a>
			<ul>
				<li><a href="{{ route('admin.bookings.index') }}?status=new">New</a></li>
				<li><a href="{{ route('admin.bookings.index') }}?status=confirmed">Confirmed</a></li>
				<li><a href="{{ route('admin.bookings.index') }}?status=canceled">Canceled</a></li>
			</ul>
		</li>

		<li><a href="{{ route('admin.testimonials.index') }}"><i class="fa fa-star"></i> Manage Testimonials</a></li>
		<li><a href="{{ route('admin.contactqueries.index') }}"><i class="fa fa-envelope"></i> Manage Contactus Query</a></li>
		<li><a href="{{ route('admin.users.index') }}"><i class="fa fa-users"></i> Reg Users</a></li>
		<li><a href="{{ route('admin.pages.index') }}"><i class="fa fa-file-text"></i> Manage Pages</a></li>
		<li><a href="{{ route('admin.contact-info.edit') }}"><i class="fa fa-cog"></i> Update Contact Info</a></li>

		<li><a href="{{ route('admin.subscribers.index') }}"><i class="fa fa-bell"></i> Manage Subscribers</a></li>

	</ul>
</nav>