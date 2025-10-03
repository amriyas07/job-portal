$(document).ready(() => {
    
  // Disable spinner
  NProgress.configure({ showSpinner: false });

  // Show NProgress on page load
  NProgress.start();

  // Complete NProgress after DataTable initialization
  setTimeout(() => {
    NProgress.done()
  }, 500);

  // Mobile Sidebar Toggle
  $("#sidebarToggle").click(() => {
    $("#sidebar").toggleClass("show")
  });

  // Close sidebar when clicking outside on mobile
  $(document).click((event) => {
    if ($(window).width() <= 768) {
      if (!$(event.target).closest("#sidebar, #sidebarToggle").length) {
        $("#sidebar").removeClass("show")
      }
    }
  });

});

$(document).ready(function() {
    let alert = $('.alert-container');

    if(alert.find('.alert').length) {
        // Scroll to alert smoothly
        $('html, body').animate({
            scrollTop: alert.offset().top - 100
        }, 500);

        // Show alert
        alert.fadeIn(300);

        // Auto-hide after 5 seconds
        setTimeout(() => {
            alert.fadeOut(300);
        }, 5000); // you can change duration
    }

    // Dismiss manually
    alert.on('click', '.btn-close', function() {
        alert.fadeOut(300);
    });
});
