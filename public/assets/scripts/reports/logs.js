var from = $('[name="from"]').val();
var to = $('[name="to"]').val();
if (from == "") {
  from = 0;
}
if (to == "") {
  to = 0;
}
var tableId = "userLogsReport";
// dataTables url
var tableDataUrl = "/admin/logs-report/" + from + "/" + to;
// dataTables column config
var columnsConfig = [
  { data: "checkbox", orderable: false, searchable: false },
  { data: "no", orderable: false, searchable: false },
  { data: "name" },
  { data: "login_at" },
  { data: "logout_at" },
  { data: "ip_address" },
  { data: "browser" },
  { data: "status" },
  { data: "action", orderable: false, searchable: false },
];
// dataTables buttons config
var buttonsConfig = [];
// show reload table button by default
buttonsConfig.push({
  text: '<i class="fa fa-refresh"></i>',
  className: "btn btn-sm btn-warning",
  titleAttr: "Reload User Logs " + title + " Table",
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
      filename: "User Logs " + title + " Information",
      extension: ".xlsx",
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7],
      },
    },
    {
      extend: "pdf",
      className: "btn btn-sm btn-danger",
      titleAttr: "Export User Logs " + title + " To PDF",
      text: '<i class="fas fa-file-pdf"></i>',
      filename: "User Logs " + title + " Information",
      extension: ".pdf",
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7],
      },
    },
    {
      extend: "csv",
      className: "btn btn-sm btn-success",
      titleAttr: "Export User Logs " + title + " To CSV",
      text: '<i class="fas fa-file-csv"></i>',
      filename: "User Logs " + title + " Information",
      extension: ".csv",
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7],
      },
    },
    {
      extend: "copy",
      className: "btn btn-sm btn-warning",
      titleAttr: "Copy User Logs " + title + " Information",
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
        "</h3><h4 class='text-center text-bold'>User Logs " +
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
      titleAttr: "Print User Logs " + title + " Information",
      text: '<i class="fa fa-print"></i>',
      filename: "User Logs " + title + " Information",
    }
  );
}

$(document).ready(function () {
  // call to dataTable initialization function
  initializeDataTable(tableId, tableDataUrl, columnsConfig, buttonsConfig);
});

function userLogForm() {
  var id = $('[name="id"]').val();
  window.location.assign("/admin/user-Forms/log/" + id);
}

// view record
function view_log(id) {
  $.ajax({
    url: "/admin/log/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $('[name="id"]').val(data.id);
      $('[name="vloginfo"]').val(data.loginfo);
      $('[name="vlogin_at"]').val(data.login_at);
      $('[name="vlogout_at"]').val(data.logout_at);
      $('[name="vduration"]').val(data.duration);
      $('[name="vip_address"]').val(data.ip_address);
      $('[name="vbrowser"]').val(data.browser);
      $('[name="vbrowser_version"]').val(data.browser_version);
      $('[name="voperating_system"]').val(data.operating_system);
      $('[name="location"]').val(data.location);
      $('[name="vlatitude"]').val(data.latitude);
      $('[name="vlongitude"]').val(data.longitude);
      $('[name="vstatus"]').val(data.status);
      $('[name="vtoken"]').val(data.token);
      $('[name="vreferrer_link"]').val(data.referrer_link);
      $('[name="vuser_id"]').val(data.name);
      $('[name="created_at"]').val(data.created_at);
      $('[name="updated_at"]').val(data.updated_at);
      $("#view_modal").modal("show");
      $(".modal-title").text("View log " + data.name);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}
