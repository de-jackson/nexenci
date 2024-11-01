// dataTables buttons config
var buttonsConfig = [];
if (userPermissions.includes("export_" + menu.toLowerCase() + titleSlug) || userPermissions === '"all"') {
  buttonsConfig.push(
    {
      extend: "excel",
      className: "btn btn-sm btn-success",
      titleAttr: "Export " + title + " To Excel",
      text: '<i class="fas fa-file-excel"></i>',
      filename: "Staff " + title + " Information",
      extension: ".xlsx",
    },
    {
      extend: "pdf",
      className: "btn btn-sm btn-danger",
      titleAttr: "Export Staff " + title + " To PDF",
      text: '<i class="fas fa-file-pdf"></i>',
      filename: "Staff " + title + " Information",
      extension: ".pdf",
    },
    {
      extend: "csv",
      className: "btn btn-sm btn-success",
      titleAttr: "Export Staff " + title + " To CSV",
      text: '<i class="fas fa-file-csv"></i>',
      filename: "Staff " + title + " Information",
      extension: ".csv",
    },
    {
      extend: "copy",
      className: "btn btn-sm btn-warning",
      titleAttr: "Copy Staff " + title + " Information",
      text: '<i class="fas fa-copy"></i>',
    },
    {
      extend: "print",
      title:
        "<h3 class='text-center text-bold'>" +
        businessName +
        "</h3><h4 class='text-center text-bold'>Staff " +
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
      titleAttr: "Print Staff " + title + " Information",
      text: '<i class="fa fa-print"></i>',
      filename: "Staff " + title + " Information",
    }
  );
}

$(function () {
  // get the running branches
  selectBranch();
  // get staff years of registration
  yearsInStaffsTable();
  // gender
  generateGender();
  // load account status
  generateStatus();
  // load the department
  selectDepartment();
  // load appointment type
  generateAppointmentType();
  // load staff account type
  generateStaffAccountTypes();
  // load the staff officer
  generateStaff();
  // JS active staff data objects
  var active = {
    entry_status: "",
    year: "",
    start_date: "",
    end_date: "",
    gender: "",
    account_no: "",
    others: "",
    status: "",
    qualification: "",
    reference_id: "",
    branch_id: "",
    staff_id: "",
    department_id: "",
    position_id: "",
    appointment_type: "",
    staff_account_type: "",
  };

  // JS inactive staff data object
  var inactive = {
    entry_status: "",
    year: "",
    start_date: "",
    end_date: "",
    gender: "",
    account_no: "",
    others: "",
    status: "",
    qualification: "",
    reference_id: "",
    branch_id: "",
    staff_id: "",
    department_id: "",
    position_id: "",
    appointment_type: "",
    staff_account_type: "",
  };

  // JS total staff data objects
  var totalStaff = {
    entry_status: "",
    year: "",
    start_date: "",
    end_date: "",
    gender: "",
    account_no: "",
    others: "",
    status: "",
    qualification: "",
    reference_id: "",
    branch_id: "",
    staff_id: "",
    department_id: "",
    position_id: "",
    appointment_type: "",
    staff_account_type: "",
  };

  // auto load the staff table report
  filterStaffsAccount();

  // auto load the staff chart
  staffChartsReport(active, inactive, totalStaff);
});

