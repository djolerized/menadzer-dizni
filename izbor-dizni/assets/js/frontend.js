jQuery(document).ready(function($) {
    'use strict';

    // Popup handling
    var popup = $('#izbor-dizni-popup');
    var popupBody = popup.find('.izbor-dizni-popup-body');
    var popupClose = popup.find('.izbor-dizni-popup-close');
    var popupOverlay = popup.find('.izbor-dizni-popup-overlay');

    // Click on nozzle to open popup
    $(document).on('click', '.izbor-dizni-nozzle', function(e) {
        e.preventDefault();
        var diznaId = $(this).data('dizna-id');

        if (!diznaId) {
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
                if (response.success) {
                    popupBody.html(response.data);
                } else {
                    popupBody.html('<p>Greška pri učitavanju podataka.</p>');
                }
            },
            error: function() {
                popupBody.html('<p>Greška pri učitavanju podataka.</p>');
            }
        });
    });

    // Close popup
    function closePopup() {
        popup.fadeOut(300);
    }

    popupClose.on('click', closePopup);
    popupOverlay.on('click', closePopup);

    // Close on ESC key
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && popup.is(':visible')) {
            closePopup();
        }
    });

    // Responsive handling
    function updateOverlaySize() {
        var canvas = $('.izbor-dizni-canvas-wrapper');
        var overlay = canvas.find('.izbor-dizni-overlay');
        var image = canvas.find('.izbor-dizni-canvas-image');

        if (image.length && overlay.length) {
            var width = image.width();
            var height = image.height();
            overlay.css({
                width: width + 'px',
                height: height + 'px'
            });
        }
    }

    // Update on image load and window resize
    $('.izbor-dizni-canvas-image').on('load', updateOverlaySize);
    $(window).on('resize', updateOverlaySize);

    // Initial update
    if ($('.izbor-dizni-canvas-image').length) {
        updateOverlaySize();
    }
});
