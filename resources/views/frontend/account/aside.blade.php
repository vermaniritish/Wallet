<div class="dashboard-menu">
    <ul class="nav flex-column" role="tablist">
        <li class="nav-item">
            <a href="{{ url('/my-account') }}" class="nav-link {{ ($screen == 'dashboard' ? 'active' : '') }}" id="dashboard-tab" ><i class="fi-rs-settings-sliders mr-10"></i>Dashboard</a>
        </li>
        <li class="nav-item">
            <a href="{{ url('/my-orders') }}" class="nav-link {{ ($screen == 'orders' ? 'active' : '') }}" id="orders-tab" ><i class="fi-rs-shopping-bag mr-10"></i>Orders</a>
        </li>
        <li class="nav-item">
            <a href="{{ url('/') }}" class="nav-link" id="track-orders-tab"><i class="fi-rs-shopping-cart-check mr-10"></i>Track Your Order</a>
        </li>
        <li class="nav-item">
            <a href="{{ url('/addresses') }}" class="nav-link {{ ($screen == 'address' ? 'active' : '') }}" id="address-tab" ><i class="fi-rs-marker mr-10"></i>My Address</a>
        </li>
        <li class="nav-item">
            <a href="{{ url('/edit-account') }}" class="nav-link {{ ($screen == 'account' ? 'active' : '') }}" id="account-detail-tab"><i class="fi-rs-user mr-10"></i>Account details</a>
        </li>
        <li class="nav-item">
            <a href="{{ route('logout') }}" class="nav-link" href="#"><i class="fi-rs-sign-out mr-10"></i>Logout</a>
        </li>
    </ul>
</div>