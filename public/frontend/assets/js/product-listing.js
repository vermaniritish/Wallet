window.offerPrice = function(item) {
    if(item.offer)
    {
        for(let offer of item.offer)
        {
            if(offer.type == 'case-2')
            {
                if((offer.offer_total_price*1) > 0 && item.quantity == offer.quantity)
                {
                    return {price: offer.offer_total_price*1, freeLogo: 0, haveOffer: true };
                }
            }
            
            if(offer.type == 'case-3')
            {
                if(item.quantity == offer.quantity)
                {
                    return {price: item.quantity*item.price, freeLogo: (offer.free_logo*1), haveOffer: false };
                }
            }
        }
    }
    
    return {price: item.quantity*item.price, freeLogo: 0, haveOffer: false };
}
const oneTimeProductCost = function(cart) {
    return 0;
    let obj = oneTimeProductObject(cart);
    let imageCost = obj.image;
    let txtCost = obj.text;
    return (imageCost !== null ? imageCost*1 : 0)+(txtCost !== null ? txtCost*1 : 0);
}
const oneTimeProductObject = function(cart) {
    let imageCost = null;
    let txtCost = null;
    if(cart && cart.length > 0)
    {
        for(let c of cart)
        {
            for(let l of c.logo)
            {
                console.log(l);
                if(l.postion && l.category && (c.quantity*1 > 0) && l.image && l.image.trim() && imageCost == null) {
                    imageCost = oneTimeLogoCost;
                }
                if(l.postion && l.category && (c.quantity*1 > 0) && l.text && l.text.trim() && txtCost == null) {
                    txtCost = oneTimeLogoTxtCost;
                }
                    
            }
        }
    }
    return {
        image: (imageCost !== null ? imageCost*1 : 0),
        text: (txtCost !== null ? txtCost*1 : 0)
    }
}
if($('#product-page').length)
var productDetail = new Vue({
    el: '#product-page',
    data: {
        id: null,
        logoPrices: [],
        editLogo: false,
        sizes: [],
        color: null,
        colorTitle: null,
        selectedSizes: {},
        uploading: null,
        buyNow: false,
        logo: [{
            category: null,
            postion: null,
            text: ``,
            image: null,
            already_uploaded: false
        }],
        logoOptions: {
            category: [],
            postions: null
        },
        skuNumber: null,
        fileSizeError: null,
        adding: null,
        accept: false,
        customization: [],
        customizationErrors: {}
    },
    methods: {       
        currency(a) {
            return '£' + (a*1).toFixed(2);
        },
        renderActiveColor(id) {
            return this.color == id ? `active` : ``;
        },
        selectColor(id, title) {
            this.color = id;
            this.colorTitle = title;
            let c = $('.slider-thumb[data-item="'+id+'"]').index();
            $('.product-image-slider').slick('slickGoTo', c);
            $('.slider-nav-thumbnails').slick('slickGoTo', c); // Goes to 3rd slide
        },
        renderSizes() {
            if(this.color) {
                return this.sizes.filter((i) => i.color_id == this.color );
            }
            else {
                return [];
            }
        },
        renderAllAddedSizes() {
            // return this.sizes;
            return this.sizes.filter((i) => ((i.quantity*1) > 0) );
        },
        manualQty(e) {
            console.log(e, e.target.value);
            let qty = e.target.value;
            let dataId = e.target.getAttribute("data-id");
            let index = this.sizes.findIndex((v) => v.id == dataId);
            let s = [...this.sizes];
            s[index].quantity = qty;
            this.sizes = JSON.parse(JSON.stringify(s));
        },
        increment(id) {
            let index = this.sizes.findIndex((v) => v.id == id);
            let s = [...this.sizes];

            if(s[index].quantity && (s[index].quantity * 1) > 0){
                s[index].quantity = (s[index].quantity*1) + 1;
            }
            else {
                s[index].quantity = 1;
            }
            this.sizes = JSON.parse(JSON.stringify(s));
        },
        decrement(id) {
            let index = this.sizes.findIndex((v) => v.id == id);
            let s = [...this.sizes];

            if(s[index].quantity && (s[index].quantity * 1) > 0){
                s[index].quantity = (s[index].quantity*1) - 1;
            }
            else {
                s[index].quantity = 0;
            }
            this.sizes = JSON.parse(JSON.stringify(s));
        },
        handleFileUpload(sizeIndex, logoKey) {
            this.uploading = {sizeIndex, logoKey};
            $('#fileUploadForm input[type=file]').click();
        },
        uploadFile() 
        {
            $('#fileUploadForm').ajaxSubmit({
                beforeSend: function() {
                },
                uploadProgress: function(event, position, total, percentComplete) {
                },
                success: function(response) {
                    if(response.status == 'success')
                    {
                        productDetail.sizes[productDetail.uploading.sizeIndex].logo[productDetail.uploading.logoKey].image = response.path;
                    }
                    else
                    {
                        set_notification('error', response.message);
                    }
                    productDetail.uploading = null;
                },
                complete: function() {
                }
            });
        },
        async addToCart(buyNow) 
        {
            if(nonExchange && !this.accept) {
                set_notification('error', 'Please acknowledge to proceed.');
                return false;
            }
            if(this.adding) return false;
            this.buyNow = buyNow ? true : false;
            this.editLogo = false;
            $('body').removeClass('overflow-hidden');
            this.adding = true;
            this.cart = this.sizes.filter((item) => {
                return (item.quantity && (item.quantity*1) > 0)
            });
            let custmomization = this.customization ? this.customization.filter((v) => v.initial && v.initial.trim()).map((v) => ({cost:v.cost, title: v.title, initial: v.initial})) : null;
            let response = await fetch(site_url + '/api/orders/add-to-cart', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({cart: this.cart, customization: custmomization}),
            });
            response = await response.json();
            if(response && response.status)
            {
               this.cart = response.cart;
            }
            let cart = localStorage.getItem('cart');
            cart = cart ? JSON.parse(cart) : [];
            if(cart && cart.length > 0) {
                cart = cart.filter((item) => {
                    return item.product_id != this.id;
                })
                cart = [...cart, ...this.cart];
                localStorage.setItem('cart', JSON.stringify(cart));
            }
            else {
                cart = this.cart;
                localStorage.setItem('cart', JSON.stringify(cart));
            }
            await sleep(350);
            minicart.updateCartCount();
            this.adding = false;
            window.location.href = '/cart';   
            
        },
        async openLogoModal() 
        {
            this.editLogo = true;
            $('body').addClass('overflow-hidden');
            let response = await fetch(site_url + `/api/products/fetch-logo-prices`);
            response = await response.json();
            if(response && response.status)
            {
                this.logoPrices = response.prices;
            }
        },
        closeModal() {
            this.editLogo = false;
            $('body').removeClass('overflow-hidden');
        },
        addMoreLogo(k) 
        {
            let sizes = {...this.sizes[k]};
            let logo = JSON.parse(JSON.stringify(sizes.logo));
            logo = Object.values(logo);
            logo.push({...this.logo[0]});
            sizes.logo = logo;
            this.$set(this.sizes, k, sizes);
        },
        onChange(index, size, category, logoKey)
        {
            let price = 0;
            let logo = size.logo[logoKey];
            if(category){
                size.logo[logoKey].category = category;
            }
            if(size.logo[logoKey] && size.logo[logoKey].postion && (category || size.logo[logoKey].category))
            {
                category = category ? category : size.logo[logoKey].category;
                const pos = size.logo[logoKey].postion;
                logo.category = category;
                console.log(pos, this.logoPrices);
                if(category != 'None')
                {
                    let logoPriceApply = this.logoPrices.filter((val) => {
                        console.log(val.position, this.convertToSlug(pos), val.option, this.convertToSlug(category));
                        return val.position == this.convertToSlug(pos) && val.option == this.convertToSlug(category) && size.quantity >= val.from_quantity && size.quantity <= val.to_quantity;
                    });
                    logo.price = logoPriceApply && logoPriceApply.length > 0 ? (logoPriceApply[0].price*1) : 0;
                }
                else
                {
                    logo.price = 0;
                }
            }
            else
            {
                logo.price = 0;
            }
            size.logo[logoKey] = logo;
        },
        convertToSlug(text) {
            return text ? text
                .toLowerCase()
                .normalize("NFD").replace(/[\u0300-\u036f]/g, "") // Remove accents
                .replace(/[^a-z0-9]+/g, '-') // Replace non-alphanumeric with dash
                .replace(/^-+|-+$/g, '')     // Trim dashes from start/end
                .replace(/--+/g, '-')        // Replace multiple dashes with one
                : '';
        }
    },
    mounted: function() 
    {
        this.customization = $('#customization').length > 0 && $('#customization').text().trim() ? JSON.parse($('#customization').text().trim()) : null;
        console.log(this.customization);
        this.id  = $('#productId').text().trim();
        let cart = localStorage.getItem('cart');
        cart = cart ? JSON.parse(cart) : [];
        this.cart = cart.filter((item) => {
            return item.product_id == this.id;
        });
        let sizes = $('#product-sizes').text().trim();
        sizes = sizes ? JSON.parse(sizes) : [];
        if(sizes.length > 0){
            let sColor = JSON.parse($('#default-color').text().trim());
            if(sColor)
            {
                this.color = sColor.id;
                this.colorTitle = sColor.title;
            }
        }
        // for(let i in sizes)
        // {
        //     let exist = this.cart.filter((item) => {
        //         return item.id == sizes[i].id
        //     });
        //     sizes[i].logo = exist && exist.length > 0 && exist[0].logo ? exist[0].logo : [...this.logo];
        //     sizes[i].quantity = exist && exist.length > 0 && exist[0].quantity ? exist[0].quantity : 0;
        // }
        this.sizes = sizes;
        this.logoOptions = $('#logo-options').text().trim() ? JSON.parse($('#logo-options').text().trim()) : [];
        if(!this.color && this.sizes.length > 0) {
            this.color = this.sizes[0].color_id;
        }
    }
});

