// dataTables buttons config
var buttonsConfig = [];
if (userPermissions.includes("export_" + menu.toLowerCase() + titleSlug) || userPermissions === '"all"') {
  buttonsConfig.push(
    {
      extend: "excel",
      className: "btn btn-sm btn-success",
      titleAttr: "Export " + title + " To Excel",
      text: '<i class="fas fa-file-excel"></i>',
      filename: "Clients " + title + " Information",
      extension: ".xlsx",
    },
    {
      extend: "pdf",
      className: "btn btn-sm btn-danger",
      titleAttr: "Export Clients " + title + " To PDF",
      text: '<i class="fas fa-file-pdf"></i>',
      filename: "Clients " + title + " Information",
      extension: ".pdf",
    },
    {
      extend: "csv",
      className: "btn btn-sm btn-success",
      titleAttr: "Export Clients " + title + " To CSV",
      text: '<i class="fas fa-file-csv"></i>',
      filename: "Clients " + title + " Information",
      extension: ".csv",
    },
    {
      extend: "copy",
      className: "btn btn-sm btn-warning",
      titleAttr: "Copy Clients " + title + " Information",
      text: '<i class="fas fa-copy"></i>',
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

// $(function () {
$(document).ready(function () {
  // get the running branches
  selectBranch();
  // get client years
  clientYears();
  // gender
  generateGender();
  // load account status
  generateStatus();
  // load the staff officer
  generateStaff();
  generateClientAccountNo();

  // JS total clients data objects
  var totalClients = {
    entry_status: "",
    year: "",
    start_date: "",
    end_date: "",
    gender: "",
    account_no: "",
    others: "",
    status: "",
    location: "",
    reference_id: "",
    branch_id: "",
    staff_id: "",
  };

  // JS active clients data objects
  var active = {
    entry_status: "",
    year: "",
    start_date: "",
    end_date: "",
    gender: "",
    account_no: "",
    others: "",
    status: "",
    location: "",
    reference_id: "",
    branch_id: "",
    staff_id: "",
  };

  // JS inactive clients data object
  var inactive = {
    entry_status: "",
    year: "",
    start_date: "",
    end_date: "",
    gender: "",
    account_no: "",
    others: "",
    status: "",
    location: "",
    reference_id: "",
    branch_id: "",
    staff_id: "",
  };

  // auto load the clients table report
  filterClientsAccount();

  // auto load the clients chart
  clientsChartsReport(active, inactive, totalClients);
});

function clientsChartsReport(activeClients, inactiveClients, totalClients) {
  /* ChartJS
   * -------
   * Here we will create a few charts using ChartJS
   */
  var ticksStyle = {
    // fontColor: '#495057',
    fontStyle: "bold",
  };

  //--------------
  //- AREA CHART -
  //--------------

  // Fetch all the keys(variables) from the object
  var totalClientKeys = Object.keys(totalClients);

  // Iterate over each key to update entry status to debit
  totalClientKeys.forEach((totalClientElement) => {
    // check whether the element is entry_status to update its value to debit
    if (totalClientElement == "entry_status") {
      totalClients[totalClientElement] = "clients";
    }
  });

  var totalClientsAccess = totalClients;

  var activeClientKeys = Object.keys(activeClients);

  // Iterate over each key to update entry status to debit
  activeClientKeys.forEach((activeClientElement) => {
    // check whether the element is entry_status to update its value to debit
    if (activeClientElement == "entry_status") {
      activeClients[activeClientElement] = "active";
    }
  });

  var activeAccess = activeClients;

  // Fetch all the keys(variables) from the object
  var inactiveClientKeys = Object.keys(inactiveClients);

  // Iterate over each key to update entry status to debit
  inactiveClientKeys.forEach((inactiveClientElement) => {
    // check whether the element is entry_status to update its value to debit
    if (inactiveClientElement == "entry_status") {
      inactiveClients[inactiveClientElement] = "inactive";
    }
  });

  var inactiveAccess = inactiveClients;

  /* area chart */
  var areaOptions = {
    series: [
      {
        name: "Active Clients",
        data: monthlyTotalClientsNumber(activeAccess),
      },
      {
        name: "Inactive Clients",
        data: monthlyTotalClientsNumber(inactiveAccess),
      },
      {
        name: "Total Clients",
        data: monthlyTotalClientsNumber(totalClientsAccess),
      },
    ],
    chart: {
      height: 320,
      type: "area",
    },
    colors: ["#23b7e5", "#f5b849", "#845adf"],
    dataLabels: {
      enabled: false,
    },
    title: {
      text: "Monthly Clients' Access Status Report - Area Chart",
      align: "left",
      style: {
        fontSize: "13px",
        fontWeight: "bold",
        color: "#8c9097",
      },
    },
    stroke: {
      curve: "smooth",
    },
    grid: {
      borderColor: "#f2f5f7",
    },
    xaxis: {
      // type: 'datetime',
      categories: [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sept",
        "Oct",
        "Nov",
        "Dec",
      ],
      title: {
        text: "Months",
        style: {
          color: "#8c9097",
        },
      },
      labels: {
        show: true,
        style: {
          colors: "#8c9097",
          fontSize: "11px",
          fontWeight: 600,
          cssClass: "apexcharts-xaxis-label",
        },
      },
    },
    yaxis: {
      title: {
        text: "Clients",
        style: {
          color: "#8c9097",
        },
      },
      labels: {
        show: true,
        style: {
          colors: "#8c9097",
          fontSize: "11px",
          fontWeight: 600,
          cssClass: "apexcharts-xaxis-label",
        },
      },
    },
    tooltip: {
      y: {
        formatter: function (val) {
          return val;
        },
      },
    },
    legend: {
      position: "top",
      style: {
        color: "#8c9097",
      },
    },
  };
  $("#area-spline").empty();
  var chart = new ApexCharts(
    document.querySelector("#area-spline"),
    areaOptions
  );
  chart.render();

  /* line chart */
  var lineOptions = {
    series: [
      {
        name: "Active Clients",
        data: monthlyTotalClientsNumber(activeAccess),
      },
      {
        name: "Inactive Clients",
        data: monthlyTotalClientsNumber(inactiveAccess),
      },
      {
        name: "Total Clients",
        data: monthlyTotalClientsNumber(totalClientsAccess),
      },
    ],
    chart: {
      height: 320,
      type: "line",
      zoom: {
        enabled: false,
      },
      animations: {
        enabled: false,
      },
    },
    grid: {
      borderColor: "#f2f5f7",
    },
    stroke: {
      width: [3, 3, 2],
      curve: "straight",
    },
    colors: ["#23b7e5", "#f5b849", "#845adf"],
    labels: [
      "Jan",
      "Feb",
      "Mar",
      "Apr",
      "May",
      "Jun",
      "Jul",
      "Aug",
      "Sept",
      "Oct",
      "Nov",
      "Dec",
    ],
    title: {
      text: "Monthly Clients' Access Status Report - Line Graph",
      align: "left",
      style: {
        fontSize: "13px",
        fontWeight: "bold",
        color: "#8c9097",
      },
    },
    xaxis: {
      title: {
        text: "Months",
        style: {
          color: "#8c9097",
        },
      },
      labels: {
        show: true,
        style: {
          colors: "#8c9097",
          fontSize: "11px",
          fontWeight: 600,
          cssClass: "apexcharts-xaxis-label",
        },
      },
    },
    yaxis: {
      title: {
        text: "Clients",
        style: {
          color: "#8c9097",
        },
      },
      labels: {
        show: true,
        style: {
          colors: "#8c9097",
          fontSize: "11px",
          fontWeight: 600,
          cssClass: "apexcharts-yaxis-label",
        },
      },
    },
    legend: {
      position: "top",
      style: {
        color: "#8c9097",
      },
    },
  };
  $("#line-chart").empty();
  var chart = new ApexCharts(
    document.querySelector("#line-chart"),
    lineOptions
  );
  chart.render();

  /* column chart  */
  var columnOptions = {
    series: [
      {
        name: "Active Clients",
        data: monthlyTotalClientsNumber(activeAccess),
      },
      {
        name: "Inactive Clients",
        data: monthlyTotalClientsNumber(inactiveAccess),
      },
      {
        name: "Total Clients",
        data: monthlyTotalClientsNumber(totalClientsAccess),
      },
    ],
    chart: {
      height: 320,
      type: "bar",
    },
    grid: {
      borderColor: "#f2f5f7",
    },
    plotOptions: {
      bar: {
        borderRadius: 10,
        dataLabels: {
          position: "top", // top, center, bottom
        },
      },
    },
    dataLabels: {
      enabled: true,
      formatter: function (val) {
        return val;
      },
      offsetY: -20,
      style: {
        fontSize: "12px",
        colors: ["#8c9097"],
      },
    },
    colors: ["#23b7e5", "#f5b849", "#845adf"],
    title: {
      text: "Monthly Clients' Access Status Report - Column Chart",
      align: "left",
      style: {
        fontSize: "13px",
        fontWeight: "bold",
        color: "#8c9097",
      },
    },
    xaxis: {
      categories: [
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
      ],
      position: "bottom",
      axisBorder: {
        show: false,
      },
      axisTicks: {
        show: false,
      },
      crosshairs: {
        fill: {
          type: "gradient",
          gradient: {
            colorFrom: "#D8E3F0",
            colorTo: "#BED1E6",
            stops: [0, 100],
            opacityFrom: 0.4,
            opacityTo: 0.5,
          },
        },
      },
      tooltip: {
        enabled: true,
      },
      title: {
        text: "Months",
        style: {
          color: "#8c9097",
        },
      },
      labels: {
        show: true,
        style: {
          colors: "#8c9097",
          fontSize: "11px",
          fontWeight: 600,
          cssClass: "apexcharts-xaxis-label",
        },
      },
    },
    yaxis: {
      axisBorder: {
        show: false,
      },
      axisTicks: {
        show: false,
      },
      title: {
        text: "Clients",
        style: {
          color: "#8c9097",
        },
      },
      labels: {
        // show: false,
        formatter: function (val) {
          return val;
        },
      },
    },
    legend: {
      position: "top",
      style: {
        color: "#8c9097",
      },
    },
  };
  $("#column-chart").empty();
  var chart = new ApexCharts(
    document.querySelector("#column-chart"),
    columnOptions
  );
  chart.render();

  /* radar chart  */
  var radarOptions = {
    series: [
      {
        name: "Active Clients",
        data: monthlyTotalClientsNumber(activeAccess),
      },
      {
        name: "Inactive Clients",
        data: monthlyTotalClientsNumber(inactiveAccess),
      },
      {
        name: "Total Clients",
        data: monthlyTotalClientsNumber(totalClientsAccess),
      },
    ],
    chart: {
      height: 320,
      type: "radar",
      dropShadow: {
        enabled: true,
        blur: 1,
        left: 1,
        top: 1,
      },
    },
    title: {
      text: "Monthly Clients' Access Status Report - Radar Chart",
      align: "left",
      style: {
        fontSize: "13px",
        fontWeight: "bold",
        color: "#8c9097",
      },
    },
    colors: ["#23b7e5", "#f5b849", "#845adf"],
    stroke: {
      width: 2,
    },
    fill: {
      opacity: 0.1,
    },
    markers: {
      size: 0,
    },
    xaxis: {
      categories: [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sept",
        "Oct",
        "Nov",
        "Dec",
      ],
    },
    yaxis: {
      labels: {
        show: true,
        formatter: function (val) {
          return val;
          // return val.toFixed(2);
        },
      },
    },
    markers: {
      size: 3,
      colors: ["#fff"],
      strokeColor: "#32a83c",
      strokeWidth: 2,
    },
    tooltip: {
      y: {
        formatter: function (val) {
          return val;
        },
      },
    },
    legend: {
      position: "top",
      style: {
        color: "#8c9097",
      },
    },
  };
  $("#radar-chart").empty();
  var chart = new ApexCharts(
    document.querySelector("#radar-chart"),
    radarOptions
  );
  chart.render();
}

function clientYears() {
  $.ajax({
    url: "/admin/reports/module/types/clientyears",
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $("select#year").html('<option value="">-- Select --</option>');
      if (data.length > 0) {
        for (var i = 0; i < data.length; i++) {
          $("<option />").val(data[i]).text(data[i]).appendTo($("select#year"));
        }
      } else {
        $("select#year").html('<option value="">No Year</option>');
      }
    },
    error: function (err) {
      $("select#year").html('<option value="">Error Occured</option>');
    },
  });
}

function filterClientsAccount() {
  $("#btnSearch").html('<i class="fa fa-spinner fa-spin"></i> searching...');
  $("#btnSearch").attr("disabled", true);

  var year = $("select#year").val();
  var start_date = $("input#start_date").val();
  var end_date = $("input#end_date").val();
  var gender = $("select#gender").val();
  var account_no = $("select#account_no").val();
  var others = $("input#others").val();
  var status = $("select#status").val();
  var location = $("input#location").val();
  var reference_id = $("input#reference_id").val();
  var branch_id = $("select#branch_id").val();
  var staff_id = $("select#staff_id").val();
  // JS  total clients data objects
  var totalClientsObject = {
    entry_status: "",
    year: year,
    start_date: start_date,
    end_date: end_date,
    gender: gender,
    account_no: account_no,
    others: others,
    status: status,
    location: location,
    reference_id: reference_id,
    branch_id: branch_id,
    staff_id: staff_id,
  };

  // JS  activate clients data objects
  var activeObject = {
    entry_status: "",
    year: year,
    start_date: start_date,
    end_date: end_date,
    gender: gender,
    account_no: account_no,
    others: others,
    status: status,
    location: location,
    reference_id: reference_id,
    branch_id: branch_id,
    staff_id: staff_id,
  };

  // JS  inactive client data objects
  var inactivateObject = {
    entry_status: "",
    year: year,
    start_date: start_date,
    end_date: end_date,
    gender: gender,
    account_no: account_no,
    others: others,
    status: status,
    location: location,
    reference_id: reference_id,
    branch_id: branch_id,
    staff_id: staff_id,
  };

  var url = baseURL + "/admin/reports/module/get-report-type/clients";
  // ajax adding data to database
  $.ajax({
    url: url,
    type: "POST",
    data: $("#form").serialize(),
    dataType: "JSON",
    beforeSend: function () {
      // load spinner where there is clients counter table report
      $("#clients_counter").html(
        '<div class="text-center">' +
          '<i class="fa fa-spinner fa-pulse fa-4x fa-fw"></i></div>'
      );
      // load spinner where there is clients table report
      $("#clients_table").html(
        '<div class="text-center">' +
          '<i class="fa fa-spinner fa-pulse fa-4x fa-fw"></i></div>'
      );
    },
    success: function (results) {
      // call the clients chart report
      clientsChartsReport(activeObject, inactivateObject, totalClientsObject);
      // clients counter
      var dataTableClients =
        '<div class="table-responsive"><table id="clientsCounter" class="table table-sm table-striped table-hover"' +
        'cellspacing="0" width="100%"><tbody></tbody>' +
        "<tfoot>" +
        "<tr>" +
        "<th>Total Clients</th>" +
        "<th>" +
        results.yearlyActiveClientsTotal +
        "</th>" +
        "<th>" +
        results.yearlyInactiveClientsTotal +
        "</th>" +
        "<th>" +
        results.yearlyClientsTotal +
        "</th>" +
        "</tr>" +
        "</tfoot></table></div>";
      $("#clients_counter").html(dataTableClients);
      var table = $("#clientsCounter").DataTable({
        data: results.clientsCounter,
        bPaginate: true,
        bLengthChange: true,
        language: {
          paginate: {
            next: '<i class="fa-solid fa-angle-right"></i>',
            previous: '<i class="fa-solid fa-angle-left"></i>',
          },
        },
        pageLength: 25,
        lengthMenu: [
          [10, 25, 50, 100],
          [10, 25, 50, 100],
        ],
        bFilter: true,
        bInfo: true,
        bAutoWidth: true,
        columns: [
          { title: "Year - Month" },
          { title: "Number of Active Clients" },
          { title: "Number of Inactive Clients" },
          { title: "Total Clients" },
        ],
        // dom: "lBfrtip",
        dom:
          "<'row'<'col-md-2'l><'col-md-6'B><'col-md-4'f>>" +
          "<'row'<'col-md-12'tr>>" +
          "<'row'<'col-md-5'i><'col-md-7'p>>",
        buttons: buttonsConfig,
      });

      // clients table information
      var clientsDataTable =
        '<div class="table-responsive"><table id="clientsDataTable" class="table table-sm table-striped table-hover"' +
        'cellspacing="0" width="100%"><tbody></tbody>' +
        "</table></div>";

      $("#clients_table").html(clientsDataTable);

      $("#clientsDataTable").DataTable({
        data: results.clients,
        bPaginate: true,
        bLengthChange: true,
        language: {
          paginate: {
            next: '<i class="fa-solid fa-angle-right"></i>',
            previous: '<i class="fa-solid fa-angle-left"></i>',
          },
        },
        pageLength: 25,
        lengthMenu: [
          [10, 25, 50, 100, 250, 500, -1],
          [10, 25, 50, 100, 250, 500, "All"],
        ],
        bFilter: true,
        bInfo: true,
        bAutoWidth: true,
        columns: [
          { title: "S.No", width: "4%" },
          { title: "Join Date" },
          { title: "Client" },
          { title: "Phone" },
          { title: "Account No" },
          { title: "Account.BAL [" + currency + "]" },
          { title: "Status" },
          { title: "Action" },
        ],
        //dom: "lBfrtip",
        dom:
          "<'row'<'col-md-2'l><'col-md-6'B><'col-md-4'f>>" +
          "<'row'<'col-md-12'tr>>" +
          "<'row'<'col-md-5'i><'col-md-7'p>>",
        buttons: buttonsConfig,

        // set column definition initialisation properties.
        columnDefs: [
          {
            targets: [0], //first column
            orderable: false, //set not orderable
          },
          {
            targets: [7], //last column
            orderable: false,
          },
        ],
      });

      $("#btnSearch").html('<i class="fa fa-search fa-fw"></i>');
      $("#btnSearch").attr("disabled", false);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      $("#btnSearch").html('<i class="fa fa-search fa-fw"></i>');
      $("#btnSearch").attr("disabled", false);
    },
  });
}

function monthlyTotalClientsNumber(data) {
  const total = [];
  $.ajax({
    url: "/admin/reports/module/get-report-type/clients",
    async: false,
    type: "POST",
    dataType: "JSON",
    data: {
      entry_status: data.entry_status,
      year: data.year,
      start_date: data.start_date,
      end_date: data.end_date,
      gender: data.gender,
      account_no: data.account_no,
      others: data.others,
      status: data.status,
      location: data.location,
      reference_id: data.reference_id,
      branch_id: data.branch_id,
      staff_id: data.staff_id,
    },
    success: function (data) {
      if (data.monthlyClientsTotal.length > 0) {
        data.monthlyClientsTotal.forEach(function (value, index) {
          total.push(value);
        });
        // for (let index in data.monthlyClientsTotal) {
        //   const element = data.monthlyClientsTotal[index];
        //   total.push(element)

        // }
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });

  return total;
}

function viewClient(id) {
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

function printClientInfo() {
  var client_id = $('[name="id"]').val();
  window.location.assign("/admin/clients/form/" + client_id);
}

// https://www.cyberithub.com/how-to-update-key-with-new-value-javascript-3-methods/
