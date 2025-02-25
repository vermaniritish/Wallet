let order = new Vue({
    el: '#product',
    data: {
        mounting: true,
        defaultSizes: [],
        sizes: [],
        selectedSize: {},
        selectedSizeIds: {},
        selectedCategory: null,
        subCategories: [],
        selectedGender: '',
        title: '',
        selectedColor: [],
        
        purchase_price: '',
        margin: '',
        price: '',
        maxPrice: '',
        selectedBrand: [],
        loading: false,
        url: '',
        selectedSubCategory: [],
        description: null,
        tags: null,
        sku_number: null,
        availableColors: JSON.parse($('#availableColor').text()),
        short_description: '',
        activeColor: null,
        dragValues: null,
        dropValues: null,
        colorImages: {}
    },
    mounted: function() {
        this.initBasics();
        this.initTagIt();
        init_editor('#product-editor');
        this.initEditValues();
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
            $('#sortable tr').css('background-color', '#FFF');
            this.dropValues = {colorSelectedId, sizeIndex};
            let sizes = this.selectedSize[colorSelectedId];
            let removedItem = sizes.splice(this.dragValues.sizeIndex, 1)[0];
            sizes.splice(sizeIndex, 0, removedItem);
            this.$set(this.selectedSize, colorSelectedId, sizes);
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
        initEditValues: function () {
            this.sizes = $('#availableSizes').text() ? JSON.parse($('#availableSizes').text()) : [];
            if ($('#edit-form').length > 0) {
                let data = JSON.parse($('#edit-form').text());
                this.url = admin_url + '/products/' + data.id + '/edit';
                this.selectedSubCategory = data && data.sub_categories && data.sub_categories.length > 0 ? data.sub_categories.map(category => category.sub_category_id) : [];
                this.selectedCategory = data.category_id;
                this.title = data.title;
                this.selectedColor = data && data.colors && data.colors.length > 0 ? data.colors.map(colors => colors.id.toString()) : [];
                this.activeColor = this.selectedColor.length > 0 ? this.selectedColor[0] : null;
                this.selectedGender = data.gender;
                this.price = data.price;
                this.purchase_price = data.purchase_price;
                this.margin = data.margin;
                this.maxPrice = data.max_price;
                this.selectedBrand = data && data.brands && data.brands.length > 0 ? data.brands.map(brand => brand.id) : [];
                this.selectedSizeIds = {};
                this.short_description = data.short_description;
                this.description = data.description;
                this.sku_number = data.sku_number;
                this.colorImages = data.color_images ? JSON.parse(data.color_images) : {};
                if (this.description !== null) {
                    put_editor_html('product-editor', this.description.trim());
                }
                if (data && data.sizes && data.sizes.length > 0) 
                {
                    console.log(data.sizes);

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
                
            }
            else {
                this.url = admin_url + '/products/add';
            };
        },
        updateSelectedSize(colorSelectedId) 
        {
            if (Array.isArray(this.defaultSizes)) {
                // if (!this.selectedSize.hasOwnProperty(colorSelectedId)) {
                //     this.$set(this.selectedSize, colorSelectedId, []);
                // }
                let selectedSizes = this.selectedSize[colorSelectedId] && this.selectedSize[colorSelectedId].length > 0 ? this.selectedSize[colorSelectedId] : [];
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
                this.$set(this.selectedSize, colorSelectedId, selectedSizes);
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
        submitForm: async function() {
            if (!this.loading) {
                if ($('#product-form').valid()) {
                    this.loading = true;
                    let formData = new FormData(document.getElementById('product-form'));
                    formData.append('sizeData', JSON.stringify(this.selectedSize));
                    formData.append('description', get_editor_html('product-editor'));
                    formData.append('color_images', JSON.stringify(this.colorImages));
                    let response = await fetch(this.url, {
                        method: 'POST',
                        body: formData,
                    });
                    response = await response.json();
                    if(response && response.status)
                    {
                        this.loading = false;
                        setTimeout(function () {
                            window.location.href = (admin_url + '/products/' + response.id + '/view');
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