if($('#product-listing-vue').length)
var productListing = new Vue({
    // Mount Vue instance to the div with id="app"
    el: '#product-listing-vue',
    data: {
        schoolId: null,
        salePage: false,
        listing: [],
        sort_by: null,
        page: 1,
        maxPages: 1,
        pagination: [],
        fetching: false,
        priceError: false,
        paginationMessage: ``,
        empty: false,
        searchPage: false,
        search: ``,
        filters: {
            gender: [],
            categories: [],
            brands: [],
            fromPrice: ``,
            toPrice: ``
        },
        counts: {
            menCount: null,
            womenCount: null,
            kidsCount: null,
            unisexCount: null,
        },
        isOpen: false,
        selectedOption: 50,
        options: [50, 100, 150, 200, "All"]
    },
    methods: {
        currency: function(amount) {
            amount = amount*1;
            return "£" + (amount && amount > 0 ? amount.toFixed(2) : '0.00');
        },
        init: async function() {
            await this.fetchListing();
        },
        toggleDropdown() {
            this.isOpen = !this.isOpen;
        },
        async selectOption(option) {
            this.selectedOption = option;
            this.isOpen = false;
            this.page = 1;
            await this.fetchListing();
        },
        fetchListing: async function() {
            if(this.fetching) return false;
            let categoryId = typeof cId !== 'undefined' && cId ? cId : ``;
            this.fetching = true;
            this.empty = false; 
            let response = await fetch(site_url + `/api/products/listing?salePage=${this.salePage ? `1` : ``}${this.search ? `&search=${this.search}` : ''}&brands=${this.filters.brands ? this.filters.brands.join(',') : ``}&cId=${categoryId}&categories=${this.filters.categories ? this.filters.categories.join(',') : ``}&gender=${this.filters.gender ? this.filters.gender.join(',') : ``}&price_from=${this.filters.fromPrice ? this.filters.fromPrice : ``}&price_to=${this.filters.toPrice ? this.filters.toPrice : ``}${this.schoolId ? `&school_id=`+this.schoolId : ``}&page=${this.page}&limit=${this.selectedOption}&sort=${this.sort_by ? this.sort_by : ``}`);
            response = await response.json();
            if(response && response.status)
            {
                if(this.page == 1 && response.products.length < 1){
                    this.empty = true; 
                }
                this.listing = response.products;
                this.maxPages = response.maxPage;
                this.pagination = Array.from({ length: response.maxPage }, (_, index) => index + 1);
                this.paginationMessage = response.paginationMessage;
                if(response.count && this.page == 1){
                    this.counts = response.count;
                }
            }
            this.fetching = false;
        },
        clearSearch: async function(e) {
            this.search = ``;
            this.page = 1;
            await this.fetchListing();
        },
        sortIt: async function(e) {
            this.sort_by = e.target.value;
            this.page = 1;
            await this.fetchListing();
        },
        paginateIt: async function(page) {
            this.page = page; 
            await this.fetchListing();
        },
        genderFilter: async function(g) {
            let genders = this.filters.gender;
            let index = genders.findIndex(function(value, index, array) {
                return value === g;
            })
            if(index > -1) {
                genders.splice(index, 1);
            }
            else {
                genders.push(g);
            }
            this.filters.gender = genders;
            this.page = 1;
            await this.fetchListing();
        },
        priceFilter: async function() {
            if((this.filters.fromPrice*1) < 1 || (this.filters.toPrice*1) < 1) return false;

            if((this.filters.fromPrice*1) > 0 && (this.filters.toPrice*1) > 0 && (this.filters.fromPrice*1) > (this.filters.toPrice*1))
            {
                this.priceError = true;
            }
            else
            {
                this.priceError = false;
                await this.fetchListing();
            }
        },
        brandFilter: async function(b) {
            let brands = this.filters.brands;
            let index = brands.findIndex(function(value, index, array) {
                return value === b;
            })
            if(index > -1) {
                brands.splice(index, 1);
            }
            else {
                brands.push(b);
            }
            this.filters.brands = brands;
            this.page = 1;
            await this.fetchListing();
        },
        categoryFilter: async function(cat) {
            if(cat)
            {
                let categories = this.filters.categories;
                let index = categories.findIndex(function(value, index, array) {
                    return value === cat;
                })
                if(index > -1) {
                    categories.splice(index, 1);
                }
                else {
                    categories.push(cat);
                }
                this.filters.categories = categories;
            }
            else
            {
                this.filters.categories = [];
            }
            this.page = 1;
            await this.fetchListing();
        }
    },
    mounted: function() {
        if(typeof schoolPageId !== 'undefined' && schoolPageId)
        {
            this.schoolId = schoolPageId;
        }
        else
        {
            let pathname = window.location.pathname.split('/');
            if(window.location.pathname.indexOf('/sale') > -1)
            {
                this.salePage = true;
            }
            else if(window.location.pathname.indexOf('/search') > -1)
            {
                const urlParams = new URLSearchParams(window.location.search);
                this.search = urlParams.get('search') ? urlParams.get('search').trim() : '';
                this.searchPage = true;
                let brand = urlParams.get('brand') ? urlParams.get('brand').trim() : '';
                if(brand)
                this.filters = {brands : [brand]};
            }
            if(pathname.length > 2) {
                this.filters.categories.push(pathname[2]);
            }
        }
        this.init()
    }
});

