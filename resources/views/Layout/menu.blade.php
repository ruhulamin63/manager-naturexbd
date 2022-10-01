<!-- BEGIN: Header-->
<div class="header-navbar-shadow"></div>
<nav class="header-navbar main-header-navbar navbar-expand-lg navbar navbar-with-menu fixed-top">
    <div class="navbar-wrapper">
        <div class="navbar-container content">
            <div class="navbar-collapse" id="navbar-mobile">
                <div class="mr-auto float-left bookmark-wrapper d-flex align-items-center">
                    <ul class="nav navbar-nav">
                        <li class="nav-item mobile-menu d-xl-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ficon bx bx-menu"></i></a></li>
                    </ul>
                    <ul class="nav navbar-nav bookmark-icons">
                        <li class="nav-item d-none d-lg-block">
                            <a class="nav-link" href="#" data-toggle="tooltip" data-placement="top" title="Email">
                                <i class="ficon bx bx-envelope"></i>
                            </a>
                        </li>
                        <li class="nav-item d-none d-lg-block">
                            <a class="nav-link" href="#" data-toggle="tooltip" data-placement="top" title="Chat">
                                <i class="ficon bx bx-chat"></i>
                            </a>
                        </li>
                        <li class="nav-item d-none d-lg-block">
                            <a class="nav-link" href="#" data-toggle="tooltip" data-placement="top" title="Todo">
                                <i class="ficon bx bx-check-circle"></i>
                            </a>
                        </li>
                        <li class="nav-item d-none d-lg-block">
                            <a class="nav-link" href="#" data-toggle="tooltip" data-placement="top" title="Calendar">
                                <i class="ficon bx bx-calendar-alt"></i>
                            </a>
                        </li>
                    </ul>
                </div>
                <ul class="nav navbar-nav float-right">
                    <li class="nav-item d-none d-lg-block">
                        <a class="nav-link nav-link-expand">
                            <i class="ficon bx bx-fullscreen nav-item-home-floating" data-toggle="tooltip" data-placement="top" title="Fullscreen"></i>
                        </a>
                    </li>
                    <li class="dropdown dropdown-user nav-item">
                        <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                            <div class="user-nav d-sm-flex d-none">
                                <span class="user-name">{{ session()->get('MANAGER_NAME') }}</span>
                                <span class="user-status text-muted">{{ session()->get('MANAGER_ROLE_TITLE') }}</span>
                            </div>
                            <span>
                                <img class="round" src="{{ url(session()->get('MANAGER_PHOTO')) }}" alt="avatar" height="40" width="40">
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right pb-0">
                            <a class="dropdown-item" href="#">
                                <i class="bx bx-user mr-50"></i> Edit Profile</a>
                            <a class="dropdown-item" href="#">
                                <i class="bx bx-envelope mr-50"></i> My Inbox</a>
                            <a class="dropdown-item" href="#">
                                <i class="bx bx-check-square mr-50"></i> Task</a>
                            <a class="dropdown-item" href="#">
                                <i class="bx bx-message mr-50"></i> Chats</a>
                            <div class="dropdown-divider mb-0"></div>
                            <a class="dropdown-item" href="{{ url('/dashboard/signout') }}">
                                <i class="bx bx-power-off mr-50"></i> Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<!-- END: Header-->

