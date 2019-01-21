$(document).ready(function () {
    $('.usernames').select2({
        width: '100%',
        tags: true,
        multiple: true,
        tokenSeparators: [',', ' '],
        minimumInputLength: 1,
        minimumResultsForSearch: 10,
        allowClear: true,
        ajax: {
            url: '/get-users',
            dataType: 'json',
            type: "GET",
            data: function (params) {
                var query = {
                    search: params.term,
                    type: 'public'
                }

                return query;
            },
            processResults: function (data) {
                var arr = []
                $.each(data, function (index, value) {
                    arr.push({
                        id: value,
                        text: value
                    })
                })
                return {
                    results: arr
                };
            },
        }
    })
})