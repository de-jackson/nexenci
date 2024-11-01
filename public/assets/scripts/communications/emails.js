$(document).ready(function () {
  // load emails
  fetch_mails();

  //check all table inputs
  $("#checkAll-emails").click(function () {
    $(".email-check").prop("checked", $(this).prop("checked"));
  });
});

//  compose new email
function compose_email(
  id = null,
  action = "compose",
  tag = null,
  subject = null
) {
  save_method = "add";
  if (action == "compose") {
    $("div#composeData").show();
  } else {
    $("div#composeData").hide();
  }
  $("#compose-form")[0].reset(); // reset form on modals
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  $("select#recipient_account").trigger("change");
  $("select#recipient_id").trigger("change");
  $("select#tag_id").trigger("change");
  $('[name="id"]').val(id);
  $('[name="action"]').val(action);
  $('[name="tag_id"]').val(tag);
  $('[name="subject"]').val(subject);
  $("textarea#addSummernote").summernote("reset");
  $("#mailList-content").hide();
  $("#compose-content").show();
}

// ajax generate email lists
function fetch_mails(key = "label", val = "inbox", tag_name = null) {
  $("#compose-content").hide();
  $("#viewMailContent").hide();
  $("#mailsContent").show();
  $("#mailList-content").show();
  // $("#checkAll").attr("checked", false);   
  active_mailLink(val);// higlight active label
  var emailSection = $("div#emails");

  $.ajax({
    url: "/admin/mails/fetch-mails/" + key + "/" + val,
    type: "GET",
    dataType: "JSON",
    success: function (response) {
      var mailItem = "";
      if (response.length > 0) {
        $.each(response, function (index, mail) {
          // if (index == 0) {
          //   view_email(mail.id, val);
          // }
          // email sent date
          var customDate = mailing_date(mail.created_at);
          // recipient photo
          var recipientPhoto = mail_photo(mail.photo, mail.recipient_account);
          var readStatus = (statusBadge = favoritetxt = "");
          // unread mail
          if (val == "inbox" && mail.status == "unread") {
            readStatus = "unread";
            var statusBadge =
              '<span class="badge bg-primary rounded-pill header-icon-badge pulse pulse-primary" id="notification-icon-badge' +
              mail.id +
              '">new</span>';
          }
          // favorite
          if (mail.label == "favorite") {
            favoritetxt = "#FF9F00";
          } else {
            favoritetxt = "#666666";
          }
          // individual mail item
          mailItem +=
            '<div class="message ' +
            readStatus +
            '">' +
            "<div>" +
            '<div class="d-flex message-single">' +
            '<div class="ps-1 align-self-center">' +
            '<div class="form-check custom-checkbox">' +
            '<input type="checkbox" class="form-check-input" id="' +
            mail.id +
            '" value="' +
            mail.id +
            '">' +
            '<label class="form-check-label" for="' +
            mail.id +
            '"></label>' +
            "</div>" +
            "</div>" +
            '<div class="ms-2">' +
            '<label class="bookmark-btn align-middle"><svg width="20" height="20" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M13.1043 4.17701L14.9317 7.82776C15.1108 8.18616 15.4565 8.43467 15.8573 8.49218L19.9453 9.08062C20.9554 9.22644 21.3573 10.4505 20.6263 11.1519L17.6702 13.9924C17.3797 14.2718 17.2474 14.6733 17.3162 15.0676L18.0138 19.0778C18.1856 20.0698 17.1298 20.8267 16.227 20.3574L12.5732 18.4627C12.215 18.2768 11.786 18.2768 11.4268 18.4627L7.773 20.3574C6.87023 20.8267 5.81439 20.0698 5.98724 19.0778L6.68385 15.0676C6.75257 14.6733 6.62033 14.2718 6.32982 13.9924L3.37368 11.1519C2.64272 10.4505 3.04464 9.22644 4.05466 9.08062L8.14265 8.49218C8.54354 8.43467 8.89028 8.18616 9.06937 7.82776L10.8957 4.17701C11.3477 3.27433 12.6523 3.27433 13.1043 4.17701Z" stroke="' +
            favoritetxt +
            '" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg></label>' +
            "</div>" +
            "</div>" +
            '<a href="javascript:void(0);" onclick="view_email(' +
            mail.id +
            "," +
            "'" +
            val +
            "'" +
            ')" class="col-mail col-mail-2">' +
            '<div class="hader">' +
            mail.subject +
            "</div>" +
            '<div class="subject">' +
            stripTags(mail.message) +
            "</div>" +
            '<div class="date">' +
            customDate +
            "</div>" +
            "</a>" +
            '<div class="icon">' +
            '<a href="javascript:void(0);" onclick="edit_email(' +
            mail.id +
            ", " +
            "'label'" +
            ", " +
            "'favorite'" +
            ')" class="align-middle" title="Favorite">' +
            '<svg width="20" height="20" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M13.1043 4.17701L14.9317 7.82776C15.1108 8.18616 15.4565 8.43467 15.8573 8.49218L19.9453 9.08062C20.9554 9.22644 21.3573 10.4505 20.6263 11.1519L17.6702 13.9924C17.3797 14.2718 17.2474 14.6733 17.3162 15.0676L18.0138 19.0778C18.1856 20.0698 17.1298 20.8267 16.227 20.3574L12.5732 18.4627C12.215 18.2768 11.786 18.2768 11.4268 18.4627L7.773 20.3574C6.87023 20.8267 5.81439 20.0698 5.98724 19.0778L6.68385 15.0676C6.75257 14.6733 6.62033 14.2718 6.32982 13.9924L3.37368 11.1519C2.64272 10.4505 3.04464 9.22644 4.05466 9.08062L8.14265 8.49218C8.54354 8.43467 8.89028 8.18616 9.06937 7.82776L10.8957 4.17701C11.3477 3.27433 12.6523 3.27433 13.1043 4.17701Z" stroke="' +
            favoritetxt +
            '" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg>' +
            "</a>" +
            '<a href="javascript:void(0);" onclick="edit_email(' +
            mail.id +
            ", " +
            "'label'" +
            ", " +
            "'archive'" +
            ')" class="ms-2 align-middle" title="Archive">' +
            '<svg width="20" height="20" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9.16683 12.8333H12.8335C13.0766 12.8333 13.3098 12.7368 13.4817 12.5648C13.6536 12.3929 13.7502 12.1598 13.7502 11.9167C13.7502 11.6736 13.6536 11.4404 13.4817 11.2685C13.3098 11.0966 13.0766 11 12.8335 11H9.16683C8.92371 11 8.69056 11.0966 8.51865 11.2685C8.34674 11.4404 8.25016 11.6736 8.25016 11.9167C8.25016 12.1598 8.34674 12.3929 8.51865 12.5648C8.69056 12.7368 8.92371 12.8333 9.16683 12.8333V12.8333ZM17.4168 2.75H4.5835C3.85415 2.75 3.15468 3.03973 2.63895 3.55546C2.12323 4.07118 1.8335 4.77065 1.8335 5.5V8.25C1.8335 8.49312 1.93007 8.72627 2.10198 8.89818C2.27389 9.07009 2.50705 9.16667 2.75016 9.16667H3.66683V16.5C3.66683 17.2293 3.95656 17.9288 4.47229 18.4445C4.98801 18.9603 5.68748 19.25 6.41683 19.25H15.5835C16.3128 19.25 17.0123 18.9603 17.528 18.4445C18.0438 17.9288 18.3335 17.2293 18.3335 16.5V9.16667H19.2502C19.4933 9.16667 19.7264 9.07009 19.8983 8.89818C20.0703 8.72627 20.1668 8.49312 20.1668 8.25V5.5C20.1668 4.77065 19.8771 4.07118 19.3614 3.55546C18.8456 3.03973 18.1462 2.75 17.4168 2.75ZM16.5002 16.5C16.5002 16.7431 16.4036 16.9763 16.2317 17.1482C16.0598 17.3201 15.8266 17.4167 15.5835 17.4167H6.41683C6.17371 17.4167 5.94056 17.3201 5.76865 17.1482C5.59674 16.9763 5.50016 16.7431 5.50016 16.5V9.16667H16.5002V16.5ZM18.3335 7.33333H3.66683V5.5C3.66683 5.25688 3.76341 5.02373 3.93531 4.85182C4.10722 4.67991 4.34038 4.58333 4.5835 4.58333H17.4168C17.6599 4.58333 17.8931 4.67991 18.065 4.85182C18.2369 5.02373 18.3335 5.25688 18.3335 5.5V7.33333Z" fill="#666666" /></svg>' +
            "</a>" +
            '<a href="javascript:void(0);" onclick="edit_email(' +
            mail.id +
            ", " +
            "'label'" +
            ", " +
            "'spam'" +
            ')" class="ms-2 align-middle" title="Spam">' +
            '<svg width="20" height="20" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2.75012C17.108 2.75012 21.25 6.89112 21.25 12.0001C21.25 17.1081 17.108 21.2501 12 21.2501C6.891 21.2501 2.75 17.1081 2.75 12.0001C2.75 6.89112 6.891 2.75012 12 2.75012Z" stroke="#666666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /><path d="M11.9951 8.20422V12.6232" stroke="#666666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /><path d="M11.995 15.7961H12.005" stroke="#666666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>' +
            "</a>" +
            '<a href="javascript:void(0);" onclick="delete_email(' +
            mail.id +
            ", '" +
            val +
            "'" +
            ')" class="ms-2 align-middle" title="Trash">' +
            '<svg width="20" height="20" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9.16667 16.5C9.40978 16.5 9.64294 16.4035 9.81485 16.2316C9.98676 16.0596 10.0833 15.8265 10.0833 15.5834V10.0834C10.0833 9.84026 9.98676 9.6071 9.81485 9.43519C9.64294 9.26328 9.40978 9.16671 9.16667 9.16671C8.92355 9.16671 8.69039 9.26328 8.51849 9.43519C8.34658 9.6071 8.25 9.84026 8.25 10.0834V15.5834C8.25 15.8265 8.34658 16.0596 8.51849 16.2316C8.69039 16.4035 8.92355 16.5 9.16667 16.5ZM18.3333 5.50004H14.6667V4.58337C14.6667 3.85403 14.3769 3.15456 13.8612 2.63883C13.3455 2.12311 12.646 1.83337 11.9167 1.83337H10.0833C9.35399 1.83337 8.65451 2.12311 8.13879 2.63883C7.62306 3.15456 7.33333 3.85403 7.33333 4.58337V5.50004H3.66667C3.42355 5.50004 3.19039 5.59662 3.01849 5.76853C2.84658 5.94043 2.75 6.17359 2.75 6.41671C2.75 6.65982 2.84658 6.89298 3.01849 7.06489C3.19039 7.2368 3.42355 7.33337 3.66667 7.33337H4.58333V17.4167C4.58333 18.1461 4.87306 18.8455 5.38879 19.3613C5.90451 19.877 6.60399 20.1667 7.33333 20.1667H14.6667C15.396 20.1667 16.0955 19.877 16.6112 19.3613C17.1269 18.8455 17.4167 18.1461 17.4167 17.4167V7.33337H18.3333C18.5764 7.33337 18.8096 7.2368 18.9815 7.06489C19.1534 6.89298 19.25 6.65982 19.25 6.41671C19.25 6.17359 19.1534 5.94043 18.9815 5.76853C18.8096 5.59662 18.5764 5.50004 18.3333 5.50004ZM9.16667 4.58337C9.16667 4.34026 9.26324 4.1071 9.43515 3.93519C9.60706 3.76328 9.84022 3.66671 10.0833 3.66671H11.9167C12.1598 3.66671 12.3929 3.76328 12.5648 3.93519C12.7368 4.1071 12.8333 4.34026 12.8333 4.58337V5.50004H9.16667V4.58337ZM15.5833 17.4167C15.5833 17.6598 15.4868 17.893 15.3148 18.0649C15.1429 18.2368 14.9098 18.3334 14.6667 18.3334H7.33333C7.09022 18.3334 6.85706 18.2368 6.68515 18.0649C6.51324 17.893 6.41667 17.6598 6.41667 17.4167V7.33337H15.5833V17.4167ZM12.8333 16.5C13.0764 16.5 13.3096 16.4035 13.4815 16.2316C13.6534 16.0596 13.75 15.8265 13.75 15.5834V10.0834C13.75 9.84026 13.6534 9.6071 13.4815 9.43519C13.3096 9.26328 13.0764 9.16671 12.8333 9.16671C12.5902 9.16671 12.3571 9.26328 12.1852 9.43519C12.0132 9.6071 11.9167 9.84026 11.9167 10.0834V15.5834C11.9167 15.8265 12.0132 16.0596 12.1852 16.2316C12.3571 16.4035 12.5902 16.5 12.8333 16.5Z" fill="#666666" /></svg>' +
            "</a>" +
            "</div>" +
            "</div>" +
            '</div>"';
        });
      }
      // no email found
      else {
        mailItem +=
          '<div class="message">' +
          "<div>" +
          '<div class="d-flex message-single">' +
          '<div class="ps-1 align-self-center">' +
          '<div class="form-check custom-checkbox">' +
          '<input type="checkbox" class="form-check-input" id="" disabled>' +
          '<label class="form-check-label" for=""></label>' +
          "</div>" +
          "</div>" +
          '<div class="ms-2">' +
          '<label class="bookmark-btn"><input type="checkbox"><span class="checkmark"></span></label>' +
          "</div>" +
          "</div>" +
          '<a href="javascript:void(0);" class="col-mail col-mail-2">' +
          '<div class="hader">You\'ve finished</div>' +
          '<div class="subject">' +
          (key == "label"
            ? capitalizeFirstLetter(val)
            : capitalizeFirstLetter(tag_name)) +
          " is Empty</div>";
        '<div class="date"></div>' +
          "</a>" +
          '<div class="icon">' +
          "</div>" +
          "</div>" +
          "</div>";
      }
      emailSection.empty(); // clear everything
      emailSection.html(mailItem); // attend new content
    },
    error: function (jqXHR, textStatus, errorThrown) {
      emailSection.html(
        '<div class="message">' +
          "<div>" +
          '<div class="d-flex message-single">' +
          '<div class="ps-1 align-self-center">' +
          '<div class="form-check custom-checkbox">' +
          '<input type="checkbox" class="form-check-input" id="checkbox213">' +
          '<label class="form-check-label" for="checkbox2"></label>' +
          "</div>" +
          "</div>" +
          '<div class="ms-2">' +
          '<label class="bookmark-btn"><input type="checkbox"><span class="checkmark"></span></label>' +
          "</div>" +
          "</div>" +
          '<a href="javascript:void(0);" class="col-mail col-mail-2">' +
          '<div class="hader">' +
          capitalizeFirstLetter(textStatus) +
          "</div>" +
          '<div class="subject">' +
          errorThrown +
          "</div>" +
          '<div class="date"></div>' +
          "</a>" +
          '<div class="icon">' +
          "</div>" +
          "</div>" +
          "</div>"
      );
    },
  });
}

