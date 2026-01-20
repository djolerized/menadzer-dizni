jQuery(document).ready(function($) {
    'use strict';

    // Debug: Check if nozzles are present
    var nozzles = $('.izbor-dizni-nozzle');
    if (nozzles.length > 0) {
        console.log('Found ' + nozzles.length + ' nozzles on the page');
        nozzles.each(function() {
            var $this = $(this);
            console.log('Nozzle:', {
                id: $this.data('dizna-id'),
                left: $this.css('left'),
                top: $this.css('top'),
                transform: $this.css('transform')
            });
        });
    } else {
        console.log('No nozzles found on the page');
    }

    // Popup handling
    var popup = $('#izbor-dizni-popup');
    var popupBody = popup.find('.izbor-dizni-popup-body');
    var popupClose = popup.find('.izbor-dizni-popup-close');
    var popupOverlay = popup.find('.izbor-dizni-popup-overlay');

    // Click on nozzle to open popup
    $(document).on('click', '.izbor-dizni-nozzle', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var diznaId = $(this).data('dizna-id');

        console.log('Nozzle clicked:', diznaId);

        if (!diznaId) {
            console.error('No dizna ID found');
            return;
        }

        // Show loading state
        popupBody.html('<div class="izbor-dizni-loading">Učitavanje...</div>');
        popup.fadeIn(300);

        // Load dizna data via AJAX
        $.ajax({
            url: izborDizniFrontend.ajaxurl,
            type: 'POST',
            data: {
                action: 'izbor_dizni_get_dizna_data',
                dizna_id: diznaId
            },
            success: function(response) {
                console.log('AJAX response:', response);
                if (response.success) {
                    popupBody.html(response.data);
                } else {
                    popupBody.html('<p>Greška pri učitavanju podataka.</p>');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', status, error);
                popupBody.html('<p>Greška pri učitavanju podataka.</p>');
            }
        });
    });

    // Close popup
    function closePopup() {
        popup.fadeOut(300);
    }

    popupClose.on('click', function(e) {
        e.preventDefault();
        closePopup();
    });

    popupOverlay.on('click', closePopup);

    // Close on ESC key
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && popup.is(':visible')) {
            closePopup();
        }
    });

    // Responsive handling
    function updateOverlaySize() {
        var canvasWrapper = $('.izbor-dizni-canvas-wrapper');

        canvasWrapper.each(function() {
            var $wrapper = $(this);
            var overlay = $wrapper.find('.izbor-dizni-overlay');
            var image = $wrapper.find('.izbor-dizni-canvas-image');

            if (image.length && overlay.length) {
                var width = image.width();
                var height = image.height();
                overlay.css({
                    width: width + 'px',
                    height: height + 'px'
                });

                console.log('Overlay sized to:', width, 'x', height);
            }
        });
    }

    // Update on image load and window resize
    $('.izbor-dizni-canvas-image').on('load', function() {
        console.log('Image loaded');
        updateOverlaySize();
    });

    $(window).on('resize', function() {
        updateOverlaySize();
    });

    // Initial update
    if ($('.izbor-dizni-canvas-image').length) {
        // Check if image is already loaded
        var img = $('.izbor-dizni-canvas-image')[0];
        if (img && img.complete) {
            console.log('Image already loaded');
            updateOverlaySize();
        }

        // Also update after a delay to be sure
        setTimeout(updateOverlaySize, 100);
    }
});
