// generate ajax dataTables
let initializeDataTable = (tableId, tableDataUrl, columnsConfig, buttonsConfig) => {
  // Load table data from datable library
  var dataTable = $("#" + tableId).DataTable({
    destroy: true,
    processing: true,
    serverSide: true,
    language: {
      paginate: {
        next: '<i class="fa-solid fa-angle-right"></i>',
        previous: '<i class="fa-solid fa-angle-left"></i>',
      },
      processing: "<img src='/assets/dist/img/6.gif' height=50px width=50px>"
    },
    order: [
      [2, "asc"]
    ], //init datatable not ordering
    responsive: true,
    // responsive: {
    //   details: {
    //     display: $.fn.dataTable.Responsive.display.modal({
    //       header: function (row) {
    //         var data = row.data();
    //         return data[0] + ' ' + data[1];
    //       }
    //     }),
    //     renderer: $.fn.dataTable.Responsive.renderer.tableAll({
    //       tableClass: 'table'
    //     })
    //   }
    // },
    // language: {
    //   searchPlaceholder: 'Search...',
    //   sSearch: '',
    // },
    autoWidth: false,
    // ajax fetch data
    ajax: {
      url: tableDataUrl,
      type: "GET",
    },
    columns: columnsConfig,
    // dom: 'lBfrtip',
    dom:
      "<'row'<'col-md-2'l><'col-md-6'B><'col-md-4'f>>" +
      "<'row'<'col-md-12'tr>>" +
      "<'row'<'col-md-5'i><'col-md-7'p>>",
    buttons: buttonsConfig,
    // manage scroll and pagination
    // scrollY: '265px',
    // scrollCollapse: true,
    // paging: false,
    // scrollX: true,
    bPaginate: true,
    bLengthChange: true,
    pageLength: 25,
    lengthMenu: [
      [10, 25, 50, 100, 250, 500, -1],
      [10, 25, 50, 100, 250, 500, "All"],
    ],
    bFilter: true,
    bInfo: true,
    bAutoWidth: true,
  });

  // Apply responsive table after data is loaded
  dataTable.on('draw', function () {
    dataTable.responsive.recalc();
  });
  // (un)select table row on click
  $("#" + tableId + " tbody").on("click", "tr", function () {
    if ($(this).hasClass("selected")) {
      $(this).removeClass("selected");
    } else {
      dataTable.$("tr.selected").removeClass("selected");
      $(this).addClass("selected");
    }
  });
  // remove selected row
  $("#button").on("click", function () {
    dataTable.row(".selected").remove().draw(false);
  });
}

// convert table to dataTables
let convertDataTable = (table, buttons) => {
  // Load table data from datatable library
  var dataTable = $("#" + table).DataTable({
    dom: 'Bfrtip',
    buttons: buttons,
    scrollX: true
  });
  // (un)select table row on click
  $("#" + tableId + " tbody").on("click", "tr", function () {
    if ($(this).hasClass("selected")) {
      $(this).removeClass("selected");
    } else {
      dataTable.$("tr.selected").removeClass("selected");
      $(this).addClass("selected");
    }
  });
}

// reload datatable ajax
function reload_table(tableId) {
  $("#" + tableId).DataTable().ajax.reload();
  /* This is to uncheck the column header check box */
  $('input[type=checkbox]').each(function () {
    this.checked = false;
  });
}