/* global Chart:false */

$(function () {
  'use strict'

  /* ChartJS
   * -------
   * Here we will create a few charts using ChartJS
   */
  var ticksStyle = {
    // fontColor: '#495057',
    fontStyle: 'bold'
  }

  //-------------
  // - LINE CHART -
  //-------------
  // Get context with jQuery - using jQuery's .get() method.
  var lineChartCanvas = $('#lineChart').get(0).getContext('2d')

  var lineChartData = {
    labels: entry_months(),
    datasets: [
      {
        label: 'Financing',
        backgroundColor: 'rgba(3, 148, 44, 0.5)',
        borderColor: 'rgba(3, 148, 44, 0.96)',
        pointRadius: false,
        pointColor: '#28ca31',
        pointStrokeColor: 'rgba(3, 148, 44, 1)',
        pointHighlightFill: '#fff',
        pointHighlightStroke: 'rgba(3, 148, 44, 1)',
        data: monthlyEntriesTotals('financing'),
        
      },
      {
        label: 'Expenses',
        backgroundColor: 'rgba(195, 11, 62, 0.5)',
        borderColor: 'rgba(195, 11, 62, 0.96)',
        pointRadius: false,
        pointColor: 'rgba(195, 11, 62, 1)',
        pointStrokeColor: '#c1c7d1',
        pointHighlightFill: '#fff',
        pointHighlightStroke: 'rgba(195, 11, 62, 1)',
        data: monthlyEntriesTotals('expense'),
      },
      {
        label: 'Transfer',
        backgroundColor: 'rgba(60, 141, 188, 0.5)',
        borderColor: 'rgba(60, 141, 188, 0.96)',
        pointRadius: false,
        pointColor: 'rgba(60, 141, 188, 1)',
        pointStrokeColor: '#c1c7d1',
        pointHighlightFill: '#fff',
        pointHighlightStroke: 'rgba(60, 141, 188, 1)',
        data: monthlyEntriesTotals('transfer'),
      }
    ]
  }

  var lineChartOptions = {
    maintainAspectRatio: false,
    responsive: true,
    legend: {
      display: true
    },
    scales: {
      xAxes: [{
        gridLines: {
          display: false
        },
      }],
      yAxes: [{
        gridLines: {
          display: false
        },
        ticks: $.extend({
          beginAtZero: true,

          // Include a currency sign in the ticks
          callback: function (value) {
          if (value >= 1000000) {
            value /= 1000000
            value += 'M'
          }
          if (value >= 1000) {
            value /= 1000
            value += 'K'
          }

          return currency + ' ' + value
          }
      }, ticksStyle)
      }]
    }
  }

  // This will get the first returned node in the jQuery collection.
  // eslint-disable-next-line no-unused-vars
  var lineChart = new Chart(lineChartCanvas, {
    type: 'line',
    data: lineChartData,
    options: lineChartOptions
  })

})

function monthlyEntriesTotals(entry_menu){
  const total = [];
  $.ajax({
    url: "/admin/reports/monthly-transactions-report/"+ entry_menu,
    async: false,
    type: "POST",
    dataType: "JSON",
    success: function(data) {
      if(data.length > 0){
        data.forEach(function(value, index){
          total.push(value);
        });
      }
    },
    error: function(jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, 'error');
    }
  });
  return total;
}

function entry_months(){
  const year = new Date().getFullYear();
  const months = [];
  $.ajax({
    url: '/admin/statements/entryMonths/'+ year,
    async: false,
    type: "GET",
    dataType: "JSON",
    success: function(data) {
      // set the entries month to zero to always start the chart from zero in x - axis
      months.push(0);
      for(var i in data){
        months.push(data[i]);
      }
    },
    error: function(jqXHR, textStatus, errorThrown) {
      Swal.fire(textStatus, errorThrown, 'error');
    }
  });
  return months;
}

// lgtm [js/unused-local-variable]
