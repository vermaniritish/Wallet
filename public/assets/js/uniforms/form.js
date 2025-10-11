let order = new Vue({
    el: '#product',
    data: {
        mounting: true,
        defaultSizes: [],
        sizes: [],
        selectedSize: {},
        selectedSizeIds: {},
        selectedCategory: ``,
        selectedSubCategory: ``,
        selectedProduct: null,
        selectedColor: [],
        subCategories: [],
        products: [],
        schools: [],
        selectedGender: '',
        title: '',
        purchase_price: '',
        margin: '',
        price: '',
        maxPrice: '',
        loading: false,
        url: '',
        description: null,
        tags: null,
        sku_number: null,
        availableColors: JSON.parse($('#availableColor').text()),
        short_description: '',
        activeColor: null,
        dragValues: null,
        dropValues: null,
        colorImages: {},
        embroidered_logo: 0,
        printed_logo: 0,
        common_product: 0,
        non_exchange: 0,
        shop_visible: 0,
        website_visible: 0,
    },
    mounted: function() {
        if(pageId){
            this.selectedProduct = pageId;
            this.initEditValues();
        }
        this.initBasics();
        this.initTagIt();
        init_editor('#product-editor');
        this.mounting = false;
        document.getElementById('product-form').classList.remove('d-none');
        
    },
    methods: {
        allowDrop(ev) {
            ev.preventDefault();
            $('#sortable tr').css('background-color', '#FFF');
            if($(ev.target).is('tr'))
                $(ev.target).css('background-color', 'whitesmoke');
            else
                $(ev.target).parents('tr').css('background-color', 'whitesmoke');
        },
        drag(colorSelectedId, sizeIndex) {
            this.dragValues = {colorSelectedId, sizeIndex}
        },
        drop(colorSelectedId, sizeIndex) {
            console.log(colorSelectedId, sizeIndex, this.dragValues.sizeIndex);
            $('#sortable tr').css('background-color', '#FFF');
            this.dropValues = {colorSelectedId, sizeIndex};
            let selectedSizes = JSON.parse(JSON.stringify(this.selectedSize));
            let sizes = selectedSizes[colorSelectedId];
            let removedItem = sizes.splice(this.dragValues.sizeIndex, 1)[0];
            sizes.splice(sizeIndex, 0, removedItem);
            this.selectedSize  = selectedSizes;
            // this.$set(this.selectedSize, colorSelectedId, sizes);
        },
        initTagIt: function () {
            $(".tag").tagit();
        },
        initBasics: function () {
            setTimeout(function () {
                $('select').removeClass('no-selectpicker');
                initSelectpicker('select');
            }, 50);
        },
        updateSelectedColor: function() {
            for (let colorId of this.selectedColor) {
                if (!this.selectedSizeIds.hasOwnProperty(colorId)) {
                    this.$set(this.selectedSizeIds, colorId, []);
                }
            }
        },
        markActiveColor: function(id) {
            if($('#edit-form').length > 0) 
            {
                this.activeColor = id;
                this.updateSelectedSize(id);
            }
            else
            {
                if(this.defaultSizes.length > 0)
                {
                    this.activeColor = id;
                    this.updateSelectedSize(id);
                }
                else   
                    set_notification('error', 'Please select sizes to proceed.')
            }
        },
        updateImage: function(id) {
                initImageUploader('#colorImage'+id, this.imageCallback);
        },
        imageCallback: function(response) {
            this.$set(this.colorImages, this.activeColor, {
                path: response.path,
                colorId: id
            });
        },
        calculatePrice: function() {
            let p = ((this.purchase_price/this.margin)*100);
            this.price = p > 0 ? p.toFixed(2) : ``;
            
        },
        initEditValues: async function () {
            this.sizes = $('#availableSizes').text() ? JSON.parse($('#availableSizes').text()) : [];
            let response = await fetch(admin_url + '/products/' + this.selectedProduct + '/fetch');
            response = await response.json();
            if(response && response.status)
            {
                let data = response.product;
                this.id = data.id;
                this.defaultSizes = data.sizes && data.sizes.length > 0 ? data.sizes.map((v) => (v.id)) : [];
                this.title = data.title;
                this.selectedColor = data && data.colors && data.colors.length > 0 ? data.colors.map(colors => colors.id.toString()) : [];
                this.activeColor = this.selectedColor.length > 0 ? this.selectedColor[0] : null;
                this.selectedGender = data.gender;
                this.price = data.price;
                this.purchase_price = data.purchase_price;
                this.margin = data.margin;
                this.maxPrice = data.max_price;
                this.selectedSizeIds = {};
                this.short_description = data.short_description;
                this.description = data.description;
                this.sku_number = data.sku_number;
                this.embroidered_logo = data.embroidered_logo;
                this.printed_logo = data.printed_logo;
                this.colorImages = data.color_images ? JSON.parse(data.color_images) : {};
                this.common_product = data.common_product;
                this.non_exchange = data.non_exchange;
                this.website_visible = data.website_visible;
                this.shop_visible = data.shop_visible;
                // if (this.description !== null) {
                //     put_editor_html('product-editor', this.description.trim());
                // }
                if (data && data.sizes && data.sizes.length > 0) 
                {

                    data.sizes.forEach(size => {
                        if (!this.selectedSize[size.color_id]) {
                            this.selectedSize[size.color_id] = [];
                        }
                        this.selectedSize[size.color_id].push({
                            id: size.id,
                            size_title: size.size_title,
                            from_cm: size.from_cm,
                            to_cm: size.to_cm,
                            price: parseFloat(size.price),
                            sale_price: size.sale_price && (size.sale_price*1) > 0 ? parseFloat(size.sale_price) : ``,
                            status: size.status
                        });
                    });
                }
                await sleep(400);
                $('select').selectpicker('refresh');
            }
        },
        updateSelectedSize(colorSelectedId) 
        {
            if (Array.isArray(this.defaultSizes)) {
                // if (!this.selectedSize.hasOwnProperty(colorSelectedId)) {
                //     this.$set(this.selectedSize, colorSelectedId, []);
                // }
                let original = JSON.parse(JSON.stringify(this.selectedSize));
                let selectedSizes = original[colorSelectedId] && original[colorSelectedId].length > 0 ? original[colorSelectedId] : [];
                for (let sizeId of this.defaultSizes) {
                    let size = this.sizes.find(size => size.id === sizeId);
                    if (size) {
                        let existingSize = selectedSizes.find(selected => selected.id === size.id);
                        if (!existingSize) {
                            selectedSizes.push({
                                id: size.id,
                                size_title: size.size_title,
                                from_cm: size.from_cm,
                                to_cm: size.to_cm,
                                price: this.price > 0 ? this.price : 0,
                                sale_price: this.maxPrice > 0 ? this.maxPrice : ``,
                                status: size.status
                            });
                        }
                    } 
                }
                original[colorSelectedId] = selectedSizes;
                this.selectedSize = original;
                this.$set(original, colorSelectedId, selectedSizes);
            }
        },
         
        removeSize(colorSelectedId, sizeIndex) 
        {
            let allSizes = {...this.selectedSize};
            this.selectedSize = [];
            let selectedSizes = allSizes[colorSelectedId];
            selectedSizes.splice(sizeIndex, 1);
            allSizes[colorSelectedId] = selectedSizes;
            this.selectedSize = allSizes;
        },       
        updateSizes: async function() 
        {
            let response = await fetch(admin_url + "/products/getSize/" + this.selectedGender);
            response = await response.json();
            if(response && response.status)
            {
                this.sizes = response.sizes;
                setTimeout(function () {
                    $(".size-select").selectpicker('refresh');
                }, 50);
            } else{
                set_notification('error', response.message);
            }
        },
        updateSubCategory: async function() {
            let response = await fetch(admin_url + "/products/getSubCategory/" + this.selectedCategory);
            response = await response.json();
            if(response && response.status)
            {
                this.subCategories = response.subCategory;
                setTimeout(function () {
                    $("#sub-category-form select").selectpicker("refresh");
                }, 50);
            } else{
                set_notification('error', response.message);
            }
        },
        async updateProducts() {
            await sleep(100);
            let response = await fetch(admin_url + `/products/search?category=${this.selectedCategory}&subcategory=${this.selectedSubCategory}`);
            response = await response.json();
            if(response && response.status)
            {
                this.products = response.products;
            }
            else
            {
                this.products = [];
            }
            await sleep(400);
            $('#productDropdown').selectpicker('refresh');
        },
        submitForm: async function() {
            if (!this.loading) {
                if ($('#product-form').valid()) {
                    this.loading = true;
                    let formData = new FormData(document.getElementById('product-form'));
                    formData.append('sizeData', JSON.stringify(this.selectedSize));
                    formData.append('color_images', JSON.stringify(this.colorImages));
                    let response = await fetch(
                        pageId ? admin_url + '/uniforms/' + pageId + '/edit' : admin_url + '/uniforms/add', 
                        {
                            method: 'POST',
                            body: formData,
                        }
                    );
                    response = await response.json();
                    if(response && response.status)
                    {
                        this.loading = false;
                        set_notification('success', response.message);

                        setTimeout(function () {
                            window.location.href = (admin_url + '/uniforms/' + response.id + '/edit');
                        }, 200)
                    }else{
                        this.loading = false;
                        set_notification('error', response.message);
                    }
                } else {
                    this.loading = false;
                    return false;
                }
            }
        }, 
    },
    watch: {
        selectedGender: function () {
            this.updateSizes();
        },
        selectedColor: function () {
            setTimeout(function () {
                $(".size-select").selectpicker('refresh');
            }, 50);
        },
        selectedCategory: function () {
            this.updateSubCategory();
        },
    },
});


