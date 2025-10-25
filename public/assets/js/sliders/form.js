var slider = new Vue({
    el: '#slider',
    data: {
        type: 'main'
    },
    mounted() {
        this.type = defaultType ? defaultType : 'main';
    },
    methods: {
    }
});

const buttonStatus = document.getElementById('buttonStatus');
const buttonFields = document.getElementById('buttonFields');

buttonStatus.addEventListener('change', function() {
    if (buttonStatus.checked) {
        buttonFields.style.display = 'block';
    } else {
        buttonFields.style.display = 'none';
    }
});

// Trigger initial check
if (buttonStatus.checked) {
    buttonFields.style.display = 'block';
}

