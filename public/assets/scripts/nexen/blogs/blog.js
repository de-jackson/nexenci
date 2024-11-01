var save_method;
var table = "posts";

// dataTables url
var tableData = "/nexen/blog/blogs/new";
// dataTables column config
var columnsConfig = [
  { data: "checkbox", orderable: false, searchable: false },
  { data: "no", orderable: false },
  { data: "image", orderable: false, searchable: false },
  { data: "title" },
  { data: "created_at" },
  { data: "name" },
  { data: "category" },
  { data: "status" },
  { data: "action", orderable: false, searchable: false },
];
// dataTables buttons config
var buttonsConfig = [];
// show create button
if (userPermissions.includes("create_" + menu.toLowerCase() + titleSlug) || userPermissions === '"all"') {
  buttonsConfig.push({
    text: '<i class="fas fa-plus"></i>',
    className: "btn btn-sm btn-secondary create" + title,
    attr: {
      id: "create" + title,
    },
    titleAttr: "Add " + title,
    action: function () {
      addBlogPost();
    },
  });
}
/*
if (userPermissions.includes("import_" + menu.toLowerCase() + titleSlug) || userPermissions === '"all"') {
  buttonsConfig.push({
    text: '<i class="fas fa-upload"></i>',
    className: "btn btn-sm btn-info import" + title,
    attr: {
      id: "import" + title,
    },
    titleAttr: "Import " + title + " Information",
    action: function () {
      import_user();
    },
  });
}
*/
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
      bulkDeleteBlogPosts();
    },
  });
}
// show reload table button by default
buttonsConfig.push({
  text: '<i class="fa fa-refresh"></i>',
  className: "btn btn-sm btn-warning",
  titleAttr: "Reload " + title + " Information",
  action: function () {
    reload_table(table);
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
      filename: title + " Information",
      extension: ".xlsx",
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7],
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
        columns: [1, 2, 3, 4, 5, 6, 7],
      },
    },
    {
      extend: "csv",
      className: "btn btn-sm btn-success",
      titleAttr: "Export " + title + " Information To CSV",
      text: '<i class="fas fa-file-csv"></i>',
      filename: title + "  Information",
      extension: ".csv",
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7],
      },
    },
    {
      extend: "copy",
      className: "btn btn-sm btn-secondary",
      titleAttr: "Copy " + title + " Information",
      text: '<i class="fas fa-copy"></i>',
      exportOptions: {
        columns: [1, 2, 3, 4, 5, 6, 7],
      },
    },
    {
      extend: "print",
      title:
        '<h3 class="text-center text-bold">' +
        businessName +
        "</h3>" +
        '<h4 class="text-center text-bold">' +
        title +
        "  Information</h4>" +
        '<h5 class="text-center">Printed On ' +
        new Date().toDateString() +
        " " +
        new Date().getHours() +
        ":" +
        new Date().getMinutes() +
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
          )
          .find("table")
          .addClass("compact")
          .css("font-size", "inherit");
        // Hide the page title
        //$(win.document.head).find('title').remove();
        $(win.document.head).find("title").text(title);
      },

      className: "btn btn-sm btn-primary",
      titleAttr: "Print " + title + " Information",
      text: '<i class="fa fa-print"></i>',
      filename: title + "  Information",
    }
  );
}

$(document).ready(function () {
  //check all
  $("#check-all").click(function () {
    $(".data-check").prop("checked", $(this).prop("checked"));
  });

  // call to dataTable initialization function
  initializeDataTable(table, tableData, columnsConfig, buttonsConfig);
});

function reload_table(table) {
  $("#" + table)
    .DataTable()
    .ajax.reload();
  /* This is to uncheck the column header check box */
  $("input[type=checkbox]").each(function () {
    this.checked = false;
  });
}

function addBlogPost() {
  save_method = "add";
  $("#form")[0].reset();
  $(".form-group").removeClass("has-error");
  $(".help-block").empty();
  $("#modal_form").modal("show");
  $(".modal-title").text("Add New Blog Post");
  $("#btnSave").text("Add");
  $("#upload-label").text("Upload Image");
  $("#user-photo-preview").html(
    '<img src="/assets/dist/img/nophoto.jpg" alt="Image preview" id="preview-image" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
  );
  $('[name="operation"]').val("create");
  $("#blogPostRow").show();
  $("#importRow").hide();
  getBlogPostCategories();
  $('[name="content"]').val(window.editor.setData(""));
}

function import_user() {
  save_method = "import";
  $("#form")[0].reset(); // reset form on modals
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  $("#modal_form").modal("show"); // show bootstrap modal
  $(".modal-title").text("Import User(s)");
  $("#btnSave").text("Import");
  $("#blogPostRow").hide();
  $("#permissionsRow").hide();
  $("#importRow").show();
}

function saveBlogPost() {
  var url, type;
  var id = $('[name="id"]').val();
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  if (save_method == "add") {
    $("#btnSave").text("adding...");
    $("#btnSave").attr("disabled", true);
    url = "/nexen/blog/blogs";
  } else if (save_method == "import") {
    $("#btnSave").text("importing...");
    $("#btnSave").attr("disabled", true);
    url = "/nexen/blog/blogs";
  } else {
    $("#btnSave").text("updating...");
    $("#btnSave").attr("disabled", true);
    url = "/nexen/blog/blogs";
  }
  var formData = new FormData($("#form")[0]);

  // Ensure the editor data is updated in the textarea
  const editorData = window.editor.getData();
  // $('textarea#content').val(editorData);
  formData.append("content", editorData);

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
          reload_table(table);
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
      if (save_method == "add") {
        $("#btnSave").text("Add");
        $("#btnSave").attr("disabled", false);
      } else if (save_method == "import") {
        $("#btnSave").text("Import");
        $("#btnSave").attr("disabled", false);
      } else if (save_method == "permissions") {
        $("#btnSave").text("Assign");
        $("#btnSave").attr("disabled", false);
      } else if (save_method == "password") {
        $("#btnSave").text("Submit");
        $("#btnSave").attr("disabled", false);
      } else {
        $("#btnSave").text("Update");
        $("#btnSave").attr("disabled", false);
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      if (save_method == "add") {
        swal(textStatus, errorThrown, "error");
        $("#btnSave").text("Add");
        $("#btnSave").attr("disabled", false);
      } else if (save_method == "import") {
        swal(textStatus, errorThrown, "error");
        $("#btnSave").text("Import");
        $("#btnSave").attr("disabled", false);
      } else if (save_method == "permissions") {
        swal(textStatus, errorThrown, "error");
        $("#btnSave").text("Assing");
        $("#btnSave").attr("disabled", false);
      } else {
        swal(textStatus, errorThrown, "error");
        $("#btnSave").text("Update");
        $("#btnSave").attr("disabled", false);
      }
    },
  });
}

function editBlogPost(id) {
  save_method = "update";
  $("#importRow").hide();
  $("#permissionsRow").hide();
  $('[name="operation"]').val("update");
  $("#blogPostRow").show();
  $("#passwordRow").hide();
  $("#form")[0].reset();
  $(".form-group").removeClass("has-error");
  $(".help-block").empty();
  $.ajax({
    url: "/nexen/blog/blogs/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $('[name="id"]').val(id);
      $('[name="intro"]').val(data.intro);
      $('[name="title"]').val(data.title);
      $('[name="content"]').val(window.editor.setData(data.content));
      $('[name="blog_id"]').val(data.blog_id);
      $('[name="blog_id"]').val(data.blog_id).trigger("change");
      $('[name="status"]').val(data.status).trigger("change");
      $("#modal_form").modal("show");
      $("#btnSave").text("Update");
      $(".modal-title").text("Update " + data.title);
      if (data.image) {
        $("#upload-label").text("Upload New Image");
        $("#user-photo-preview").html(
          '<img src="/uploads/posts/' +
            data.image +
            '" alt="Image preview" id="preview-image" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
        );
      } else {
        $("#upload-label").text("Upload Photo");
        $("#user-photo-preview").html(
          '<img src="/assets/dist/img/nophoto.jpg" alt="Image preview" id="preview-image" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
        );
      }
      getBlogPostCategories(data.blog_id);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      swal(textStatus, errorThrown, "error");
    },
  });
}

function view_user(id) {
  $("#form")[0].reset();
  $(".form-group").removeClass("has-error");
  $(".help-block").empty();
  $.ajax({
    url: "/nexen/blog/blogs/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $('[name="id"]').val(data.id);
      $('[name="vname"]').val(data.name);
      $('[name="vphone"]').val(data.mobile);
      $('[name="vemail"]').val(data.email);
      $('[name="oldemail"]').val(data.email);
      $('[name="vaddress"]').val(data.address);
      $('[name="vbranch_name"]').val(data.branch_name);
      $('[name="vaccount_type"]').val(data.account_type);
      $('[name="vaccess_status"]').val(data.access_status);
      if (data.photo && imageExists("/uploads/users/" + data.photo)) {
        $("#photo-preview div").html(
          '<img src="/uploads/users/' +
            data.photo +
            '" class="img-fluid thumbnail"  style="width: 190px; height: 190px;">'
        );
      } else {
        $("#photo-preview div").html(
          '<img src="/assets/dist/img/nophoto.jpg" class="img-fluid thumbnail"  style="width: 190px; height: 190px;">'
        );
      }
      $("#view_modal_form").modal("show");
      $(".modal-title").text("View " + data.name);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      swal(textStatus, errorThrown, "error");
    },
  });
}

function view_user_photo(id) {
  $.ajax({
    url: "/nexen/blog/blogs/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $('[name="id"]').val(data.id);
      $("#photo_modal_form").modal("show");
      $(".modal-title").text(data.name);
      $("#photo-preview").show();

      if (data.photo) {
        $("#photo-preview div").html(
          '<img src="/uploads/users/' +
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
      swal(textStatus, errorThrown, "error");
    },
  });
}

function deleteBlogPost(id, title) {
  Swal.fire({
    title: "Are you sure?",
    text: "You want to delete " + title,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "/nexen/blog/blogs/" + id,
        type: "DELETE",
        dataType: "JSON",
        success: function (data) {
          if (data.status && data.error == null) {
            Swal.fire("Deleted!", title + " " + data.messages, "success");
            reload_table(table);
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
          swal(textStatus, errorThrown, "error");
        },
      });
    }
  });
}

function bulkDeleteBlogPosts() {
  var list_id = [];
  $(".data-check:checked").each(function () {
    list_id.push(this.value);
  });
  if (list_id.length > 0) {
    const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        confirmButton: "btn btn-primary",
        cancelButton: "btn btn-danger",
      },
      buttonsStyling: true,
    });

    swalWithBootstrapButtons
      .fire({
        title: "Are you sure?",
        text:
          "You won't be able to revert this " +
          list_id.length +
          " record(s) once deleted!",
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
            data: { id: list_id },
            url: "/blog/blog/bulk-delete-posts",
            dataType: "JSON",
            success: function (data) {
              if (data.status && data.error == null) {
                swalWithBootstrapButtons.fire(
                  "Success!",
                  data.messages,
                  "success"
                );
                reload_table(table);
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
              swalWithBootstrapButtons.fire(textStatus, errorThrown, "error");
            },
          });
        } else if (
          /* Read more about handling dismissals below */
          result.dismiss === Swal.DismissReason.cancel
        ) {
          swalWithBootstrapButtons.fire(
            "Cancelled",
            "Selected Records are NOT Deleted :)",
            "error"
          );
        }
      });
  } else {
    Swal.fire(
      "Sorry!",
      "At least select one record to complete this process!",
      "error"
    );
    reload_table(table);
  }
}
