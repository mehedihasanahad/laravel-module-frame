let processTypeId = document.getElementById('processTypeId').value;
let csrf_token = document.querySelector('meta[name="csrf_token"]').content;

function showApplicationListByDesk(apiUrl,targetElementId,requestCallback) {
    $('#' + targetElementId).DataTable({
        iDisplayLength: '25',
        processing: true,
        serverSide: true,
        searching: true,
        responsive: true,
        destroy: true,
        ajax: {
            headers: {
                'X-CSRF-TOKEN': csrf_token
            },
            url: apiUrl,
            method: 'POST',
            data: requestCallback
        },
        columns: [{
            data: 'tracking_no',
            name: 'tracking_no',
            orderable: false,
            searchable: true
        },
            {
                data: 'desk_name',
                name: 'desk_name',
                orderable: false,
                searchable: false
            },
            {
                data: 'process_name',
                name: 'process_name',
                orderable: false,
                searchable: false
            },
            {
                data: 'json_object',
                name: 'json_object',
                orderable: false,
            },
            {
                data: 'status_name',
                name: 'status_name',
                orderable: false,
                searchable: false
            },
            {
                data: 'updated_at',
                name: 'updated_at',
                orderable: false,
                searchable: false
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
        ],
    });
}


