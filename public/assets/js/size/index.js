if($('#men').length)
{
    let men = new Vue({
        el: '#men',
        data: {
            mens: [{ 
                id: null,
                size_title: '',
                from_cm: '',
                to_cm: '',
                chest: '',
                waist: '',
                hip: '',
                length: '',
                vat: 0,
            }
        ]
        },
        mounted: function() {
            this.initEditValues();
            this.initSortable();
        },
        methods: {
            initEditValues: function () {
                if ($('#male').length > 0 && $('#male').text().trim() !== '[]') {
                    let data = JSON.parse($('#male').text());
                    this.mens = data;
                }
            },
            initSortable() {
                const el = this.$refs.menContainer;
                Sortable.create(el, {
                    handle: '.handle',
                    animation: 150
                });
            },
            addForm() {
                this.mens.push({ 
                    id: null,
                    size_title: '',
                    from_cm: '',
                    to_cm: '',
                    chest: '',
                    waist: '',
                    hip: '',
                    length: '',
                    vat: 0
                });
            },
            async remove(id, i) {
                if( id ) {
                    if(confirm('Are you sure to remove the size?')) {       
                        await fetch(admin_url + '/size/'+id+'/delete');
                    }
                }
                this.mens.splice(i, 1);
            },
            validate() {
                let data = [...this.mens];
                const duplicates = data.filter((item, index, self) =>
                    index !== self.findIndex((t) => (
                        t.size_title === item.size_title
                    ))
                );            
                if(duplicates && duplicates.length > 0)  {
                    set_notification('error', 'Duplicate entries are not allowed');
                    return false;
                }
                else {
                    $('#men-size-form').submit();
                }
            }
        }
    });
}
if($('#women').length)
{
    let women = new Vue({
        el: '#women',
        data: {
            mens: [{ 
                id: null,
                size_title: '',
                from_cm: '',
                to_cm: '',
                chest: '',
                waist: '',
                hip: '',
                length: '',
                vat: 0
            }
        ]
        },
        mounted: function() {
            this.initEditValues();
            this.initSortable();
        },
        methods: {
            initEditValues: function () {
                if ($('#female').length > 0 && $('#female').text().trim() !== '[]') {
                    let data = JSON.parse($('#female').text());
                    this.mens = data;
                }
            },
            initSortable() {
                const el = this.$refs.menContainer;
                Sortable.create(el, {
                    handle: '.handle',
                    animation: 150
                });
            },
            addForm() {
                this.mens.push({ 
                    id: null,
                    size_title: '',
                    from_cm: '',
                    to_cm: '',
                    chest: '',
                    waist: '',
                    hip: '',
                    length: '',
                    vat: 1
                });
            },
            async remove(id, i) {
                if( id ) {
                    if(confirm('Are you sure to remove the size?')) {       
                        await fetch(admin_url + '/size/'+id+'/delete');
                    }
                }
                this.mens.splice(i, 1);
            },
            validate() {
                let data = [...this.mens];
                console.log(data);
                const duplicates = data.filter((item, index, self) =>
                    index !== self.findIndex((t) => {
                        console.log(t.title, item.title);
                        return t.size_title === item.size_title;
                    })
                );

                if(duplicates && duplicates.length > 0)  {
                    set_notification('error', 'Duplicate entries are not allowed');
                    return false;
                }
                else {
                    $('#women-size-form').submit();
                }
            }
        }
    });
}
if($('#uni').length)
{
    let unisex = new Vue({
        el: '#uni',
        data: {
            mens: [{ 
                id: null,
                size_title: '',
                from_cm: '',
                to_cm: '',
                chest: '',
                waist: '',
                hip: '',
                length: '',
                vat: 0
            }
        ]
        },
        mounted: function() {
            this.initEditValues();
            this.initSortable();
        },
        methods: {
            initEditValues: function () {
                if ($('#unisex').length > 0 && $('#unisex').text().trim() !== '[]') {
                    let data = JSON.parse($('#unisex').text());
                    this.mens = data;
                }
            },
            initSortable() {
                const el = this.$refs.menContainer;
                Sortable.create(el, {
                    handle: '.handle',
                    animation: 150
                });
            },
            addForm() {
                this.mens.push({ 
                    id: null,
                    size_title: '',
                    from_cm: '',
                    to_cm: '',
                    chest: '',
                    waist: '',
                    hip: '',
                    length: '',
                    vat: 1
                });
            },
            async remove(id, i) {
                if( id ) {
                    if(confirm('Are you sure to remove the size?')) {       
                        await fetch(admin_url + '/size/'+id+'/delete');
                    }
                }
                this.mens.splice(i, 1);
            },
            validate() {
                let data = [...this.mens];
                const duplicates = data.filter((item, index, self) =>
                    index !== self.findIndex((t) => (
                        t.size_title === item.size_title
                    ))
                );            
                if(duplicates && duplicates.length > 0)  {
                    set_notification('error', 'Duplicate entries are not allowed');
                    return false;
                }
                else {
                    $('#unisex-size-form').submit();
                }
            }
        }
    });
}

if($('#kidkid').length)
{
    let kids = new Vue({
        el: '#kidkid',
        data: {
            mens: [{ 
                id: null,
                size_title: '',
                from_cm: '',
                to_cm: '',
                chest: '',
                waist: '',
                hip: '',
                length: '',
                vat: 0
            }]
        },
        mounted: function() {
            this.initEditValues();
            this.initSortable();
        },
        methods: {
            initEditValues: function () {
                if ($('#kids').length > 0 && $('#kids').text().trim() !== '[]') {
                    let data = JSON.parse($('#kids').text());
                    this.mens = data;
                }
            },
            initSortable() {
                const el = this.$refs.menContainer;
                Sortable.create(el, {
                    handle: '.handle',
                    animation: 150
                });
            },
            addForm() {
                this.mens.push({ 
                    id: null,
                    size_title: '',
                    from_cm: '',
                    to_cm: '',
                    chest: '',
                    waist: '',
                    hip: '',
                    length: '',
                    vat: 1
                });
            },
            async remove(id, i) {
                if( id ) {
                    if(confirm('Are you sure to remove the size?')) {       
                        await fetch(admin_url + '/size/'+id+'/delete');
                    }
                }
                this.mens.splice(i, 1);
            },
            validate() {
                let data = [...this.mens];
                const duplicates = data.filter((item, index, self) =>
                    index !== self.findIndex((t) => (
                        t.size_title === item.size_title
                    ))
                );            
                if(duplicates && duplicates.length > 0)  {
                    set_notification('error', 'Duplicate entries are not allowed');
                    return false;
                }
                else {
                    $('#kids-size-form').submit();
                }
            }
        }
    });
}