if($('#header').length)
var minicart = new Vue({
    el: '#header',
    data: {
        oneTimeCost: 0,
        open: false,
        agree: false,
        logoPricesDynamix: [],
        cart: [],
        gstTax: ``,
        cartCount: 0,
        search: ''
    },
    mounted: async function() {
        $('#header, main, .mobile-header-active').removeClass('d-none');
        $('.select-active').select2();
        this.gstTax = gstTax();
        this.updateCartCount();
        this.fetchLogoPrices();
        this.initSearch();
    },
    methods: {
        initSearch()  {
            $('#search-global').autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: site_url + `/api/products/listing?cId=${minicart.search}&limit=50`,
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            query: request.term
                        },
                        success: function (data) {
                            if (data.status && data.products) {
                                response($.map(data.products, function (item) {
                                    return {
                                        label: item.title,
                                        value: item.title,
                                        id: item.id,
                                        price: item.price,
                                        sku: item.sku_number,
                                        image: item.image?.[0]?.small || '/frontend/assets/imgs/shop/product-2-2.jpg',
                                        slug: item.slug
                                    };
                                }));
                            } else {
                                response([]);
                            }
                        }
                    });
                },
                minLength: 3,
                select: function (event, ui) {
                    window.location.href = '/' + ui.item.slug;
                }
            }).data("ui-autocomplete")._renderItem = function (ul, item) {
                return $("<li>")
                    .append(`
                        <div class="ui-menu-item-wrapper">
                            <img src="${item.image}" alt="${item.label}">
                            <div>
                                <div class="autocomplete-product-title">${item.label}</div>
                                <div class="autocomplete-product-price">₹${item.price}</div>
                                <div class="autocomplete-product-sku">SKU: ${item.sku}</div>
                            </div>
                        </div>
                    `)
                    .appendTo(ul);
            };
        },
        async fetchLogoPrices(){
            if(this.logoPricesDynamix && this.logoPricesDynamix.length < 1){
                let res = await fetch(site_url+'/api/actions/logo-prices');
                res = await res.json();
                this.logoPricesDynamix = res.logoprices;
            }

            return this.logoPricesDynamix;
        },
        formatMoney(m) {
          return (m*1).toFixed(2);  
        },
        updateCartCount() {
            let cart = localStorage.getItem('cart');
            cart = cart ? JSON.parse(cart) : [];
            this.cartCount = cart.length;
        },
        cartcount(){
            let cart = localStorage.getItem('cart');
            cart = cart ? JSON.parse(cart) : [];
            console.log(cart.length);
            return cart.length;
        },
        initcart() {
            this.open = !this.open;
            if(this.open) {
                let cart = localStorage.getItem('cart');
                cart = cart ? JSON.parse(cart) : [];
                this.cart = this.handleLogoPrices(cart);
            }
        },
        manualQty(e) {
            let qty = e.target.value;
            let dataId = e.target.getAttribute("data-id");
            let index = this.cart.findIndex((v) => v.id == dataId);
            let s = [...this.cart];
            s[index].quantity = qty;
            this.cart = this.handleLogoPrices(s);
            this.store();
        },
        increment(id) {
            let index = this.cart.findIndex((v) => v.id == id);
            let s = [...this.cart];

            if(s[index].quantity && (s[index].quantity * 1) > 0){
                s[index].quantity = (s[index].quantity*1) + 1;
            }
            else {
                s[index].quantity = 1;
            }
            this.cart = this.handleLogoPrices(s);
            this.store();
        },
        decrement(id) {
            let index = this.cart.findIndex((v) => v.id == id);
            let s = [...this.cart];

            if(s[index].quantity && (s[index].quantity * 1) > 0){
                s[index].quantity = (s[index].quantity*1) - 1;
            }
            else {
                s[index].quantity = 0;
            }
            this.cart = this.handleLogoPrices(s);
            this.store();
        },
        handleLogoPrices(s){
            for(let i in s)
            {
                for(let k in s[i].logo)
                {
                    if(typeof s[i].logo[k].price !== 'undefined')
                    {
                        let exist = this.logoPricesDynamix.filter((item) => {
                            return item.option == (s[i].logo[k].category).toLowerCase().replace(/\s+/g , '-')
                            && item.position == (s[i].logo[k].postion).toLowerCase().replace(/\s+/g , '-')
                            && (s[i].quantity*1) >= (item.from_quantity*1) && (s[i].quantity*1) <= (item.to_quantity*1)
                        });
                        console.log(exist);
                        if(exist && exist.length > 0 && exist[0].price)
                        {
                            s[i].logo[k].price = exist[0].price;
                        }
                    }
                }
            }
            this.oneTimeCost = oneTimeProductCost(s);
            return s;
        },
        remove(id) {
            let index = this.cart.findIndex((v) => v.id == id);
            let s = [...this.cart];
            s.splice(index, 1);
            this.cart = this.handleLogoPrices(s);
            this.store();
        },
        store() {
            localStorage.setItem('cart', JSON.stringify(this.cart))
        },
        offerPrice(item) {
            return window.offerPrice(item);
        },
        calculate: function(){
            let t = {
                subtotal: 0,
                total: 0,
                discount: 0,
                logo_cost: 0,
                product_cost:0,
                logo_discount:0,
                applied_logo_discount: 0
            }

            let subtotal = this.cart.map((item) => {
                if(item.offer && item.offer)
                {
                    return this.offerPrice(item).price;
                    // return item.quantity*item.price;
                }
                else
                {
                    return item.quantity*item.price;
                }
            });
            let total = subtotal;
            t.total = total.reduce((partialSum, a) => partialSum + a, 0);
            t.product_cost = subtotal.reduce((partialSum, a) => partialSum + a, 0);
            
            t.tax = 0;
            let logoCost = this.calcaualteLogoCost();
            t.logo_cost = logoCost.cost;
            t.logo_discount = (logoCost.logoDiscount*1) > 0 ? (logoCost.logoDiscount*1) : 0;
            t.applied_logo_discount = (logoCost.appliedDiscount*1) > 0 ? (logoCost.appliedDiscount*1) : 0;
            let haveLogo =  logoCost.haveLogo;
            
            t.oneTimeCost = (t.product_cost*1) > 0 && (this.oneTimeCost*1) > 0 && haveLogo ? (this.oneTimeCost*1) : 0;
            t.subtotal = t.product_cost + (t.logo_cost - t.logo_discount) + (t.product_cost > 0 ? t.oneTimeCost : 0 );
            t.discount = 0;
            t.tax = this.calculateTax(t);
            t.total = t.subtotal - t.discount + t.tax;
            return t;
        },
        calcaualteLogoCost()
        {
            let cost = 0;
            let haveLogo = 0;
            let appliedDiscount = 0;
            let logoDiscount = 0;
            for(let c of this.cart )
            {
                let freeLogo = this.offerPrice(c).freeLogo;
                if(c.logo)
                {
                    for(let item of c.logo)
                    {
                        if( (item.image || item.text) && !item.already_uploaded && item.category != 'None' )
                        {
                            haveLogo += (c.quantity*1);
                        }
                        if(item && item.category != 'None' && (item.price*1) > 0)
                        {
                            cost += item.price*c.quantity;

                            if(freeLogo > 0)
                            {
                                discountQty = c.quantity > freeLogo ? freeLogo : c.quantity;
                                appliedDiscount += discountQty;
                                logoDiscount += item.price*discountQty;
                            }
                        }
                    }
                }
            }
            if(appliedDiscount < 1 && haveLogo > 0)
            {
                let subtotal = this.cart.map((item) => {
                    if(item.offer && item.offer)
                    {
                        return this.offerPrice(item).price;
                        // return item.quantity*item.price;
                    }
                    else
                    {
                        return item.quantity*item.price;
                    }
                });
                subtotal = subtotal.reduce((partialSum, a) => partialSum + a, 0);
                let prices = [];
                for(let c of this.cart )
                {
                    for(let item of c.logo)
                    {
                        for(let i = 0; i < (c.quantity*1); i++)
                        prices.push(item.price);
                    }
                }
                let discount = freeLogoDiscount ? freeLogoDiscount : null;
                if(discount &&  (subtotal*1) >= (discount.min_cart_price*1) && prices.length >= discount.quantity)
                {
                    const sortedPrices = prices.sort((a, b) => a - b);
                    const topPrices = sortedPrices.slice(0, discount.quantity);
                
                    logoDiscount = topPrices.reduce((acc, price) => acc + price, 0);
                    appliedDiscount = discount.quantity;
                }
            }
            console.log(`logo`, {
                cost,
                haveLogo,
                logoDiscount,
                appliedDiscount
            });
            return {
                cost,
                haveLogo: haveLogo > 0 ? true : false,
                logoDiscount,
                appliedDiscount
            };
        },
        freeDelivery(){
            let subtotal = this.cart.map((item) => {
                if(item.offer && item.offer)
                {
                    return this.offerPrice(item).price;
                    // return item.quantity*item.price;
                }
                else
                {
                    return item.quantity*item.price;
                }
            });
            subtotal = subtotal.reduce((partialSum, a) => partialSum + a, 0);
            let discount = freeDelivery ? freeDelivery : null;
            if(discount && subtotal >= (discount.min_cart_price*1)){
                return true;
            }
            return false;
        },
        calculateTax(t) {
            let tax = (t.subtotal - t.discount) * (this.gstTax > 0 ? this.gstTax : 0);
            tax = (tax > 0 ? tax / 100 : 0);
            return tax;
        },
        getImagePath(image) {
            if(image)
            {
                image = JSON.parse(image);
                return image[0];
            }
            return null;
        }
    }
    
});