<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header mb-3">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto">
                <a class="navbar-brand" href="{{ url('/dashboard') }}">
                    <img src="{{ asset('/images/logo/logo.png') }}" width="100%" />
                </a>
            </li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content" style="padding-bottom: 100px;">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation" data-icon-style="lines">
            <li class="nav-item nav-hover @if(url('/dashboard') == Request::url()) active @endif">
                <a class="nav-hover" href="{{ url('/dashboard') }}">
                    <i class="bx bx-desktop mr-50"></i>
                    <span class="menu-title" data-i18n="Home">Home</span>
                </a>
            </li>
            <li class=" navigation-header"><span>Apps</span></li>
            <li class="nav-item @if($id == '1279') active @endif">
                <a class="nav-hover" href="{{ url('/dashboard/page?id=' . 1279) }}">
                    <i class="bx bxs-city mr-50"></i>
                    <span class="menu-title" data-i18n="City Manager">City Manager</span>
                </a>
            </li>
            <li class="nav-item @if($id == '3043') active @endif">
                <a class="nav-hover" href="{{ url('/dashboard/page?id=' . 3043) }}">
                    <i class="bx bxs-dish mr-50"></i>
                    <span class="menu-title" data-i18n="City Manager">Category Manager</span>
                </a>
            </li>
            <li class="nav-item has-sub @if(isset($cityID) && !isset($type)) open @endif">
                <a href="#">
                    <i class="bx bx-restaurant mr-50"></i>
                    <span class="menu-title" data-i18n="Restaurant Manager">Restaurant Manager</span>
                </a>
                <ul class="menu-content">
                    @foreach($cityList as $city)
                    <li class="@if(!isset($type) && isset($cityID) && $cityID == $city->city_id) active @endif">
                        <a href="{{ url('/dashboard/page?id=' . 2210) }}&city={{ $city->city_id }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="Basic">{{ $city->city_name }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </li>
            <li class="nav-item has-sub @if(isset($type) && $type == 'featured') open @endif">
                <a href="#">
                    <i class="bx bx-trending-up mr-50"></i>
                    <span class="menu-title" data-i18n="Restaurant Manager">Featured Restaurants</span>
                </a>
                <ul class="menu-content">
                    @foreach($cityList as $city)
                    <li class="@if(isset($type) && $cityID == $city->city_id) active @endif">
                        <a href="{{ url('/dashboard/page?id=' . 7580) }}&city={{ $city->city_id }}&type=featured">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="Basic">{{ $city->city_name }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </li>
            <li class="nav-item @if($id == '6709') active @endif">
                <a class="nav-hover" href="{{ url('/dashboard/page?id=' . 6709) }}">
                    <i class="bx bxs-bell-ring mr-50"></i>
                    <span class="menu-title" data-i18n="Notification Manager">Notification Manager</span>
                </a>
            </li>

            <li class=" navigation-header"><span>Report</span></li>
            <li class="nav-item @if($id == '7158') active @endif">
                <a class="nav-hover" href="{{ url('/dashboard/page?id=' . 7158) }}">
                    <i class="bx bxs-report mr-50"></i>
                    <span class="menu-title" data-i18n="Sales Report">Sales Report</span>
                </a>
            </li>

            <li class=" navigation-header"><span>User</span></li>
            <li class="nav-item @if($id == '4533') active @endif">
                <a class="nav-hover" href="{{ url('/dashboard/page?id=' . 4533) }}">
                    <i class="bx bxs-user-check mr-50"></i>
                    <span class="menu-title" data-i18n="User Manager">User Manager</span>
                </a>
            </li>
            <li class="nav-item @if($id == '9909') active @endif">
                <a class="nav-hover" href="{{ url('/dashboard/page?id=' . 9909) }}">
                    <i class="bx bx-group mr-50"></i>
                    <span class="menu-title" data-i18n="Old User Manager">Old User Manager</span>
                </a>
            </li>

            <li class=" navigation-header"><span>Rider</span></li>
            <!-- <li class="nav-item @if($id == '4400') active @endif">
                <a class="nav-hover" href="{{ url('/dashboard/page?id=' . 4400) }}">
                    <i class="bx bx-bicycle mr-50"></i>
                    <span class="menu-title" data-i18n="Rider Manager">Rider Manager</span>
                </a>
            </li> -->
            <li class="nav-item @if($id == '2970') active @endif">
                <a class="nav-hover" href="{{ url('/dashboard/page?id=' . 2970) }}">
                    <i class="bx bxs-bell-ring mr-50"></i>
                    <span class="menu-title" data-i18n="Rider Manager">Rider Notification</span>
                </a>
            </li>

            <li class=" navigation-header"><span>Tools</span></li>
            <li class="nav-item @if($id == '5865') active @endif">
                <a class="nav-hover" href="{{ url('/dashboard/page?id=' . 5865) }}">
                    <i class="bx bxs-conversation mr-50"></i>
                    <span class="menu-title" data-i18n="Send SMS">Send SMS</span>
                </a>
            </li>

            <li class=" navigation-header"><span>Settings</span></li>
            <li class="nav-item @if($id == '7531') active @endif">
                <a class="nav-hover" href="{{ url('/dashboard/page?id=' . 7531) }}">
                    <i class="bx bx-receipt mr-50"></i>
                    <span class="menu-title" data-i18n="Page Manager">Page Manager</span>
                </a>
            </li>
            <li class="nav-item @if($id == '8246') active @endif">
                <a class="nav-hover" href="{{ url('/dashboard/page?id=' . 8246) }}">
                    <i class="bx bx-lock-alt mr-50"></i>
                    <span class="menu-title" data-i18n="Access Control">Access Control</span>
                </a>
            </li>
            <li class="nav-item @if($id == '3438') active @endif">
                <a class="nav-hover" href="{{ url('/dashboard/page?id=' . 3438) }}">
                    <i class="bx bx-key mr-50"></i>
                    <span class="menu-title" data-i18n="API Manager">API Manager</span>
                </a>
            </li>
            <li class=" navigation-header"><span>Authentication</span></li>
            <li class="nav-item @if($id == '5175') active @endif">
                <a class="nav-hover" href="{{ url('/dashboard/page?id=' . 5175) }}">
                    <i class="bx bxs-key mr-50"></i>
                    <span class="menu-title" data-i18n="Change Password">Change Password</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-hover" href="{{ url('/dashboard/signout') }}">
                    <i class="bx bx-log-out-circle mr-50"></i>
                    <span class="menu-title" data-i18n="Logout">Logout</span>
                </a>
            </li>
        </ul>
    </div>
</div>
<!-- END: Main Menu-->
