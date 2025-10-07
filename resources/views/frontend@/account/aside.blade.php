<ul class="account__menu">
    <li class="account__menu--list {{ strpos(url()->current(), 'my-orders') ? 'active' : '' }}"><a href="{{ url('/my-orders') }}">Order History</a></li>
    <li class="account__menu--list {{ strpos(url()->current(), 'edit-account') || strpos(url()->current(), 'my-account') ? 'active' : '' }}"><a href="{{ url('/my-account') }}">My Details</a></li>
    <li class="account__menu--list"><a href="{{ url('/auth/logout') }}">Log Out</a></li>
</ul>