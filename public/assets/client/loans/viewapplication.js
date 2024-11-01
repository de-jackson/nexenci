var save_method;
var account_typeId = 18; // revenue from loans particulars
var entry_menu = "financing"; //default entry menu for all application payments
// table IDs 
var chargesPaymentsId = 'chargesPayments';
var collateralsTableId = 'collaterals';
var filesTableId = 'files';
// dataTables urls
var chargesPaymentsUrl = "/client/transactions/entries/" + id;
var collateralsTableUrl = "/client/application/files/" + id + "/collateral";
var filesTableUrl = "/client/application/files/" + id + "/files";
// dataTables column configs for each table
var columnsConfig = {
  chargesPayments: [
    { data: "checkbox", orderable: false, searchable: false, },
    { data: "no", orderable: false, searchable: false, },
    { data: "particular_name", },
    { data: "amount", render: $.fn.DataTable.render.number(","), },
    { data: "ref_id", },
    { data: "created_at", },
    { data: "staff_name", },
    { data: "action", orderable: false, searchable: false, },
  ],
  collaterals: [
    { data: "checkbox", orderable: false, searchable: false },
    { data: "no", orderable: false, searchable: false },
    { data: "file_name" },
    { data: "extension", orderable: false, searchable: false },
    { data: "photo", orderable: false, searchable: false },
    { data: "action", orderable: false, searchable: false }
  ],
  files: [
    { data: "checkbox", orderable: false, searchable: false, },
    { data: "no", orderable: false, searchable: false, },
    { data: "file_name", },
    { data: "extension", orderable: false, searchable: false, },
    { data: "type", },
    { data: "photo", orderable: false, searchable: false, },
    { data: "action", orderable: false, searchable: false, }
  ],
};
// dataTables buttons config for each table
buttonsConfig = {
  chargesPayments: [
    {
      extend: "excel",
      className: "btn btn-sm btn-success",
      titleAttr: "Export To Excel",
      text: '<i class="fas fa-file-excel"></i>',
      filename: "Appliction Payments",
      extension: ".xlsx",
      exportOptions: {
        columns: [1, 2, 3, 4],
      },
    },
    {
      extend: "pdf",
      className: "btn btn-sm btn-danger",
      titleAttr: "Export To PDF",
      text: '<i class="fas fa-file-pdf"></i>',
      filename: "Appliction Payments",
      extension: ".pdf",
      exportOptions: {
        columns: [1, 2, 3, 4],
      },
    },
    {
      extend: "csv",
      className: "btn btn-sm btn-success",
      titleAttr: "Export To CSV",
      text: '<i class="fas fa-file-csv"></i>',
      filename: "Appliction Payments",
      extension: ".csv",
      exportOptions: {
        columns: [1, 2, 3, 4],
      },
    },
    {
      extend: 'copy',
      className: 'btn btn-sm btn-warning',
      titleAttr: 'Copy ' + title + ' Table Data',
      text: '<i class="fas fa-copy"></i>',
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7]
      },
    },
    {
      text: '<i class="fa fa-refresh"></i>',
      className: "btn btn-sm btn-sm btn-secondary",
      titleAttr: "Refresh Table",
      action: function () {
        reload_table("chargesPayments");
      },
    },
    {
      extend: "print",
      title:
        "<h3 class='text-center text-bold'>" +
        businessName +
        "</h3><h4 class='text-center text-bold'>Appliction Payments Information</h4><h5 class='text-center'>Printed On " +
        new Date().getHours() +
        " : " +
        new Date().getMinutes() +
        " " +
        new Date().toDateString() +
        "</h5>",
      exportOptions: {
        columns: [1, 2, 3, 4],
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
      },
      className: "btn btn-sm btn-primary",
      titleAttr: "Print Payments",
      text: '<i class="fa fa-print"></i>',
      filename: "Application Payments",
    },
  ],
  collaterals: [
    {
      text: '<i class="fa fa-trash"></i>',
      className: "btn btn-sm btn-danger",
      titleAttr: 'Bulky Delete',
      action: function () {
        bulk_deleteFiles();
      }
    },
    {
      text: '<i class="fa fa-refresh"></i>',
      className: "btn btn-sm btn-secondary",
      titleAttr: 'Reload Table',
      action: function () {
        reload_table('collaterals')
      }
    },
    {
      extend: 'print',
      title: "<h3 class='text-center text-bold'>" + businessName + "</h3><h4 class='text-center text-bold'>Collaterals Information</h4><h5 class='text-center'>Printed On " + new Date().getHours() + " : " + new Date().getMinutes() + " " + new Date().toDateString() + "</h5>",
      exportOptions: {
        columns: [1, 2, 3, 4]
      },
      customize: function (win) {
        $(win.document.body)
          .css('font-size', '10pt')
          .css('font-family', 'calibri')
          .prepend(
            '<img src="' + logo + '" style="position:absolute; top:0; left:0;width:100px;height:100px;" />'
          );
        $(win.document.body).find('table')
          .addClass('compact')
          .css('font-size', 'inherit');
        // Replace the page title with the actual page title
        $(win.document.head).find('title').text(title);
      },
      className: 'btn btn-sm btn-primary',
      titleAttr: 'Print Collaterals',
      text: '<i class="fa fa-print"></i>',
      filename: 'Collaterals Information'
    },
  ],
  files: [
    {
      text: '<i class="fa fa-upload"></i>',
      className: "btn btn-sm btn-info",
      titleAttr: "Add Income File",
      action: function () {
        add_files("income");
      },
    },
    {
      text: '<i class="fa fa-trash"></i>',
      className: "btn btn-sm btn-danger",
      titleAttr: "Bulky Delete",
      action: function () {
        bulk_deleteFiles();
      },
    },
    {
      text: '<i class="fa fa-refresh"></i>',
      className: "btn btn-sm btn-secondary",
      titleAttr: "Reload Table",
      action: function () {
        reload_table("files");
      },
    },
    {
      extend: "print",
      title:
        "<h3 class='text-center text-bold'>" +
        businessName +
        "</h3><h4 class='text-center text-bold'>Income Files Information</h4><h5 class='text-center'>Printed On " +
        new Date().getHours() +
        " : " +
        new Date().getMinutes() +
        " " +
        new Date().toDateString() +
        "</h5>",
      exportOptions: {
        columns: [1, 2, 3, 4],
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
      },
      className: "btn btn-sm btn-primary",
      titleAttr: "Print Income Files",
      text: '<i class="fa fa-print"></i>',
      filename: "Income Files Information",
    },
  ],
}

