<?php
use App\Models\Admin\Orders;
$shipOptions = Orders::getShippingOptions();?>
<div class="table-responsive" style="overflow:hidden" id="ordered-products" data-id="{{$id}}">
    <!--!!!!! DO NOT REMOVE listing-table, mark_all  CLASSES. INCLUDE THIS IN ALL TABLES LISTING PAGES !!!!!-->
    <table class="table align-items-center table-flush listing-table">
        <thead class="thead-light">
            <tr>
                <th class="checkbox-th">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input mark_all" id="mark_all">
                        <label class="custom-control-label" for="mark_all"></label>
                    </div>
                </th>
                <th class="sort" width="10%">
                    <!--- MAKE SURE TO USE PROPOER FIELD IN data-field AND PROPOER DIRECTION IN data-sort -->
                    Id
                    <?php if(isset($_GET['sort']) && $_GET['sort'] == 'order_products.id' && isset($_GET['direction']) && $_GET['direction'] == 'asc'): ?>
                    <i class="fas fa-sort-down active" data-field="order_products.id" data-sort="asc"></i>
                    <?php elseif(isset($_GET['sort']) && $_GET['sort'] == 'order_products.id' && isset($_GET['direction']) && $_GET['direction'] == 'desc'): ?>
                    <i class="fas fa-sort-up active" data-field="order_products.id" data-sort="desc"></i>
                    <?php else: ?>
                    <i class="fas fa-sort" data-field="order_products.id" data-sort="asc"></i>
                    <?php endif; ?>
                </th>
                <th class="sort" width="30%">
                    Product Title
                    <?php if(isset($_GET['sort']) && $_GET['sort'] == 'order_products.product_title' && isset($_GET['direction']) && $_GET['direction'] == 'asc'): ?>
                    <i class="fas fa-sort-down active" data-field="order_products.product_title" data-sort="asc"></i>
                    <?php elseif(isset($_GET['sort']) && $_GET['sort'] == 'order_products.product_title' && isset($_GET['direction']) && $_GET['direction'] == 'desc'): ?>
                    <i class="fas fa-sort-up active" data-field="order_products.product_title" data-sort="desc"></i>
                    <?php else: ?>
                    <i class="fas fa-sort" data-field="order_products.product_title"></i>
                    <?php endif; ?>
                </th>
                <th class="sort" width="15%">
                    Quantity
                    <?php if(isset($_GET['sort']) && $_GET['sort'] == 'order_products.quantity' && isset($_GET['direction']) && $_GET['direction'] == 'asc'): ?>
                    <i class="fas fa-sort-down active" data-field="order_products.quantity" data-sort="asc"></i>
                    <?php elseif(isset($_GET['sort']) && $_GET['sort'] == 'order_products.quantity' && isset($_GET['direction']) && $_GET['direction'] == 'desc'): ?>
                    <i class="fas fa-sort-up active" data-field="order_products.quantity" data-sort="desc"></i>
                    <?php else: ?>
                    <i class="fas fa-sort" data-field="order_products.quantity"></i>
                    <?php endif; ?>
                </th>				
                <th class="sort" width="15%" >
                    Price
                    <?php if(isset($_GET['sort']) && $_GET['sort'] == 'order_products.amount' && isset($_GET['direction']) && $_GET['direction'] == 'asc'): ?>
                    <i class="fas fa-sort-down active" data-field="order_products.amount" data-sort="asc"></i>
                    <?php elseif(isset($_GET['sort']) && $_GET['sort'] == 'order_products.amount' && isset($_GET['direction']) && $_GET['direction'] == 'desc'): ?>
                    <i class="fas fa-sort-up active" data-field="order_products.amount" data-sort="desc"></i>
                    <?php else: ?>
                    <i class="fas fa-sort" data-field="order_products.amount"></i>
                    <?php endif; ?>
                </th>
            </tr>
        </thead>
        <tbody class="list">
            <?php if(!empty($listing->items())): ?>
                @include('admin.orders.orderedProducts.listingLoop')
            <?php else: ?>
                <td align="left" colspan="7">
                    No records found!
                </td>
            <?php endif; ?>
        </tbody>
        
    </table>
    
</div>
<?php 
$shipped = $page->shipment_tracking;
// $shipped = Arr::pluck($listing->items(), 'shipment_tracking');
// $shipped = array_map(function($i) {
//     return $i ? true : false;
// }, $shipped);
// $shipped = array_filter($shipped);
// if( count($shipped) < $listing->count()): 
if(!$shipped): 
?>
<div class="row p-4">
    <div class="col-sm-4">
        <small>No. of parcel</small><br />
        <input type="number" class="form-control" id="parcel" value="1" onkeyup="this.value == '' ? this.value = 1 : null" />
    </div>
    <div class="col-sm-4">
        <small>Ship Options</small><br />
        <select class="form-control" id="ship-options">
            <option value=""></option>
            <option value="DPD">DPD</option>
            <?php foreach($shipOptions as $c => $s): ?>
            <option value="{{ $c }}">{{ $s }}</option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-sm-4">
        <small></small><br />
        <button type="button" class="btn btn-primary" id="ship-products">Make Shipment</button>
    </div>
</div>
<?php endif; ?>