// ajax display selected email
function view_email(id, label) {
  var viewMailContent = $("div#viewMailContent");
  $("div#mailsContent").hide();
  viewMailContent.show();
  active_mailLink(label); // higlight active label

  $.ajax({
    url: "/admin/communications/emails/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      if (!data.error) {
        // mark email as read
        if (data.status == "unread") {
          edit_email(id, "status", "read");
        }
        var mailContent = attachmentsection = "";
        // add attachments section
        if (data.attachments) {
          if (data.attachments && data.attachments.length > 0) {
            attachmentsection =
              '<h5 class="mb-0">' +
              '<span class="fs-14 fw-semibold"><i class="fa fa-paperclip me-2"></i>Attachments (' +
              data.attachments.length +
              "):</span>" +
              "</h5>" +
              '<div class="row">';
            // loop through attachments
            $.each(data.attachments, function (index, attachment) {
              attachmentsection +=
                '<div class="col-xxl-2 col-xl-3 col-md-4 col-sm-6">' +
                '<div class="card">' +
                '<div class="card-body px-4 py-3 d-flex align-items-center gap-3">' +
                '<div><a href="/admin/attachments/download/' +
                attachment.id +
                '" class="mail-attachment ms-2 col-3 mt-1">' +
                '<i class="fa fa-paperclip me-2"></i>' +
                "</a></div>" +
                '<div class="clearfix">' +
                '<h6 class="mb-0">' +
                attachment.attachment +
                "</h6>";
              '<span class="fs-14">' +
                formatBytes(attachment.size) +
                "</span>" +
                "</div>" +
                "</div>" +
                "</div>" +
                "</div>";
            });
            attachmentsection += "</div>";
          }
        }
        // email sent date
        var customDate = mailing_date(data.created_at);
        // recipient photo
        var recipientPhoto = mail_photo(data.photo, data.recipient_account);
        // colorize email icons
        var favoritetxt = archivetxt = spamtxt = trashtxt = "#666666";
        switch (data.label) {
          case "favorite":
            var favoritetxt = "#FF9F00";
            break;
          case "archive":
            var archivetxt = "#FF5E5E";
            break;
          case "spam":
            var spamtxt = "#FF5E5E";
            break;
          case "trash":
            var trashtxt = "#FF5E5E";
            break;
          default:
            var favoritetxt = "#666666";
            var archivetxt = "#666666";
            var spamtxt = "#666666";
            var trashtxt = "#666666";
            break;
        }
        
        // show reply buttons
        var replyBtn = replyBtn2 = '';
        if (label == "inbox") {
          replyBtn = '<a href="javascript:void(0);" class="btn btn-primary px-3 my-1 light me-2" onclick="compose_email(' +
            data.id +
            ", " +
            "'reply'" +
            "," +
            data.tag_id +
            "," +
            "'" +
            data.subject +
            "'" +
            ')" title="Reply"><i class="fa fa-reply"></i></a>';
          replyBtn2 = '<button class="btn btn-secondary btn-sm" onclick="compose_email(' +
            data.id +
            ", " +
            "'reply'" +
            "," +
            data.tag_id +
            "," +
            "'" +
            data.subject +
            "'" +
            ')" title="Reply"><i class="fa-solid fa-reply me-1"></i>Reply</button>';
        }

        // content
        mailContent +=
          '<div role="toolbar" class="toolbar ms-1 ms-sm-0">' +
          '<div class="saprat ps-sm-3 ps-0">' +
          '<div class="mail-tools ms-0">' +
          '<a href="javascrip:void(0);" onclick="edit_email(' +
          data.id +
          ", " +
          "'label'" +
          ", " +
          "'favorite'" +
          ')" class="ms-2 align-middle" title="Favorite"><svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M13.1043 4.17701L14.9317 7.82776C15.1108 8.18616 15.4565 8.43467 15.8573 8.49218L19.9453 9.08062C20.9554 9.22644 21.3573 10.4505 20.6263 11.1519L17.6702 13.9924C17.3797 14.2718 17.2474 14.6733 17.3162 15.0676L18.0138 19.0778C18.1856 20.0698 17.1298 20.8267 16.227 20.3574L12.5732 18.4627C12.215 18.2768 11.786 18.2768 11.4268 18.4627L7.773 20.3574C6.87023 20.8267 5.81439 20.0698 5.98724 19.0778L6.68385 15.0676C6.75257 14.6733 6.62033 14.2718 6.32982 13.9924L3.37368 11.1519C2.64272 10.4505 3.04464 9.22644 4.05466 9.08062L8.14265 8.49218C8.54354 8.43467 8.89028 8.18616 9.06937 7.82776L10.8957 4.17701C11.3477 3.27433 12.6523 3.27433 13.1043 4.17701Z" stroke="' +
          favoritetxt +
          '" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg></a>' +
          '<a href="javascrip:void(0);" onclick="edit_email(' +
          data.id +
          ", " +
          "'label'" +
          ", " +
          "'archive'" +
          ')" class="ms-2 align-middle" title="Archive"><svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9.16683 12.8333H12.8335C13.0766 12.8333 13.3098 12.7368 13.4817 12.5648C13.6536 12.3929 13.7502 12.1598 13.7502 11.9167C13.7502 11.6736 13.6536 11.4404 13.4817 11.2685C13.3098 11.0966 13.0766 11 12.8335 11H9.16683C8.92371 11 8.69056 11.0966 8.51865 11.2685C8.34674 11.4404 8.25016 11.6736 8.25016 11.9167C8.25016 12.1598 8.34674 12.3929 8.51865 12.5648C8.69056 12.7368 8.92371 12.8333 9.16683 12.8333V12.8333ZM17.4168 2.75H4.5835C3.85415 2.75 3.15468 3.03973 2.63895 3.55546C2.12323 4.07118 1.8335 4.77065 1.8335 5.5V8.25C1.8335 8.49312 1.93007 8.72627 2.10198 8.89818C2.27389 9.07009 2.50705 9.16667 2.75016 9.16667H3.66683V16.5C3.66683 17.2293 3.95656 17.9288 4.47229 18.4445C4.98801 18.9603 5.68748 19.25 6.41683 19.25H15.5835C16.3128 19.25 17.0123 18.9603 17.528 18.4445C18.0438 17.9288 18.3335 17.2293 18.3335 16.5V9.16667H19.2502C19.4933 9.16667 19.7264 9.07009 19.8983 8.89818C20.0703 8.72627 20.1668 8.49312 20.1668 8.25V5.5C20.1668 4.77065 19.8771 4.07118 19.3614 3.55546C18.8456 3.03973 18.1462 2.75 17.4168 2.75ZM16.5002 16.5C16.5002 16.7431 16.4036 16.9763 16.2317 17.1482C16.0598 17.3201 15.8266 17.4167 15.5835 17.4167H6.41683C6.17371 17.4167 5.94056 17.3201 5.76865 17.1482C5.59674 16.9763 5.50016 16.7431 5.50016 16.5V9.16667H16.5002V16.5ZM18.3335 7.33333H3.66683V5.5C3.66683 5.25688 3.76341 5.02373 3.93531 4.85182C4.10722 4.67991 4.34038 4.58333 4.5835 4.58333H17.4168C17.6599 4.58333 17.8931 4.67991 18.065 4.85182C18.2369 5.02373 18.3335 5.25688 18.3335 5.5V7.33333Z" fill="' +
          archivetxt +
          '" /></svg></a>' +
          '<a href="javascrip:void(0);" onclick="edit_email(' +
          data.id +
          ", " +
          "'label'" +
          ", " +
          "'spam'" +
          ')" class="ms-2 align-middle" title="Spam"><svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2.75012C17.108 2.75012 21.25 6.89112 21.25 12.0001C21.25 17.1081 17.108 21.2501 12 21.2501C6.891 21.2501 2.75 17.1081 2.75 12.0001C2.75 6.89112 6.891 2.75012 12 2.75012Z" stroke="' +
          spamtxt +
          '" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /><path d="M11.9951 8.20422V12.6232" stroke="' +
          spamtxt +
          '" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /><path d="M11.995 15.7961H12.005" stroke="' +
          spamtxt +
          '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>' +
          "</a>" +
          '<a href="javascrip:void(0);" onclick="delete_email(' +
          data.id +
          ", '" +
          label +
          "'" +
          ')" class="ms-2 align-middle" title="Trash"><svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M19.3248 9.4682C19.3248 9.4682 18.7818 16.2032 18.4668 19.0402C18.3168 20.3952 17.4798 21.1892 16.1088 21.2142C13.4998 21.2612 10.8878 21.2642 8.27979 21.2092C6.96079 21.1822 6.13779 20.3782 5.99079 19.0472C5.67379 16.1852 5.13379 9.4682 5.13379 9.4682" stroke="' +
          trashtxt +
          '" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /><path d="M20.708 6.23969H3.75" stroke="' +
          trashtxt +
          '" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /><path d="M17.4406 6.23967C16.6556 6.23967 15.9796 5.68467 15.8256 4.91567L15.5826 3.69967C15.4326 3.13867 14.9246 2.75067 14.3456 2.75067H10.1126C9.53358 2.75067 9.02558 3.13867 8.87558 3.69967L8.63258 4.91567C8.47858 5.68467 7.80258 6.23967 7.01758 6.23967" stroke="' +
          trashtxt +
          '" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg></a>' +
          "</div>" +
          "</div>" +
          "</div>" +
          '<div class="row">' +
          '<div class="col-12">' +
          '<div class="right-box-padding p-0">' +
          '<div class="read-wapper dz-scroll" id="read-content">' +
          '<div class="read-content">' +
          '<div class="media pt-3 d-sm-flex d-block justify-content-between">' +
          '<div class="clearfix mb-3 d-flex">' +
          recipientPhoto +
          '<div class="media-body me-2">' +
          '<h5 class="text-primary mb-0 mt-1">' +
          data.name +
          "</h5>" +
          '<p class="mb-0">' +
          extractDate(customDate) +
          "</p>" +
          "</div>" +
          "</div>" +
          '<div class="clearfix mb-3">' +
          replyBtn +
          '<a href="javascript:void(0);" class="btn btn-primary px-3 my-1 light me-2" onclick="delete_email(' +
          data.id +
          ", '" +
          label +
          "'" +
          ')"><i class="fa fa-trash"></i></a>' +
          "</div>" +
          "</div>" +
          "<hr>" +
          '<div class="media mb-2 mt-3">' +
          '<div class="media-body"><span class="pull-end"><svg width="20" height="20" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M21.25 12.0005C21.25 17.1095 17.109 21.2505 12 21.2505C6.891 21.2505 2.75 17.1095 2.75 12.0005C2.75 6.89149 6.891 2.75049 12 2.75049C17.109 2.75049 21.25 6.89149 21.25 12.0005Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M15.4316 14.9429L11.6616 12.6939V7.84686" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg> ' +
          extractTime(customDate) +
          "</span>" +
          '<h5 class="my-1 text-primary">' +
          data.subject +
          "</h5>" +
          '<p class="read-content-email">To: ' +
          data.email +
          "</p>" +
          "</div>" +
          "</div>" +
          '<div class="read-content-body">' +
          data.message +
          "<hr>" +
          "</div>" +
          '<div class="read-content-attachment">' +
          attachmentsection +
          "</div>" +
          "<hr>" +
          '<div class="mb-3 pt-3">' +
          replyBtn2 +
          "</div>" +
          "</div>" +
          "</div>" +
          "</div>" +
          "</div>" +
          "</div>";
      } else {
        $(".msg").addClass("text-danger").text(data.messages);
        hideNotifyMessage();
      }
      // show email content
      viewMailContent.html(mailContent);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      $(".msg").addClass("text-danger").text(errorThrown);
      hideNotifyMessage();
    },
  });
}

