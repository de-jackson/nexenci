var from = $('[name="from"]').val();
var to = $('[name="to"]').val();
if (from == "") {
  from = 0;
}
if (to == "") {
  to = 0;
}
var tableId = "branchReport";
// dataTables url
var tableDataUrl = "/admin/branches/branches-report/" + from + "/" + to;
// dataTables column config
var columnsConfig = [
  { data: "checkbox", orderable: false, searchable: false },
  { data: "no", orderable: false, searchable: false },
  { data: "branch_name" },
  { data: "branch_mobile" },
  { data: "branch_email" },
  { data: "branch_address" },
  { data: "branch_code" },
  { data: "id" },
  { data: "action", orderable: false, searchable: false },
];
// dataTables buttons config
var buttonsConfig = [];
// show reload table button by default
buttonsConfig.push({
  text: '<i class="fa fa-refresh"></i>',
  className: "btn btn-sm btn-warning",
  titleAttr: "Reload Branches " + title + " Table",
  action: function () {
    reload_table(tableId);
  },
});
// show export buttons
if (userPermissions.includes("export_" + menu.toLowerCase() + titleSlug) || userPermissions === '"all"') {
  buttonsConfig.push(
    {
      extend: "excel",
      className: "btn btn-sm btn-success",
      titleAttr: "Export " + title + " To Excel",
      text: '<i class="fas fa-file-excel"></i>',
      filename: "Branches " + title + " Table Data",
      extension: ".xlsx",
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7],
      },
    },
    {
      extend: "pdf",
      className: "btn btn-sm btn-danger",
      titleAttr: "Export Branches " + title + " To PDF",
      text: '<i class="fas fa-file-pdf"></i>',
      filename: "Branches " + title + " Table Data",
      extension: ".pdf",
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7],
      },
    },
    {
      extend: "csv",
      className: "btn btn-sm btn-success",
      titleAttr: "Export Branches " + title + " To CSV",
      text: '<i class="fas fa-file-csv"></i>',
      filename: "Branches " + title + " Table Data",
      extension: ".csv",
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7],
      },
    },
    {
      extend: "copy",
      className: "btn btn-sm btn-warning",
      titleAttr: "Copy Branches " + title + " Table Data",
      text: '<i class="fas fa-copy"></i>',
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7],
      },
    },
    {
      extend: "print",
      title:
        "<h3 class='text-center text-bold'>" +
        businessName +
        "</h3><h4 class='text-center text-bold'>Branches " +
        title +
        " Table Data</h4><h5 class='text-center'>Printed On " +
        new Date().getHours() +
        " : " +
        new Date().getMinutes() +
        " " +
        new Date().toDateString() +
        "</h5>",
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7],
      },
      customize: function (win) {
        $(win.document.body)
          .css("font-size", "10pt")
          .css("font-family", "calibri")
          .prepend(
            '<img src="' +
              logo +
              '" style="position:absolute; top:0; left:0;width:100px;height:100px;" />'
          );
        $(win.document.body)
          .find("table")
          .addClass("compact")
          .css("font-size", "inherit");
        // Replace the page title with the actual page title
        $(win.document.head).find("title").text(title);
      },

      className: "btn btn-sm btn-primary",
      titleAttr: "Print Branches " + title + " Table Data",
      text: '<i class="fa fa-print"></i>',
      filename: "Branches " + title + " Table Data",
    }
  );
}

$(document).ready(function () {
  // call to dataTable initialization function
  initializeDataTable(tableId, tableDataUrl, columnsConfig, buttonsConfig);
});

function exportBranchForm() {
  var branch_id = $('[name="id"]').val();
  window.location.assign("/admin/branches/print-branchForm/" + branch_id);
}

function view_branch(id) {
  //Ajax Load data from ajax
  $.ajax({
    url: "/admin/company/branch/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $('[name="id"]').val(data.id);
      $('[name="vbranch_code"]').val(data.branch_code);
      $('[name="vbranch_name"]').val(data.branch_name);
      $('[name="vbranch_email"]').val(data.branch_email);
      $('[name="vbranch_mobile"]').val(data.branch_mobile);
      $('[name="valternate_mobile"]').val(data.alternate_mobile);
      $('[name="vbranch_status"]').val(data.branch_status);
      $('[name="vbranch_address"]').val(data.branch_address);
      $('[name="created_at"]').val(data.created_at);
      $('[name="updated_at"]').val(data.updated_at);
      $("#view_modal").modal("show");
      $(".modal-title").text("View " + data.branch_name);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}
