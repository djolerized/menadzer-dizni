jQuery(document).ready(function($) {
    'use strict';

    // Drag & Drop functionality
    var dragDropContainer = $('#izbor-dizni-drag-drop-container');

    if (dragDropContainer.length === 0) {
        return;
    }

    var overlay = $('#izbor-dizni-overlay');
    var canvasImage = $('#izbor-dizni-canvas-image');
    var addButton = $('#izbor-dizni-add-nozzle');
    var nozzleSelect = $('#izbor-dizni-nozzle-select');

    // Show select when add button is clicked
    addButton.on('click', function() {
        if (nozzleSelect.is(':visible')) {
            nozzleSelect.hide();
        } else {
            nozzleSelect.show();
        }
    });

    // Add nozzle when selected
    nozzleSelect.on('change', function() {
        var diznaId = $(this).val();
        var thumbnail = $(this).find(':selected').data('thumbnail');
        var title = $(this).find(':selected').text();

        if (diznaId) {
            addNozzleToCanvas(diznaId, thumbnail, title, 50, 50);
            $(this).val('').hide();
            syncWithACF();
        }
    });

    // Function to add nozzle icon to canvas
    function addNozzleToCanvas(diznaId, thumbnail, title, posX, posY) {
        var nozzleIcon = $('<div>')
            .addClass('izbor-dizni-nozzle-icon')
            .attr('data-dizna-id', diznaId)
            .css({
                position: 'absolute',
                left: posX + '%',
                top: posY + '%',
                transform: 'translate(-50%, -50%)',
                cursor: 'move'
            });

        var img;
        if (thumbnail) {
            img = $('<img>')
                .attr('src', thumbnail)
                .attr('title', title)
                .css({
                    width: '50px',
                    height: '50px',
                    border: '2px solid #0073aa',
                    borderRadius: '5px',
                    background: 'white'
                });
        } else {
            img = $('<div>')
                .text(title.substring(0, 3))
                .css({
                    width: '50px',
                    height: '50px',
                    background: '#0073aa',
                    color: 'white',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    borderRadius: '5px',
                    fontSize: '10px',
                    textAlign: 'center'
                });
        }

        var removeButton = $('<button>')
            .attr('type', 'button')
            .addClass('izbor-dizni-remove-nozzle')
            .text('×')
            .css({
                position: 'absolute',
                top: '-8px',
                right: '-8px',
                background: 'red',
                color: 'white',
                border: 'none',
                borderRadius: '50%',
                width: '20px',
                height: '20px',
                cursor: 'pointer',
                fontSize: '12px',
                lineHeight: '1'
            });

        nozzleIcon.append(img).append(removeButton);
        overlay.append(nozzleIcon);

        // Make draggable
        makeNozzleDraggable(nozzleIcon);

        // Remove button handler
        removeButton.on('click', function(e) {
            e.stopPropagation();
            nozzleIcon.remove();
            syncWithACF();
        });
    }

    // Make nozzle draggable
    function makeNozzleDraggable(element) {
        element.draggable({
            containment: overlay,
            stop: function(event, ui) {
                // Calculate position in percentages
                var containerWidth = overlay.width();
                var containerHeight = overlay.height();
                var elementWidth = element.width();
                var elementHeight = element.height();

                // Get position (including the offset from transform)
                var left = ui.position.left + (elementWidth / 2);
                var top = ui.position.top + (elementHeight / 2);

                var percentX = (left / containerWidth) * 100;
                var percentY = (top / containerHeight) * 100;

                // Update element position
                element.css({
                    left: percentX + '%',
                    top: percentY + '%'
                });

                // Sync with ACF
                syncWithACF();
            }
        });
    }

    // Make existing nozzles draggable
    $('.izbor-dizni-nozzle-icon').each(function() {
        makeNozzleDraggable($(this));

        // Add remove handler
        $(this).find('.izbor-dizni-remove-nozzle').on('click', function(e) {
            e.stopPropagation();
            $(this).closest('.izbor-dizni-nozzle-icon').remove();
            syncWithACF();
        });
    });

    // Sync positions with ACF repeater
    function syncWithACF() {
        var nozzles = [];

        $('.izbor-dizni-nozzle-icon').each(function() {
            var $this = $(this);
            var diznaId = $this.data('dizna-id');
            var leftPercent = parseFloat($this.css('left'));
            var topPercent = parseFloat($this.css('top'));

            nozzles.push({
                dizna: diznaId,
                pozicija_x: leftPercent,
                pozicija_y: topPercent
            });
        });

        // Update ACF repeater
        updateACFRepeater(nozzles);
    }

    // Update ACF repeater field
    function updateACFRepeater(nozzles) {
        var repeater = $('[data-name="pozicionirane_dizne"]');

        if (repeater.length === 0) {
            return;
        }

        // Remove all existing rows
        repeater.find('.acf-row:not(.acf-clone)').remove();

        // Add new rows
        nozzles.forEach(function(nozzle, index) {
            // Click add row button
            var addButton = repeater.find('.acf-button[data-event="add-row"]');
            addButton.trigger('click');

            // Wait a bit for ACF to create the row
            setTimeout(function() {
                var row = repeater.find('.acf-row:not(.acf-clone)').eq(index);

                // Set dizna
                var diznaSelect = row.find('[data-name="dizna"] select');
                diznaSelect.val(nozzle.dizna).trigger('change');

                // Set positions
                var xInput = row.find('[data-name="pozicija_x"] input');
                var yInput = row.find('[data-name="pozicija_y"] input');

                xInput.val(nozzle.pozicija_x.toFixed(1));
                yInput.val(nozzle.pozicija_y.toFixed(1));
            }, 100 * (index + 1));
        });
    }

    // Handle image load and resize
    canvasImage.on('load', function() {
        updateOverlaySize();
    });

    $(window).on('resize', function() {
        updateOverlaySize();
    });

    function updateOverlaySize() {
        var width = canvasImage.width();
        var height = canvasImage.height();
        overlay.css({
            width: width + 'px',
            height: height + 'px'
        });
    }

    // Initialize overlay size
    updateOverlaySize();
});
