<?php $user = request()->session()->get('user') ?>
<p class="account__welcome--text">Hello, {{$user->first_name .' '.$user->last_name}} welcome to your dashboard!</p>