if($('#cart-page').length)
var minicart = new Vue({
    el: '#cart-page',
    data: {
        agree: false,
        cart: [],
        note: ``,
        coupon: ``,
        appliedCoupon: null,
        couponError: ``,
        gstTax: ``,
        oneTimeCost: 0,
        logoPricesDynamix: []
    },
    methods: {
        formatMoney(m) {
            return (m*1).toFixed(2);
        },
        cartcount(){
            let cart = localStorage.getItem('cart');
            cart = cart ? JSON.parse(cart) : [];
            return cart.length;
        },
        initcart() {
            let cart = localStorage.getItem('cart');
            cart = cart ? JSON.parse(cart) : [];
            this.cart = this.handleLogoPrices(cart);

            let coupon = localStorage.getItem('coupon');
            coupon = coupon ? JSON.parse(coupon) : null;
            if(coupon && cart.length > 0)
            {
                this.coupon = coupon.coupon_code;
                this.appliedCoupon = coupon;
            }
        },
        renderLogoInfo(c) {
            let amount = 0;
            let totalLogos = 0;
            for(let a of c.logo)
            {
                if((a.price*1) > 0)
                {
                    amount += (a.price*1) * c.quantity;
                    totalLogos += (c.quantity*1)
                }
            }

            return (amount > 0) ? (`Price for ` + `${(totalLogos + (totalLogos > 1 ? ` logos are ` : ` logo is `))} <strong>£${amount.toFixed(2)}</strong>`) : '';
        },
        renderOneTimeFeeHtml() {
            // if(this.cart && this.cart.length > 0)
            // {
            //     let obj = oneTimeProductObject(this.cart);
            //     console.log(`obj`, obj);
            //     if(obj && obj.image !== null && obj.text !== null && obj.image > 0 && obj.text > 0){
            //         return `<div class="d-flex flex-row justify-content-between gap-4">
            //         <span class="cart__content--variant" style="color: rgb(238, 39, 97);">For Logo <strong>£${obj.image.toFixed(2)}</strong></span>
            //             <span class="cart__content--variant" style="color: rgb(238, 39, 97);">For Text <strong>£${obj.text.toFixed(2)}</strong></span>
            //         </div>`
            //     }
            // }
            return ``;
        },
        manualQty(e) {
            console.log(e, e.target.value);
            let qty = e.target.value;
            let dataId = e.target.getAttribute("data-id");
            let index = this.cart.findIndex((v) => v.id == dataId);
            let s = [...this.cart];
            s[index].quantity = qty;
            this.cart = this.handleLogoPrices(s);
            this.store();
        },
        increment(id) {
            let index = this.cart.findIndex((v) => v.id == id);
            let s = [...this.cart];

            if(s[index].quantity && (s[index].quantity * 1) > 0){
                s[index].quantity = (s[index].quantity*1) + 1;
            }
            else {
                s[index].quantity = 1;
            }
            this.cart = this.handleLogoPrices(s);
            this.store();
        },
        decrement(id) {
            let index = this.cart.findIndex((v) => v.id == id);
            let s = [...this.cart];

            if(s[index].quantity && (s[index].quantity * 1) > 0){
                s[index].quantity = (s[index].quantity*1) - 1;
            }
            else {
                s[index].quantity = 0;
            }
            this.cart = this.handleLogoPrices(s);
            this.store();
        },
        handleLogoPrices(s) {
            for(let i in s)
            {
                for(let k in s[i].logo)
                {
                    if(typeof s[i].logo[k].price !== 'undefined')
                    {
                        let exist = this.logoPricesDynamix.filter((item) => {
                            return item.option == (s[i].logo[k].category).toLowerCase().replace(/\s+/g , '-')
                            && item.position == (s[i].logo[k].postion).toLowerCase().replace(/\s+/g , '-')
                            && (s[i].quantity*1) >= (item.from_quantity*1) && (s[i].quantity*1) <= (item.to_quantity*1)
                        });
                        console.log(exist);
                        if(exist && exist.length > 0 && exist[0].price)
                        {
                            s[i].logo[k].price = exist[0].price;
                        }
                    }
                }
            }
            this.oneTimeCost = oneTimeProductCost(s);
            return s;
        },
        remove(id) {
            let index = this.cart.findIndex((v) => v.id == id);
            let s = [...this.cart];
            s.splice(index, 1);
            this.cart = this.handleLogoPrices(s);
            this.store();
        },
        store() {
            localStorage.setItem('cart', JSON.stringify(this.cart))
        },
        offerPrice(item) {
            return window.offerPrice(item);
        },
        calculate: function(){
            let t = {
                subtotal: 0,
                total: 0,
                discount: 0,
                logo_cost: 0,
                product_cost:0,
                logo_discount:0,
                applied_logo_discount: 0
            }

            let subtotal = this.cart.map((item) => {
                if(item.offer && item.offer)
                {
                    return this.offerPrice(item).price;
                    // return item.quantity*item.price;
                }
                else
                {
                    return item.quantity*item.price;
                }
            });
            let total = this.cart.map((item) => item.quantity*item.price);
            t.total = total.reduce((partialSum, a) => partialSum + a, 0);
            t.product_cost = subtotal.reduce((partialSum, a) => partialSum + a, 0);
            
            t.tax = 0;
            let logoCost = this.calcaualteLogoCost();
            console.log(logoCost);
            t.logo_cost = logoCost.cost;
            t.logo_discount = (logoCost.logoDiscount*1) > 0 ? (logoCost.logoDiscount*1) : 0;
            t.applied_logo_discount = (logoCost.appliedDiscount*1) > 0 ? (logoCost.appliedDiscount*1) : 0;
            let haveLogo =  logoCost.haveLogo;
            
            t.oneTimeCost = (t.product_cost*1) > 0 && (this.oneTimeCost*1) > 0 && haveLogo ? (this.oneTimeCost*1) : 0;
            t.subtotal = t.product_cost + (t.logo_cost - t.logo_discount) + (t.product_cost > 0 ? t.oneTimeCost : 0 );
            t.discount = this.detectDiscount(t.subtotal);
            t.tax = this.calculateTax(t);
            t.total = t.subtotal - t.discount + t.tax;
            return t;
        },
        getCustomizationCost(custom)
        {
            return custom ? custom.reduce((sum, item) => sum + item.cost, 0) : 0;
        },
        calcaualteLogoCost()
        {
            let cost = 0;
            for(let c of this.cart )
            {
                cost += this.getCustomizationCost(c.customization)*1;
            }
            
            return {cost};
        },
        freeDelivery(){
            let subtotal = this.cart.map((item) => {
                if(item.offer && item.offer)
                {
                    return this.offerPrice(item).price;
                    // return item.quantity*item.price;
                }
                else
                {
                    return item.quantity*item.price;
                }
            });
            subtotal = subtotal.reduce((partialSum, a) => partialSum + a, 0);
            let discount = freeDelivery ? freeDelivery : null;
            if(discount && subtotal >= (discount.min_cart_price*1)){
                return true;
            }
            return false;
        },
        calculateTax(t) {
            
            let tax = (t.subtotal - t.discount) * (this.gstTax > 0 ? this.gstTax : 0);
            tax = (tax > 0 ? tax / 100 : 0);
            return tax;
        },
        getImagePath(image) {
            if(image)
            {
                image = JSON.parse(image);
                return image[0];
            }
            return null;
        },
        clearCart() {
            this.cart = [];
            localStorage.removeItem('cart');
        },
        detectDiscount(subtotal) {
            if(this.appliedCoupon && this.appliedCoupon.is_percentage > 0 && this.appliedCoupon.amount > 0)
            {
                let disc = (subtotal * this.appliedCoupon.amount)/100;
                return disc.toFixed(2);
            }
            else if(this.appliedCoupon && this.appliedCoupon.amount > 0) {
                return this.appliedCoupon.amount.toFixed(2);
            }
            return 0;
        },
        async applyCoupon() {
            if(!this.coupon.trim()) return false;
            this.couponError = ``;
            let response  = await fetch(site_url + `/api/coupons?code=` + this.coupon.trim());
            response = await response.json();
            if(response && response.data && response.data.data && response.data.data.length > 0) {
                this.appliedCoupon = response.data.data[0];
                localStorage.setItem('coupon', JSON.stringify(this.appliedCoupon));
            }
            else {
                this.appliedCoupon = null;
                this.couponError = `Entered coupon in invalid or expired.`
            }
        },
        removeCoupon() {
            this.appliedCoupon = null;
            this.coupon = null;
            localStorage.removeItem('coupon');
        }
    },
    mounted: async function() {
        this.gstTax = gstTax();
        this.logoPricesDynamix = await minicart.fetchLogoPrices();
        this.initcart();
    }
});

