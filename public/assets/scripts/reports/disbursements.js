var filter = $('[name="filter"]').val();
var bal = $('[name="bal"]').val();
var btn = $('[name="btn"]').val();
var from = $('[name="from"]').val();
var to = $('[name="to"]').val();
if (filter == "") {
  filter = null;
}
if (bal == "") {
  bal = 0;
}
if (btn == "") {
  btn = 0;
}
if (from == "") {
  from = 0;
}
if (to == "") {
  to = 0;
}
var tableId = "disbursementsReport";
// dataTables url
var tableDataUrl =
  "/admin/loans/disbursements-report/" +
  filter +
  "/" +
  selected +
  "/" +
  bal +
  "/" +
  btn +
  "/" +
  from +
  "/" +
  to;
// dataTables column config
var columnsConfig = [
  { data: "checkbox", orderable: false, searchable: false },
  { data: "no", orderable: false, searchable: false },
  { data: "name" },
  { data: "product_name" },
  { data: "disbursement_code" },
  { data: "actual_repayment" },
  { data: "actual_installment" },
  { data: "total_balance" },
  { data: "total_collected" },
  { data: "class" },
  { data: "id" },
  { data: "action", orderable: false, searchable: false },
];
// dataTables buttons config
var buttonsConfig = [];
// show reload table button by default
buttonsConfig.push({
  text: '<i class="fa fa-refresh"></i>',
  className: "btn btn-sm btn-warning",
  titleAttr: "Reload Disbursements " + title + " Table",
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
      filename: "Disbursements " + title + " Information",
      extension: ".xlsx",
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7],
      },
    },
    {
      extend: "pdf",
      className: "btn btn-sm btn-danger",
      titleAttr: "Export Disbursements " + title + " To PDF",
      text: '<i class="fas fa-file-pdf"></i>',
      filename: "Disbursements " + title + " Information",
      extension: ".pdf",
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7],
      },
    },
    {
      extend: "csv",
      className: "btn btn-sm btn-success",
      titleAttr: "Export Disbursements " + title + " To CSV",
      text: '<i class="fas fa-file-csv"></i>',
      filename: "Disbursements " + title + " Information",
      extension: ".csv",
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7],
      },
    },
    {
      extend: "copy",
      className: "btn btn-sm btn-warning",
      titleAttr: "Copy Disbursements " + title + " Information",
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
        "</h3><h4 class='text-center text-bold'>Disbursements " +
        title +
        " Information</h4><h5 class='text-center'>Printed On " +
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
      titleAttr: "Print Disbursements " + title + " Information",
      text: '<i class="fa fa-print"></i>',
      filename: "Disbursements " + title + " Information",
    }
  );
}

$(document).ready(function () {
  selected_value(selected);
  // call to dataTable initialization function
  initializeDataTable(tableId, tableDataUrl, columnsConfig, buttonsConfig);
});

$("select#filter").on("change", function () {
  var filter = $("select#filter").val();
  // dynamically add options
  switch (filter.toLowerCase()) {
    case "product":
      $.ajax({
        url: "/admin/loans/all-products",
        type: "POST",
        dataType: "JSON",
        success: function (data) {
          $("select#selectOpt").html(
            '<option value="">select product</option>'
          );
          if (data.length > 0) {
            for (var i = 0; i < data.length; i++) {
              $("<option />")
                .val(data[i].id)
                .text(data[i].product_name)
                .appendTo($("select#selectOpt"));
            }
          } else {
            $("select#selectOpt").html('<option value="">No Product</option>');
          }
        },
        error: function (err) {
          $("select#selectOpt").html('<option value="">Error Occured</option>');
        },
      });
      break;
    case "class":
      $.ajax({
        url: "/admin/reports/filter-options/" + report + "/" + filter,
        type: "POST",
        dataType: "JSON",
        success: function (data) {
          $("select#selectOpt").html('<option value="">select class</option>');
          if (data.length > 0) {
            for (var i = 0; i < data.length; i++) {
              $("<option />")
                .val(data[i])
                .text(data[i])
                .appendTo($("select#selectOpt"));
            }
          } else {
            $("select#selectOpt").html('<option value="">No class</option>');
          }
        },
        error: function (err) {
          $("select#selectOpt").html('<option value="">Error Occured</option>');
        },
      });
      break;
    case "amount":
      $.ajax({
        url: "/admin/reports/filter-options/" + report + "/" + filter,
        type: "POST",
        dataType: "JSON",
        success: function (data) {
          $("select#selectOpt").html('<option value="">select filter</option>');
          if (data.length > 0) {
            for (var i = 0; i < data.length; i++) {
              $("<option />")
                .val(data[i])
                .text(data[i])
                .appendTo($("select#selectOpt"));
            }
          } else {
            $("select#selectOpt").html('<option value="">No Filter</option>');
          }
        },
        error: function (err) {
          $("select#selectOpt").html('<option value="">Error Occured</option>');
        },
      });
      break;
    default:
      $("select#selectOpt").html('<option value="">no filter</option>');
      break;
  }
});

function selected_value(selected) {
  var filter = $("select#filter").val();
  // dynamically add options
  switch (filter.toLowerCase()) {
    case "product":
      $.ajax({
        url: "/admin/loans/all-products",
        type: "POST",
        dataType: "JSON",
        success: function (response) {
          if (response.length > 0) {
            $("select#selectOpt").find("option").not(":first").remove();
            // Add options
            $.each(response, function (index, data) {
              if (data["id"] == selected) {
                var selection = "selected";
              } else {
                var selection = "";
              }
              $("select#selectOpt").append(
                '<option value="' +
                  data["id"] +
                  '" ' +
                  selection +
                  ">" +
                  data["product_name"] +
                  "</option>"
              );
            });
          } else {
            $("select#selectOpt").html('<option value="">No Product</option>');
          }
        },
      });
      break;
    case "class":
      $.ajax({
        url: "/admin/reports/filter-options/" + report + "/" + filter,
        type: "POST",
        dataType: "JSON",
        success: function (response) {
          if (response.length > 0) {
            $("select#selectOpt").find("option").not(":first").remove();
            // Add options
            $.each(response, function (index, data) {
              if (data == selected) {
                var selection = "selected";
              } else {
                var selection = "";
              }
              $("select#selectOpt").append(
                '<option value="' +
                  data +
                  '" ' +
                  selection +
                  ">" +
                  data +
                  "</option>"
              );
            });
          } else {
            $("select#selectOpt").html('<option value="">No Filter</option>');
          }
        },
      });
      break;
    case "amount":
      $.ajax({
        url: "/admin/reports/filter-options/" + report + "/" + filter,
        type: "POST",
        dataType: "JSON",
        success: function (response) {
          if (response.length > 0) {
            $("select#selectOpt").find("option").not(":first").remove();
            // Add options
            $.each(response, function (index, data) {
              if (data == selected) {
                var selection = "selected";
              } else {
                var selection = "";
              }
              $("select#selectOpt").append(
                '<option value="' +
                  data +
                  '" ' +
                  selection +
                  ">" +
                  data +
                  "</option>"
              );
            });
          } else {
            $("select#selectOpt").html('<option value="">No Filter</option>');
          }
        },
      });
      break;
    default:
      $("select#selectOpt").html('<option value="">choose filter</option>');
      break;
  }
}
