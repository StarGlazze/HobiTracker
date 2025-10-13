$(function () {
  // -----------------------------------------------------------------------
  // sales overview
  // -----------------------------------------------------------------------

  // Get chart data from the data attribute
  var chartElement = document.querySelector("#sales-overview");
  var chartData = chartElement ? JSON.parse(chartElement.getAttribute('data-chart') || '{}') : {};

  var options_sales_overview = {
    series: [
      {
        name: "Aktivitas",
        data: chartData.series || [0, 0, 0, 0, 0],
      },
    ],
    chart: {
      type: "bar",
      height: 275,
      toolbar: {
        show: false,
      },
      foreColor: "#adb0bb",
      fontFamily: "inherit",
      sparkline: {
        enabled: false,
      },
    },
    grid: {
      show: false,
      borderColor: "transparent",
      padding: {
        left: 0,
        right: 0,
        bottom: 0,
      },
    },
    plotOptions: {
      bar: {
        horizontal: false,
        columnWidth: "25%",
        endingShape: "rounded",
        borderRadius: 5,
      },
    },
    colors: ["var(--bs-primary)"],
    dataLabels: {
      enabled: false,
    },
    yaxis: {
      show: true,
      min: 0,
      max: Math.max(...(chartData.series || [0])) + 5 || 10,
      tickAmount: 3,
    },
    stroke: {
      show: true,
      width: 5,
      lineCap: "butt",
      colors: ["var(--bs-primary)"],
    },
    xaxis: {
      type: "category",
      categories: chartData.categories || ["Jan", "Feb", "Mar", "Apr", "May"],
      axisBorder: {
        show: false,
      },
    },
    fill: {
      opacity: 1,
    },
    tooltip: {
      theme: "dark",
    },
    legend: {
      show: false,
    },
  };

  var chart_column_basic = new ApexCharts(
    document.querySelector("#sales-overview"),
    options_sales_overview
  );
  chart_column_basic.render();
});