var checkoutPage = null;
if($('#checkout-page').length)
checkoutPage = new Vue({
    el: '#checkout-page',
    data: {
        orderPlaced: null,
        errors: {},
        saving: false,
        logoPricesDynamix: [],
        checkout:{
            phone_email: loginuseremail,
            first_name:``,
            last_name:``,
            last_name:``,
            company:``,
            address:``,
            address2:``,
            city:``,
            postalcode:``,
            saveInfo: false,
            newsletterSubscribe: false,
        },
        shippingOptions: null,
        cart: [],
        note: ``,
        coupon: ``,
        appliedCoupon: null,
        couponError: ``,
        gstTax: ``,
        oneTimeCost: (oneTimeProductCost()*1) > 0 ? (oneTimeProductCost()*1) : 0,
        parcelforceCost: 0,
        dpdCost: 0
    },
    methods: {
        formatMoney(m) {
            return (m*1).toFixed(2);
        },
        cartcount(){
            let cart = localStorage.getItem('cart');
            cart = cart ? JSON.parse(cart) : [];
            return cart.length;
        },
        initcart() {
            let cart = localStorage.getItem('cart');
            cart = cart ? JSON.parse(cart) : [];
            if(cart && cart.length < 1)
            {
                window.location.href = site_url + '/';
            }
            this.cart = this.handleLogoPrices(cart);

            let coupon = localStorage.getItem('coupon');
            coupon = coupon ? JSON.parse(coupon) : null;
            if(coupon && cart.length > 0)
            {
                this.coupon = coupon.coupon_code;
                this.appliedCoupon = coupon;
            }
        },
        renderLogoInfo(c) {
            let amount = 0;
            let totalLogos = 0;
            for(let a of c.logo)
            {
                if((a.price*1) > 0)
                {
                    amount += (a.price*1) * c.quantity;
                    totalLogos += (c.quantity*1)
                }
            }

            return (amount > 0) ? (`Price for ` + `${(totalLogos + (totalLogos > 1 ? ` logos are ` : ` logo is `))} <strong>£${amount.toFixed(2)}</strong>`) : '';
        },
        renderOneTimeFeeHtml() {
            // if(this.cart && this.cart.length > 0)
            // {
            //     let obj = oneTimeProductObject(this.cart);
            //     console.log(`obj`, obj);
            //     if(obj && obj.image !== null && obj.text !== null && obj.image > 0 && obj.text > 0){
            //         return `<div class="d-flex flex-row gap-4">
            //         <span class="cart__content--variant" style="color: rgb(238, 39, 97);">For Logo <strong>£${obj.image.toFixed(2)}</strong></span>
            //             <span class="cart__content--variant" style="color: rgb(238, 39, 97);">For Text <strong>£${obj.text.toFixed(2)}</strong></span>
            //         </div>`
            //     }
            // }
            return ``;
        },
        manualQty(e) {
            let qty = e.target.value;
            let dataId = e.target.getAttribute("data-id");
            let index = this.cart.findIndex((v) => v.id == dataId);
            let s = [...this.cart];
            s[index].quantity = qty;
            this.cart = this.handleLogoPrices(s);
            this.store();
        },
        increment(id) {
            let index = this.cart.findIndex((v) => v.id == id);
            let s = [...this.cart];

            if(s[index].quantity && (s[index].quantity * 1) > 0){
                s[index].quantity = (s[index].quantity*1) + 1;
            }
            else {
                s[index].quantity = 1;
            }
            this.cart = this.handleLogoPrices(s);
            this.store();
        },
        decrement(id) {
            let index = this.cart.findIndex((v) => v.id == id);
            let s = [...this.cart];

            if(s[index].quantity && (s[index].quantity * 1) > 0){
                s[index].quantity = (s[index].quantity*1) - 1;
            }
            else {
                s[index].quantity = 0;
            }
            this.cart = this.handleLogoPrices(s);
            this.store();
        },
        handleLogoPrices(s){
            for(let i in s)
            {
                for(let k in s[i].logo)
                {
                    if(typeof s[i].logo[k].price !== 'undefined')
                    {
                        let exist = this.logoPricesDynamix.filter((item) => {
                            return item.option == (s[i].logo[k].category).toLowerCase().replace(/\s+/g , '-')
                            && item.position == (s[i].logo[k].postion).toLowerCase().replace(/\s+/g , '-')
                            && (s[i].quantity*1) >= (item.from_quantity*1) && (s[i].quantity*1) <= (item.to_quantity*1)
                        });
                        if(exist && exist.length > 0 && exist[0].price)
                        {
                            s[i].logo[k].price = exist[0].price;
                        }
                    }
                }
            }
            this.oneTimeCost = oneTimeProductCost(s);
            return s;
        },
        handleShipping(e) {
            let option = e.target.value;
            if(option == 'parcelforce')
                this.shippingOptions = { value: "Ship by Parcel Force", price: this.parcelforceCost };
            else if(option == 'dpd')
                this.shippingOptions = { value: "DPD", price: this.dpdCost };
            else
                this.shippingOptions = { value: option, price: 0 };
        },
        remove(id) {
            let index = this.cart.findIndex((v) => v.id == id);
            let s = [...this.cart];
            s.splice(index, 1);
            this.cart = this.handleLogoPrices(s);
            this.store();
        },
        store() {
            localStorage.setItem('cart', JSON.stringify(this.cart))
        },
        offerPrice(item) {
            return window.offerPrice(item);
        },
        calculate: function(){
            let t = {
                subtotal: 0,
                total: 0,
                discount: 0,
                logo_cost: 0,
                product_cost:0,
                logo_discount:0,
                applied_logo_discount: 0,
                shipping_cost: 0
            }

            let subtotal = this.cart.map((item) => {
                if(item.offer && item.offer)
                {
                    return this.offerPrice(item).price;
                    // return item.quantity*item.price;
                }
                else
                {
                    return item.quantity*item.price;
                }
            });
            let total = this.cart.map((item) => item.quantity*item.price);
            t.total = total.reduce((partialSum, a) => partialSum + a, 0);
            t.product_cost = subtotal.reduce((partialSum, a) => partialSum + a, 0);
            
            t.tax = 0;
            let logoCost = this.calcaualteLogoCost();
            t.logo_cost = logoCost.cost;
            t.logo_discount = (logoCost.logoDiscount*1) > 0 ? (logoCost.logoDiscount*1) : 0;
            t.applied_logo_discount = (logoCost.appliedDiscount*1) > 0 ? (logoCost.appliedDiscount*1) : 0;
            let haveLogo =  logoCost.haveLogo;
            
            t.oneTimeCost = (t.product_cost*1) > 0 && (this.oneTimeCost*1) > 0 && haveLogo ? (this.oneTimeCost*1) : 0;
            t.subtotal = t.product_cost + (t.logo_cost - t.logo_discount) + (t.product_cost > 0 ? t.oneTimeCost : 0 );
            t.discount = this.detectDiscount(t.subtotal);
            t.tax = this.calculateTax(t);
            t.shipping_cost = ( this.shippingOptions && this.shippingOptions.price * 1 > 0 ? this.shippingOptions.price * 1 : 0 );
            t.total = t.subtotal - t.discount + t.tax + t.shipping_cost;
            return t;
        },
        getCustomizationCost(custom)
        {
            return custom ? custom.reduce((sum, item) => sum + item.cost, 0) : 0;
        },
        calcaualteLogoCost()
        {
            let cost = 0;
            for(let c of this.cart )
            {
                cost += this.getCustomizationCost(c.customization)*1;
            }
            
            return {cost};
        },
        freeDelivery(){
            let subtotal = this.cart.map((item) => {
                if(item.offer && item.offer)
                {
                    return this.offerPrice(item).price;
                    // return item.quantity*item.price;
                }
                else
                {
                    return item.quantity*item.price;
                }
            });
            subtotal = subtotal.reduce((partialSum, a) => partialSum + a, 0);
            let discount = freeDelivery ? freeDelivery : null;
            if(discount && subtotal >= (discount.min_cart_price*1)){
                return true;
            }
            return false;
        },
        calculateTax(t) {
            
            let tax = (t.subtotal - t.discount) * (this.gstTax > 0 ? this.gstTax : 0);
            tax = (tax > 0 ? tax / 100 : 0);
            return tax;
        },
        getImagePath(image) {
            if(image)
            {
                image = JSON.parse(image);
                return image[0];
            }
            return null;
        },
        clearCart() {
            this.cart = [];
            localStorage.removeItem('cart');
        },
        detectDiscount(subtotal) {
            if(this.appliedCoupon && this.appliedCoupon.is_percentage > 0 && this.appliedCoupon.amount > 0)
            {
                let disc = (subtotal * this.appliedCoupon.amount)/100;
                return disc;
            }
            else if(this.appliedCoupon && this.appliedCoupon.amount > 0) {
                return this.appliedCoupon.amount > subtotal ? subtotal : this.appliedCoupon.amount;
            }
            return 0;
        },
        async applyCoupon() {
            if(!this.coupon.trim()) return false;
            this.couponError = ``;
            let response  = await fetch(site_url + `/api/coupons?code=` + this.coupon.trim());
            response = await response.json();
            if(response && response.data && response.data.data && response.data.data.length > 0) {
                this.appliedCoupon = response.data.data[0];
                localStorage.setItem('coupon', JSON.stringify(this.appliedCoupon));
            }
            else {
                this.appliedCoupon = null;
                this.couponError = `Entered coupon in invalid or expired.`
            }
        },
        removeCoupon() {
            this.appliedCoupon = null;
            this.coupon = null;
            localStorage.removeItem('coupon');
        },
        async submit() {
            if(this.checkout.saveInfo) {
                localStorage.setItem('addressInfo', JSON.stringify(this.checkout));
            }
            if(!this.shippingOptions) {
                set_notification('error', 'Please select the shipping and delivery option.')
            }
            if(this.saving) return false;
            let haveErrors = false;

            let errs = {};
            let checkout = JSON.parse(JSON.stringify(this.checkout));
            for(let e in checkout) {
                if(checkout[e] === ``) {
                    errs[e] = ``;
                    haveErrors = true;
                }
            }

            console.log(`haveErrors`, haveErrors);
            if(!haveErrors)
            {
                this.errors = {};
                let data = {...checkout, ...{coupon: this.appliedCoupon, shipping: ( this.shippingOptions && this.shippingOptions.price ? this.shippingOptions.price : 0 ), cart: this.cart, token: $('#checkout-page').attr('data-token')} };
                this.saving = true;
                data.lastId = localStorage.getItem('orderId') ? localStorage.getItem('orderId') : null;
                let response = await fetch(site_url + '/api/orders/booking', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
					},
					body: JSON.stringify(data),
				});
				response = await response.json();
                if(response && response.status)
                {
                    localStorage.setItem('orderId', response.orderId);

                    window.scrollTo(0,0)
                    this.orderPlaced = response.orderId;
                    localStorage.removeItem('cart');
                    localStorage.removeItem('coupon');
                }
                else if(response && response.message)
                {
                    set_notification('error', response.message)
                }
                else
                {
                    set_notification('error', 'Something went wrong. Order could not be placed.')
                }
                this.saving = false;

                return response;
            }
            else
            {
                this.errors = errs;
            }
        }
    },
    mounted: async function() {
        this.parcelforceCost = parcelforceCost*1;
        this.dpdCost = dpdCost*1;
        this.gstTax = gstTax();
        this.logoPricesDynamix = await minicart.fetchLogoPrices();
        this.initcart();
        let addressInfo = localStorage.getItem('addressInfo');
        if(addressInfo) {
            addressInfo = JSON.parse(addressInfo);
            this.checkout = {...this.checkout, ...addressInfo}
        }
    }
});