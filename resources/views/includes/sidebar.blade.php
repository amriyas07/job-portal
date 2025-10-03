<aside class="sidebar" id="sidebar">
    <ul class="sidebar-menu">
        <li>
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
        </li>
        @role('employer')
            <li>
                <a href="{{ route('employer.jobs') }}" class="{{ request()->routeIs('employer.jobs') ? 'active' : '' }}">
                    <i class="fas fa-briefcase"></i>
                    <span>Jobs</span>
                </a>
            </li>
        @endrole
        @role('employee')
            <li>
                <a href="{{ route('jobseeker.jobs.page') }}" class="{{ request()->routeIs('jobseeker.jobs.page') ? 'active' : '' }}">
                    <i class="fas fa-briefcase"></i>
                    <span>Apply Jobs</span>
                </a>
            </li>
        @endrole
        @role('employee')
            <li>
                <a href="{{ route('jobseeker.applications.view') }}" class="{{ request()->routeIs('jobseeker.applications.view') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i>
                    <span>Applied Jobs</span>
                </a>
            </li>
        @endrole

        @role('employer')
            <li>
                <a href="{{ route('employer.profiles') }}" class="{{ request()->routeIs('employer.profiles') ? 'active' : '' }}">
                    <i class="fas fa-user"></i>
                    <span>Profile</span>
                </a>
            </li>
        @endrole
        {{-- <li>
            <a href="#">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
        </li> --}}
    </ul>
</aside>
