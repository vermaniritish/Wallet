<div class="table-responsive">
    <!--!!!!! DO NOT REMOVE listing-table, mark_all  CLASSES. INCLUDE THIS IN ALL TABLES LISTING PAGES !!!!!-->
    <table class="table align-items-center table-flush listing-table">
        <thead class="thead-light">
            <tr>
                <th class="sort text-center" width="15%">
                    Colour
                    <?php if(isset($_GET['sort']) && $_GET['sort'] == 'colors.title' && isset($_GET['direction']) && $_GET['direction'] == 'asc'): ?>
                    <i class="fas fa-sort-down active" data-field="colors.title" data-sort="asc"></i>
                    <?php elseif(isset($_GET['sort']) && $_GET['sort'] == 'colors.title' && isset($_GET['direction']) && $_GET['direction'] == 'desc'): ?>
                    <i class="fas fa-sort-up active" data-field="colors.title" data-sort="desc"></i>
                    <?php else: ?>
                    <i class="fas fa-sort" data-field="colors.title"></i>
                    <?php endif; ?>
                </th>
                <th class="sort text-center" width="15%">
                    Size Title
                    <?php if(isset($_GET['sort']) && $_GET['sort'] == 'product_sizes.size_title' && isset($_GET['direction']) && $_GET['direction'] == 'asc'): ?>
                    <i class="fas fa-sort-down active" data-field="product_sizes.size_title" data-sort="asc"></i>
                    <?php elseif(isset($_GET['sort']) && $_GET['sort'] == 'product_sizes.size_title' && isset($_GET['direction']) && $_GET['direction'] == 'desc'): ?>
                    <i class="fas fa-sort-up active" data-field="product_sizes.size_title" data-sort="desc"></i>
                    <?php else: ?>
                    <i class="fas fa-sort" data-field="product_sizes.size_title"></i>
                    <?php endif; ?>
                </th>				
                <th class="sort text-center" width="15%" >
                    From cm 
                    <?php if(isset($_GET['sort']) && $_GET['sort'] == 'product_sizes.from_cm' && isset($_GET['direction']) && $_GET['direction'] == 'asc'): ?>
                    <i class="fas fa-sort-down active" data-field="product_sizes.from_cm" data-sort="asc"></i>
                    <?php elseif(isset($_GET['sort']) && $_GET['sort'] == 'product_sizes.from_cm' && isset($_GET['direction']) && $_GET['direction'] == 'desc'): ?>
                    <i class="fas fa-sort-up active" data-field="product_sizes.from_cm" data-sort="desc"></i>
                    <?php else: ?>
                    <i class="fas fa-sort" data-field="product_sizes.from_cm"></i>
                    <?php endif; ?>
                </th>
                <th class="sort text-center" width="15%" >
                    To cm
                    <?php if(isset($_GET['sort']) && $_GET['sort'] == 'product_sizes.to_cm' && isset($_GET['direction']) && $_GET['direction'] == 'asc'): ?>
                    <i class="fas fa-sort-down active" data-field="product_sizes.to_cm" data-sort="asc"></i>
                    <?php elseif(isset($_GET['sort']) && $_GET['sort'] == 'product_sizes.to_cm' && isset($_GET['direction']) && $_GET['direction'] == 'desc'): ?>
                    <i class="fas fa-sort-up active" data-field="product_sizes.to_cm" data-sort="desc"></i>
                    <?php else: ?>
                    <i class="fas fa-sort" data-field="product_sizes.to_cm"></i>
                    <?php endif; ?>
                </th>
                <th class="sort text-center" width="15%">
                    Price
                    <?php if(isset($_GET['sort']) && $_GET['sort'] == 'product_sizes.price' && isset($_GET['direction']) && $_GET['direction'] == 'asc'): ?>
                    <i class="fas fa-sort-down active" data-field="product_sizes.price" data-sort="asc"></i>
                    <?php elseif(isset($_GET['sort']) && $_GET['sort'] == 'product_sizes.price' && isset($_GET['direction']) && $_GET['direction'] == 'desc'): ?>
                    <i class="fas fa-sort-up active" data-field="product_sizes.price" data-sort="desc"></i>
                    <?php else: ?>
                    <i class="fas fa-sort" data-field="product_sizes.price"></i>
                    <?php endif; ?>
                </th>
                <th class="sort text-center" width="15%">
                    Sale Price
                    <?php if(isset($_GET['sort']) && $_GET['sort'] == 'product_sizes.price' && isset($_GET['direction']) && $_GET['direction'] == 'asc'): ?>
                    <i class="fas fa-sort-down active" data-field="product_sizes.price" data-sort="asc"></i>
                    <?php elseif(isset($_GET['sort']) && $_GET['sort'] == 'product_sizes.price' && isset($_GET['direction']) && $_GET['direction'] == 'desc'): ?>
                    <i class="fas fa-sort-up active" data-field="product_sizes.price" data-sort="desc"></i>
                    <?php else: ?>
                    <i class="fas fa-sort" data-field="product_sizes.price"></i>
                    <?php endif; ?>
                </th>
                
                <th class="sort text-center" width="15%">
                    Stock Status
                    <?php if(isset($_GET['sort']) && $_GET['sort'] == 'product_sizes.status' && isset($_GET['direction']) && $_GET['direction'] == 'asc'): ?>
                    <i class="fas fa-sort-down active" data-field="product_sizes.status" data-sort="asc"></i>
                    <?php elseif(isset($_GET['sort']) && $_GET['sort'] == 'product_sizes.status' && isset($_GET['direction']) && $_GET['direction'] == 'desc'): ?>
                    <i class="fas fa-sort-up active" data-field="product_sizes.status" data-sort="desc"></i>
                    <?php else: ?>
                    <i class="fas fa-sort" data-field="product_sizes.status"></i>
                    <?php endif; ?>
                </th>
                <th class="sort text-center" width="15%">
                    Non Exchange
                    <?php if(isset($_GET['sort']) && $_GET['sort'] == 'product_sizes.non_exchange' && isset($_GET['direction']) && $_GET['direction'] == 'asc'): ?>
                    <i class="fas fa-sort-down active" data-field="product_sizes.non_exchange" data-sort="asc"></i>
                    <?php elseif(isset($_GET['sort']) && $_GET['sort'] == 'product_sizes.non_exchange' && isset($_GET['direction']) && $_GET['direction'] == 'desc'): ?>
                    <i class="fas fa-sort-up active" data-field="product_sizes.non_exchange" data-sort="desc"></i>
                    <?php else: ?>
                    <i class="fas fa-sort" data-field="product_sizes.non_exchange"></i>
                    <?php endif; ?>
                </th>
            </tr>
        </thead>
        <tbody class="list">
            <?php if(!empty($listing->items())): ?>
                @include('admin.products.productSizes.listingLoop')
            <?php else: ?>
                <td align="left" colspan="7">
                    No records found!
                </td>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <th align="left" colspan="20">
                    @include('admin.partials.pagination', ["pagination" => $listing])
                </th>
            </tr>
        </tfoot>
    </table>
</div>