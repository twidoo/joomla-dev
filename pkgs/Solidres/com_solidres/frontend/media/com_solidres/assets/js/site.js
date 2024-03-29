/*------------------------------------------------------------------------
  Solidres - Hotel booking extension for Joomla
  ------------------------------------------------------------------------
  @Author    Solidres Team
  @Website   http://www.solidres.com
  @Copyright Copyright (C) 2013 Solidres. All Rights Reserved.
  @License   GNU General Public License version 3, or later
------------------------------------------------------------------------*/

if (typeof(Solidres) === 'undefined') {
    var Solidres = {};
}

Solidres.setCurrency = function(id) {
    Solidres.jQuery.ajax({
        type: 'POST',
        url: window.location.pathname,
        data: 'option=com_solidres&format=json&task=currency.setId&id='+parseInt(id),
        success: function(msg) {
            location.reload(true);
        }
    });
};

Solidres.jQuery(function($) {
    if (document.getElementById('sr-reservation-form-room')) {
        $('#sr-reservation-form-room').validate();
    }

    if (document.getElementById("sr-checkavailability-form")) {
        $("#sr-checkavailability-form").validate();
    }

    $('a.room_type_details').click(function(e) {
        e.preventDefault();
        var room_type_details = $('div.'+$(this).attr('id') );
        if (room_type_details.hasClass('hidden')) {
            room_type_details.removeClass('hidden');
        } else {
            room_type_details.addClass('hidden');
        }
    });
    $('#coupon_code').blur(function() {
        var self = $(this);
        var coupon_code = self.val();
        if (coupon_code) {
            $.ajax({
                type: 'POST',
                url: window.location.pathname,
                data: 'option=com_solidres&format=json&task=coupon.isValid&coupon_code=' + coupon_code + '&raid=' + $('input[name="id"]').val(),
                success: function(response) {
                    self.next().remove();
                    self.after(response.message);
                    if (!response.status) {
                        $('#apply-coupon').attr('disabled', 'disabled');
                    } else {
                        $('#apply-coupon').removeAttr('disabled');
                    }
                },
                dataType: 'JSON'
            });
        }
    });
    $('.coupon').on('click', '#apply-coupon', function () {
        $.ajax({
            type: 'POST',
            url: window.location.pathname,
            data: 'option=com_solidres&format=json&task=coupon.applyCoupon&coupon_code=' + $('#coupon_code').val() + '&raid=' + $('input[name="id"]').val(),
            success: function(response) {
                if (response.status) {
                    location.reload(true);
                }
            },
            dataType: 'JSON'
        });
    });
    $('#sr-remove-coupon').click(function(e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: window.location.pathname,
            data: 'option=com_solidres&format=json&task=reservation.removeCoupon&id=' + $(this).data('couponid'),
            success: function(response) {
                if (response.status) {
                    location.reload(true);
                } else {
                    alert(Joomla.JText._('SR_CAN_NOT_REMOVE_COUPON'));
                }
            },
            dataType: 'JSON'
        });
    });

    var checkout = $("#checkout").datepicker({
        minDate : 0,
        numberOfMonths : 3,
        showButtonPanel : true,
        dateFormat : "dd-mm-yy"
    });
    var checkin = $("#checkin").datepicker({
        minDate : 0,
        numberOfMonths : 3,
        showButtonPanel : true,
        dateFormat : "dd-mm-yy",
        onSelect : function() {
            var next = $(this).datepicker('getDate', '+1d');
            next.setDate(next.getDate() + 1);
            checkout.datepicker( "option", "minDate", next );
            checkout.datepicker('setDate', next);
        }
    });

    var checkout_component = $("#checkout_component").datepicker({
        minDate : 0,
        numberOfMonths : 3,
        showButtonPanel : true,
        dateFormat : "dd-mm-yy"
    });
    var checkin_component = $("#checkin_component").datepicker({
        minDate : 0,
        numberOfMonths : 3,
        showButtonPanel : true,
        dateFormat : "dd-mm-yy",
        onSelect : function() {
            var next = $(this).datepicker('getDate', '+1d');
            next.setDate(next.getDate() + 1);
            checkout_component.datepicker( "option", "minDate", next );
            checkout_component.datepicker('setDate', next);
        }
    });

    var checkout_component2 = $("#checkout_component2").datepicker({
        minDate : 0,
        numberOfMonths : 3,
        showButtonPanel : true,
        dateFormat : "dd-mm-yy"
    });
    var checkin_component2 = $("#checkin_component2").datepicker({
        minDate : 0,
        numberOfMonths : 3,
        showButtonPanel : true,
        dateFormat : "dd-mm-yy",
        onSelect : function() {
            var next = $(this).datepicker('getDate', '+1d');
            next.setDate(next.getDate() + 1);
            checkout_component2.datepicker( "option", "minDate", next );
            checkout_component2.datepicker('setDate', next);
        }
    });

    var submitReservationForm = function(form) {
        var self = $(form),
            url = self.attr( 'action'),
            formHolder = self.parent('.reservation-box-inner'),
            submitBtn = self.children('button[type=submit]'),
            processingIndicator = self.children('div.processing-indicator');

        submitBtn.attr('disabled', 'disabled');
        submitBtn.addClass('nodisplay');
        processingIndicator.removeClass('nodisplay');
        processingIndicator.addClass('active');
        $.post( url, self.serialize(), function(data) {
            if (data.status == 1) {
                $.ajax({
                    type: 'GET',
                    cache: false,
                    url: 'index.php?option=com_solidres&task=reservation.progress&format=json&next='+data.next,
                    success: function(response) {
                        formHolder.css('display', 'none');
                        submitBtn.removeClass('nodisplay');
                        processingIndicator.addClass('nodisplay');
                        processingIndicator.removeClass('active');
                        var next = $('#' + data.next);
                        next.children('.reservation-box-inner').remove();
                        next.append(response);
                        if (data.next == 'payment') {
                            $.metadata.setType("attr", "validate");
                        }
                        var next_form = next.find('form.sr-reservation-form');
                        if (next_form.attr('id') == 'sr-reservation-form-guest') {
                            next_form.validate({
                                rules: {
                                    'jform[customer_email]': {
                                        required: true,
                                        email: true
                                    }
                                }
                            });
                        } else {
                            next_form.validate();
                        }

                    }
                });
            }
        }, "json");
    }

    $('#reservation-form-holder').on('submit', 'form.sr-reservation-form', function (event) {
        event.preventDefault();
        submitReservationForm(this);
    });

    $('#submit-ppec').click(function(event) {
        event.preventDefault();
        var form = $('#sr-availability-form');
        form.children('input[name="task"]').val('reservationppec.startPaypalExpressCheckout');
        form.trigger('submit');
    });

    $('#submit-norm').click(function(event) {
        event.preventDefault();
        var form = $('#sr-availability-form');
        form.children('input[name="task"]').val('reservation.start');
        form.trigger('submit');
    });

    $('#reservation-form-holder .reservation-box').on('click', 'h3', function() {
        $('.reservation-box').children('.reservation-box-inner').css('display', 'none');
        var active = $(this).next('.reservation-box-inner');
        active.css('display', 'block');
        active.find('form button[type=submit]').removeAttr('disabled');
    });

    $('#reservation-form-holder .reservation-box').on('click', '#termsandconditions', function() {
        var buttonId = $(this).data('target');
        if ($(this).is(':checked')) {
            $('#' + buttonId).removeAttr('disabled');
        } else {
            $('#' + buttonId).attr('disabled', 'disabled');
        }
    });

    $('#reservation-form-holder .reservation-box').on('click', '#finalbutton', function() {
        var payment = $(this).data('payment');
        if (payment == 'paypal') {
            $('<p>Your order is being processed, ' +
                'you will be redirected to Paypal website, ' +
                'please wait for a moment...</p>').insertAfter($(this));
        }
    });

    $('#sr-reservation-form-room input:checkbox').click(function() {
        if ($(this).is(':checked')) {
            $('.' + $(this).attr('class') + '-d').removeAttr('disabled');
        } else {
            $('.' + $(this).attr('class') + '-d').attr('disabled', 'disabled');
        }
    });

    $('#reservation-form-holder .reservation-box').on('change', '.country_select', function() {
        $.ajax({
            url : 'index.php?option=com_solidres&format=json&task=states.find&id=' + $(this).val(),
            success : function(html) {
                if (html.length > 0) {
                    $('.state_select').empty().html(html);
                }
            }
        });
    });

    $('button.load-calendar').click(function() {
        var self = $(this);
        var id = self.data('roomtypeid');
        var processingIndicator = self.prev('div.processing-indicator');

        self.attr('disabled', 'disabled');
        self.addClass('nodisplay');
        processingIndicator.removeClass('nodisplay');
        processingIndicator.addClass('active');
        $('#availability-calendar-' + id).empty();
        $.ajax({
            url : 'index.php?option=com_solidres&format=json&task=reservationasset.getAvailabilityCalendar&id=' + id,
            success : function(html) {
                self.removeClass('nodisplay');
                self.removeAttr('disabled', 'disabled');
                processingIndicator.addClass('nodisplay');
                processingIndicator.removeClass('active');
                if (html.length > 0) {
                    $('#availability-calendar-' + id).empty().html(html);
                }
            }
        });
    });
});