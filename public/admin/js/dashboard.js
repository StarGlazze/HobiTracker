$(function () {
  // -----------------------------------------------------------------------
  // Activity Overview Chart - Simplified Version
  // -----------------------------------------------------------------------

  var chartElement = document.querySelector("#sales-overview");
  if (!chartElement) {
    console.error('Chart element not found');
    return;
  }

  var chartDataAttr = chartElement.getAttribute('data-chart');
  var chartData = {};

  try {
    chartData = JSON.parse(chartDataAttr || '{}');
  } catch (e) {
    console.error('Error parsing chart data:', e);
    chartData = { categories: ['Belum ada data'], series: [0] };
  }

  // Function to truncate long category names
  function truncateCategory(cat, maxLength = 12) {
    if (!cat) return 'Unknown';
    return cat.length > maxLength ? cat.substring(0, maxLength) + '...' : cat;
  }

  // Store full category names for tooltip
  var fullCategories = chartData.categories || ['Belum ada data'];
  var truncatedCategories = fullCategories.map(cat => truncateCategory(cat));
  var seriesData = chartData.series || [0];

  // =======================================================================
  // FUNGSI HELPER BARU UNTUK KONFIGURASI CHART
  // =======================================================================

  /**
   * Mendapatkan Opsi Chart dasar
   */
  function getBaseChartOptions(categories, data, max) {
    return {
      series: [{ name: "Aktivitas", data: data }],
      chart: {
        // ===================================================================
        // PERBAIKAN FINAL (v7): Menghapus 'id' untuk menghindari bug internal
        // id: 'activity-chart', // <-- BARIS INI DIHAPUS
        // ===================================================================
        height: 350,
        toolbar: { show: false },
        foreColor: "#6c757d",
        fontFamily: "inherit",
        animations: { 
          enabled: true, 
          easing: 'easeinout', 
          speed: 600,
          animateGradually: { enabled: false },
          dynamicAnimation: { enabled: true, speed: 300 }
        }
      },
      grid: {
        show: true,
        borderColor: "#e9ecef",
        strokeDashArray: 3,
        padding: { left: 20, right: 20, top: 0, bottom: 0 },
        xaxis: { lines: { show: false } },
        yaxis: { lines: { show: true } }
      },
      colors: ["#5d87ff"],
      dataLabels: { enabled: false },
      yaxis: {
        show: true,
        min: 0,
        max: max,
        tickAmount: 5,
        labels: {
          style: { fontSize: '12px', colors: '#6c757d' },
          formatter: function(val) { return Math.floor(val); }
        }
      },
      xaxis: {
        categories: categories,
        axisBorder: { show: true, color: '#e9ecef' },
        axisTicks: { show: false },
        labels: {
          style: { fontSize: '11px', colors: '#6c757d' },
          rotate: -45,
          rotateAlways: categories.length > 6,
          trim: false,
          hideOverlappingLabels: true,
          offsetY: 5
        },
        tooltip: { enabled: false }
      },
      legend: { show: false },
      responsive: [
        {
          breakpoint: 992,
          options: {
            chart: { height: 300 },
            xaxis: { labels: { style: { fontSize: '10px' }, rotate: -45 } }
          }
        },
        {
          breakpoint: 576,
          options: {
            chart: { height: 280 },
            xaxis: { labels: { style: { fontSize: '9px' } }, rotate: -45 }
          }
        }
      ]
    };
  }

  /**
   * Opsi spesifik untuk AREA chart
   */
  function getAreaChartOptions(categories, data, max) {
    let options = getBaseChartOptions(categories, data, max);
    options.chart.type = 'area';
    options.markers = {
      size: 5,
      colors: ["#5d87ff"],
      strokeColors: "#fff",
      strokeWidth: 2,
      hover: { size: 7 }
    };
    options.stroke = { 
      show: true, 
      curve: 'smooth', 
      width: 3, 
      lineCap: "round" 
    };
    options.fill = {
      type: 'gradient',
      opacity: 0.8,
      gradient: {
        shade: 'light',
        type: "vertical",
        shadeIntensity: 0.5,
        gradientToColors: ['#ecf2ff'],
        inverseColors: false,
        opacityFrom: 0.8,
        opacityTo: 0.2,
        stops: [0, 100]
      }
    };
    options.tooltip = {
      theme: "light",
      x: {
        show: true,
        formatter: function(val, opts) {
          return window.fullCategories[opts.dataPointIndex] || val;
        }
      },
      y: { formatter: function(val) { return val + " aktivitas"; } },
      marker: { show: true }
    };
    return options;
  }

  /**
   * Opsi spesifik untuk LINE chart
   */
  function getLineChartOptions(categories, data, max) {
    let options = getBaseChartOptions(categories, data, max);
    options.chart.type = 'line';
    options.markers = {
      size: 5,
      colors: ["#5d87ff"],
      strokeColors: "#fff",
      strokeWidth: 2,
      hover: { size: 7 }
    };
    options.stroke = { 
      show: true, 
      curve: 'smooth', 
      width: 3, 
      lineCap: "round" 
    };
    options.fill = {
      type: 'solid',
      opacity: 1, 
    };
    options.tooltip = {
      theme: "light",
      x: {
        show: true,
        formatter: function(val, opts) {
          return window.fullCategories[opts.dataPointIndex] || val;
        }
      },
      y: { formatter: function(val) { return val + " aktivitas"; } },
      marker: { show: true }
    };
    return options;
  }

  /**
   * Opsi spesifik untuk BAR chart
   */
  function getBarChartOptions(categories, data, max) {
    let options = getBaseChartOptions(categories, data, max);
    options.chart.type = 'bar';
    options.markers = {
      size: 0,
      hover: { size: 0 }
    };
    options.stroke = { 
      show: false, 
      width: 0, 
    };
    options.fill = {
      type: 'gradient',
      opacity: 0.8,
      gradient: {
        shade: 'light',
        type: "vertical",
        shadeIntensity: 0.5,
        inverseColors: false,
        opacityFrom: 0.8,
        opacityTo: 0.8,
        stops: [0, 100]
      }
    };
    options.tooltip = {
      theme: "light",
      x: {
        show: true,
        formatter: function(val, opts) {
          return window.fullCategories[opts.dataPointIndex] || val;
        }
      },
      y: { formatter: function(val) { return val + " aktivitas"; } },
      marker: { show: false } 
    };
    return options;
  }

  // =======================================================================
  // AKHIR FUNGSI HELPER BARU
  // =======================================================================


  // Initialize chart (saat load pertama)
  var initialMax = Math.max(...seriesData);
  var dynamicMax = initialMax > 0 ? initialMax + Math.ceil(initialMax * 0.2) : 10;
  var initialChartOptions = getAreaChartOptions(truncatedCategories, seriesData, dynamicMax);
  
  var chart_activity_overview = new ApexCharts(chartElement, initialChartOptions);

  if (typeof ApexCharts !== 'undefined') {
    chart_activity_overview.render().then(function() {
      console.log('Chart rendered successfully');
    }).catch(function(err) {
      console.error('Error rendering chart:', err);
    });
  } else {
    console.error('ApexCharts is not loaded');
  }

  // Store chart instance and data globally
  if (typeof ApexCharts !== 'undefined') {
    window.activityChart = chart_activity_overview;
    window.fullCategories = fullCategories;
    window.truncateCategory = truncateCategory;
    window.currentChartData = seriesData;
    window.currentChartCategories = truncatedCategories;
    window.currentChartType = 'area';
  }


  // -----------------------------------------------------------------------
  // Chart Type Toggle - Fixed
  // -----------------------------------------------------------------------
  window.updateChartType = function(type) {
    if (!window.activityChart || typeof ApexCharts === 'undefined') {
      console.error('Chart not available');
      return;
    }
    
    if (type === window.currentChartType) {
        return;
    }

    $('.btn-group button').removeClass('active');
    $('#chart-' + type).addClass('active');
    window.currentChartType = type;

    var chartElement = document.querySelector("#sales-overview");
    if (chartElement) {

      // Salinan data (dari v5)
      var currentData = [...window.currentChartData] || [0];
      var currentCategories = [...window.currentChartCategories] || ['Belum ada data'];

      var maxVal = Math.max(...currentData);
      var dynamicMax = maxVal > 0 ? maxVal + Math.ceil(maxVal * 0.2) : 10;

      // Perbaikan v6: Bersihkan DIV secara paksa
      window.activityChart.destroy();
      chartElement.innerHTML = ""; 

      var newOptions;
      if (type === 'line') {
          newOptions = getLineChartOptions(currentCategories, currentData, dynamicMax);
      } else if (type === 'bar') {
          newOptions = getBarChartOptions(currentCategories, currentData, dynamicMax);
      } else { 
          newOptions = getAreaChartOptions(currentCategories, currentData, dynamicMax);
      }

      window.activityChart = new ApexCharts(chartElement, newOptions);
      window.activityChart.render().then(function() {
        console.log('Chart recreated successfully with type:', type);
      }).catch(function(err) {
        console.error('Error recreating chart:', err); // Ini adalah baris 314
      });
    }
  };

  // -----------------------------------------------------------------------
  // Period Filter - Simplified
  // -----------------------------------------------------------------------
  $('#period-selector').on('change', function() {
    var selectedText = $(this).val();
    var period = selectedText === 'Harian' ? 'daily' : selectedText === 'Mingguan' ? 'weekly' : 'monthly';

    $(this).prop('disabled', true);
    var $chartContainer = $('#sales-overview');
    $chartContainer.css('opacity', '0.5');

    $.ajax({
      url: '/dashboard/chart-data',
      method: 'GET',
      data: { period: period },
      success: function(data) {
        console.log('Chart data loaded:', data);

        // Simpan data "master"
        window.fullCategories = data.categories || ['Belum ada data'];
        window.currentChartData = data.series || [0];
        window.currentChartCategories = window.fullCategories.map(cat => window.truncateCategory(cat));

        // Salinan data (dari v5)
        var seriesData = [...window.currentChartData];
        var truncatedCategories = [...window.currentChartCategories];

        var maxVal = Math.max(...seriesData);
        var newMax = maxVal > 0 ? maxVal + Math.ceil(maxVal * 0.2) : 10;

        var chartElement = document.querySelector("#sales-overview");
        if (chartElement) {

          // Perbaikan v6: Bersihkan DIV secara paksa
          window.activityChart.destroy();
          chartElement.innerHTML = ""; 

          var currentType = window.currentChartType || 'area';
          var newOptions;

          if (currentType === 'line') {
              newOptions = getLineChartOptions(truncatedCategories, seriesData, newMax);
          } else if (currentType === 'bar') {
              newOptions = getBarChartOptions(truncatedCategories, seriesData, newMax);
          } else { 
              newOptions = getAreaChartOptions(truncatedCategories, seriesData, newMax);
          }

          window.activityChart = new ApexCharts(chartElement, newOptions);
          window.activityChart.render().then(function() {
            console.log('Chart updated successfully with new data');
          }).catch(function(err) {
            console.error('Error rendering updated chart:', err);
          });
        }

        // Update stats
        var totalKategori = (data.categories[0] === 'Belum ada data') ? 0 : data.categories.length;
        var totalAktivitas = seriesData.reduce((a, b) => a + b, 0);
        var rataRata = totalKategori > 0 ? (totalAktivitas / totalKategori).toFixed(1) : 0;
        var tertinggi = Math.max(...seriesData);

        $('.border-top .col-6.col-md-3').eq(0).find('.fw-bold').text(totalKategori);
        $('.border-top .col-6.col-md-3').eq(1).find('.fw-bold').text(totalAktivitas);
        $('.border-top .col-6.col-md-3').eq(2).find('.fw-bold').text(rataRata);
        $('.border-top .col-6.col-md-3').eq(3).find('.fw-bold').text(tertinggi);

        $chartContainer.css('opacity', '1');
        $('#period-selector').prop('disabled', false);
      },
      error: function(xhr, status, error) {
        console.error('Error fetching chart data:', error);
        alert('Gagal memuat data. Silakan coba lagi.');
        $chartContainer.css('opacity', '1');
        $('#period-selector').prop('disabled', false);
      }
    });
  });

  // -----------------------------------------------------------------------
  // Hover effects for cards
  // -----------------------------------------------------------------------
  $('.card').hover(
    function() { $(this).css({ 'transform': 'translateY(-5px)', 'transition': 'transform 0.3s ease' }); },
    function() { $(this).css({ 'transform': 'translateY(0)', 'transition': 'transform 0.3s ease' }); }
  );

  // =======================================================================
  // LOGIKA SHARE
  // =======================================================================
  const shareUrl = window.location.href;
  const shareText = "Cek progres hobi saya di HobiTracker!";

  $('#share-twitter').on('click', function(e) {
    e.preventDefault();
    const twitterUrl = `https://twitter.com/intent/tweet?url=${encodeURIComponent(shareUrl)}&text=${encodeURIComponent(shareText)}`;
    window.open(twitterUrl, '_blank', 'width=550,height=420');
  });

  $('#share-facebook').on('click', function(e) {
    e.preventDefault();
    const facebookUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(shareUrl)}`;
    window.open(facebookUrl, '_blank', 'width=550,height=420');
  });

  $('#share-whatsapp').on('click', function(e) {
    e.preventDefault();
    const whatsappUrl = `https://api.whatsapp.com/send?text=${encodeURIComponent(shareText + ' ' + shareUrl)}`;
    window.open(whatsappUrl, '_blank');
  });

  // Handler untuk Instagram
  $('#share-instagram').on('click', function(e) {
    e.preventDefault();
    const $btn = $(this);
    const originalHtml = $btn.html();
    
    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(shareUrl).then(function() {
        showCopyFeedback($btn, originalHtml, '<i class="ti ti-check me-2 text-success"></i>Link Tersalin!');
      }, function(err) {
        fallbackCopyTextToClipboard(shareUrl, $btn, originalHtml);
      });
    } else {
      fallbackCopyTextToClipboard(shareUrl, $btn, originalHtml);
    }
    
    const instagramUrl = `https://www.instagram.com/`;
    window.open(instagramUrl, '_blank');
  });


  $('#share-copy-link').on('click', function(e) {
    e.preventDefault();
    const $btn = $(this);
    const originalHtml = $btn.html();
    
    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(shareUrl).then(function() {
        showCopyFeedback($btn, originalHtml, '<i class="ti ti-check me-2 text-success"></i>Tersalin!');
      }, function(err) {
        console.error('Could not copy text: ', err);
        fallbackCopyTextToClipboard(shareUrl, $btn, originalHtml);
      });
    } else {
      fallbackCopyTextToClipboard(shareUrl, $btn, originalHtml);
    }
  });

  // Fungsi feedback yang bisa dipakai ulang
  function showCopyFeedback($btn, originalHtml, feedbackText) {
    $btn.html(feedbackText);
    $btn.addClass('text-success');
    setTimeout(function() { 
        $btn.html(originalHtml); 
        $btn.removeClass('text-success'); 
    }, 2000);
  }
  
  // Fungsi fallback yang bisa dipakai ulang
  function fallbackCopyTextToClipboard(text, $btn, originalHtml) {
    var textArea = document.createElement("textarea");
    textArea.value = text;
    textArea.style.position = "fixed";
    textArea.style.top = "0";
    textArea.style.left = "0";
    textArea.style.width = "2em";
    textArea.style.height = "2em";
    textArea.style.padding = "0";
    textArea.style.border = "none";
    textArea.style.outline = "none";
    textArea.style.boxShadow = "none";
    textArea.style.background = "transparent";
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    try {
      var successful = document.execCommand('copy');
      if (successful) {
        if($btn && originalHtml) {
            showCopyFeedback($btn, originalHtml, '<i class="ti ti-check me-2 text-success"></i>Tersalin!');
        }
      }
      else {
        alert('Gagal menyalin link. Silakan salin manual: ' + text);
      }
    } catch (err) {
      console.error('Fallback: Oops, unable to copy', err);
      alert('Gagal menyalin link. Silakan salin manual: ' + text);
    }
    document.body.removeChild(textArea);
  }
  // =======================================================================
  // AKHIR LOGIKA SHARE
  // =======================================================================


  // -----------------------------------------------------------------------
  // Initialize tooltips
  // -----------------------------------------------------------------------
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  console.log('Dashboard initialized successfully');
});