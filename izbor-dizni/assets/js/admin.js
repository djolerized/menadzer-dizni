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
    var nozzleCounter = 0;

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
        nozzleCounter++;

        var nozzleIcon = $('<div>')
            .addClass('izbor-dizni-nozzle-icon')
            .attr('data-dizna-id', diznaId)
            .attr('data-nozzle-uid', 'nozzle-' + nozzleCounter)
            .attr('data-pos-x', posX)
            .attr('data-pos-y', posY)
            .css({
                position: 'absolute',
                left: posX + '%',
                top: posY + '%',
                transform: 'translate(-50%, -50%)',
                cursor: 'move',
                zIndex: 100
            });

        var img;
        if (thumbnail) {
            img = $('<img>')
                .attr('src', thumbnail)
                .attr('title', title)
                .attr('draggable', 'false')
                .css({
                    width: '50px',
                    height: '50px',
                    border: '3px solid #0073aa',
                    borderRadius: '5px',
                    background: 'white',
                    boxShadow: '0 2px 8px rgba(0,0,0,0.3)',
                    pointerEvents: 'none'
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
                    textAlign: 'center',
                    boxShadow: '0 2px 8px rgba(0,0,0,0.3)',
                    pointerEvents: 'none'
                });
        }

        var removeButton = $('<button>')
            .attr('type', 'button')
            .addClass('izbor-dizni-remove-nozzle')
            .html('&times;')
            .css({
                position: 'absolute',
                top: '-10px',
                right: '-10px',
                background: '#dc3232',
                color: 'white',
                border: 'none',
                borderRadius: '50%',
                width: '24px',
                height: '24px',
                cursor: 'pointer',
                fontSize: '16px',
                lineHeight: '1',
                fontWeight: 'bold',
                zIndex: 101
            });

        nozzleIcon.append(img).append(removeButton);
        overlay.append(nozzleIcon);

        // Make draggable
        makeNozzleDraggable(nozzleIcon);

        // Remove button handler
        removeButton.on('click', function(e) {
            e.stopPropagation();
            e.preventDefault();
            nozzleIcon.remove();
            syncWithACF();
        });
    }

    // Make nozzle draggable
    function makeNozzleDraggable(element) {
        element.draggable({
            containment: 'parent',
            scroll: false,
            drag: function(event, ui) {
                // Show visual feedback while dragging
                $(this).css('opacity', '0.8');
            },
            stop: function(event, ui) {
                $(this).css('opacity', '1');

                // Get container dimensions
                var containerWidth = overlay.width();
                var containerHeight = overlay.height();

                // Get the actual pixel position from jQuery UI
                var pixelLeft = ui.position.left;
                var pixelTop = ui.position.top;

                // Element is centered with transform: translate(-50%, -50%)
                // So we need to add half the element's size to get the center point
                var elementWidth = element.outerWidth();
                var elementHeight = element.outerHeight();

                var centerX = pixelLeft + (elementWidth / 2);
                var centerY = pixelTop + (elementHeight / 2);

                // Calculate percentage
                var percentX = (centerX / containerWidth) * 100;
                var percentY = (centerY / containerHeight) * 100;

                // Clamp values between 0 and 100
                percentX = Math.max(0, Math.min(100, percentX));
                percentY = Math.max(0, Math.min(100, percentY));

                // Store the percentage values as data attributes
                element.attr('data-pos-x', percentX);
                element.attr('data-pos-y', percentY);

                // Update element position with percentages
                element.css({
                    left: percentX + '%',
                    top: percentY + '%',
                    transform: 'translate(-50%, -50%)'
                });

                console.log('Nozzle positioned at:', percentX.toFixed(2) + '%', percentY.toFixed(2) + '%');

                // Sync with ACF
                syncWithACF();
            }
        });
    }

    // Make existing nozzles draggable and add data attributes
    $('.izbor-dizni-nozzle-icon').each(function() {
        var $this = $(this);

        // Store position from CSS in data attributes for easier retrieval
        var leftValue = $this.css('left');
        var topValue = $this.css('top');

        // Extract percentage values
        var posX = parseFloat(leftValue);
        var posY = parseFloat(topValue);

        $this.attr('data-pos-x', posX);
        $this.attr('data-pos-y', posY);

        makeNozzleDraggable($this);

        // Add remove handler
        $this.find('.izbor-dizni-remove-nozzle').on('click', function(e) {
            e.stopPropagation();
            e.preventDefault();
            $this.remove();
            syncWithACF();
        });
    });

    // Sync positions with ACF repeater
    function syncWithACF() {
        var repeater = $('[data-name="pozicionirane_dizne"]');

        if (repeater.length === 0) {
            console.warn('ACF repeater field not found');
            return;
        }

        var nozzles = [];

        // Collect all nozzle data
        $('.izbor-dizni-nozzle-icon').each(function() {
            var $this = $(this);
            var diznaId = $this.data('dizna-id');
            var posX = parseFloat($this.attr('data-pos-x')) || 50;
            var posY = parseFloat($this.attr('data-pos-y')) || 50;

            nozzles.push({
                dizna: diznaId,
                pozicija_x: posX,
                pozicija_y: posY
            });
        });

        console.log('Syncing nozzles:', nozzles);

        // Remove all existing ACF rows
        var existingRows = repeater.find('.acf-row:not(.acf-clone)');
        existingRows.each(function() {
            var removeBtn = $(this).find('.acf-icon.-minus');
            if (removeBtn.length) {
                removeBtn.trigger('click');
            }
        });

        // Add rows for each nozzle
        if (nozzles.length > 0) {
            addACFRowsRecursive(repeater, nozzles, 0);
        }
    }

    // Recursive function to add ACF rows one by one
    function addACFRowsRecursive(repeater, nozzles, index) {
        if (index >= nozzles.length) {
            console.log('ACF sync complete');
            return;
        }

        var nozzle = nozzles[index];
        var addButton = repeater.find('> .acf-actions .acf-button[data-event="add-row"]');

        // Trigger add row
        addButton.trigger('click');

        // Wait for ACF to create the row
        setTimeout(function() {
            var rows = repeater.find('> .acf-table > tbody > .acf-row:not(.acf-clone)');
            var row = rows.eq(index);

            if (row.length) {
                // Find and set the dizna select field
                var diznaField = row.find('.acf-field[data-name="dizna"]');
                var diznaSelect = diznaField.find('select');

                if (diznaSelect.length) {
                    diznaSelect.val(nozzle.dizna);
                    diznaSelect.trigger('change');
                }

                // Find and set position X
                var xField = row.find('.acf-field[data-name="pozicija_x"]');
                var xInput = xField.find('input[type="number"]');

                if (xInput.length) {
                    xInput.val(nozzle.pozicija_x.toFixed(2));
                    xInput.trigger('change');
                }

                // Find and set position Y
                var yField = row.find('.acf-field[data-name="pozicija_y"]');
                var yInput = yField.find('input[type="number"]');

                if (yInput.length) {
                    yInput.val(nozzle.pozicija_y.toFixed(2));
                    yInput.trigger('change');
                }

                console.log('Added ACF row ' + (index + 1) + ':', nozzle);
            }

            // Add next row
            addACFRowsRecursive(repeater, nozzles, index + 1);
        }, 200);
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
    if (canvasImage[0] && canvasImage[0].complete) {
        updateOverlaySize();
    }

    // Double-check after a short delay
    setTimeout(updateOverlaySize, 100);
});