function staffChartsReport(activeAccounts, inactiveAccounts, totalStaff) {
  /* ChartJS
   * -------
   * Here we will create a few charts using ChartJS
   */
  var ticksStyle = {
    // fontColor: '#495057',
    fontStyle: "bold",
  };

  // Fetch all the keys(variables) from the object
  var activeAccountKeys = Object.keys(activeAccounts);

  //console.log(activeAccountKeys) // [entry_status, year, start_date etc]
  // Iterate over each key to update entry status to debit
  activeAccountKeys.forEach((activeAccountElement) => {
    // check whether the element is entry_status to update its value to debit
    if (activeAccountElement == "entry_status") {
      activeAccounts[activeAccountElement] = "active";
    }
  });

  var activeAccess = activeAccounts;

  // Fetch all the keys(variables) from the object
  var inactiveAccountKeys = Object.keys(inactiveAccounts);

  //console.log(inactiveAccountKeys) // [entry_status, year, start_date etc]

  // Iterate over each key to update entry status to debit
  inactiveAccountKeys.forEach((inactiveAccountElement) => {
    // check whether the element is entry_status to update its value to debit
    if (inactiveAccountElement == "entry_status") {
      inactiveAccounts[inactiveAccountElement] = "inactive";
    }
  });

  var inactiveAccess = inactiveAccounts;

  // Fetch all the keys(variables) from the object
  var totalStaffKeys = Object.keys(totalStaff);

  //console.log(totalStaffKeys) // [entry_status, year, start_date etc]
  // Iterate over each key to update entry status to debit
  totalStaffKeys.forEach((totalStaffElement) => {
    // check whether the element is entry_status to update its value to debit
    if (totalStaffElement == "entry_status") {
      totalStaff[totalStaffElement] = "staff";
    }
  });

  var totalStaffNumber = totalStaff;

  /* area chart */
  var areaOptions = {
    series: [
      {
        name: "Active Staff",
        data: monthlyTotalStaffNumber(activeAccess),
      },
      {
        name: "Inactive Staff",
        data: monthlyTotalStaffNumber(inactiveAccess),
      },
      {
        name: "Total Staff",
        data: monthlyTotalStaffNumber(totalStaffNumber),
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
      text: "Monthly Staff Access Status Report - Area Chart",
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
        text: "Staff",
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
        name: "Active Staff",
        data: monthlyTotalStaffNumber(activeAccess),
      },
      {
        name: "Inactive Staff",
        data: monthlyTotalStaffNumber(inactiveAccess),
      },
      {
        name: "Total Staff",
        data: monthlyTotalStaffNumber(totalStaffNumber),
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
      text: "Monthly Staff Access Status Report - Line Graph",
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
        text: "Staff",
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
        name: "Active Staff",
        data: monthlyTotalStaffNumber(activeAccess),
      },
      {
        name: "Inactive Staff",
        data: monthlyTotalStaffNumber(inactiveAccess),
      },
      {
        name: "Total Staff",
        data: monthlyTotalStaffNumber(totalStaffNumber),
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
      text: "Monthly Staff Access Status Report - Column Chart",
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
        text: "Staff",
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
        name: "Active Staff",
        data: monthlyTotalStaffNumber(activeAccess),
      },
      {
        name: "Inactive Staff",
        data: monthlyTotalStaffNumber(inactiveAccess),
      },
      {
        name: "Total Staff",
        data: monthlyTotalStaffNumber(totalStaffNumber),
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
      text: "Monthly Staff Access Status Report - Radar Chart",
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

function yearsInStaffsTable() {
  $.ajax({
    url: "/admin/reports/module/types/staffyears",
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

function filterStaffsAccount() {
  $("#searchButton").html('<i class="fa fa-spinner fa-spin"></i> searching...');
  $("#searchButton").attr("disabled", true);

  var year = $("select#year").val();
  var start_date = $("input#start_date").val();
  var end_date = $("input#end_date").val();
  var gender = $("select#gender").val();
  var account_no = $("input#account_no").val();
  var others = $("input#others").val();
  var status = $("select#status").val();
  var qualification = $("input#qualification").val();
  var reference_id = $("input#reference_id").val();
  var branch_id = $("select#branch_id").val();
  var staff_id = $("select#staff_id").val();
  var department_id = $("select#department_id").val();
  var position_id = $("select#position_id").val();
  var appointment_type = $("select#appointment_type").val();
  var staff_account_type = $("select#staff_account_type").val();

  // JS  activate staff data objects
  var activeObject = {
    entry_status: "",
    year: year,
    start_date: start_date,
    end_date: end_date,
    gender: gender,
    account_no: account_no,
    others: others,
    status: status,
    qualification: qualification,
    reference_id: reference_id,
    branch_id: branch_id,
    staff_id: staff_id,
    department_id: department_id,
    position_id: position_id,
    appointment_type: appointment_type,
    staff_account_type: staff_account_type,
  };

  // JS  inactive staff data objects
  var inactivateObject = {
    entry_status: "",
    year: year,
    start_date: start_date,
    end_date: end_date,
    gender: gender,
    account_no: account_no,
    others: others,
    status: status,
    qualification: qualification,
    reference_id: reference_id,
    branch_id: branch_id,
    staff_id: staff_id,
    department_id: department_id,
    position_id: position_id,
    appointment_type: appointment_type,
    staff_account_type: staff_account_type,
  };

  // JS  total number of staff data objects
  var totalNumberOfStaff = {
    entry_status: "",
    year: year,
    start_date: start_date,
    end_date: end_date,
    gender: gender,
    account_no: account_no,
    others: others,
    status: status,
    qualification: qualification,
    reference_id: reference_id,
    branch_id: branch_id,
    staff_id: staff_id,
    department_id: department_id,
    position_id: position_id,
    appointment_type: appointment_type,
    staff_account_type: staff_account_type,
  };

  var url = baseURL + "/admin/reports/module/get-report-type/staff";
  // ajax adding data to database
  $.ajax({
    url: url,
    type: "POST",
    data: $("#form").serialize(),
    dataType: "JSON",
    beforeSend: function () {
      $("#staff_counter").html(
        '<div class="text-center">' +
          '<i class="fa fa-spinner fa-pulse fa-4x fa-fw"></i></div>'
      );
      // load the spinner where staff table is
      $("#staff_table").html(
        '<div class="text-center">' +
          '<i class="fa fa-spinner fa-pulse fa-4x fa-fw"></i></div>'
      );
    },
    success: function (results) {
      // call the staff chart report
      staffChartsReport(activeObject, inactivateObject, totalNumberOfStaff);
      // staff counter
      var staffCounterDataTable =
        '<div class="table-responsive"><table id="staffCounter" class="table table-sm table-striped table-hover"' +
        'cellspacing="0" width="100%"><tbody></tbody>' +
        "<tfoot>" +
        "<tr>" +
        "<th>Total Staff</th>" +
        "<th>" +
        results.yearlyActiveStaffTotal +
        "</th>" +
        "<th>" +
        results.yearlyInactiveStaffTotal +
        "</th>" +
        "<th>" +
        results.yearlyStaffTotal +
        "</th>" +
        "</tr>" +
        "</tfoot></table></div>";

      $("#staff_counter").html(staffCounterDataTable);

      $("#staffCounter").DataTable({
        data: results.staffAccountsCounter,
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
        responsive: true,
        bInfo: true,
        bAutoWidth: true,
        columns: [
          { title: "Year - Month" },
          { title: "Number of Active Staff" },
          { title: "Number of Inactive Staff" },
          { title: "Total Staff" },
        ],
        // dom: "lBfrtip",
        // buttons: ["copy", "csv", "excel", "pdf", "print"],
        dom:
          "<'row'<'col-md-2'l><'col-md-6'B><'col-md-4'f>>" +
          "<'row'<'col-md-12'tr>>" +
          "<'row'<'col-md-5'i><'col-md-7'p>>",
        buttons: buttonsConfig,
      });

      // staff table information
      var staffDataTable =
        '<div class="table-responsive"><table id="staff" class="table table-sm table-striped table-hover"' +
        'cellspacing="0" width="100%"><tbody></tbody>' +
        "</table></div>";

      $("#staff_table").html(staffDataTable);

      $("#staff").DataTable({
        data: results.staffAccounts,
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
        responsive: true,
        bAutoWidth: true,
        columns: [
          { title: "S.No", width: "4%" },
          { title: "Join Date" },
          { title: "Name" },
          { title: "Phone" },
          { title: "ID Number" },
          { title: "Position" },
          { title: "Status" },
          { title: "Action" },
        ],
        // dom: "lBfrtip",
        // buttons: ["copy", "csv", "excel", "pdf", "print"],
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

      $("#searchButton").html('<i class="fa fa-search fa-fw"></i>');
      $("#searchButton").attr("disabled", false);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      $("#searchButton").html('<i class="fa fa-search fa-fw"></i>');
      $("#searchButton").attr("disabled", false);
    },
  });
}

function monthlyTotalStaffNumber(data) {
  const total = [];
  $.ajax({
    url: "/admin/reports/module/get-report-type/staff",
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
      qualification: data.qualification,
      reference_id: data.reference_id,
      branch_id: data.branch_id,
      staff_id: data.staff_id,
      department_id: data.department_id,
      position_id: data.position_id,
      appointment_type: data.appointment_type,
      staff_account_type: data.staff_account_type,
    },
    success: function (data) {
      if (data.monthlyStaffTotal.length > 0) {
        data.monthlyStaffTotal.forEach(function (value, index) {
          total.push(value);
        });
        // for (let index in data.monthlyStaffTotal) {
        //   const element = data.monthlyStaffTotal[index];
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

function viewStaffAccount(id) {
  //Ajax Load data from ajax
  $.ajax({
    url: "/admin/staff/administrator/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $('[name="id"]').val(data.id);
      $('[name="staffID"]').val(data.staffID);
      $('[name="staff_name"]').val(data.staff_name);
      $('[name="mobile"]').val(data.mobile);
      $('[name="alternate_mobile"]').val(data.alternate_mobile);
      $('[name="email"]').val(data.email);
      $('[name="address"]').val(data.address);
      $('[name="id_type"]').val(data.id_type);
      $('[name="id_number"]').val(data.id_number);
      $('[name="id_expiry"]').val(data.id_expiry_date);
      $('[name="position_id"]').val(data.position);
      $('[name="department"]').val(data.department_name);
      $('[name="branch_id"]').val(data.branch_name);
      $('[name="qualifications"]').val(data.qualifications);
      $('[name="salary_scale"]').val(data.salary_scale);
      $('[name="bank_name"]').val(data.bank_name);
      $('[name="bank_branch"]').val(data.bank_branch);
      $('[name="bank_account"]').val(data.bank_account);
      $('[name="gender"]').val(data.gender);
      $('[name="religion"]').val(data.religion);
      $('[name="marital_status"]').val(data.marital_status);
      $('[name="nationality"]').val(data.nationality);
      $('[name="date_of_birth"]').val(data.date_of_birth);
      $('[name="appointment_type"]').val(data.appointment_type);
      $('[name="created_at"]').val(data.created_at);
      $('[name="updated_at"]').val(data.updated_at);
      if (
        data.photo &&
        imageExists("/uploads/staffs/admins/passports/" + data.photo)
      ) {
        $("#photo-preview div").html(
          '<img src="/uploads/staffs/admins/passports/' +
            data.photo +
            '" class="img-fluid thumbnail"  style="width: 190px; height: 190px;">'
        );
      } else {
        $("#photo-preview div").html(
          '<img src="/assets/dist/img/nophoto.jpg" class="img-fluid thumbnail"  style="width: 190px; height: 190px;">'
        );
      }
      if (
        data.id_photo &&
        imageExists("/uploads/admins/ids/" + data.id_photo)
      ) {
        $("#id-preview div").html(
          '<img src="/uploads/admins/ids/' +
            data.id_photo +
            '" class="img-fluid thumbnail"  style="width: 200px; height: 180px;">'
        );
      } else {
        $("#id-preview div").html(
          '<img src="/assets/dist/img/id.jpg" class="img-fluid thumbnail"  style="width: 200px; height: 180px;">'
        );
      }
      if (
        data.signature &&
        imageExists("/uploads/staffs/admins/signatures/" + data.signature)
      ) {
        $("#signature-preview div").html(
          '<img src="/uploads/staffs/admins/signatures/' +
            data.signature +
            '" alt="Sign preview" id="preview-sign" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
        );
      } else {
        $("#signature-preview div").html(
          '<img src="/assets/dist/img/sign.png" alt="Sign preview" id="preview-sign" class="img-fluid thumbnail" style="width: 140px; height: 140px;">'
        );
      }
      $("#view_modal").modal("show"); // show bootstrap modal when complete loaded
      $(".modal-title").text("View " + data.staff_name); // Set title to Bootstrap modal title
    },
    error: function (jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, "error");
    },
  });
}
function viewStaffAccountPhoto(id) {
  $.ajax({
    url: "/admin/staff/administrator/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $('[name="id"]').val(data.id);
      $("#photo_modal_form").modal("show");
      $(".modal-title").text(data.name);
      $("#photo-preview").show(300);
      if (
        data.photo &&
        imageExists("/uploads/staffs/admins/passports/" + data.photo)
      ) {
        $("#photo-preview div").html(
          '<img src="/uploads/staffs/admins/passports/' +
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

function printStaffAccount() {
  var admin_id = $('[name="id"]').val();
  window.qualification.assign("/admin/staff/form/" + admin_id);
}

// https://www.cyberithub.com/how-to-update-key-with-new-value-javascript-3-methods/
