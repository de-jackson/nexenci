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
if (filter.toLowerCase() == "between") {
  $("div#balCol").removeClass("col-md-10");
  $("div#balCol").addClass("col-md-6");
  $("div#btnCol").show(400);
} else {
  $("div#balCol").removeClass("col-md-6");
  $("div#balCol").addClass("col-md-10");
  $("div#btnCol").hide(400);
}
var tableId = "clientsReport";
// dataTables url
var tableDataUrl =
  "/admin/clients/clients-report/" +
  filter +
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
  { data: "account_no" },
  { data: "mobile" },
  { data: "email" },
  { data: "residence" },
  { data: "account_balance", render: $.fn.DataTable.render.number(",") },
  { data: "photo", orderable: false, searchable: false },
  { data: "id" },
  { data: "action", orderable: false, searchable: false },
];
// dataTables buttons config
var buttonsConfig = [];
// show reload table button by default
buttonsConfig.push({
  text: '<i class="fa fa-refresh"></i>',
  className: "btn btn-sm btn-warning",
  titleAttr: "Reload Clients " + title + " Table",
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
      filename: "Clients " + title + " Information",
      extension: ".xlsx",
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7, 8],
      },
    },
    {
      extend: "pdf",
      className: "btn btn-sm btn-danger",
      titleAttr: "Export Clients " + title + " To PDF",
      text: '<i class="fas fa-file-pdf"></i>',
      filename: "Clients " + title + " Information",
      extension: ".pdf",
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7, 8],
      },
    },
    {
      extend: "csv",
      className: "btn btn-sm btn-success",
      titleAttr: "Export Clients " + title + " To CSV",
      text: '<i class="fas fa-file-csv"></i>',
      filename: "Clients " + title + " Information",
      extension: ".csv",
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7, 8],
      },
    },
    {
      extend: "copy",
      className: "btn btn-sm btn-warning",
      titleAttr: "Copy Clients " + title + " Information",
      text: '<i class="fas fa-copy"></i>',
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7, 8],
      },
    },
    {
      extend: "print",
      title:
        "<h3 class='text-center text-bold'>" +
        businessName +
        "</h3><h4 class='text-center text-bold'>Clients " +
        title +
        " Information</h4><h5 class='text-center'>Printed On " +
        new Date().getHours() +
        " : " +
        new Date().getMinutes() +
        " " +
        new Date().toDateString() +
        "</h5>",
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7, 8],
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
      titleAttr: "Print Clients " + title + " Information",
      text: '<i class="fa fa-print"></i>',
      filename: "Clients " + title + " Information",
    }
  );
}

$(document).ready(function () {
  // call to dataTable initialization function
  initializeDataTable(tableId, tableDataUrl, columnsConfig, buttonsConfig);
});

function exportClientForm() {
  var client_id = $('[name="id"]').val();
  window.location.assign("/admin/clients/form/" + client_id);
}

function view_client(id) {
  //Ajax Load data from ajax
  $.ajax({
    url: "/admin/clients/client/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      var dob = data.dob;
      $('[name="id"]').val(data.id);
      $('[name="vname"]').val(data.name);
      $('[name="vaccount_no"]').val(data.account_no);
      $('[name="vaccount_type"]').val(data.account_type);
      $('[name="vaccount_balance"]').val(data.account_balance);
      $('[name="vemail"]').val(data.email);
      $('[name="vmobile"]').val(data.mobile);
      $('[name="valternate_no"]').val(data.alternate_no);
      $('[name="vgender"]').val(data.gender);
      $('[name="vnationality"]').val(data.nationality);
      $('[name="vdob"]').val(data.dob);
      $('[name="vmarital_status"]').val(data.marital_status);
      $('[name="vreligion"]').val(data.religion);
      $('[name="staff_name"]').val(data.staff_name);
      $('[name="voccupation"]').val(data.occupation);
      $('[name="vjob_location"]').val(data.job_location);
      $('[name="vresidence"]').val(data.residence);
      $('[name="vid_type"]').val(data.id_type);
      $('[name="vid_number"]').val(data.id_number);
      $('[name="vid_expiry"]').val(data.id_expiry_date);
      $('[name="vnext_of_kin"]').val(data.next_of_kin_name);
      $('[name="vnok_relationship"]').val(data.next_of_kin_relationship);
      $('[name="vnok_phone"]').val(data.next_of_kin_contact);
      $('[name="vnok_alt_phone"]').val(data.next_of_kin_alternate_contact);
      $('[name="vnok_email"]').val(data.nok_email);
      $('[name="vnok_address"]').val(data.nok_address);
      $('[name="vbranch_id"]').val(data.branch_name);
      $('[name="created_at"]').val(data.created_at);
      $('[name="update_at"]').val(data.updated_at);
      $('[name="age"]').val(new Date().getYear() - new Date(dob).getYear());
      if (
        data.photo &&
        imageExists("/uploads/clients/passports/" + data.photo)
      ) {
        $("#photo-preview div").html(
          '<img src="/uploads/clients/passports/' +
            data.photo +
            '" class="img-fluid thumbnail"  style="width: 190px; height: 190px;">'
        );
      } else {
        $("#photo-preview div").html(
          '<img src="/assets/dist/img/nophoto.jpg" class="img-fluid thumbnail"  style="width: 190px; height: 190px;">'
        );
      }
      if (
        data.signature &&
        imageExists("/uploads/clients/signatures/" + data.signature)
      ) {
        $("#signature-preview div").html(
          '<img src="/uploads/clients/signatures/' +
            data.signature +
            '" alt="Sign preview" id="preview-sign" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
        );
      } else {
        $("#signature-preview div").html(
          '<img src="/assets/dist/img/sign.png" alt="Sign preview" id="preview-sign" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
        );
      }
      $("#view_modal").modal("show"); // show bootstrap modal when complete loaded
      $(".modal-title").text("View " + data.name); // Set title to Bootstrap modal title
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}

function view_client_photo(id) {
  $.ajax({
    url: "/admin/clients/client/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $('[name="id"]').val(data.id);
      $("#photo_modal_form").modal("show");
      $(".modal-title").text(data.name);
      $("#photo-preview").show();
      if (
        data.photo &&
        imageExists("/uploads/clients/passports/" + data.photo)
      ) {
        $("#photo-preview div").html(
          '<img src="/uploads/clients/passports/' +
            data.photo +
            '" class="img-fluid thumbnail">'
        );
      } else {
        $("#photo-preview div").html(
          '<img src="/assets/dist/img/nophoto.jpg" class="img-fluid thumbnail">'
        );
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}

$("select#filter").on("change", function () {
  filter = $("select#filter").val();
  if (filter.toLowerCase() == "between") {
    $("div#balCol").removeClass("col-md-10");
    $("div#balCol").addClass("col-md-6");
    $("div#btnCol").show(400);
  } else {
    $("div#balCol").removeClass("col-md-6");
    $("div#balCol").addClass("col-md-10");
    $("div#btnCol").hide(400);
  }
});
