<div class="table-responsive">
    <!--!!!!! DO NOT REMOVE listing-table, mark_all  CLASSES. INCLUDE THIS IN ALL TABLES LISTING PAGES !!!!!-->
    <table class="table align-items-center table-flush listing-table">
        <thead class="thead-light">
            <tr>
                <th class="checkbox-th d-none" width="5%">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input mark_all" id="mark_all">
                        <label class="custom-control-label" for="mark_all"></label>
                    </div>
                </th>
                <th class="sort" width="5%">
                    <!--- MAKE SURE TO USE PROPOER FIELD IN data-field AND PROPOER DIRECTION IN data-sort -->
                    Id
                    <?php if(isset($_GET['sort']) && $_GET['sort'] == 'orders.id' && isset($_GET['direction']) && $_GET['direction'] == 'asc'): ?>
                    <i class="fas fa-sort-down active" data-field="orders.id" data-sort="asc"></i>
                    <?php elseif(isset($_GET['sort']) && $_GET['sort'] == 'orders.id' && isset($_GET['direction']) && $_GET['direction'] == 'desc'): ?>
                    <i class="fas fa-sort-up active" data-field="orders.id" data-sort="desc"></i>
                    <?php else: ?>
                    <i class="fas fa-sort" data-field="orders.id" data-sort="asc"></i>
                    <?php endif; ?>
                </th>
                <th class="sort" width="10%">
                    Amount
                    <?php if(isset($_GET['sort']) && $_GET['sort'] == 'orders.total_amount' && isset($_GET['direction']) && $_GET['direction'] == 'asc'): ?>
                    <i class="fas fa-sort-down active" data-field="orders.total_amount" data-sort="asc"></i>
                    <?php elseif(isset($_GET['sort']) && $_GET['sort'] == 'orders.total_amount' && isset($_GET['direction']) && $_GET['direction'] == 'desc'): ?>
                    <i class="fas fa-sort-up active" data-field="orders.total_amount" data-sort="desc"></i>
                    <?php else: ?>
                    <i class="fas fa-sort" data-field="orders.total_amount"></i>
                    <?php endif; ?>
                </th>
                <th class="sort" width="10%">
                    Status
                    <?php if(isset($_GET['sort']) && $_GET['sort'] == 'orders.status' && isset($_GET['direction']) && $_GET['direction'] == 'asc'): ?>
                    <i class="fas fa-sort-down active" data-field="orders.status" data-sort="asc"></i>
                    <?php elseif(isset($_GET['sort']) && $_GET['sort'] == 'orders.status' && isset($_GET['direction']) && $_GET['direction'] == 'desc'): ?>
                    <i class="fas fa-sort-up active" data-field="orders.status" data-sort="desc"></i>
                    <?php else: ?>
                    <i class="fas fa-sort" data-field="orders.status"></i>
                    <?php endif; ?>
                </th>
                <th class="sort" width="15%">
                    Created ON
                    <?php if(isset($_GET['sort']) && $_GET['sort'] == 'orders.created' && isset($_GET['direction']) && $_GET['direction'] == 'asc'): ?>
                    <i class="fas fa-sort-down active" data-field="orders.created" data-sort="asc"></i>
                    <?php elseif(isset($_GET['sort']) && $_GET['sort'] == 'orders.created' && isset($_GET['direction']) && $_GET['direction'] == 'desc'): ?>
                    <i class="fas fa-sort-up active" data-field="orders.created" data-sort="desc"></i>
                    <?php else: ?>
                    <i class="fas fa-sort" data-field="orders.created"></i>
                    <?php endif; ?>
                </th>
            </tr>
        </thead>
        <tbody class="list">
            <?php if($listing && !empty($listing->items())): ?>
                @include('admin.staff.orders.listingLoop')
            <?php else: ?>
                <td align="left" colspan="7">
                    No records found!
                </td>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <th align="left" colspan="20">
                    @if($listing)
                    @include('admin.partials.pagination', ["pagination" => $listing])
                    @endif
                </th>
            </tr>
        </tfoot>
    </table>
</div>