let customization = new Vue({
    el: '#customization',
    data: {
        id: null,
        loading: false,
        items: [
        {
          id: "1",
          title: "",
          description: "",
          cost: 0,
          quantity: 1,
          total: 0,
          required: false
        }
      ],
      grandTotal: 0
    },
    async mounted() {
        let response = await fetch(admin_url + '/products/' + order.selectedProduct + '/fetch');
        response = await response.json();
        if(response && response.status)
        {
            let data = response.product;
            this.id = data.id;
            if(data && data.id && data.logo_customization) {
                this.items = JSON.parse(data.logo_customization);
            }
        }
    },
     methods: {
        updateItem(item, field, value) {
            item[field] = value;
            if (field === "cost" || field === "quantity") {
                item.total = item.cost * item.quantity;
            }
        },
        addRow() {
            const newId = Date.now().toString();
            this.items.push({
                id: newId,
                title: "",
                description: "",
                cost: 0,
                quantity: 1,
                total: 0,
                required: false
            });
        },
        removeRow(id) {
            if (this.items.length > 1) {
                this.items = this.items.filter(i => i.id !== id);
            }
        },
        calculateGrandTotal() {
            return this.grandTotal = (this.items && this.items.length > 0 ? this.items.reduce(
                (sum, item) => sum + Number(item.total),
                0
            ) : 0)*1;
        },
        submitForm: async function (e) {
            e.preventDefault();

            if (this.loading) return false;
            this.loading = true;

            try {
                // 👇 replace `product_id` with your actual product variable
                let response = await fetch(admin_url + '/products/' + this.id + '/update-customization', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ items: this.items, _token: csrf_token() }),
                });

                response = await response.json();

                if (response && response.status) {
                    set_notification('success', response.message);
                } else {
                    set_notification('error', response.message);
                }
            } catch (error) {
                console.error('Submit error:', error);
                set_notification('error', 'Something went wrong!');
            } finally {
                this.loading = false;
            }
        }
    }
});