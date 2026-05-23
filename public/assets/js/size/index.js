function createSizeManager(config) {

    return new Vue({

        el: config.el,

        data: {

            category: config.category,

            mens: [{
                id: null,
                size_title: '',
                from_cm: '',
                to_cm: '',
                chest: '',
                waist: '',
                hip: '',
                length: '',
                vat: config.vat || 0
            }]
        },

        mounted() {
            this.initEditValues();
            this.initSortable();
        },

        methods: {

            initEditValues() {

                if (
                    $(config.dataSelector).length > 0 &&
                    $(config.dataSelector).text().trim() !== '[]'
                ) {

                    let data = JSON.parse($(config.dataSelector).text());

                    this.mens = data;
                }
            },

            initSortable() {

                const el = this.$refs.menContainer;

                if (!el) return;

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
                    vat: config.vat || 0
                });
            },

            async remove(id, i) {

                if (id) {

                    if (confirm('Are you sure to remove the size?')) {

                        await fetch(admin_url + '/size/' + id + '/delete');
                    }
                }

                this.mens.splice(i, 1);
            },

            validate(e) {

    if (e) {
        e.preventDefault();
    }

    const seen = {};

    for (let i = 0; i < this.mens.length; i++) {

        const item = this.mens[i];

        const size_title = String(item.size_title || '')
            .trim()
            .toLowerCase();

        const length = String(item.length || '')
            .trim()
            .toLowerCase();

        const vat = parseInt(item.vat || 0);

        // skip blank rows
        if (size_title === '' && length === '') {
            continue;
        }

const vatText = vat ? 'with VAT' : 'without VAT';

const key = size_title + '|' + length + '|' + vat;

        console.log('ROW:', i, key);

        // ignore same DB row while editing
        if (
            seen[key] &&
            seen[key] !== item.id
        ) {

            console.log(
    'DUPLICATE FOUND:',
    size_title,
    length,
    vatText
);

set_notification(
    'error',
    `Duplicate size "${size_title}" ${length ? '(' + length + ')' : ''} ${vatText} is not allowed`
);

            return false;
        }

        seen[key] = item.id || ('new_' + i);
    }

    document.querySelector(config.formSelector).submit();
}
        }
    });
}


/*
|--------------------------------------------------------------------------
| MEN
|--------------------------------------------------------------------------
*/

if ($('#men').length) {

    createSizeManager({
        el: '#men',
        category: 'men',
        dataSelector: '#male',
        formSelector: '#men-size-form',
        vat: 0
    });
}


/*
|--------------------------------------------------------------------------
| WOMEN
|--------------------------------------------------------------------------
*/

if ($('#women').length) {

    createSizeManager({
        el: '#women',
        category: 'women',
        dataSelector: '#female',
        formSelector: '#women-size-form',
        vat: 1
    });
}


/*
|--------------------------------------------------------------------------
| UNISEX
|--------------------------------------------------------------------------
*/

if ($('#uni').length) {

    createSizeManager({
        el: '#uni',
        category: 'unisex',
        dataSelector: '#unisex',
        formSelector: '#unisex-size-form',
        vat: 1
    });
}


/*
|--------------------------------------------------------------------------
| KIDS
|--------------------------------------------------------------------------
*/

if ($('#kidkid').length) {

    createSizeManager({
        el: '#kidkid',
        category: 'kids',
        dataSelector: '#kids',
        formSelector: '#kids-size-form',
        vat: 1
    });
}