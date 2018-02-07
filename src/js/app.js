(function() {
    function loadData() {
        $.get('/example.php', function(data) {
            if (!('error' in data)) populateTable(data);
        });
    }

    function populateTable(data) {
        const tmpl_tableRow = _.template(
            '<li>' +
                '<div class="table-col"><%= name %></div>' +
                '<div class="table-col even"><%= unit_price %></div>' +
                '<div class="table-col"><%= qty_sold %></div>' +
                '<div class="table-col even"><%= gross_total %></div>' +
                // '<div class="clearfix"></div>' +
            '</li>'
        );

        data.items.forEach(function(row) {
            let el_newRow = tmpl_tableRow({
                'name': row.name,
                'unit_price': '$' + row.unit_price.toFixed(),
                'qty_sold': row.qty_sold,
                'gross_total': '$' + row.gross_total.toFixed(2)
            });

            $('.report-table').append(el_newRow);
        });
    }

    $(document).ready(loadData());
})();
