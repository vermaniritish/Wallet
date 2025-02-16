function submitForm(statusKey) {
    document.getElementById('statusInput').value = statusKey;
    var allTabs = document.querySelectorAll('.nav-pills .nav-link');
    allTabs.forEach(function (el) {
        el.classList.remove('active');
    });
    var clickedTab = document.getElementById(statusKey + '-tab');
    if (clickedTab) {
        clickedTab.classList.add('active');
    }
    document.getElementById('filterForm').submit();
}

async function switch_diary_page_action(url, that) {
    var flag = $(that).data('value');
    var currentStatus = $('#Currentstatus').val();
    if (flag.toLowerCase() === currentStatus) {
        var text = $(that).closest('.dropdown').find('.btn').text().toLowerCase();
        set_notification('error', 'Cannot change status to ' + text + ' again.');
        return;
    }
    var statusStyles = await getStatuses();
    $.ajax({
        url: url,
        type: 'post',
        data: {
            flag: flag,
            _token: csrf_token()
        },
        success: function (resp) {
            if ($(that).data('value') && resp.status === 'success') {
                var buttonText = $(that).text();
                var statusData = statusStyles[flag];
                var buttonStyle = statusData.styles || 'background-color: #ffffff; color: #000000;';
                $(that).closest('.dropdown').find('.btn').removeClass().addClass('btn btn-sm dropdown-toggle').attr('style', buttonStyle).text(buttonText);
                set_notification('success', 'Order status updated successfully.');
            }
        }
    })
}
function getStatuses() {
    return $.ajax({
        url: admin_url +'/order/getStatus',
        method: 'GET',
    });
}
