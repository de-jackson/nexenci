(function ($) {
  "use strict";

  var table = $(".data-table").DataTable({
    //dom: 'Bfrtip',
    // dom: "ZBfrltip",
    dom:
      "<'row'<'col-md-2'l><'col-md-6'B><'col-md-4'f>>" +
      "<'row'<'col-md-12'tr>>" +
      "<'row'<'col-md-5'i><'col-md-7'p>>",
    buttons: [
      {
        extend: "excel",
        text: '<i class="fa-solid fa-file-excel"></i> Excel Export Report',
        className: "btn btn-sm btn-info",
      },
      {
        extend: "pdf",
        className: "btn btn-sm btn-danger",
        titleAttr: "Export Transactions To PDF",
        text: '<i class="fas fa-file-pdf"></i> PDF Export Report',
        filename: "Transactions Information",
        extension: ".pdf",
        exportOptions: {
          columns: [1, 2, 3, 4, 5, 6, 7],
        },
      },
      {
        extend: "print",
        text: '<i class="fa-solid fa-print"></i> Print Export Report',
        className: "btn btn-sm btn-primary",
      },
    ],
    searching: true,
    select: false,
    pageLength: 5,
    lengthMenu: [
      [5, 10, 25, 50, 100, 250, 500],
      [5, 10, 25, 50, 100, 250, 500],
    ],
    lengthChange: false,
    language: {
      paginate: {
        next: '<i class="fa-solid fa-angle-right"></i>',
        previous: '<i class="fa-solid fa-angle-left"></i>',
      },
    },
  });
})(jQuery);
