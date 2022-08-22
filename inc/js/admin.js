( function ( $ ) {
	// main part of script
	$( document ).ready( function () { 
        let feedbackRequestsTable = $('.feedback-requests').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: ajaxurl,
                type: "GET",
                contentType: "application/json; charset=utf-8",
                dataType: 'json',
                data: function(d){
                    d.action = 'fdbckrqst_feedbacks_table';
                },
            },
            "columnDefs": [
                {
                    "orderable": false,
                    "targets": 4
                },
            ],
            columns: [
                { data: "name"},
                { data: "email"},
                { data: "phone"},
                { data: "date"},
                { data: null,
                render: function(data, type, row){
                    return '<a class="feedback-requests-delete button-link-delete" data-id="'+row.id+'" href="#">'+fdbckrqstValues.delete+'</a>';
                }},
            ]
        });

        $('body').on('click', '.feedback-requests-delete', function(e){
            e.preventDefault();
            let id = $(e.target).attr('data-id');
            let data = {
                'action': 'fdbckrqst_delete_feedback',
                'id': id
            };
            $.post(
                ajaxurl,
                data,
                function(response) {
                    feedbackRequestsTable.ajax.reload();
                }
            );
        })
    });
} )( jQuery );