// save\send composed email
function send_email() {
  eID = $('[name="id"]').val();
  $(".form-group").removeClass("has-error"); // clear error class
  $(".help-block").empty(); // clear error string
  $("#btnSend").text("sending..."); //change button text
  $("#btnSend").attr("disabled", true); //set button disable
  var url;
  if (save_method == "add") {
    url = "/admin/communications/emails";
  } else {
    // url = "/admin/communications/update-status/" + eID;
  }
  var formData = new FormData($("#compose-form")[0]);
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
          fetch_mails();
          count_mails();
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
      $("#btnSend").text("Send"); //change button text
      $("#btnSend").attr("disabled", false); //set button enable
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
      $("#btnSend").text("Send");
      $("#btnSend").attr("disabled", false);
    },
  });
}

// edit email label, read status
function edit_email(id, item, data) {
  $.ajax({
    type: "POST",
    data: {
      id: id,
      key: item,
      value: data,
    },
    url: "/admin/mails/update-mail/" + id,
    dataType: "JSON",
    success: function (data) {
      if (data.status && data.error == null) {
        if (item == "label") {
          view_email(id, data);
        }
        $(".msg").addClass("text-success").text(data.messages);
        hideNotifyMessage();
        count_mails();
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

// delete email
function delete_email(id, label) {
  $.ajax({
    url: "/admin/communications/emails/" + id,
    type: "DELETE",
    dataType: "JSON",
    success: function (data) {
      if (data.status && data.error == null) {
        $(".msg").addClass("text-danger").text(data.messages);
        fetch_mails("label", label);
        hideNotifyMessage();
        count_mails();
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

// bulk delete email
function bulky_mailUpdate(action, value) {
  var list_id = [];
  var label = $("#email-label").text();
  $(".email-check:checked").each(function () {
    list_id.push(this.value);
  });
  if (list_id.length > 0) {
    $.ajax({
      type: "POST",
      data: {
        id: list_id,
        action: action,
        value: value,
      },
      url: "/admin/mails/bulk-mailUpdate",
      dataType: "JSON",
      success: function (response) {
        if (response.status && response.error == null) {
          // Swal.fire("Success!", response.messages, "success");
          $(".msg").addClass("text-danger").text(data.messages);
          fetch_mails("label", value);
          hideNotifyMessage();
          count_mails();
          $(".email-check").attr("checked", false);
        } else if (response.error != null) {
          Swal.fire(response.error, response.messages, "error");
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
  } else {
    Swal.fire("Sorry!", "No emails selected :)", "error");
  }
}

// highlight active label\message
function active_mailLink(label) {
  var labelList = $(".list-group-item");
  if (labelList.length > 0) {
    $.each(labelList, function (index, item) {
      var labelClasses = item.classList;
      if (labelClasses.contains("mailLabel-" + label)) {
        labelClasses.add("active");
      } else {
        if (labelClasses.contains("active")) {
          labelClasses.remove("active");
        }
      }
    });
  }
}

// return recipient photo
function mailing_date(date) {
  // email sent date
  var dateSent = new Date(date);
  var daySent = dateSent.getDate();
  var monthSent = dateSent.toLocaleString("default", { month: "short" });
  var yearSent = dateSent.getFullYear();
  var hourSent = dateSent.getHours();
  var minsSent = dateSent.getMinutes().toString().padStart(2, "0"); // Ensure two digits for minutes

  var formattedDate = `${daySent} ${monthSent}, ${yearSent} ${hourSent}:${minsSent}`;

  return formattedDate;
}
//  extract only the time from the formatted date string
function extractTime(formattedDate) {
  // Split the date string into date and time parts
  var timePart = formattedDate.split(" ")[3]; // Get the time part (hh:mm)

  return timePart;
}
// extract only the date from the formatted date string
function extractDate(formattedDate) {
  // Split the date string into date and time parts
  var datePart = formattedDate.split(" ").slice(0, 3).join(" "); // Get the date part (dd mm, yyyy)

  return datePart;
}

// return recipient photo
function mail_photo(photo, account) {
  // recipient photo
  if (photo) {
    // client
    if (account == "Client") {
      if (imageExists("/uploads/clients/passports/" + photo)) {
        var photo =
          '<img class="me-3 rounded" width="70" height="70" src="/uploads/clients/passports/' +
          photo +
          '" alt="Recipient Photo">';
      }
    }
    // employee
    if (account == "Employee") {
      if (imageExists("/uploads/staffs/clients/passports/" + photo)) {
        var photo =
          '<img class="me-3 rounded" width="70" height="70" src="/uploads/staffs/employees/passports/' +
          photo +
          '" alt="Recipient Photo">';
      }
    }
    // admin
    if (account == "Administrator") {
      if (imageExists("/uploads/staffs/clients/passports/" + photo)) {
        var photo =
          '<img class="me-3 rounded" width="70" height="70" src="/uploads/staffs/admins/passports/' +
          photo +
          '" alt="Recipient Photo">';
      }
      ("");
    }
  } else {
    var photo =
      '<img class="me-3 rounded" width="70" height="70" src="/assets/dist/img/nophoto.jpg" alt="No Image">';
  }

  return photo;
}

// convert bytes to other units
function formatBytes(bytes, precision = 2) {
  const units = ["B", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"];
  const exp = Math.floor(Math.log(bytes) / Math.log(1024));

  return (bytes / Math.pow(1024, exp)).toFixed(precision) + " " + units[exp];
}
// fetch recipient emails on change of account types
function get_recipients(account) {
  var recipientSelect = $("select#recipient_id");
  if (account != 0 || account != "") {
    $.ajax({
      url: "/admin/mails/recipients/" + account,
      type: "GET",
      dataType: "JSON",
      success: function (response) {
        if (response.length > 0) {
          // recipientSelect.empty();
          // Add options
          $.each(response, function (index, data) {
            var option = $("<option>", {
              value: data.id,
              text: data.email,
            });
            recipientSelect.append(option);
          });
        } else {
          recipientSelect.html('<option value="">No Emails</option>');
        }
      },
      error: function (err) {
        recipientSelect.html('<option value="">Error Occured</option>');
      },
    });
  }
}

function hideNotifyMessage() {
  setTimeout(function () {
    $(".msg").removeClass("text-success").text("");
    $(".msg").removeClass("text-danger").text("");
  }, 4500);
}

// Wait for the document to be fully loaded
document.addEventListener("DOMContentLoaded", function () {
  "use strict";

  var myElement11 = document.getElementById("mail-main-nav");
  new SimpleBar(myElement11, { autoHide: true });

  var myElement12 = document.getElementById("mail-messages");
  new SimpleBar(myElement12, { autoHide: true });

  var myElement13 = document.getElementById("mail-info-body");
  if (myElement13) {
    new SimpleBar(myElement13, { autoHide: true });
  }

  var myElement14 = document.getElementById("mail-recepients");
  if (myElement14) {
    new SimpleBar(myElement14, { autoHide: true });
  }

  /* to choices js */
  const multipleCancelButton = new Choices("#recipient_id", {
    allowHTML: true,
    removeItemButton: true,
  });

  let mailContainer = document.querySelectorAll(".mail-messages-container >li");

  let i = true;

  let closeButton = document.querySelectorAll(
    ".responsive-mail-action-icons > button"
  )[0];

  if (closeButton) {
    closeButton.onclick = () => {
      document.querySelector(".total-mails").classList.remove("d-none");
      document.querySelector(".mails-information").style.display = "none";
      i = true;
    };
  }

  window.addEventListener("resize", () => {
    if (window.screen.width > 1399) {
      document.querySelector(".mails-information").style.display = "block";
      document.querySelector(".total-mails").classList.remove("d-none");
    } else {
      if (i) {
        document.querySelector(".mails-information").style.display = "none";
      }
    }

    if (window.screen.width < 1399 && i == false) {
      document.querySelector(".total-mails").classList.add("d-none");
    } else {
      // if(document.querySelector(".mail-navigation").style.display != "block"){
      document.querySelector(".total-mails").classList.remove("d-none");
      // }
    }

    if (window.screen.width > 991) {
      document.querySelector(".mail-navigation").style.display = "block";
      document.querySelector(".total-mails").style.display = "block";
    } else {
      if (
        document.querySelector(".total-mails").style.display == "block" &&
        i == false
      ) {
        document.querySelector(".mail-navigation").style.display = "none";
      }
      if ((document.querySelector(".mail-navigation").style.display = "none")) {
        // document.querySelector(".total-mails").style.display = "none"
      }
    }
  });

  document.querySelectorAll(".mail-type").forEach((element) => {
    element.onclick = () => {
      if (window.screen.width <= 991) {
        document.querySelector(".total-mails").style.display = "block";
        document.querySelector(".total-mails").classList.remove("d-none");
        document.querySelector(".mail-navigation").style.display = "none";
        i = true;
      }
    };
  });

  document.querySelector(".total-mails-close").onclick = () => {
    if (window.screen.width < 992) {
      document.querySelector(".mail-navigation").style.display = "block";
      document.querySelector(".total-mails").classList.add("d-none");
      i = true;
    }
  };
});
