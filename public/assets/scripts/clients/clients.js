var save_method;
var tableId = "clients";
var account_typeId = 12; // set id for client savings particular
// dataTables url
var tableDataUrl = "/admin/clients/generate-clients/" + id;
// dataTables column config for each table
var columnsConfig = {
  clients: [
    { data: "checkbox", orderable: false, searchable: false },
    { data: "no", orderable: false, searchable: true },
    { data: "name" },
    { data: "branch_name" },
    { data: "gender" },
    { data: "mobile" },
    { data: "account_no" },
    { data: "residence" },
    { data: "account_balance", render: $.fn.DataTable.render.number(",") },
    { data: "savings", orderable: false, searchable: false },
    { data: "membership", orderable: false, searchable: false },
    { data: "shares", orderable: false, searchable: false },
    { data: "action", orderable: false, searchable: false },
  ],
  savings: [
    { data: "checkbox", orderable: false, searchable: false },
    { data: "no", orderable: false, searchable: false },
    { data: "date" },
    { data: "type" },
    { data: "amount", render: $.fn.DataTable.render.number(",") },
    { data: "ref_id" },
    { data: "payment_method" },
    { data: "account_bal", render: $.fn.DataTable.render.number(",") },
    { data: "action", orderable: false, searchable: false },
  ],
};
// dataTables buttons config
var buttonsConfig = [];
// show create button
if (
  userPermissions.includes("create_" + menu.toLowerCase() + titleSlug) ||
  userPermissions === '"all"'
) {
  buttonsConfig.push({
    text: '<i class="fas fa-plus"></i>',
    className: "btn btn-sm btn-secondary create" + title,
    attr: {
      id: "create" + title,
    },
    titleAttr: "Add " + title,
    action: function () {
      add_client();
    },
  });
}
// show upload button
if (
  userPermissions.includes("import_" + menu.toLowerCase() + titleSlug) ||
  userPermissions === '"all"'
) {
  buttonsConfig.push({
    text: '<i class="fas fa-upload"></i>',
    className: "btn btn-sm btn-info import" + title,
    attr: {
      id: "import" + title,
    },
    titleAttr: "Import " + title + "s",
    action: function () {
      import_clients();
    },
  });
}
// show bulk-delete
if (
  userPermissions.includes("bulkDelete_" + menu.toLowerCase() + titleSlug) ||
  userPermissions === '"all"'
) {
  buttonsConfig.push({
    text: '<i class="fa fa-trash"></i>',
    className: "btn btn-sm btn-danger delete" + title,
    attr: {
      id: "delete" + title,
    },
    titleAttr: "Bulky Delete " + title,
    action: function () {
      bulk_deleteClients();
    },
  });
}
// show reload table button by default
buttonsConfig.push({
  text: '<i class="fa fa-refresh"></i>',
  className: "btn btn-sm btn-warning",
  titleAttr: "Reload " + title + " Table",
  action: function () {
    reload_table(tableId);
  },
});
// show export buttons
if (
  userPermissions.includes("export_" + menu.toLowerCase() + titleSlug) ||
  userPermissions === '"all"'
) {
  buttonsConfig.push(
    {
      extend: "excel",
      className: "btn btn-sm btn-success",
      titleAttr: "Export " + title + " To Excel",
      text: '<i class="fas fa-file-excel"></i>',
      filename: title + " Information",
      extension: ".xlsx",
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
      },
    },
    {
      extend: "pdf",
      className: "btn btn-sm btn-danger",
      titleAttr: "Export " + title + " To PDF",
      text: '<i class="fas fa-file-pdf"></i>',
      filename: title + " Information",
      extension: ".pdf",
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
      },
    },
    {
      extend: "csv",
      className: "btn btn-sm btn-info",
      titleAttr: "Export " + title + " To CSV",
      text: '<i class="fas fa-file-csv"></i>',
      filename: title + " Information",
      extension: ".csv",
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
      },
    },
    {
      extend: "copy",
      className: "btn btn-sm btn-secondary",
      titleAttr: "Copy " + title + " Information",
      text: '<i class="fas fa-copy"></i>',
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
      },
    },
    {
      extend: "print",
      title:
        "<h3 class='text-center text-bold'>" +
        businessName +
        "</h3><h4 class='text-center text-bold'>" +
        title +
        " Information</h4><h5 class='text-center'>Printed On " +
        new Date().getHours() +
        " : " +
        new Date().getMinutes() +
        " " +
        new Date().toDateString() +
        "</h5>",
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
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
      titleAttr: "Print " + title + " Information",
      text: '<i class="fa fa-print"></i>',
      filename: title + " Information",
    }
  );
}

$(document).ready(function () {
  // call to dataTable initialization function
  initializeDataTable(
    tableId,
    tableDataUrl,
    columnsConfig[tableId],
    buttonsConfig
  );

  selectBranch();
  // load savings products
  selectProducts("savings");

  $.ajax({
    url: "/admin/branches/get-branches",
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $("select#branchID").html('<option value="">-- select --</option>');
      if (data.length > 0) {
        for (var i = 0; i < data.length; i++) {
          $("<option />")
            .val(data[i].id)
            .text(data[i].branch_name)
            .appendTo($("select#branchID"));
        }
      } else {
        $("select#branchID").html('<option value="">No Branch</option>');
      }
    },
    error: function (err) {
      $("select#branchID").html('<option value="">Error Occured</option>');
    },
  });

  // load payment methods
  selectPaymentMethod();
  // load ajax options
  generateCustomeSettings("title");
  generateCustomeSettings("gender");
  generateCustomeSettings("nationality");
  generateCustomeSettings("maritalstatus");
  generateCustomeSettings("religion");
  generateCustomeSettings("relationships");
  generateCustomeSettings("idtypes");
  generateCustomeSettings("occupation");
  // reset form on modal close event
  $("#modal_form").on("hidden.bs.modal", function (e) {
    // Clear the selected options from the select box
    $("#savings_products").val(null).trigger("change");
  });
});

// ajax load client transactions
function client_transactions(id, name) {
  // dataTables buttons config
  var buttons = [];

  // show export buttons
  if (
    userPermissions.includes("export_savingsTransactions") ||
    userPermissions === '"all"'
  ) {
    buttons.push(
      {
        extend: "excel",
        className: "btn btn-sm btn-success",
        titleAttr: "Export To Excel",
        text: '<i class="fas fa-file-excel"></i>',
        filename: name + " Savings Transactions",
        extension: ".xlsx",
        exportOptions: {
          columns: [1, 2, 3, 4, 5, 6, 7],
        },
      },
      {
        extend: "pdf",
        className: "btn btn-sm btn-danger",
        titleAttr: " Export To PDF",
        text: '<i class="fas fa-file-pdf"></i>',
        filename: name + "Savings Transactions",
        extension: ".pdf",
        exportOptions: {
          columns: [1, 2, 3, 4, 5, 6, 7],
        },
      },
      {
        extend: "csv",
        className: "btn btn-sm btn-info",
        titleAttr: "Export To CSV",
        text: '<i class="fas fa-file-csv"></i>',
        filename: name + " Savings Transactions",
        extension: ".csv",
        exportOptions: {
          columns: [1, 2, 3, 4, 5, 6, 7],
        },
      },
      {
        extend: "copy",
        className: "btn btn-sm btn-secondary",
        titleAttr: "Copy " + name + " Savings Transactions",
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
          "</h3><h4 class='text-center text-bold'>" +
          name +
          " Savings Transactions Information</h4><h5 class='text-center'>Printed On " +
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
        titleAttr: "Print " + title + " Information",
        text: '<i class="fa fa-print"></i>',
        filename: title + " Information",
      }
    );
  }

  var url =
    "/admin/transactions/client-transactions/" + id + "/" + account_typeId;
  // call to dataTable initialization function
  initializeDataTable("savings", url, columnsConfig["savings"], buttons);
}

function exportClientForm() {
  var client_id = $('[name="id"]').val();
  window.location.assign("/admin/clients/form/" + client_id);
}

function add_client() {
  save_method = "add";
  $("#form")[0].reset();
  $(".form-group").removeClass("has-error");
  $(".help-block").empty();
  $("#importRow").hide();
  $("#formRow").show();
  $("#passwordRow").hide();
  $("#upload-label").text("Upload Photo");
  $("#user-photo-preview").html(
    '<img src="/assets/dist/img/nophoto.jpg" alt="Image preview" id="preview-image" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
  );
  $("#id-labelFront").text("Upload Front ID Photo");
  $("#id-previewFront").html(
    '<img src="/assets/dist/img/id.jpg" alt="ID Front Preview" id="preview-idFront" class="img-fluid thumbnail" style="width: 200px; height: 140px;">'
  );
  $("#id-labelBack").text("Upload Back ID Photo");
  $("#id-previewBack").html(
    '<img src="/assets/dist/img/id.jpg" alt="ID Back Preview" id="preview-idBack" class="img-fluid thumbnail" style="width: 200px; height: 140px;">'
  );
  $("#sign-label").text("Upload Signature");
  $("#signature-preview").html(
    '<img src="/assets/dist/img/sign.png" alt="Sign preview" id="preview-sign" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
  );
  $('[name="id"]').val(0);
  $('[name="mode"]').val("create");
  $("select#branchID").trigger("change");
  $("select#title").trigger("change");
  $("select#gender").trigger("change");
  $("select#nationality").trigger("change");
  $("select#maritalstatus").trigger("change");
  $("select#religion").trigger("change");
  $("select#branch_id").trigger("change");
  $("select#id_type").trigger("change");
  $("select#occupation").trigger("change");
  $("select#relationships").trigger("change");
  setPhoneNumberWithCountryCode($("#mobile"), "");
  setPhoneNumberWithCountryCode($("#alternate_mobile"), "");
  setPhoneNumberWithCountryCode($("#nok_phone"), "");
  setPhoneNumberWithCountryCode($("#nok_alt_phone"), "");
  $("select#savings_products").trigger("change");

  $(".modal-title").text("Add New Client");
  $("#modal_form").modal("show");
}

function import_clients() {
  save_method = "add";
  $("#form")[0].reset(); // reset form on modals
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  $("#importRow").show();
  $("#formRow").hide();
  $("#passwordRow").hide();
  $("#export").hide();
  $('[name="id"]').val(0);
  $('[name="mode"]').val("import");
  $('[name="account_type"]').val("Client");
  $("select#savings_products").trigger("change");
  $(".modal-title").text("Import Client(s)");
  $("#modal_form").modal("show"); // show bootstrap modal
}
// generate new user password
function create_password(id, password) {
  save_method = "password";
  $("#importRow").hide();
  $("#formRow").hide();
  $("#passwordRow").show();
  $("#form")[0].reset();
  $(".form-group").removeClass("has-error");
  $(".help-block").empty();
  $.ajax({
    url: "/admin/clients/client/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $('[name="id"]').val(data.id);
      $('[name="mode"]').val("regenerate");
      $('[name="name"]').val(data.name);
      $('[name="phone"]').val(data.mobile);
      setPhoneNumberWithCountryCode($("#phone"), data.mobile);
      $('[name="email"]').val(data.email);
      $('[name="password"]').val(password);
      $('[name="c_password"]').val(password);
      $(".modal-title").text("Generate " + data.name + " New Password");
      $("#modal_form").modal("show");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}
// edit client data
function edit_client(id) {
  save_method = "update";
  $("#export").hide();
  $("#form")[0].reset();
  $("#importRow").hide();
  $("#formRow").show();
  $("#passwordRow").hide();
  $(".form-group").removeClass("has-error");
  $(".help-block").empty();
  $.ajax({
    url: "/admin/clients/client/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $('[name="id"]').val(data.id);
      $('[name="relation_id"]').val(data.relation_id);
      $('[name="title"]').val(data.title).trigger("change");
      $('[name="c_name"]').val(data.name);
      $('[name="account_no"]').val(data.account_no);
      $('[name="account_type"]').val(data.account_type);
      $('[name="c_email"]').val(data.email);
      $('[name="mobile"]').val(data.mobile);
      $('[name="alternate_mobile"]').val(data.alternate_no);
      setPhoneNumberWithCountryCode($("#mobile"), data.mobile);
      setPhoneNumberWithCountryCode($("#alternate_mobile"), data.alternate_no);
      $('[name="dob"]').val(data.dob);
      $('[name="reg_date"]').val(data.reg_date);
      $('[name="staff_id"]').val(data.staff_id);
      $('[name="occupation"]').val(data.occupation).trigger("change");
      $('[name="job_location"]').val(data.job_location);
      $('[name="residence"]').val(data.residence);
      $('[name="closest_landmark"]').val(data.closest_landmark);
      $('[name="id_number"]').val(data.id_number);
      $('[name="id_expiry"]').val(data.id_expiry_date);
      $('[name="next_of_kin"]').val(data.next_of_kin_name);
      $('[name="nok_phone"]').val(data.next_of_kin_contact);
      $('[name="nok_alt_phone"]').val(data.next_of_kin_alternate_contact);
      setPhoneNumberWithCountryCode($("#nok_phone"), data.next_of_kin_contact);
      setPhoneNumberWithCountryCode(
        $("#nok_alt_phone"),
        data.next_of_kin_alternate_contact
      );
      $('[name="nok_email"]').val(data.nok_email);
      $('[name="nok_address"]').val(data.nok_address);
      $('[name="branch_id"]').val(data.branch_id).trigger("change");
      $('[name="gender"]').val(data.gender).trigger("change");
      $('[name="nationality"]').val(data.nationality).trigger("change");
      $('[name="id_type"]').val(data.id_type).trigger("change");
      $('[name="marital_status"]').val(data.marital_status).trigger("change");
      $('[name="religion"]').val(data.religion).trigger("change");
      // if data.savingsProducts is not empty
      if (data.savingsProducts) {
        // loop thru data.savingsProducts
        var selectedProductIds = data.savingsProducts.map(function (product) {
          return product.product_id;
        });

        $("#savings_products").val(selectedProductIds).trigger("change");
      }
      // passport
      $('[name="nok_relationship"]')
        .val(data.next_of_kin_relationship)
        .trigger("change");
      if (
        data.photo &&
        imageExists("/uploads/clients/passports/" + data.photo)
      ) {
        $("#upload-label").text("Update Photo");
        $("#user-photo-preview").html(
          '<img src="/uploads/clients/passports/' +
            data.photo +
            '" alt="Image preview" id="preview-image" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
        );
      } else {
        $("#upload-label").text("Upload Photo");
        $("#user-photo-preview").html(
          '<img src="/assets/dist/img/nophoto.jpg" alt="Image preview" id="preview-image" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
        );
      }
      // id photos
      if (
        data.id_photo_front &&
        imageExists("/uploads/clients/ids/front/" + data.id_photo_front)
      ) {
        $("#id-label").text("Update Photo");
        $("#id-previewFront").html(
          '<img src="/uploads/clients/ids/front/' +
            data.id_photo_front +
            '" alt="Image preview" id="preview-idFront" class="img-fluid thumbnail" style="width: 200px; height: 140px;">'
        );
      } else {
        $("#id-label").text("Upload Photo");
        $("#id-previewFront").html(
          '<img src="/assets/dist/img/id.jpg" alt="Image preview" id="preview-idFront" class="img-fluid thumbnail" style="width: 200px; height: 140px;">'
        );
      }
      if (
        data.id_photo_back &&
        imageExists("/uploads/clients/ids/back/" + data.id_photo_back)
      ) {
        $("#id-label").text("Update Photo");
        $("#id-previewBack").html(
          '<img src="/uploads/clients/ids/back/' +
            data.id_photo_back +
            '" alt="Image preview" id="preview-idBack" class="img-fluid thumbnail" style="width: 200px; height: 140px;">'
        );
      } else {
        $("#id-label").text("Upload Photo");
        $("#id-previewBack").html(
          '<img src="/assets/dist/img/id.jpg" alt="Image preview" id="preview-idBack" class="img-fluid thumbnail" style="width: 200px; height: 140px;">'
        );
      }
      // signiture
      if (
        data.signature &&
        imageExists("/uploads/clients/signatures/" + data.signature)
      ) {
        $("#sign-label").text("Update Signature");
        $("#signature-preview").html(
          '<img src="/uploads/clients/signatures/' +
            data.signature +
            '" alt="Image preview" id="preview-sign" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
        );
      } else {
        $("#sign-label").text("Upload Signature");
        $("#signature-preview").html(
          '<img src="/assets/dist/img/sign.png" alt="Image preview" id="preview-sign" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
        );
      }
      selectBranch(data.branch_id);
      $(".modal-title").text("Update " + data.name);
      $("#modal_form").modal("show"); // show bootstrap modal when complete loaded
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}
// view client info
function view_client(id) {
  //Ajax Load data from ajax
  $.ajax({
    url: "/admin/clients/client/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      var dob = data.dob;
      var title = data.title != null ? data.title + " " : "";
      $('[name="id"]').val(data.id);
      $('[name="vname"]').val(title + data.name);
      $('[name="vaccount_no"]').val(data.account_no);
      $('[name="vaccount_type"]').val(data.account_type);
      $('[name="vaccount_balance"]').val(data.account_balance);
      $('[name="vemail"]').val(data.email);
      $('[name="vmobile"]').val(data.mobile);
      $('[name="valternate_no"]').val(data.alternate_no);
      setPhoneNumberWithCountryCode($("#vmobile"), data.mobile);
      setPhoneNumberWithCountryCode($("#valternate_no"), data.alternate_no);
      $('[name="vgender"]').val(data.gender);
      $('[name="vnationality"]').val(data.nationality);
      $('[name="vdob"]').val(data.dob);
      $('[name="view_reg_date"]').val(data.reg_date);
      $('[name="vmarital_status"]').val(data.marital_status);
      $('[name="vreligion"]').val(data.religion);
      $('[name="staff_name"]').val(data.staff_name);
      $('[name="voccupation"]').val(data.occupation);
      $('[name="vjob_location"]').val(data.job_location);
      $('[name="vresidence"]').val(data.residence);
      $('[name="vclosest_landmark"]').val(data.closest_landmark);
      $('[name="vid_type"]').val(data.id_type);
      $('[name="vid_number"]').val(data.id_number);
      $('[name="vid_expiry"]').val(data.id_expiry_date);
      $('[name="vnext_of_kin"]').val(data.next_of_kin_name);
      $('[name="vnok_relationship"]').val(data.next_of_kin_relationship);
      $('[name="vnok_phone"]').val(data.next_of_kin_contact);
      $('[name="vnok_alt_phone"]').val(data.next_of_kin_alternate_contact);
      setPhoneNumberWithCountryCode($("#vnok_phone"), data.next_of_kin_contact);
      setPhoneNumberWithCountryCode(
        $("#vnok_alt_phone"),
        data.next_of_kin_alternate_contact
      );
      $('[name="vnok_email"]').val(data.nok_email);
      $('[name="vnok_address"]').val(data.nok_address);
      $('[name="vbranch_id"]').val(data.branch_name);
      $('[name="created_at"]').val(data.created_at);
      $('[name="updated_at"]').val(data.updated_at);
      $('[name="age"]').val(new Date().getYear() - new Date(dob).getYear());
      // passport photo
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
      // id photos
      if (
        data.id_photo_front &&
        imageExists("/uploads/clients/ids/front/" + data.id_photo_front)
      ) {
        $("#id-previewFront div").html(
          '<img src="/uploads/clients/ids/front/' +
            data.id_photo_front +
            '" class="img-fluid thumbnail"  style="width: 200px; height: 180px;">'
        );
      } else {
        $("#id-previewFront div").html(
          '<img src="/assets/dist/img/id.jpg" class="img-fluid thumbnail"  style="width: 200px; height: 180px;">'
        );
      }
      if (
        data.id_photo_back &&
        imageExists("/uploads/clients/ids/back/" + data.id_photo_back)
      ) {
        $("#id-previewBack div").html(
          '<img src="/uploads/clients/ids/back/' +
            data.id_photo_back +
            '" class="img-fluid thumbnail"  style="width: 200px; height: 180px;">'
        );
      } else {
        $("#id-previewBack div").html(
          '<img src="/assets/dist/img/id.jpg" class="img-fluid thumbnail"  style="width: 200px; height: 180px;">'
        );
      }
      // signiture
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
      $(".modal-title").text("View " + data.name); // Set modal title
      $("#view_modal").modal("show"); // show bootstrap modal when complete loaded
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
      $(".modal-title").text(data.name);
      $("#photo_modal_form").modal("show");
    },

    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}

function save_client() {
  id = $('[name="id"]').val();
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  $("#btnSav").text("submitting...");
  $("#btnSav").attr("disabled", true);
  var url, method;
  if (save_method == "add") {
    url = "/admin/clients/client";
  } else if (save_method == "password") {
    url = "/admin/clients/update-clientStatus/" + id;
  } else {
    url = "/admin/clients/edit-client/" + id;
  }
  // ajax adding data to database
  var formData = new FormData($("#form")[0]);
  $.ajax({
    url: url,
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    dataType: "JSON",
    success: function (data) {
      if (!data.inputerror) {
        if (data.status && data.error == null) {
          $("#modal_form").modal("hide");
          Swal.fire("Success!", data.messages, "success");
          reload_table(tableId);
        } else if (data.error != null) {
          Swal.fire(data.error, data.messages, "error");
        } else {
          Swal.fire(
            "Error",
            "Something unexpected happened, try again later",
            "error"
          );
        }
      } else {
        for (var i = 0; i < data.inputerror.length; i++) {
          $('[name="' + data.inputerror[i] + '"]')
            .parent()
            .parent()
            .addClass("has-error");
          $('[name="' + data.inputerror[i] + '"]')
            .closest(".form-group")
            .find(".help-block")
            .text(data.error_string[i]);
        }
      }
      $("#btnSav").text("Submit"); //change button text
      $("#btnSav").attr("disabled", false); //set button enable
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
      $("#btnSav").text("Submit"); //change button text
      $("#btnSav").attr("disabled", false); //set button enable
    },
  });
}

function edit_clientStatus(id, name) {
  Swal.fire({
    title: "Are you sure?",
    text: name + " access status will be changed!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Update!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "/admin/clients/update-clientStatus/" + id,
        type: "POST",
        dataType: "JSON",
        success: function (data) {
          if (data.status && data.error == null) {
            Swal.fire("Success!", name + " " + data.messages, "success");
            reload_table(tableId);
          } else if (data.error != null) {
            Swal.fire(data.error, data.messages, "error");
          } else {
            Swal.fire(
              "Error",
              "Something unexpected happened, try again later",
              "error"
            );
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          Swal.fire(textStatus, errorThrown, "error");
        },
      });
    }
  });
}

function delete_client(id, name) {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to recover " + name + "!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "/admin/clients/client/" + id,
        type: "DELETE",
        dataType: "JSON",
        success: function (data) {
          if (data.status && data.error == null) {
            Swal.fire("Deleted!", name + " " + data.messages, "success");
            reload_table(tableId);
          } else if (data.error != null) {
            Swal.fire(data.error, data.messages, "error");
          } else {
            Swal.fire(
              "Error",
              "Something unexpected happened, try again later",
              "error"
            );
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          Swal.fire(textStatus, errorThrown, "error");
        },
      });
    }
  });
}

function bulk_deleteClients() {
  var list_id = [];
  $(".data-check:checked").each(function () {
    list_id.push(this.value);
  });
  if (list_id.length > 0) {
    const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        confirmButton: "btn btn-success",
        cancelButton: "btn btn-danger",
      },
      buttonsStyling: true,
    });
    swalWithBootstrapButtons
      .fire({
        title: "Are you sure?",
        text:
          "You will not be able to recover the selected " +
          list_id.length +
          " clients once deleted!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel!",
        reverseButtons: false,
      })
      .then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            type: "POST",
            data: {
              id: list_id,
            },
            url: "/admin/clients/bulk-delete",
            dataType: "JSON",
            success: function (data) {
              if (data.status && data.error == null) {
                Swal.fire("Success!", data.messages, "success");
                reload_table(tableId);
              } else if (data.error != null) {
                Swal.fire(data.error, data.messages, "error");
              } else {
                Swal.fire(
                  "Error",
                  "Something unexpected happened, try again later",
                  "error"
                );
              }
            },
            error: function (jqXHR, textStatus, errorThrown) {
              Swal.fire(textStatus, errorThrown, "error");
            },
          });
        } else if (
          /* Read more about handling dismissals below */
          result.dismiss === Swal.DismissReason.cancel
        ) {
          swalWithBootstrapButtons.fire(
            "Cancelled",
            "Bulky delete cancelled :)",
            "error"
          );
        }
      });
  } else {
    Swal.fire("Sorry!", "No client selected!", "error");
  }
}

// hide\show add transaction form
$("button#btnTransaction").click(function () {
  $("div#addTransactionCard").toggle(function (e) {
    if ($(this).is(":visible")) {
      $("div#addTransactionCard").show();
      $("button#btnTransaction").text("Hide Form");
    } else {
      $("div#addTransactionCard").hide();
      $("button#btnTransaction").html(
        '<i class="fas fa-plus"></i> Add Transaction'
      );
    }
  });
});
// pop up model for client savings
function client_savings(id) {
  $("#savingsForm")[0].reset();
  var clientSavingsProducts = {}; // Object to store SavingsProducts for the selected client

  $.ajax({
    url: "/admin/clients/client/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $(".modal-title").text(data.name);
      $("#savings_modal_form").modal("show");
      transaction_types();
      client_transactions(data.id, data.name);
      $("strong#cName").text(data.name);
      $("strong#cContact").text(data.mobile);
      $("strong#cContact2").text(data.alternate_no);
      $("strong#cEmail").text(data.email);
      $("strong#cAccountNo").text(data.account_no);
      $("strong#cBalance").text(Number(data.account_balance).toLocaleString());
      $("strong#cAddress").text(data.residence);
      $("strong#cRegDate").text(data.reg_date);
      // fill transaction form
      $('[name="client_id"]').val(data.id);
      $('[name="account_typeId"]').val(account_typeId);
      $('[name="contact"]').val(data.mobile);
      setPhoneNumberWithCountryCode($("#contact"), data.mobile);
      $("select#entry_typeId").trigger("change");
      $("select#particular_id").trigger("change");
      $("select#payment_id").trigger("change");
      $("select#entry_typeId").trigger("change");
      $("select#product_id").trigger("change");
      $("select#product_id").empty();

      $("select#product_id").empty();
      $("select#product_id").html('<option value="">-- select --</option>');
      if (data.savingsProducts) {
        clientSavingsProducts[id] = data.savingsProducts; // Store data.savingsProducts
        // Add options
        $.each(data.savingsProducts, function (index, product) {
          // Append to product_id
          var selection = product.product_id == product_id ? "selected" : "";
          $("select#product_id").append(
            '<option value="' +
              product.product_id +
              '" ' +
              selection +
              ">" +
              product.product_name +
              "</option>"
          );
        });

        $("select#product_id").on("change", function () {
          var productId = $(this).val();
          if (productId) {
            var selectedProduct = clientSavingsProducts[id].find(
              (item) => item.product_id == productId
            );
            selectParticulars(
              account_typeId,
              selectedProduct.savings_particular_id
            );
          } else {
            $("select#particular_id").empty();
          }
        });
      } else {
        $("select#product_id").html('<option value="">No Product</option>');
      }

      // $("textarea#addSummernote").summernote("reset");
      if (
        data.photo &&
        imageExists("/uploads/clients/passports/" + data.photo)
      ) {
        $("#photo-preview div").html(
          '<img src="/uploads/clients/passports/' +
            data.photo +
            '" class="img-fluid thumbnail" style="height: 100px; width: 100px;">'
        );
      } else {
        $("#photo-preview div").html(
          '<img src="/assets/dist/img/nophoto.jpg" class="img-fluid thumbnail" style="height: 50px; width: 50px;">'
        );
      }
      $("div#addTransactionCard").hide(); // hide transaction form
      $("span.help-block").empty(); // remove error messages
      $("button#btnTransaction").html(
        '<i class="fas fa-plus"></i> Add Transaction'
      ); // change text of form button
    },

    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}

function save_Savingstransaction() {
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  $("#btnSavings").text("submitting...");
  $("#btnSavings").attr("disabled", true);
  // ajax adding data to database
  var formData = new FormData($("#savingsForm")[0]);
  $.ajax({
    url: "/admin/transactions/transaction",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    dataType: "JSON",
    success: function (data) {
      if (!data.inputerror) {
        if (data.status && data.error == null) {
          $("#savings_modal_form").modal("hide");
          Swal.fire("Success!", data.messages, "success");
          reload_table(tableId);
          reload_table("savings");
        } else if (data.error != null) {
          Swal.fire(data.error, data.messages, "error");
        } else {
          Swal.fire(
            "Error",
            "Something unexpected happened, try again later",
            "error"
          );
        }
      } else {
        for (var i = 0; i < data.inputerror.length; i++) {
          $('[name="' + data.inputerror[i] + '"]')
            .parent()
            .parent()
            .addClass("has-error");
          $('[name="' + data.inputerror[i] + '"]')
            .closest(".form-group")
            .find(".help-block")
            .text(data.error_string[i]);
        }
      }
      $("#btnSavings").text("Submit"); //change button text
      $("#btnSavings").attr("disabled", false); //set button enable
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
      $("#btnSavings").text("Submit"); //change button text
      $("#btnSavings").attr("disabled", false); //set button enable
    },
  });
}