$(document).ready(function () {
  // redifine dataTable column length on change of data-bs-toggle tab
  $("button[data-bs-toggle=\"tab\"]").on("shown.bs.tab", function (e) {
    $($.fn.dataTable.tables(true)).DataTable()
      .columns.adjust()
      .responsive.recalc();
  });
  view_application(id);
  // get applicant collateral files
  initializeDataTable(collateralsTableId, collateralsTableUrl, columnsConfig['collaterals'], buttonsConfig['collaterals']);
  // get applicant income files
  initializeDataTable(filesTableId, filesTableUrl, columnsConfig['files'], buttonsConfig['files']);
  // get applicant application particulars payments
  initializeDataTable(chargesPaymentsId, chargesPaymentsUrl, columnsConfig['chargesPayments'], buttonsConfig['chargesPayments']);

  // load application remarks
  load_applicationRemarks();

  // generate loan application particular payables
  application_particularPayables(account_typeId);

  // load payment methods
  selectPaymentMethod();
});

function exportApplicationForm(menu) {
  var application_id = $('[name="id"]').val();
  window.location.assign("/admin/loans/applicationform/" + application_id);
}
function exportApplicationActionForm(menu) {
  var application_id = $('[name="id"]').val();
  if (menu == "approve") {
    window.location.assign(
      "/loans/applicationactionform/approve/" + application_id
    );
  } else {
    window.location.assign(
      "/loans/applicationactionform/pending/" + application_id
    );
  }
}
// view application data
function view_application(id) {
  $.ajax({
    url: "/client/applications/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      buttons_display(data.status)
      $('[name="id"]').val(data.id);
      $('[name="application_id"]').val(data.id);
      $('[name="client_id"]').val(data.c_id);
      $('[name="vapplication_code"]').val(data.application_code);
      $('[name="vapplication_"]').val(data.application_);
      $('[name="vproduct_id"]').val(data.product_name);
      $('[name="vinterest_rate"]').val(data.interest_rate);
      $('[name="vinterest_type"]').val(data.interest_type);
      $('[name="vrepayment_period"]').val(
        data.repayment_period + " " + data.repayment_duration
      );
      $('[name="vrepayment_freq"]').val(data.repayment_freq);
      $('[name="vprincipal"]').val(data.principal);
      $('[name="vstatus"]').val(data.status);
      $('[name="vlevel"]').val(level);
      $('[name="vpurpose"]').val(data.purpose);
      $('[name="overall_charges"]').val(overallCharges);
      $('[name="total_charges"]').val(data.total_charges);
      $('[name="reduct_charges"]').val(data.reduct_charges);

      $('[name="vsecurity_item"]').val(data.security_item);
      $("textarea#viewSummernote").summernote("code", data.security_info);
      $('[name="vest_value"]').val(data.est_value);
      $('[name="vref_name"]').val(data.ref_name);
      $('[name="vref_address"]').val(data.ref_address);
      $('[name="vref_job"]').val(data.ref_job);
      $('[name="vref_contact"]').val(data.ref_contact);
      $('[name="vref_alt_contact"]').val(data.ref_alt_contact);
      $('[name="vref_email"]').val(data.ref_email);
      $('[name="vref_relation"]').val(data.ref_relation);
      $('[name="vref_name2"]').val(data.ref_name2);
      $('[name="vref_address2"]').val(data.ref_address2);
      $('[name="vref_job2"]').val(data.ref_job2);
      $('[name="vref_contact2"]').val(data.ref_contact2);
      $('[name="vref_alt_contact2"]').val(data.ref_alt_contact2);
      $('[name="vref_email2"]').val(data.ref_email2);
      $('[name="vref_relation2"]').val(data.ref_relation2);
      
      $('[name="vnet_salary"]').val(data.net_salary);
      $('[name="vfarming"]').val(data.farming);
      $('[name="vbusiness"]').val(data.business);
      $('[name="vothers"]').val(data.others);
      $('[name="vincome_total"]').val(
        Number(data.net_salary) +
        Number(data.farming) +
        Number(data.business) +
        Number(data.others)
      );
      $('[name="vrent"]').val(data.rent);
      $('[name="veducation"]').val(data.education);
      $('[name="vmedical"]').val(data.medical);
      $('[name="vtransport"]').val(data.transport);
      $('[name="vexp_others"]').val(data.exp_others);
      $('[name="vexp_total"]').val(
        Number(data.rent) +
        Number(data.education) +
        Number(data.medical) +
        Number(data.transport) +
        Number(data.exp_others)
      );
      $('[name="vdifference"]').val(data.difference);
      $('[name="vdif_status"]').val(data.dif_status);

      $('[name="vinstitute_name"]').val(data.institute_name);
      $('[name="vinstitute_branch"]').val(data.institute_branch);
      $('[name="vaccount_type"]').val(data.account_type);
      $('[name="vinstitute_name2"]').val(data.institute_name2);
      $('[name="vinstitute_branch2"]').val(data.institute_branch2);
      $('[name="vaccount_type2"]').val(data.account_type2);
      $('[name="vamt_advance"]').val(data.amt_advance);
      $('[name="vdate_advance"]').val(data.date_advance);
      $('[name="vloan_duration"]').val(data.loan_duration);
      $('[name="vamt_outstanding"]').val(data.amt_outstanding);
      $('[name="vamt_advance2"]').val(data.amt_advance2);
      $('[name="vdate_advance2"]').val(data.date_advance2);
      $('[name="vloan_duration2"]').val(data.loan_duration2);
      $('[name="vamt_outstanding2"]').val(data.amt_outstanding2);
      $('[name="vloan_officer"]').val(data.staff_name);
      $('[name="vstatus"]').val(data.status);
      $('[name="vcreated_at"]').val(data.created_at);
      $('[name="vupdated_at"]').val(data.updated_at);
      if (
        data.photo &&
        imageExists("/uploads/clients/passports/" + data.photo)
      ) {
        $("#photo-preview div").html(
          '<img src="/uploads/clients/passports/' +
          data.photo +
          '" class="img-fluid thumbnail"  style="width: 140px; height: 140px;">'
        );
      } else {
        $("#photo-preview div").html(
          '<img src="/assets/dist/img/nophoto.jpg" class="img-fluid thumbnail"  style="width: 140px; height: 140px;">'
        );
      }
      if (
        data.id_photo_front &&
        imageExists("/uploads/clients/ids/front/" + data.id_photo_front)
      ) {
        $("#id-preview div").html(
          '<img src="/uploads/clients/ids/front/' +
          data.id_photo_front +
          '" class="img-fluid thumbnail"  style="width: 200px; height: 140px;">'
        );
      } else {
        $("#id-preview div").html(
          '<img src="/assets/dist/img/id.jpg" class="img-fluid thumbnail"  style="width: 200px; height: 140px;">'
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
      // get applicant bio & appraisal data
      view_client(data.client_id);
      // view_appraisal(data.a_id);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}
// view client data
function view_client(id) {
  $.ajax({
    url: "/client/clients/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      var today = new Date();
      var dob = new Date(data.dob);
      var age = today.getYear() - dob.getYear();
      $('[name="c_id"]').val(data.c_id);
      $('[name="name"]').val(data.name);
      $('[name="account_no"]').val(data.account_no);
      $('[name="client_account"]').val(data.account_type);
      $('[name="email"]').val(data.email);
      $('[name="mobile"]').val(data.mobile);
      $('[name="alternate_no"]').val(data.alternate_no);
      $('[name="gender"]').val(data.gender);
      $('[name="nationality"]').val(data.nationality);
      $('[name="dob"]').val(data.dob);
      $('[name="marital_status"]').val(data.marital_status);
      $('[name="religion"]').val(data.religion);
      $('[name="officer"]').val(data.responsible_officer);
      $('[name="occupation"]').val(data.occupation);
      $('[name="job_location"]').val(data.job_location);
      $('[name="residence"]').val(data.residence);
      $('[name="id_type"]').val(data.id_type);
      $('[name="id_number"]').val(data.id_number);
      $('[name="id_expiry"]').val(data.id_expiry_date);
      $('[name="next_of_kin"]').val(data.next_of_kin_name);
      $('[name="nok_relationship"]').val(data.next_of_kin_relationship);
      $('[name="nok_phone"]').val(data.next_of_kin_contact);
      $('[name="nok_alt_phone"]').val(data.next_of_kin_alternate_contact);
      $('[name="nok_address"]').val(data.nok_address);
      $('[name="branch_name"]').val(data.branch_name);
      $('[name="age"]').val(
        new Date().getYear() - new Date(data.dob).getYear()
      );
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}

// cancel application
function cancel_application(id, code) {
    Swal.fire({
        title: "Cancel Application?",
        text: "Do you wish to CANCEL Loan Application " + code + "?",
        icon: "warning",
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Cancel!",
        preConfirm: () => {
            return new Promise((resolve) => {
                Swal.showLoading();
                $.ajax({
                    url: "/client/reports/types/cancelapplication/" + id,
                    type: "post",
                    dataType: "JSON",
                    success: function (data) {
                        //if success reload ajax table
                        if (data.status && data.error == null) {
                            Swal.fire("Success!", code + " " + data.messages, "success");
                            count_applications();
                            reload_table("pendingApplications");
                            reload_table("processApplications");
                            reload_table("reviewApplications");
                            reload_table("cancelledApplications");
                            reload_table("declinedApplications");
                            reload_table("approvedApplications");
                            resolve();
                        } else if (data.error != null) {
                            Swal.fire(data.error, data.messages, "error");
                            Swal.close();
                        } else {
                            Swal.fire(
                                "Error",
                                "Something unexpected happened, try again later",
                                "error"
                            );
                            Swal.close();
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        Swal.fire(textStatus, errorThrown, "error");
                        Swal.close();
                    },
                    complete: function () {
                        // Close the SweetAlert2 modal
                        Swal.close();
                    },
                });
            });
        },
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: "Success!",
                text: "Application status updated to Processing",
                icon: "success",
            });
        }
    });
}

// get all application action & statues
function get_applicantionOptions(level = null, action = null, mode = null) {
  // application actions
  $.ajax({
    url: "/admin/applications/get-actions",
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      if (action == null) {
        // select action
        $("select#action").html('<option value="">-- select --</option>');
        if (data.length > 0) {
          for (var i = 0; i < data.length; i++) {
            $("<option />")
              .val(data[i])
              .text(data[i])
              .appendTo($("select#action"));
          }
        } else {
          $("select#action").html('<option value="">No Action</option>');
        }
      } else {
        // select current action
        if (data.length > 0) {
          $("select#action").find("option").not(":first").remove();
          if (mode == null) {
            // Add options
            $.each(data, function (index, result) {
              if (result == action) {
                var selection = "selected";
              } else {
                var selection = "";
              }
              $("select#action").append(
                '<option value="' +
                result +
                '" ' +
                selection +
                " readonly>" +
                result +
                "</option>"
              );
            });
          } else {
            //disable changing action on remark edit\disburse
            $.each(data, function (index, result) {
              if (result == action) {
                var selection = "selected";
                $("select#action").append(
                  '<option value="' +
                  result +
                  '" ' +
                  selection +
                  " readonly>" +
                  result +
                  "</option>"
                );
              }
            });
          }
        } else {
          $("select#action").html('<option value="">No Action</option>');
        }
      }
    },
    error: function (err) {
      $("select#action").html('<option value="">Error Occured</option>');
    },
  });
  // application levels
  $.ajax({
    url: "/admin/applications/get-levels",
    type: "GET",
    dataType: "JSON",
    success: function (response) {
      if (level == null) {
        // select level
        $("select#level").html('<option value="">-- select --</option>');
        if (response.length > 0) {
          for (var i = 0; i < response.length; i++) {
            $("<option />")
              .val(response[i])
              .text(response[i])
              .appendTo($("select#level"));
          }
        } else {
          $("select#level").html('<option value="">No Level</option>');
        }
      } else {
        // select current action
        if (response.length > 0) {
          $("select#level").find("option").not(":first").remove();
          // Add options
          $.each(response, function (index, data) {
            if (data == level) {
              var selection = "selected";
              $("select#level").append(
                '<option value="' +
                data +
                '" ' +
                selection +
                " readonly>" +
                data +
                "</option>"
              );
            }
          });
        } else {
          $("select#level").html('<option value="">No Level</option>');
        }
      }
    },
    error: function (err) {
      $("select#level").html('<option value="">Error Occured</option>');
    },
  });
}
// display application remarks
function load_applicationRemarks() {
  var color;
  $.ajax({
    url: "/admin/loans/get-applicationRemarks/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      var remarks = "";
      if (data.length > 0) {
        for (var i = 0; i < data.length; i++) {
          // change action color based on action choosen
          if (
            data[i].action.toLowerCase() == "declined" ||
            data[i].action.toLowerCase() == "cancelled"
          ) {
            color = "text-danger";
          } else if (
            data[i].action.toLowerCase() == "approved" ||
            data[i].action.toLowerCase() == "disbursed"
          ) {
            color = "text-success";
          } else if (data[i].action.toLowerCase() == "review") {
            color = "text-warning";
          } else {
            color = "text-info";
          }
          remarks +=
            '<div class="card border border-warning custom-card">' +
            '<div class="card-header">' +
            '<div class="d-flex align-items-center w-100">' +
            '<div class="me-2">' +
            '<i class="fas fa-comment me-2 align-middle text-info"></i>' +
            '</div>' +
            '<div class="">' +
            '<div class="fs-15 fw-semibold ' + color + '">' + data[i].action + '</div>' +
            '<p class="mb-0 text-muted fs-11">Level: ' + data[i].level + ', Status: ' + data[i].status + '</p>' +
            '</div>' +
            // dropdown buttons
            '<div class="dropdown ms-auto">' +
            '<a href="javascript:void(0);" class="btn btn-icon btn-sm btn-light" data-bs-toggle="dropdown">' +
            '<i class="fe fe-more-vertical"></i>' +
            '</a>' +
            '<ul class="dropdown-menu">' +
            // edit remark button
            '<li>' +
            '<a class="dropdown-item text-info" href="javascript:void(0);"  onclick="edit_applicationRemark(' +
            data[i].id +
            ')" title="Edit Remark">' +
            '<i class="fas fa-edit"></i>Edit Remark' +
            '</a>' +
            '</li>' +
            // delete remark btn
            '<li>' +
            '<a class="dropdown-item text-danger" href="javascript:void(0);"  onclick="delete_applicationRemark(' + "'" + data[i].id + "'" + "," + "'" + data[i].level + "'" + "," + "'" + data[i].action + "'" + ')" title="Delete Remark">' +
            '<i class="fas fa-trash"></i>Delete Remark' +
            '</a>' +
            '</li>' +
            '</ul>' +
            '</div>' +
            // dropdown buttons ends
            '</div>' +
            "</div>" +
            // card body
            '<div class="card-body">' +
            data[i].remarks +
            '</div>' +
            // card footer
            '<div class="card-footer">' +
            '<div class="d-flex justify-content-between">' +
            '<div class="fs-semibold fs-14">' + data[i].created_at + '</div>' +
            '<div class="fw-semibold text-success">' + data[i].staff_name + '</div>' +
            ' </div>' +
            '</div>' +
            "</div>";
        }
      } else {
        remarks +=
          '<center><a href="javascript: void(0)" class="text-info" onclick="add_applicationRemarks(' +
          "'" +
          id +
          "'" +
          ')" title="Add Remarks"><i class="fas fa-plus"></i> Add Remarks</a></center>';
      }
      $("#remarksCard").html(remarks);
      // hide delete and edit buttons
      if (
        appStatus == "approved" ||
        appStatus == "declined" ||
        appStatus == "disbursed"
      ) {
        $("a.editRemark").hide();
        $("a.deleteRemark").hide();
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}
// edit application comment
function edit_applicationRemark(id) {
  save_method = "update";
  $("#statusForm")[0].reset();
  $(".form-group").removeClass("has-error");
  $(".help-block").empty();
  $("#level").trigger("change");
  $("#action").trigger("change");
  $.ajax({
    url: "/admin/applications/get-remark/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $('[name="remark_id"]').val(data.id);
      $('[name="application_id"]').val(data.application_id);
      $('[name="application_code"]').val(data.application_code);
      get_applicantionOptions(data.level, data.action, "edit"); // selected
      $("textarea#newSummernote").summernote("code", data.remarks);
      $('[name="created_at"]').val(data.created_at);
      $('[name="updated_at"]').val(data.updated_at);
      $("#remarks_form").modal("show");
      $(".modal-title").text("Edit " + data.application_code + " remarks");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}
// delete application comment
function delete_applicationRemark(id, level, action) {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to recover " + level + " " + action + " remark!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "/admin/remarks/delete-remark/" + id,
        type: "POST",
        dataType: "JSON",
        success: function (data) {
          if (data.status && data.error == null) {
            Swal.fire("Success!", name + " " + data.messages, "success");
            window.location.reload();
          } else {
            Swal.fire(data.error, data.messages, "error");
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          Swal.fire(textStatus, errorThrown, "error");
        },
      });
    }
  });
}
// bulk delete application comment
function bulk_deleteApplicationRemarks() {
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
          "You will not be able to recover selected " +
          list_id.length +
          " comment(s) once deleted!",
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
            url: "/admin/remarks/bulk-deleteRemarks",
            dataType: "JSON",
            success: function (data) {
              if (data.status && data.error == null) {
                Swal.fire("Success!", data.messages, "success");
              } else if (data.error != null) {
                Swal.fire(data.error, data.messages, "error");
              } else {
                Swal.fire(
                  "Error",
                  "Something unexpected happened, try again later",
                  "error"
                );
              }
              window.location.reload();
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
    Swal.fire("Sorry!", "No comment selected :)", "error");
  }
}

// make payment
function add_applicationPayment(particular_id, particular_name) {
  particularCharge(particular_id);
  $("#paymentForm")[0].reset();
  $(".form-group").removeClass("has-error");
  $(".help-block").empty();
  $('[name="application_id"]').val(id);
  $('[name="client_id"]').val(clientId);
  $('[name="account_typeId"]').val(account_typeId);
  $('[name="particular_id"]').val(particular_id);
  $("select#payment_id").trigger("change");
  transaction_types(); // load transaction types
  $("select#disbursement_method").trigger("change");
  $("textarea#viewSummernote").summernote("reset");
  $(".modal-title").text("Pay " + particular_name);
  $("#pay_modal_form").modal("show");
}

function application_particularPayables(account_typeId) {
  $.ajax({
    url: "/admin/accounts/accountType-particulars/" + account_typeId,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      var html = "",
        action = "";
      if (data.length > 0) {
        var total, paid, balance;
        var totalCharge = (totalPaid = totalBalance = 0);
        for (var i = 0; i < data.length; i++) {
          // if(data[i].charged.toLowerCase() == 'yes'){
            // get total paid per particular
          paid = applicant_particularTransactions(id, data[i].id);
          // total charge
          if (data[i].charge_method == "Amount") {
            total = Number(data[i].charge);
          } else {
            total = Number((data[i].charge / 100) * principal);
          }
          balance = Number(total - paid); // balance
          // set buttons
          if (balance <= 0) {
            action =
              '<a href="javascript:void(0)" id="addPay" class="text-success">Paid <i class="fas fa-check"></i></a>';
          } else {
            if( data[i].charge_mode == "Manual"){
              action =
                '<a href="javascript:void(0)" id="addPay" class="text-danger" onclick="add_applicationPayment(' +
                "'" +
                data[i].id +
                "'" +
                "," +
                "'" +
                data[i].particular_name +
                "'" +
                ')" title="Make Payment">Pay <i class="fas fa-money-bill-wave"></i></a>';
            } else{
              action =
              '<a href="javascript:void(0)" id="addPay" class="text-warning">Pending <i class="ri-timer-2-line"></i></a>';
            }
          }
          totalCharge += total;
          totalPaid += paid;
          totalBalance += balance;
          html +=
            "<tr>" +
            "<td>" +
            Number(i + 1) +
            "</td><td>" +
            data[i].particular_name +
            "</td>" +
            "<td>" +
            data[i].charge_method +
            "</td><td>" +
            data[i].charge +
            "</td>" +
            "<td>" +
            total +
            "</td><td>" +
            paid +
            "</td>" +
            "<td>" +
            balance +
            "</td>" +
            "<td>" +
            action +
            "</td>" +
            "</tr>";
          // }
        }
        html +=
          "<tr>" +
          '<td colspan="2"></td>' +
          '<td colspan="2">TOTALS</td>' +
          "<td>" +
          totalCharge +
          "</td><td>" +
          totalPaid +
          "</td>" +
          "<td>" +
          totalBalance +
          "</td>" +
          "<td></td>" +
          "</tr>";
      } else {
        var html =
          '<tr><td colspan="8"><center>No Data Found</center></tr>';
      }
      $("tbody#applicationCharges").html(html);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}
// particular payments
function applicant_particularTransactions(application_id, particular_id) {
  var totalPayments = 0;
  $.ajax({
    async: false,
    url:
      "/admin/transactions/applicant-payments/" +
      application_id +
      "/" +
      particular_id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      if (data.length > 0) {
        for (var i = 0; i < data.length; i++) {
          totalPayments += Number(data[i].amount);
        }
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
  return totalPayments;
}
// particular charges
function particularCharge(particular_id) {
  var paid, total, balance;
  $.ajax({
    url: "/admin/accounts/particular/" + particular_id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      paid = applicant_particularTransactions(id, data.id);
      if (paid != 0) {
        if (data.charge_method == "Amount") {
          total = data.charge;
          balance = Number(total - paid);
          $('[name="charge"]').val(total);
          $('[name="amount"]').val(balance);
        } else {
          total = (data.charge / 100) * principal;
          balance = Number(total - paid);
          $('[name="charge"]').val(total);
          $('[name="amount"]').val(balance);
        }
        $("input#amount").attr({ max: balance });
      } else {
        if (data.charge_method == "Amount") {
          $('[name="charge"]').val(data.charge);
          $('[name="amount"]').val(data.charge);
          $("input#amount").attr({ max: data.charge });
        } else {
          $('[name="charge"]').val((data.charge / 100) * principal);
          $('[name="amount"]').val((data.charge / 100) * principal);
          $("input#amount").attr({ max: (data.charge / 100) * principal });
        }
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}
// view payment
function view_transaction(id) {
  $.ajax({
    url: "/admin/transactions/transaction/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      if (data.client_id != null) {
        $("div#clientData").show();
      }
      if (data.disbursement_id != null) {
        $("div#disbursementData").show();
      }
      if (data.application_id != null) {
        $("div#applicationData").show();
      }
      $('[name="id"]').val(data.id);
      $('[name="vclient_name"]').val(data.name);
      $('[name="vaccount_no"]').val(data.account_no);
      $('[name="vcontact"]').val(data.contact);
      $('[name="vaccount_type"]').val(data.module);
      $('[name="vparticular_name"]').val(data.particular_name);
      $('[name="ventry_type"]').val(data.type);
      $('[name="vpayment"]').val(data.payment);
      $('[name="vdate"]').val(data.date);
      $('[name="vbranch_name"]').val(data.branch_name);
      $('[name="vstatus"]').val(data.status);
      $('[name="vstaff_id"]').val(data.staff_name);
      $('[name="vproduct_name"]').val(data.product_name);
      $('[name="vdisbursement_id"]').val(data.disbursement_code);
      $('[name="vclass"]').val(data.class);
      $('[name="vapplication_id"]').val(data.application_code);
      $('[name="vapplication_status"]').val(data.status);
      $('[name="vref_id"]').val(data.ref_id);
      $('[name="vamount"]').val(data.amount);
      $('[name="vstatus"]').val(data.status);
      $("textarea#viewSummernote").summernote("code", data.entry_details);
      $('[name="vremarks"]').val(data.remarks);
      $('[name="ventry_menu"]').val(data.entry_menu);
      $('[name="vbalance"]').val(data.balance);
      $('[name="created_at"]').val(data.created_at);
      $('[name="updated_at"]').val(data.updated_at);
      $("#view_pay_modal").modal("show");
      $(".modal-title").text("View transaction " + data.ref_id);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}
// save
function save_applicationPayment() {
  $("#btnPay").text("submitting...");
  $("#btnPay").attr("disabled", true);
  // ajax adding data to database
  var formData = new FormData($("#paymentForm")[0]);
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
          $("#pay_modal_form").modal("hide");
          Swal.fire("Success!", data.messages, "success");
          application_particularPayables(account_typeId);
          reload_table("chargesPayments");
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
      $("#btnPay").text("Submit");
      $("#btnPay").attr("disabled", false);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
      $("#btnPay").text("Submit");
      $("#btnPay").attr("disabled", false);
    },
  });
}
function delete_particularPayment(id, name) {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to " + name + " live collection!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "/admin/transactions/transaction/" + id,
        type: "DELETE",
        dataType: "JSON",
        success: function (data) {
          if (data.status && data.error == null) {
            Swal.fire("Success!", name + " " + data.messages, "success");
            reload_table("chargesPayments");
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
function bulk_deleteParticularPayments() {
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
          "You will not be able to recover selected " +
          list_id.length +
          " collection(s) once deleted!",
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
            url: "/admin/transactions/transactionsBulk-delete",
            dataType: "JSON",
            success: function (data) {
              if (data.status && data.error == null) {
                Swal.fire("Success!", data.messages, "success");
                reload_table("applPayments");
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
    Swal.fire("Sorry!", "No collection selected :)", "error");
  }
}

// pop add model
function add_files(menu) {
  file_module = menu;
  if (menu == "income") {
    $("#files").attr("name", "income[]");
  }
  if (menu == "expense") {
    $("#files").attr("name", "expense[]");
  }
  $("#file_form")[0].reset();
  $(".form-group").removeClass("has-error");
  $(".help-block").empty();
  $("#modal_form").modal("show");
  $(".modal-title").text("Add File(s)");
}
// view application files
function view_file(id) {
  $.ajax({
    url: "/admin/loans/view-file/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $('[name="id"]').val(data.id);
      $('[name="applicant_name"]').val(data.name);
      $('[name="file_name"]').val(data.file_name);
      $('[name="created_at"]').val(data.created_at);
      if (data.type == "collateral") {
        if (data.file_name) {
          $("#file-preview div").html(
            '<img src="/uploads/applications/collaterals/' +
            data.file_name +
            '" class="img-fluid thumbnail">'
          );
        } else {
          $("#file-preview div").html(
            '<img src="/assets/dist/img/nophoto.jpg" class="img-fluid thumbnail">'
          );
        }
      }
      if (data.type == "income") {
        if (data.file_name) {
          $("#file-preview div").html(
            '<img src="/uploads/applications/income/' +
            data.file_name +
            '" class="img-fluid thumbnail">'
          );
        } else {
          $("#file-preview div").html(
            '<img src="/assets/dist/img/nophoto.jpg" class="img-fluid thumbnail">'
          );
        }
      }
      if (data.type == "expense") {
        if (data.file_name) {
          $("#file-preview div").html(
            '<img src="/uploads/applications/expense/' +
            data.file_name +
            '" class="img-fluid thumbnail">'
          );
        } else {
          $("#file-preview div").html(
            '<img src="/assets/dist/img/nophoto.jpg" class="img-fluid thumbnail">'
          );
        }
      }
      $("#file_view").modal("show");
      $(".modal-title").text("View " + data.file_name);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}
function save_file() {
  $("#btnFile").text("uploading..."); //change button text
  $("#btnFile").attr("disabled", true); //set button disable
  var url;
  if (file_module == "collateral") {
    url = "/admin/loans/save-applicationFiles/" + "collateral";
  } else if (file_module == "income") {
    url = "/admin/loans/save-applicationFiles/" + "income";
  } else {
    url = "/admin/loans/save-applicationFiles/" + "expense";
  }
  // ajax adding data to database
  var formData = new FormData($("#file_form")[0]);
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
          reload_table("collaterals");
          reload_table("incomes");
          reload_table("expenses");
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
      $("#btnFile").text("Upload");
      $("#btnFile").attr("disabled", false);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
      $("#btnFile").text("Upload");
      $("#btnFile").attr("disabled", false);
    },
  });
}
// delete
function delete_file(id, name) {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to recover " + name + "file!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "/admin/files/delete-file/" + id,
        type: "POST",
        dataType: "JSON",
        success: function (data) {
          //if success reload ajax table
          if (data.status && data.error == null) {
            Swal.fire("Success!", name + " " + data.messages, "success");
            reload_table("collaterals");
            reload_table("incomes");
            reload_table("expenses");
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
// bulk delete
function bulk_deleteFiles() {
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
          "You will not be able to recover these " +
          list_id.length +
          " file(s) once deleted!",
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
            url: "/admin/files/fileBulk-delete",
            dataType: "JSON",
            success: function (data) {
              if (data.status && data.error == null) {
                Swal.fire("Sucess!", data.messages, "success");
                reload_table("collaterals");
                reload_table("incomes");
                reload_table("expenses");
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
    Swal.fire("Sorry!", "No file selected....", "error");
  }
}

// hide\show buttons based on application status
function buttons_display(status) {
  switch (status.toLowerCase()) {
    case "pending":
      $("#disburseBtn").hide();
      $("#cancelApplication").show();
      $("#applicationRemarksBtn").hide();
      $("#applicationPayments-tab").hide();
      $("#applicationRemarks-tab").hide();
      break;
    case "processing":
      $("#disburseBtn").hide();
      $("#cancelApplication").show();
      $("#applicationRemarksBtn").show();
      $("#applicationPayments-tab").show();
      $("#applicationRemarks-tab").show();
      break;
    case "approved":
      $("#applicationRemarksBtn").hide();
      $("#disburseBtn").show();
      $("#cancelApplication").show();
      $("#applicationPayments-tab").show();
      $("#applicationRemarks-tab").show();
      break;
    default: // Declined && disbursed
      $("#cancelApplication").hide();
      $("#applicationRemarksBtn").hide();
      $("#disburseBtn").hide();
      $("#applicationPayments-tab").show();
      $("#applicationRemarks-tab").show();
      break;
  }
}

// show more actions on change of application status
$("select#status").on("change", function () {
  var status = this.value;
  var id = $('[name="application_id"]').val();
  if (status != "" || status != 0) {
    $.ajax({
      url: "/admin/applications/get-levels/" + id + "/" + status,
      type: "GET",
      dataType: "JSON",
      success: function (data) {
        $("select#level").html('<option value="">-- select --</option>');
        if (data.length > 0) {
          for (var i = 0; i < data.length; i++) {
            $("<option />")
              .val(data[i])
              .text(data[i])
              .appendTo($("select#level"));
          }
        } else {
          $("select#level").html('<option value="">No Status</option>');
        }
      },
      error: function (err) {
        $("select#level").html('<option value="">Error Occured</option>');
      },
    });

    if (status.toLowerCase() == "disbursed") {
      $("div#approvalCals").show();
      $.ajax({
        url: "/admin/loans/application/" + id,
        type: "GET",
        dataType: "JSON",
        success: function (data) {
          var interval,
            payouts,
            installments_num,
            actual_installment,
            principal_installment,
            interest_installment,
            computed_interest,
            computed_repayment,
            actual_repayment,
            actual_interest;
          interval = getInterval(data.repayment_freq); // space btn installments(months)
          payouts = Number(12 / interval); // annual payouts based om frequency
          installments_num = Number(data.repayment_period / interval); // number of installments
          if (data.interest_type.toLowerCase() == "reducing") {
            // calculated installment[with decimals]
            computed_installment = Number(
              EMI(
                data.interest_type,
                principal,
                data.interest_rate,
                data.repayment_period,
                interval
              )
            );
            // interest paid back per installment
            interest_installment = Number(
              principal * (data.interest_rate / 100 / payouts)
            ).toFixed(0);
          } else {
            // calculatedinstallment[with decimals]
            computed_installment = Number(
              EMI(
                data.interest_type,
                principal,
                data.interest_rate,
                data.repayment_period,
                interval
              )
            );
            // interest paid back per installment
            interest_installment = Number(
              principal * (data.interest_rate / 100 / payouts)
            ).toFixed(0);
          }
          // calculated interest[with decimals]
          computed_interest = calculateInterest(
            data.interest_type,
            principal,
            computed_installment,
            data.interest_rate,
            data.repayment_freq,
            installments_num,
            data.repayment_period
          );
          // installment rounded off as set in settings
          actual_installment = Number(
            Math.ceil(computed_installment / roundOff) * roundOff
          );
          // total interest to be paid
          actual_interest = Number(
            Math.ceil(computed_interest / roundOff) * roundOff
          );
          // installment towards principal
          principal_installment = Number(
            actual_installment - interest_installment
          ).toFixed(0);
          // repayment based on computed interest
          computed_repayment = Number(principal + computed_interest);
          // repayament caluclated on rounded off installment
          actual_repayment = Number(principal + actual_interest);
          
          var totalCharges = data.total_charges;
          var principalRecievable = (principal - Number(totalCharges));

          // hide/show inputs
          if(data.reduct_charges.toLowerCase() == 'savings'){
            $('div#reductCharges').show()
            selectParticulars(12)
            $('div#totalPrincipal').removeClass('col-md-4')
            $('div#totalCharges').removeClass('col-md-4')
            $('div#principalRecievable').removeClass('col-md-4')
            $('div#totalPrincipal').addClass('col-md-3')
            $('div#totalCharges').addClass('col-md-3')
            $('div#principalRecievable').addClass('col-md-3')
          } else{
            $('div#reductCharges').hide()
          }

          // fill form inputs
          $('input#loan_principal_amount').val(principal);
          $('input#loan_interest_type').val(data.interest_type);
          $('input#loan_interest_rate').val(data.interest_rate);
          $('input#loan_frequency_type').val(data.repayment_freq);
          $('input#installments_no').val(installments_num);
          $('input#loan_repayment_period').val(data.repayment_period);
          $('input#loan_installment').val(computed_installment);

          $('[name="application_id"]').val(id);
          $('[name="application_code"]').val(data.application_code);
          $('[name="client_id"]').val(data.client_id);
          $('[name="principal"]').val(principal);
          $('[name="total_charges"]').val(totalCharges.toLocaleString());
          $('[name="principal_receivable"]').val(principalRecievable.toLocaleString());
          $('[name="installments_num"]').val(installments_num.toLocaleString());
          $('[name="computed_installment"]').val(
            computed_installment.toLocaleString()
          );
          $('[name="actual_installment"]').val(
            actual_installment.toLocaleString()
          );
          $('[name="computed_interest"]').val(
            computed_interest.toLocaleString()
          );
          $('[name="actual_interest"]').val(actual_interest.toLocaleString());
          $('[name="computed_repayment"]').val(
            computed_repayment.toLocaleString()
          );
          $('[name="principal_installment"]').val(
            principal_installment.toLocaleString()
          );
          $('[name="interest_installment"]').val(
            interest_installment.toLocaleString()
          );
          $('[name="actual_repayment"]').val(actual_repayment.toLocaleString());
          $('[name="principal"]').val(principal.toLocaleString());
          if (
            data.loan_agreement &&
            imageExists(
              "/uploads/applications/agreements/" + data.loan_agreement
            )
          ) {
            $("#upload-label").text("Upload New Agreement");
            $("#loanAgreement-preview").html(
              '<img src="/uploads/applications/agreements/' +
              data.loan_agreement +
              '" alt="Image preview" id="preview-image" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
            );
          } else {
            $("#upload-label").text("Upload Agreement");
            $("#loanAgreement-preview").html(
              '<img src="/assets/dist/img/doc.jpg" alt="Agreement preview" id="preview-image" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
            );
          }
          //
          get_applicantionOptions(data.level, data.action, "disburse");
          $("#remarks_form").modal("show");
          $(".modal-title").text(
            "Disburse " +
            principal.toLocaleString() +
            " for " +
            data.application_code
          );
        },
        error: function (jqXHR, textStatus, errorThrown) {
          Swal.fire(textStatus, errorThrown, "error");
        },
      });
    } else {
      if (status.toLowerCase() == "approved") {
        $("div#approvalCals").hide();
        $("div#loan-agreement").show();
      } else {
        $("div#approvalCals").hide();
        $("div#loan-agreement").hide();
        $("div#actionRemarks").removeClass("col-md-8");
        $("div#actionRemarks").addClass("col-md-12");
      }
      $.ajax({
        url: "/admin/loans/application/" + id,
        type: "GET",
        dataType: "JSON",
        success: function (data) {
          // $('textarea#newSummernote').summernote('code', data.application_remarks);
          if (data.agreement) {
            $("#upload-label").text("Upload New Agreement");
            $("#loanAgreement-preview").html(
              '<img src="/uploads/applications/agreements/' +
              data.agreement +
              '" alt="Image preview" id="preview-image" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
            );
          } else {
            $("#upload-label").text("Upload Agreement");
            $("#loanAgreement-preview").html(
              '<img src="/assets/dist/img/nophoto.jpg" alt="Agreement preview" id="preview-image" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
            );
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          Swal.fire(textStatus, errorThrown, "error");
        },
      });
    }
  }
});
// get credit module particulars
$("select#creditModule").change(function () {
  var crModule = $("#creditModule").val();
  if (crModule != "") {
    $.ajax({
      type: "GET",
      dataType: "JSON",
      url: "/admin/accounts/accountType-particulars/" + crModule,
      success: function (data) {
        $("select#cr_particular").html(
          '<option value="">-- select --</option>'
        );
        if (data.length > 0) {
          for (var i = 0; i < data.length; i++) {
            $("<option />")
              .val(data[i].id)
              .text(data[i].particular_name)
              .appendTo($("select#cr_particular"));
          }
        } else {
          $("select#cr_particular").html(
            '<option value="">No Particular</option>'
          );
        }
      },
      error: function (err) {
        $("select#cr_particular").html(
          '<option value="">Error Occured</option>'
        );
      },
    });
  }
});

// pick data from dirsbure model and show it in the loan agreement
$('#btnAgreement').on('click', function(){
  // pick from form
  var dateDisbursed = new Date($('#date_disbursed').val());
  var dayDisbursed = dateDisbursed.getDate();
  var monthDisbursed = dateDisbursed.toLocaleString('default', { month: 'short' });
  var yearDisbursed = dateDisbursed.getFullYear();
  var customDate = dayDisbursed +' '+ monthDisbursed +' '+ yearDisbursed
  var totalInstallments = $('#installments_num').val();
  var installment = $('#actual_installment').val();
  var repayment = $('#actual_repayment').val();
  var disbursementMode = $('#disbursed_by').val();
    // display on agreement
  $('span#disbursement-date').text(customDate);
  $('span#day-disbursed').text(dayDisbursed);
  $('span#month-disbursed').text(monthDisbursed);
  $('span#year-disbursed').text(yearDisbursed);
  $('span#installments').text(totalInstallments +' ('+ convertNumberToWords((totalInstallments)) +')');
  $('span#installment').text(installment +' ('+ convertNumberToWords((installment.replace(/\,/g,''))) +' only)');
  $('span.total-repayment').text(repayment.toLocaleString() +'= ('+ convertNumberToWords(repayment.replace(/\,/g,'')) +')');
  $('span.disbursement-mode').text(disbursementMode);
})