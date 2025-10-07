<?php
use App\Models\Admin\ProductCategories;
$categories = ProductCategories::select(['title', 'slug'])->where('status', 1)->get();
?>
<form class="d-flex header__search--form" action="{{ url('/search')}}">
    <div class="header__select--categories select">
        <select class="header__select--inner" name="category">
            <option selected value="">All Categories</option>
            @foreach ($categories as $c)
            <option {{ isset($_GET['category']) && $_GET['category'] == $c->slug ? 'selected' : ''}} value="{{$c->slug}}">{{$c->title}}</option>
            @endforeach
        </select>
    </div>
    <div class="header__search--box">
        <label>
            <input class="header__search--input" required placeholder="Keyword here..."
                type="text" name="search" value="{{ isset($_GET['search']) ? $_GET['search'] : '' }}">
        </label>
        <button class="header__search--button bg__secondary text-white" type="submit"
            aria-label="search button">
            <svg class="header__search--button__svg" xmlns="http://www.w3.org/2000/svg"
                width="27.51" height="26.443" viewBox="0 0 512 512">
                <path d="M221.09 64a157.09 157.09 0 10157.09 157.09A157.1 157.1 0 00221.09 64z"
                    fill="none" stroke="currentColor" stroke-miterlimit="10"
                    stroke-width="32">
                </path>
                <path fill="none" stroke="currentColor" stroke-linecap="round"
                    stroke-miterlimit="10" stroke-width="32" d="M338.29 338.29L448 448">
                </path>
            </svg>
        </button>
    </div>
</form>