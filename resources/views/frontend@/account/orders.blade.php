<?php use App\Models\Admin\Orders; ?>
@extends('layouts.frontendlayout')
@section('content')
    <section class="my__account--section section--padding">
        <div class="container">
            @include('frontend.account.name')
            <div class="my__account--section__inner border-radius-10 d-flex">
                <div class="account__left--sidebar">
                    <h2 class="account__content--title h3 mb-20">My Profile</h2>
                    @include('frontend.account.aside')
                </div>
                <div class="account__wrapper">
                    <div class="account__content">
                        <h2 class="account__content--title h3 mb-20">Orders History</h2>
                        <div class="account__table--area">
                            <table class="account__table">
                                <thead class="account__table--header">
                                    <tr class="account__table--header__child">
                                        <th class="account__table--header__child--items">Order</th>
                                        <th class="account__table--header__child--items">Date</th>
                                        <th class="account__table--header__child--items">Payment Status</th>
                                        <th class="account__table--header__child--items">Fulfillment Status</th>
                                        <th class="account__table--header__child--items">Total</th>	 	 	 	
                                        <th class="account__table--header__child--items"></th>	 	 	 	
                                    </tr>
                                </thead>
                                <tbody class="account__table--body mobile__none">
                                    <?php 
                                    foreach($orders as $o): ?>
                                    <tr class="account__table--body__child">
                                        <td class="account__table--body__child--items">#{{$o->prefix_id}}</td>
                                        <td class="account__table--body__child--items">{{_dt($o->created)}}</td>
                                        <td class="account__table--body__child--items"><span class="{{ $o->paid ? 'text-success' : 'text-danger'}}">{{ $o->paid ? 'Paid' : 'Unpaid'}}</span></td>
                                        <td class="account__table--body__child--items"><span class="badge" style="{{ Orders::getStatuses($o->status)['styles'] }}" >{{ Orders::getStatuses($o->status)['label'] }}</td>
                                        <td class="account__table--body__child--items">{{ _currency($o->total_amount) }}</td>
                                        <td class="account__table--body__child--items">
                                            
                                            <a href="{{ route('invoice', ['id' => $o->prefix_id]) }}" class="btn" data-toggle="tooltip" data-title="Download Invoice"><i class="fa fa-download"></i></a>
                                            <?php if($o->shipment): 
                                            $shipment = explode(',', $o->shipment);
                                            ?>
                                            <div class="dropdown custom-dropdown">
                                                <button class="btn btn-warning dropdown-toggle" type="button" data-toggle="dropdown">Track Order
                                                <span class="caret"></span></button>
                                                <ul class="dropdown-menu">
                                                    <?php foreach($shipment as $s): ?>
                                                    <li><a target="_blank" href="http://www.parcelforce.com/track-trace?trackNumber={{$s}}">{{ $s }}</a></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tbody class="account__table--body mobile__block">
                                    <?php foreach($orders as $o): ?>
                                    <tr class="account__table--body__child">
                                        <td class="account__table--body__child--items">
                                            <strong>Order</strong>
                                            <span>#{{$o->prefix_id}}</span>
                                        </td>
                                        <td class="account__table--body__child--items">
                                            <strong>Date</strong>
                                            <span>{{_dt($o->created)}}</span>
                                        </td>
                                        <td class="account__table--body__child--items">
                                            <strong>Payment Status</strong>
                                            <span class="{{ $o->paid ? 'text-success' : 'text-danger'}}">{{ $o->paid ? 'Paid' : 'Unpaid'}}</span>
                                        </td>
                                        <td class="account__table--body__child--items">
                                            <strong>Fulfillment Status</strong>
                                            <span><span class="badge" style="{{ Orders::getStatuses($o->status)['styles'] }}" >{{ Orders::getStatuses($o->status)['label'] }}</span>
                                        </td>
                                        <td class="account__table--body__child--items">
                                            <strong>Total</strong>
                                            <span>{{ _currency($o->total_amount) }}</span>
                                        </td>
                                        <td class="account__table--body__child--items">
                                            
                                            <a href="{{ route('admin.orders.download', ['id' => $o->id]) }}" class="btn" data-toggle="tooltip" data-title="Download Invoice"><i class="fa fa-download"></i></a>
                                            <div class="dropdown">
                                                <button class="btn btn-warning dropdown-toggle" type="button" data-toggle="dropdown">Click on Me
                                                <span class="caret"></span></button>
                                                <ul class="dropdown-menu">
                                                <li><a href="#">Phantom</a></li>
                                                <li><a href="#">Cluster</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection