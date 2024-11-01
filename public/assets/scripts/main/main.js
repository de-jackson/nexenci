$(document).ready(function () {
  // menus() // load menu
  // remove errors on input fields
  /*
    $("input").on("input", function () {
        $(this).parent().removeClass('has-error');
        $(this).next().empty();
    });
    */
  // Select input elements of type 'text', 'tel', and 'number'
  $("input[type='text'], input[type='tel'], input[type='number']").on(
    "input",
    function () {
      $(this).parent().removeClass("has-error");
      $(this).next().empty();
    }
  );

  // remove errors on select boxes
  $("select").on("change", function () {
    $(this).parent().removeClass("has-error");
    $(this).next().next().empty();
  });
  // remove errors on text areas
  $("textarea").on("input", function () {
    $(this).parent().parent().removeClass("has-error");
    $(this).next().empty();
  });

  //check all table inputs
  $("#check-all").click(function () {
    $(".data-check").prop("checked", $(this).prop("checked"));
  });

  // Initialize Select2 Elements dynamically based on data-placeholder attribute
  $(".select2").each(function () {
    var placeholder = $(this).data("placeholder"); // Get the placeholder from data-placeholder attribute
    $(this).select2({
      placeholder: placeholder,
      allowClear: true,
    });
  });

  //Initialize Select2 Elements
  // $(".select2").select2();

  $(".select2bs5").select2({
    theme: "bootstrap-5",
  });

  $(".select2bs4").each(function () {
    $(this).select2({
      // theme: 'bootstrap-5',
      dropdownParent: $(this).parent(),
    });
  });

  // Initialize the intl-tel-input library for phone number inputs with the "phone-input" class
  var phoneInputs = document.querySelectorAll(".phone-input-4");

  phoneInputs.forEach(function (input) {
    var iti = window.intlTelInput(input, {
      // Automatically select the user's country based on their IP address
      //initialCountry: "auto",
      initialCountry: "UG", // Uganda as the default country for each phone input
      separateDialCode: true, // Display the country code separately
    });
  });

  // count emails
  count_mails();
});

// get DatePicker
// $(document).on("focus", ".getDatePicker", function () {
//     $(this).datepicker({
//         changeMonth: true,
//         changeYear: true,
//         dateFormat: "yy-mm-dd",
//         yearRange: "2020:2050",
//     });
// });
document.addEventListener("focusin", (e) => {
  if (e.target.closest(".flatpickr-calendar") !== null) {
    e.stopImmediatePropagation();
  }
});
/* For Human Friendly dates */
flatpickr(".getDatePicker", {
  altInput: false,
  altFormat: "F j, Y",
  dateFormat: "Y-m-d",
  // defaultDate: "today",
});

// load resources
$(function () {
  // Summernote editor
  $("#summernote").summernote({
    placeholder: "Enter Text Here...",
    tabsize: 2,
    height: 100,
  });
  $("#newSummernote").summernote({
    placeholder: "Enter Text Here...",
    tabsize: 2,
    height: 100,
  });
  $("#addSummernote").summernote({
    placeholder: "Enter Text Here...",
    tabsize: 2,
    height: 100,
  });
  $("#viewSummernote").summernote({
    placeholder: "Display Text Here...",
    tabsize: 2,
    height: 100,
  });
  $("#seeSummernote").summernote({
    placeholder: "Display Text Here...",
    tabsize: 2,
    height: 100,
  });
  /* basic select2 */
  $(".js-example-basic-single").select2();

  /* multiple select */
  $(".js-example-basic-multiple").select2();

  /* single select with placeholder */
  $(".js-example-placeholder-single").select2({
    placeholder: "Select a state",
    allowClear: true,
    dir: "ltr",
  });

  /* multiple select with placeholder */
  $(".js-example-placeholder-multiple").select2({
    placeholder: "Select a state",
  });
  //Initialize Select2 Elements
  // $(".select2").select2(); // default

  // $(".branch_id").select2({
  //     dropdownParent: $("#modal_form"),
  //     ajax: {
  //         url: "/admin/branches/get-branches",
  //         type: "GET",
  //         dataType: "JSON",
  //         success: function (data) {
  //           $(".branch_id").html('<option value="">-- select --</option>');
  //           if (data.length > 0) {
  //             for (var i = 0; i < data.length; i++) {
  //               $("<option />")
  //                 .val(data[i].id)
  //                 .text(data[i].branch_name)
  //                 .appendTo($(".branch_id"));
  //             }
  //           } else {
  //             $(".branch_id").html('<option value="">No Branch</option>');
  //           }
  //         },
  //         error: function (err) {
  //           $(".branch_id").html('<option value="">Error Occured</option>');
  //         },
  //     }
  // })

  // $(".select2bs4").select2({
  //     theme: "bootstrap4",
  //     dropdownParent: $("#modal_form"),
  // });
 
  // savings
  $(".select2sm").select2({
    theme: "bootstrap4",
    dropdownParent: $("#savings_modal_form"),
  });
  // remarks
  $(".select2rm").select2({
    theme: "bootstrap4",
    dropdownParent: $("#remarks_form"),
  });
  // application payments
  $(".select2pmd").select2({
    theme: "bootstrap4",
    dropdownParent: $("#pay_modal_form"),
  });
  // repayment
  $(".select2rmf").select2({
    theme: "bootstrap4",
    dropdownParent: $("#repayment_modal_form"),
  });
});
// date formatting options
let formatDate = (date, format = "short") => {
  var format_date = new Date(date);
  const month = format_date.getMonth(); // 0-11
  const day = format_date.getDate(); // 1-31
  const year = format_date.getFullYear(); // 4-digit year

  if (format == "short") {
    var monthNames = [
      "Jan",
      "Feb",
      "Mar",
      "Apr",
      "May",
      "Jun",
      "Jul",
      "Aug",
      "Sep",
      "Oct",
      "Nov",
      "Dec",
    ];
  } else {
    var monthNames = [
      "January",
      "February",
      "March",
      "April",
      "May",
      "June",
      "July",
      "August",
      "September",
      "October",
      "November",
      "December",
    ];
  }

  return `${monthNames[month]} ${day}, ${year}`;
};

//emails counter
let count_mails = () => {
  $.ajax({
    url: "/counter/emails",
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $("#notification-icon-badge").text(
        Number(data.inbox) > 0 ? data.inbox : ""
      );
      $("#inboxCount").text(Number(data.inbox) > 0 ? data.inbox : "");
      $("#sentCount").text(Number(data.sent) > 0 ? data.sent : "");
      $("#favoriteCount").text(Number(data.favorite) > 0 ? data.favorite : "");
      $("#draftsCount").text(Number(data.draft) > 0 ? data.draft : "");
      $("#spamCount").text(Number(data.spam) > 0 ? data.spam : "");
      $("#importantCount").text(
        Number(data.important) > 0 ? data.important : ""
      );
      $("#trashCount").text(Number(data.trash) > 0 ? data.trash : "");
      $("#archiveCount").text(Number(data.archive) > 0 ? data.archive : "");
      $("#favoriteCount").text(Number(data.favorite) > 0 ? data.favorite : "");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      // Swal.fire(textStatus, errorThrown, 'error');
    },
  });
};
// make first letter on a word capital
let capitalizeFirstLetter = (word) => {
  return word.charAt(0).toUpperCase() + word.slice(1);
};

let stripTags = (html) => {
  var doc = new DOMParser().parseFromString(html, "text/html");
  return doc.body.textContent || "";
};

let convertNumberToWords = (num) => {
  var number = parseInt(num);
  if (number < 0 || number > 999999999) {
    return "Number is out of range";
  }

  var Gn = Math.floor(number / 1000000);
  number -= Gn * 1000000;
  var kn = Math.floor(number / 1000);
  number -= kn * 1000;
  var Hn = Math.floor(number / 100);
  number -= Hn * 100;
  var Dn = Math.floor(number / 10);
  var n = number % 10;

  var res = "";

  if (Gn) {
    res += convertNumberToWords(Gn) + " Million";
  }
  if (kn) {
    res += (res === "" ? "" : " ") + convertNumberToWords(kn) + " Thousand";
  }
  if (Hn) {
    res += (res === "" ? "" : " ") + convertNumberToWords(Hn) + " Hundred";
  }

  var ones = [
    "",
    "One",
    "Two",
    "Three",
    "Four",
    "Five",
    "Six",
    "Seven",
    "Eight",
    "Nine",
    "Ten",
    "Eleven",
    "Twelve",
    "Thirteen",
    "Fourteen",
    "Fifteen",
    "Sixteen",
    "Seventeen",
    "Eighteen",
    "Nineteen",
  ];
  var tens = [
    "",
    "",
    "Twenty",
    "Thirty",
    "Forty",
    "Fifty",
    "Sixty",
    "Seventy",
    "Eighty",
    "Ninety",
  ];

  if (Dn || n) {
    if (res !== "") {
      res += " and ";
    }

    if (Dn < 2) {
      res += ones[Dn * 10 + n];
    } else {
      res += tens[Dn];
      if (n) {
        res += "-" + ones[n];
      }
    }
  }

  if (res === "") {
    res = "zero";
  }

  return res;
};

// function textEditor() {
//     /* Summernote Validation */

//     var summernoteForm = $('.form-horizontal');
//     var summernoteElement = $('.summernote');

//     var summernoteValidator = summernoteForm.validate({
//         errorElement: "div",
//         errorClass: 'is-invalid',
//         validClass: 'is-valid',
//         ignore: ':hidden:not(.summernote),.note-editable.card-block',
//         errorPlacement: function (error, element) {
//             // Add the `help-block` class to the error element
//             error.addClass("invalid-feedback");
//             if (element.prop("type") === "checkbox") {
//                 error.insertAfter(element.siblings("label"));
//             } else if (element.hasClass("summernote")) {
//                 error.insertAfter(element.siblings(".note-editor"));
//             } else {
//                 error.insertAfter(element);
//             }
//         }
//     });

//     summernoteElement.summernote({
//         height: 300,
//         callbacks: {
//             onChange: function (contents, $editable) {
//                 // Note that at this point, the value of the `textarea` is not the same as the one
//                 // you entered into the summernote editor, so you have to set it yourself to make
//                 // the validation consistent and in sync with the value.
//                 summernoteElement.val(summernoteElement.summernote('isEmpty') ? "" : contents);

//                 // You should re-validate your element after change, because the plugin will have
//                 // no way to know that the value of your `textarea` has been changed if the change
//                 // was done programmatically.
//                 summernoteValidator.element(summernoteElement);
//             }
//         }
//     });
// }

// load user menu
function menus() {
  if (userMenu) {
    var html = (tabClass = "");
    if (userMenu.parents && userMenu.parents.length > 0) {
      $.each(userMenu.parents, function (index, parentTab) {
        // Load tab without dropdown tabs
        if (
          parentTab.parent_id == 0 &&
          parentTab.url != "javascript: void(0)"
        ) {
          // Assign class to nav tabs
          navClass =
            currentURL === baseURL + parentTab.url ? "slide active" : "slide";
          html +=
            '<li class="slide__category">' +
            '<span class="category-name">' +
            parentTab.title +
            "</span>" +
            "</li>" +
            '<li class="' +
            navClass +
            '">' +
            '<a href="/' +
            parentTab.url +
            '" class="side-menu__item">' +
            '<i class="' +
            parentTab.icon +
            ' side-menu__icon"></i> ' +
            ' <span class="side-menu__label">' +
            parentTab.title +
            "</span>" +
            "</a>" +
            "</li>";
        }
        // Load tab with dropdown tabs
        if (
          parentTab.parent_id == 0 &&
          parentTab.url == "javascript: void(0)"
        ) {
          // Assign class to parent nav tabs
          tabClass =
            menu.toLowerCase() === parentTab.menu.toLowerCase()
              ? "slide has-sub open active "
              : "slide has-sub";
          html +=
            '<li class="slide__category"><span class="category-name">' +
            parentTab.title +
            "</span></li>" +
            '<li class="' +
            tabClass +
            '" id="' +
            parentTab.slug +
            '">' +
            '<a href="' +
            parentTab.url +
            '" class="side-menu__item" onclick="toogle_dropdown(\'' +
            parentTab.slug +
            "', this)\">" +
            '<i class="' +
            parentTab.icon +
            ' side-menu__icon"></i> ' +
            ' <span class="side-menu__label">' +
            parentTab.title +
            "</span>" +
            '<i class="fe fe-chevron-right side-menu__angle"></i>' +
            "</a>" +
            '<ul class="slide-menu child1" data-popper-placement="buttom">' +
            '<li class="slide side-menu__label1">' +
            '<a href="javascript:void(0)">' +
            parentTab.title +
            "</a>" +
            "</li>";
          // Load dropdown tabs for parent tab
          if (userMenu.children && userMenu.children.length > 0) {
            $.each(userMenu.children, function (index, childTab) {
              // Assign class to nav tabs
              navClass =
                currentURL === baseURL + childTab.url
                  ? "slide active"
                  : "slide";
              if (childTab.parent_id == parentTab.id) {
                html +=
                  '<li class="' +
                  navClass +
                  '">' +
                  '<a href="/' +
                  childTab.url +
                  '" class="side-menu__item">' +
                  childTab.title +
                  "</a>" +
                  "</li>";
              }
            });
          }
          html += "</ul>" + "</li>" + "</li>";
        }
      });
    }
    // Load logout tab
    html +=
      '<li class="slide">' +
      '<a href="/logout" class="side-menu__item">' +
      '<i class="fas fa-sign-out side-menu__icon"></i>' +
      '<span class="text-danger">' +
      "Sign Out" +
      "</span>" +
      "</a>" +
      "</li>";

    // Attach generated tabs to side menu
    $("ul#main-menu").html(html);
  }
}
// open\close dropwon menu
let toogle_dropdown = (parent_eleID, element) => {
  // get parent element of the function calling element by ID
  let navTab = $("#" + parent_eleID);
  // find the child ul element of the element making a call to the function
  dropDown = $(element).next("ul.slide-menu");
  // check if it has class open
  if (navTab.hasClass("open")) {
    dropDown.css({
      position: "relative",
      left: "0px",
      top: "0px",
      transform: "translate(120px, 148px)",
      "border-sizing": "border-box",
      display: "none",
    });
    dropDown.attr("data-popper-escaped", false);
    navTab.removeClass("open");
  } else {
    dropDown.css({
      position: "relative",
      left: "0px",
      top: "0px",
      transform: "translate(12px, 486px)",
      "border-sizing": "border-box",
      display: "block",
    });
    dropDown.attr("data-popper-escaped", true);
    navTab.addClass("open");
  }
};

$(".amount")
  .toArray()
  .forEach(function (field) {
    new Cleave(field, {
      numeral: true,
      numeralDecimalMark: ".",
      delimiter: ",",
    });
  });

function removeCommasFromAmount(amount) {
  const cleanAmount = amount.replace(/,/g, "");
  return cleanAmount;
}
