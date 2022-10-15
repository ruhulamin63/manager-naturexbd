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
                                <span class="user-name">{{ session()->get('GR_MANAGER_NAME') }}</span>
                                <span class="user-status text-muted">{{ session()->get('GR_MANAGER_EMAIL') }}</span>
                            </div>
                            <span>
                                <img class="round" src="{{ url('/images/avatar/avatar.png') }}" alt="avatar" height="40" width="40">
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
                            <a class="dropdown-item" href="{{ url('/grocery/dashboard/signout') }}">
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
                <a class="navbar-brand" href="{{ url('/grocery/dashboard') }}">
                    <img src="{{ asset('/images/logo/logo.png') }}" width="100%" />
                </a>
            </li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content" style="padding-bottom: 100px;">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation" data-icon-style="lines">
            @php
            $permission = \App\Models\Grocery\Admin::select('*')->where('email', session()->get('GR_MANAGER_EMAIL'))->get();
            $permission = $permission[0];
            @endphp
            @if(strpos($permission, 'home') !== false)
            <li class="nav-item @if(url('/grocery/dashboard') == Request::url()) active @endif">
                <a class="nav-hover" href="{{ url('/grocery/dashboard') }}">
                    <i class="bx bx-desktop mr-50"></i>
                    <span class="menu-title">Dashboard</span>
                </a>
            </li>
            @endif

{{--            Start Blog Menu--}}
{{--            @if(strpos($permission, 'product') !== false)--}}
                <li class="nav-item has-sub">
                    <a href="#">
                        <i class="bx bxs-package mr-50"></i>
                        <span class="menu-title" data-i18n="Category Manager">Blog Menu</span>
                    </a>
                    <ul class="menu-content">
                        <li class="nav-item @if(url('/blog/show') == Request::url()) active @endif">
                            <a class="nav-hover" href="{{ url('/blog/show')}}">
                                <i class="bx bxs-navigation mr-50"></i>
                                <span class="menu-title">Show Blog</span>
                            </a>
                        </li>

                        <li class="nav-item @if(url('/blog/create') == Request::url()) active @endif">
                            <a class="nav-hover" href="{{ url('/blog/create') }}">
                                <i class="bx bx-add-to-queue mr-50"></i>
                                <span class="menu-title">Create Blog</span>
                            </a>
                        </li>
                    </ul>
                </li>
{{--            @endif--}}
{{--            End Blog Menu--}}

            <!-- Restaurant Menu Start -->
            <li class=" navigation-header"><span>Restaurant Menu</span></li>
            <li class="nav-item has-sub">
                <a href="#">
                    <i class="bx bx-restaurant mr-50"></i>
                    <span class="menu-title" data-i18n="Category Manager">Restaurant</span>
                </a>
                <ul class="menu-content">
                    <li class="nav-item has-sub">
                        <a class="nav-hover" href="!#">
                            <i class="bx bx-restaurant mr-50"></i>
                            <span class="menu-title">Manage Restaurant</span>
                        </a>
                        <ul class="menu-content">

                            <li class="nav-item @if(url('/restaurant/addRestaurant') == Request::url()) active @endif">
                                <a class="nav-hover" href="{{ url('/restaurant/addRestaurant') }}">
                                    <i class="bx bx-add-to-queue mr-50"></i>
                                    <span class="menu-title">Add Restaurant</span>
                                </a>
                            </li>
                            <li class="nav-item @if(url('/restaurant/RestaurantList') == Request::url()) active @endif">
                                <a class="nav-hover" href="{{ url('/restaurant/RestaurantList')}}">
                                    <i class="bx bxs-package mr-50"></i>
                                    <span class="menu-title">Restaurant List</span>
                                </a>
                            </li>
                            <!-- <li class="nav-item @if(url('/restaurant/addBranch') == Request::url()) active @endif">
                                <a class="nav-hover" href="{{ url('/restaurant/addBranch')}}">
                                    <i class="bx bxs-package mr-50"></i>
                                    <span class="menu-title">Branch Manage</span>
                                </a>
                            </li> -->
                        </ul>
                    </li>
                    <li class=" navigation-header"><span>Category</span></li>
                    <li class="nav-item">
                        <a class="nav-hover" href="#">
                            <i class="bx bxs-package mr-50"></i>
                            <span class="menu-title">Manage Menu/Category</span>
                        </a>
                        <ul class="menu-content">
                            <li class="nav-item @if(url('/restaurant/ProductCategory') == Request::url()) active @endif">
                                <a class="nav-hover" href="{{ url('/restaurant/ProductCategory')}}">
                                    <i class="bx bxs-package mr-50"></i>
                                    <span class="menu-title">Product Menu</span>
                                </a>
                            </li>
                            <li class="nav-item @if(url('/restaurant/RestaurantCategory') == Request::url()) active @endif">
                                <a class="nav-hover" href="{{ url('/restaurant/RestaurantCategory')}}">
                                    <i class="bx bxs-package mr-50"></i>
                                    <span class="menu-title">Restaurant Category</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class=" navigation-header"><span>Product</span></li>
                    <li class="nav-item">
                        <a class="nav-hover" href="#">
                            <i class="bx bxs-package mr-50"></i>
                            <span class="menu-title">Manage Product</span>
                        </a>
                        <ul class="menu-content">
                            <li class="nav-item @if(url('/restaurant/addProduct') == Request::url()) active @endif">
                                <a class="nav-hover" href="{{ url('/restaurant/addProduct')}}">
                                    <i class="bx bx-add-to-queue mr-50"></i>
                                    <span class="menu-title">Add Product</span>
                                </a>
                            </li>
                            <li class="nav-item @if(url('/restaurant/allProduct') == Request::url()) active @endif">
                                <a class="nav-hover" href="{{ url('/restaurant/allProduct')}}">
                                    <i class="bx bxs-package mr-50"></i>
                                    <span class="menu-title">All Product</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- restaurant pormo start -->
                    <li class=" navigation-header"><span>Promo</span></li>

                    <li class="nav-item @if(url('/restaurant/addPromo') == Request::url()) active @endif">
                        <a class="nav-hover" href="{{ url('/restaurant/addPromo') }}">
                            <i class="bx bx-planet mr-50"></i>
                            <span class="menu-title" data-i18n="City Manager">Add Promo</span>
                        </a>
                    </li>

                    <li class="nav-item @if(url('/restaurant/managePromo') == Request::url()) active @endif">
                        <a class="nav-hover" href="{{ url('/restaurant/managePromo') }}">
                            <i class="bx bx-planet mr-50"></i>
                            <span class="menu-title" data-i18n="City Manager">Manage Promo</span>
                        </a>
                    </li>
                    <!-- Promo end-->

                    <li class=" navigation-header"><span>Order</span></li>
                    <li class="nav-item has-sub">
                        <a href="#">
                            <i class="bx bx-receipt mr-50"></i>
                            <span class="menu-title" data-i18n="Order Manager">Manage Order</span>
                        </a>
                        <ul class="menu-content">
                            <li class="nav-item @if(url('/restaurant/order') == Request::url())) active @endif">

                            </li>
                            @foreach($cityList as $city)
                            <li class="nav-item @if(isset($cityID) && $cityID == $city->id && (url('/restaurant/order') == Request::url())) active @endif">
                                <a class="nav-hover" href="{{ url('/restaurant/order') . '?city=' . $city->id }}">
                                    <i class="bx bxs-navigation mr-50"></i>
                                    <span class="menu-title">{{ $city->city_name }}</span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                </ul>
            </li>

            <!-- Restaurant Menu END -->


            @if(strpos($permission, 'city') !== false || strpos($permission, 'area_coverage') !== false)
            <li class=" navigation-header"><span>Services</span></li>
            @endif
            @if(strpos($permission, 'city') !== false)
            <li class="nav-item @if(url('/grocery/city') == Request::url()) active @endif">
                <a class="nav-hover" href="{{ url('/grocery/city') }}">
                    <i class="bx bxs-city mr-50"></i>
                    <span class="menu-title" data-i18n="City Manager">City Manager</span>
                </a>
            </li>
            @endif
            @if(strpos($permission, 'area_coverage') !== false)
            <li class="nav-item has-sub">
                <a href="#">
                    <i class="bx bxs-city mr-50"></i>
                    <span class="menu-title" data-i18n="Area Coverage">Area Coverage</span>
                </a>
                <ul class="menu-content">
                    @foreach($cityList as $city)
                    @if(strpos(strtoupper($permission), strtoupper($city->city_name)) !== false)
                    <li class="nav-item @if(isset($cityID) && $cityID == $city->id && (url('/grocery/city/area-coverage') == Request::url())) active @endif">
                        <a class="nav-hover" href="{{ url('/grocery/city/area-coverage') . '?city=' . $city->id }}">
                            <i class="bx bxs-navigation mr-50"></i>
                            <span class="menu-title">{{ $city->city_name }}</span>
                        </a>
                    </li>
                    @endif
                    @endforeach
                </ul>
            </li>
            @endif

            @if(strpos($permission, 'category') !== false || strpos($permission, 'product') !== false)
            <li class=" navigation-header"><span>Inventory</span></li>
            @endif
            @if(strpos($permission, 'category') !== false)
            <li class="nav-item has-sub">
                <a href="#">
                    <i class="bx bx-restaurant mr-50"></i>
                    <span class="menu-title" data-i18n="Category Manager">Category Manager</span>
                </a>
                <ul class="menu-content">
                    <li class="nav-item @if(url('/grocery/category/add') == Request::url()) active @endif">
                        <a class="nav-hover" href="{{ url('/grocery/category/add') }}">
                            <i class="bx bx-add-to-queue mr-50"></i>
                            <span class="menu-title">Add New Category</span>
                        </a>
                    </li>
                    @foreach($cityList as $city)
                    @if(strpos(strtoupper($permission), strtoupper($city->city_name)) !== false)
                    <li class="nav-item @if(isset($cityID) && $cityID == $city->id && (url('/grocery/category') == Request::url())) active @endif">
                        <a class="nav-hover" href="{{ url('/grocery/category') . '?city=' . $city->id }}">
                            <i class="bx bxs-navigation mr-50"></i>
                            <span class="menu-title">{{ $city->city_name }}</span>
                        </a>
                    </li>
                    @endif
                    @endforeach
                </ul>
            </li>
            @endif

            @if(strpos($permission, 'product') !== false)
            <li class="nav-item has-sub">
                <a href="#">
                    <i class="bx bxs-package mr-50"></i>
                    <span class="menu-title" data-i18n="Category Manager">Product Manager</span>
                </a>
                <ul class="menu-content">
                    <li class="nav-item @if(isset($cityID) && $cityID == $city->id && (url('/grocery/products') == Request::url())) active @endif">
                        <a class="nav-hover" href="{{ url('/grocery/products') . '?city=' . $city->id }}">
                            <i class="bx bxs-navigation mr-50"></i>
                            <span class="menu-title">All Products</span>
                        </a>
                    </li>

                    {{--                    @foreach($cityList as $city)--}}
                    {{--                        @if(strpos(strtoupper($permission), strtoupper($city->city_name)) !== false)--}}
                    {{--                        <li class="nav-item @if(isset($cityID) && $cityID == $city->id && (url('/grocery/products') == Request::url())) active @endif">--}}
                    {{--                            <a class="nav-hover" href="{{ url('/grocery/products') . '?city=' . $city->id }}">--}}
                    {{--                                <i class="bx bxs-navigation mr-50"></i>--}}
                    {{--                                <span class="menu-title">{{ $city->city_name }}</span>--}}
                    {{--                            </a>--}}
                    {{--                        </li>--}}
                    {{--                        @endif--}}
                    {{--                    @endforeach--}}


                    <li class="nav-item @if(url('/grocery/products/add') == Request::url()) active @endif">
                        <a class="nav-hover" href="{{ url('/grocery/products/add') }}">
                            <i class="bx bx-add-to-queue mr-50"></i>
                            <span class="menu-title">Add New Product</span>
                        </a>
                    </li>
                    <li class="nav-item @if(url('/grocery/SeasonalProduct/addSeasonalCampain') == Request::url()) active @endif">
                        <a class="nav-hover" href="{{ url('/grocery/SeasonalProduct/addSeasonalCampain') }}">
                            <i class="bx bx-add-to-queue mr-50"></i>
                            <span class="menu-title">Seasional Campain</span>
                        </a>
                    </li>
                    <li class="nav-item @if(url('/grocery/manageSeasonalCampain') == Request::url()) active @endif">
                        <a class="nav-hover" href="{{ url('/grocery/manageSeasonalCampain') }}">
                            <i class="bx bx-add-to-queue mr-50"></i>
                            <span class="menu-title">Manage Seasional Campain</span>
                        </a>
                    </li>
                    <li class="nav-item @if(url('/grocery/SeasonalProduct/add') == Request::url()) active @endif">
                        <a class="nav-hover" href="{{ url('/grocery/SeasonalProduct/add') }}">
                            <i class="bx bx-add-to-queue mr-50"></i>
                            <span class="menu-title">Add Seasional Product</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endif

            @if(strpos($permission, 'product_analysis') !== false)
            <li class="nav-item has-sub">
                <a href="#">
                    <i class="bx bxs-bar-chart-alt-2 mr-50"></i>
                    <span class="menu-title" data-i18n="Category Manager">Product Analysis</span>
                </a>
                <ul class="menu-content">
                    @foreach($cityList as $city)
                    @if(strpos(strtoupper($permission), strtoupper($city->city_name)) !== false)
                    <li class="nav-item @if(isset($cityID) && $cityID == $city->id && (url('/grocery/products/sale') == Request::url())) active @endif">
                        <a class="nav-hover" href="{{ url('/grocery/products/sale') . '?city=' . $city->id }}">
                            <i class="bx bxs-navigation mr-50"></i>
                            <span class="menu-title">{{ $city->city_name }}</span>
                        </a>
                    </li>
                    @endif
                    @endforeach
                </ul>
            </li>
            @endif
            <li class=" navigation-header"><span>Banners</span></li>
            <li class="nav-item has-sub">
                <a href="#">
                    <i class="bx bxs-package mr-50"></i>
                    <span class="menu-title" data-i18n="Category Manager">Marketing Banner</span>
                </a>
                <ul class="menu-content">
                    <li class="nav-item @if(url('/grocery/marketing-banner/add') == Request::url()) active @endif">
                        <a class="nav-hover" href="{{ url('/grocery/marketing-banner/add') }}">
                            <i class="bx bx-add-to-queue mr-50"></i>
                            <span class="menu-title">Add Marketing Banner</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item has-sub">
                <a href="#">
                    <i class="bx bxs-package mr-50"></i>
                    <span class="menu-title" data-i18n="Category Manager">HomePage Banner</span>
                </a>
                <ul class="menu-content">
                    <li class="nav-item @if(url('/grocery/homepage-banner/add') == Request::url()) active @endif">
                        <a class="nav-hover" href="{{ url('/grocery/homepage-banner/add') }}">
                            <i class="bx bx-add-to-queue mr-50"></i>
                            <span class="menu-title">Add Homepage Banner</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- pormo start -->
            <li class=" navigation-header"><span>Promo</span></li>

            <li class="nav-item @if(url('/grocery/addPromo') == Request::url()) active @endif">
                <a class="nav-hover" href="{{ url('/grocery/addPromo') }}">
                    <i class="bx bx-planet mr-50"></i>
                    <span class="menu-title" data-i18n="City Manager">Add Promo</span>
                </a>
            </li>

            <li class="nav-item @if(url('/grocery/managePromo') == Request::url()) active @endif">
                <a class="nav-hover" href="{{ url('/grocery/managePromo') }}">
                    <i class="bx bx-planet mr-50"></i>
                    <span class="menu-title" data-i18n="City Manager">Manage Promo</span>
                </a>
            </li>

            <!-- Promo end-->

            <li class=" navigation-header"><span>Invoice Image</span></li>

            <li class="nav-item @if(url('/grocery/invoiceManage') == Request::url()) active @endif">
                <a class="nav-hover" href="{{ url('/grocery/invoiceManage') }}">
                    <i class="bx bx-planet mr-50"></i>
                    <span class="menu-title" data-i18n="City Manager">Manage Invoice Image</span>
                </a>
            </li>

            @if(strpos($permission, 'order') !== false || strpos($permission, 'create_order') !== false)
            <li class=" navigation-header"><span>Orders</span></li>
            @endif
            @if(strpos($permission, 'create_order') !== false)
            <li class="nav-item has-sub">
                <a href="#">
                    <i class="bx bx-customize mr-50"></i>
                    <span class="menu-title" data-i18n="Create Order">Create Order</span>
                </a>
                <ul class="menu-content">
                    @foreach($cityList as $city)
                    @if(strpos(strtoupper($permission), strtoupper($city->city_name)) !== false)
                    <li class="nav-item @if(isset($cityID) && $cityID == $city->id && (url('/grocery/order/new') == Request::url())) active @endif">
                        <a class="nav-hover" href="{{ url('/grocery/order/new') . '?city=' . $city->id }}">
                            <i class="bx bxs-navigation mr-50"></i>
                            <span class="menu-title">{{ $city->city_name }}</span>
                        </a>
                    </li>
                    @endif
                    @endforeach
                </ul>
            </li>
            @endif
            @if(strpos($permission, 'order') !== false)
            <li class="nav-item has-sub">
                <a href="#">
                    <i class="bx bx-receipt mr-50"></i>
                    <span class="menu-title" data-i18n="Order Manager">Order Manager</span>
                </a>
                <ul class="menu-content">
                    @foreach($cityList as $city)
                    @if(strpos(strtoupper($permission), strtoupper($city->city_name)) !== false)
                    <li class="nav-item @if(isset($cityID) && $cityID == $city->id && (url('/grocery/order') == Request::url())) active @endif">
                        <a class="nav-hover" href="{{ url('/grocery/order') . '?city=' . $city->id }}">
                            <i class="bx bxs-navigation mr-50"></i>
                            <span class="menu-title">{{ $city->city_name }}</span>
                        </a>
                    </li>
                    @endif
                    @endforeach
                </ul>
            </li>
            @endif
            @if(strpos($permission, 'mango_order') !== false)
            <li class="nav-item @if(url('/grocery/order/mango') == Request::url()) active @endif">
                <a class="nav-hover" href="{{ url('/grocery/order/mango') }}">
                    <i class="bx bx-planet mr-50"></i>
                    <span class="menu-title" data-i18n="City Manager">Mango Order</span>
                </a>
            </li>
            @endif


            @if(strpos($permission, 'mega_days') !== false)
            <li class=" navigation-header"><span>Mega Days</span></li>
            @endif
            @if(strpos($permission, 'mega_days') !== false)
            <li class="nav-item @if(route('megadays.create') == Request::url()) active @endif">
                <a class="nav-hover" href="{{ route('megadays.create') }}">
                    <i class="bx bxs-component mr-50"></i>
                    <span class="menu-title">Create Mega Days</span>
                </a>
            </li>
            @endif
            @if(strpos($permission, 'mega_days') !== false)
            <li class="nav-item @if(route('megadays.manage') == Request::url()) active @endif">
                <a class="nav-hover" href="{{ route('megadays.manage') }}">
                    <i class="bx bx-chart mr-50"></i>
                    <span class="menu-title">Mega Days Manager</span>
                </a>
            </li>
            @endif
            @if(strpos($permission, 'mega_days') !== false)
            <li class="nav-item has-sub">
                <a href="#">
                    <i class="bx bx-purchase-tag mr-50"></i>
                    <span class="menu-title" data-i18n="Order Manager">Mega Days Category</span>
                </a>
                <ul class="menu-content">
                    @php
                    $megaDays = \App\Models\MegaDays\MegaDays::where('status', 'Active')->get();
                    @endphp
                    @foreach($megaDays as $mega)
                    <li class="nav-item @if(route('megadays.category', ['mid' => $mega->mid]) == Request::url()) active @endif">
                        <a class="nav-hover" href="{{ route('megadays.category', ['mid' => $mega->mid]) }}">
                            <i class="bx bxs-navigation mr-50"></i>
                            <span class="menu-title">{{ $mega->title }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </li>
            <li class="nav-item has-sub">
                <a href="#">
                    <i class="bx bx-package mr-50"></i>
                    <span class="menu-title">Mega Days Products</span>
                </a>
                <ul class="menu-content" style="">
                    @foreach($megaDays as $mega)
                    <li class="has-sub">
                        <a href="#">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item">{{ $mega->title }}</span>
                        </a>
                        <ul class="menu-content" style="">
                            @php
                            $megaCategory = \App\Models\MegaDays\MegaDaysCategory::where('mid', $mega->mid)->get();
                            @endphp
                            @foreach($megaCategory as $cat)
                            <li class="@if(route('megadays.products', ['mid' => $mega->mid, 'cid' => $cat->cid]) == Request::url()) active @endif">
                                <a href="{{ route('megadays.products', ['mid' => $mega->mid, 'cid' => $cat->cid]) }}">
                                    <i class="bx bx-purchase-tag"></i>
                                    <span class="menu-item">{{ $cat->title }}</span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                    @endforeach
                </ul>
            </li>
            @endif
            @if(strpos($permission, 'transactions') !== false)
            <li class=" navigation-header"><span>Transactions</span></li>
            @endif
            @if(strpos($permission, 'transactions') !== false)
            <li class="nav-item @if(url('/payment/tokens') == Request::url()) active @endif">
                <a class="nav-hover" href="{{ url('/payment/tokens') }}">
                    <i class="bx bxl-discourse mr-50"></i>
                    <span class="menu-title">Payment Tokens</span>
                </a>
            </li>
            @endif
            @if(strpos($permission, 'transactions') !== false)
            <li class="nav-item @if(url('/payment/bkash') == Request::url()) active @endif">
                <a class="nav-hover" href="{{ url('/payment/bkash') }}">
                    <!-- <i class="bx bx-planet mr-50"></i> -->
                    <img class="mr-50" src="{{ asset('images/logo/bkash-ico.png') }}" width="24px" />
                    <span class="menu-title">bKash Payments</span>
                </a>
            </li>
            @endif
            @if(strpos($permission, 'transactions') !== false)
            <li class="nav-item @if(url('/payment/bkash/refund') == Request::url()) active @endif">
                <a class="nav-hover" href="{{ url('/payment/bkash/refund') }}">
                    <!-- <i class="bx bx-planet mr-50"></i> -->
                    <img class="mr-50" src="{{ asset('images/logo/bkash-ico.png') }}" width="24px" />
                    <span class="menu-title">bKash Refunds</span>
                </a>
            </li>
            @endif
            <!-- @if(strpos($permission, 'transactions') !== false)
            <li class="nav-item @if(url('/grocery/order/mango') == Request::url()) active @endif">
                <a class="nav-hover" href="{{ url('/grocery/order/mango') }}">
                    <img class="mr-50" src="{{ asset('images/logo/AamarPay-ico.png') }}" width="24px"/>
                    <span class="menu-title" data-i18n="City Manager">AamarPay Payments</span>
                </a>
            </li>
            @endif -->
            @if(strpos($permission, 'leads_data') !== false)
            <li class=" navigation-header"><span>Marketing</span></li>
            @endif
            @if(strpos($permission, 'push_notification') !== false)
            <li class="nav-item @if(url('/grocery/notification') == Request::url()) active @endif">
                <a class="nav-hover" href="{{ url('/grocery/notification') }}">
                    <i class="bx bxs-bell-ring mr-50"></i>
                    <span class="menu-title" data-i18n="Notification Manager">Notification Manager</span>
                </a>
            </li>
            @endif
            @if(strpos($permission, 'leads_data') !== false)
            <li class="nav-item @if(url('/grocery/leads') == Request::url()) active @endif">
                <a class="nav-hover" href="{{ url('/grocery/leads') }}">
                    <i class="bx bx-layer mr-50"></i>
                    <span class="menu-title" data-i18n="City Manager">Leads Data</span>
                </a>
            </li>
            @endif
            @if(strpos($permission, 'mango_leads_data') !== false)
            <li class="nav-item @if(url('/grocery/leads/mango') == Request::url()) active @endif">
                <a class="nav-hover" href="{{ url('/grocery/leads/mango') }}">
                    <i class="bx bx-layer mr-50"></i>
                    <span class="menu-title" data-i18n="City Manager">Mango Leads Data</span>
                </a>
            </li>
            @endif
            @if(strpos($permission, 'print_dealer_invoice') !== false)
            <li class=" navigation-header"><span>Print</span></li>
            @endif
            @if(strpos($permission, 'print_dealer_invoice') !== false)
            <li class="nav-item has-sub">
                <a href="#">
                    <i class="bx bx-printer mr-50"></i>
                    <span class="menu-title" data-i18n="Order Manager">Print for Vendor</span>
                </a>
                <ul class="menu-content">
                    <li class="nav-item @if(url('/grocery/dealer/print') == Request::url()) active @endif">
                        <a class="nav-hover" href="{{ url('/grocery/dealer/print') }}">
                            <i class="bx bxs-navigation mr-50"></i>
                            <span class="menu-title">Dhaka</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endif
            @if(strpos($permission, 'rider') !== false)
            <li class=" navigation-header"><span>Delivery</span></li>
            @endif
            @if(strpos($permission, 'rider') !== false)
            <li class="nav-item has-sub">
                <a href="#">
                    <i class="bx bx-cycling mr-50"></i>
                    <span class="menu-title" data-i18n="Order Manager">Rider Manager</span>
                </a>
                <ul class="menu-content">
                    @foreach($cityList as $city)
                    <li class="nav-item @if(isset($cityID) && $cityID == $city->id && (url('/grocery/rider') == Request::url())) active @endif">
                        <a class="nav-hover" href="{{ url('/grocery/rider') . '?city=' . $city->id }}">
                            <i class="bx bxs-navigation mr-50"></i>
                            <span class="menu-title">{{ $city->city_name }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </li>
            @endif


            @if(strpos($permission, 'user_manager') !== false)
            <li class=" navigation-header"><span>Users</span></li>
            @endif
            @if(strpos($permission, 'user_manager') !== false)
            <li class="nav-item has-sub">
                <a href="#">
                    <i class="bx bx-user mr-50"></i>
                    <span class="menu-title" data-i18n="Order Manager">User Manager</span>
                </a>
                <ul class="menu-content">

                        <li class="nav-item @if(isset($cityID) && $cityID == $city->id && (url('/grocery/users') == Request::url())) active @endif">
                            <a class="nav-hover" href="{{ url('/grocery/users') . '?city=' . $city->id }}">
                                <i class="bx bxs-navigation mr-50"></i>
                                <span class="menu-title">All Users</span>
                            </a>
                        </li>


{{--                    @foreach($cityList as $city)--}}
{{--                    <li class="nav-item @if(isset($cityID) && $cityID == $city->id && (url('/grocery/users') == Request::url())) active @endif">--}}
{{--                        <a class="nav-hover" href="{{ url('/grocery/users') . '?city=' . $city->id }}">--}}
{{--                            <i class="bx bxs-navigation mr-50"></i>--}}
{{--                            <span class="menu-title">{{ $city->city_name }}</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    @endforeach--}}
                </ul>
            </li>
            @endif

            @if(strpos($permission, 'admin') !== false)
            <li class="nav-item nav-hover @if(url('/grocery/admins') == Request::url()) active @endif">
                <a class="nav-hover" href="{{ url('/grocery/admins') }}">
                    <i class="bx bxs-shield-alt-2 mr-50"></i>
                    <span class="menu-title" data-i18n="Home">Admin Accounts</span>
                </a>
            </li>
            @endif
            @if(strpos($permission, 'login_log') !== false)
            <li class=" navigation-header"><span>Logging</span></li>
            <li class="nav-item @if(url('/grocery/log/login') == Request::url()) active @endif">
                <a class="nav-hover" href="{{ url('/grocery/log/login') }}">
                    <i class="bx bxs-report mr-50"></i>
                    <span class="menu-title" data-i18n="Change Password">Login Log</span>
                </a>
            </li>
            @endif
            <li class=" navigation-header"><span>Authentication</span></li>
            <li class="nav-item @if(url('/grocery/admin/changePassword') == Request::url()) active @endif">
                <a class="nav-hover" href="{{ url('/grocery/admin/changePassword') }}">
                    <i class="bx bxs-key mr-50"></i>
                    <span class="menu-title" data-i18n="Change Password">Change Password</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-hover" href="{{ url('/grocery/dashboard/signout') }}">
                    <i class="bx bx-log-out-circle mr-50"></i>
                    <span class="menu-title" data-i18n="Logout">Logout</span>
                </a>
            </li>

            <li class=" navigation-header"><span>Server Maintenance</span></li>
            <li class="nav-item @if(url('/serverMaintenance') == Request::url()) active @endif">
                <a class="nav-hover" href="{{ url('/serverMaintenance') }}">
                    <i class="bx bxs-shield-alt-2 mr-50"></i>
                    <span class="menu-title" data-i18n="Manage server">Manage Maintenance</span>
                </a>
            </li>
        </ul>
    </div>
</div>
<!-- END: Main Menu-->
