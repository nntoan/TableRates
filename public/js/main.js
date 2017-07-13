/*
 * Copyright © 2017 Toan Nguyen. All rights reserved.
 * See COPYING.txt for license details.
 */

function selectRegion(region) {
    var reg = pcregions[region];
    $.each(reg.countries, function (key, value) {
        $("input[id^='country_" + value + "']").prop('checked', true);
    });
}

function clearValues() {
    $('input[type="checkbox"]:checked').each(function (index, element) {
        var countryCode = $(element).attr('id');
        countryCode = countryCode.replace('country_', '');
        $("input[name^='price_" + countryCode + "']").val('');
        $("input[name^='from_" + countryCode + "']").val('');
    });
}

function reset() {
    $("input[name^='country_']").each(function (index, element) {
        $(element).prop('checked', false);
    });
}

function setDefPrice() {
    $("input:checked").each(function (index, elmnt) {
        $(elmnt).parent().parent().find("input[name*=price]").val($("#defPrice").val());
    });
}

function setDefSubtotal() {
    $("input:checked").each(function (index, elmnt) {
        $(elmnt).parent().parent().find("input[name*=from]").val($("#defSubtotal").val());
    });
}

function addRowSelectedCountries() {
    $("input:checked").each(function (index, element) {
        var tr = $(element).closest('tr');
        tr.find('a.addRow').click();
    });
}

function changeLabel() {
    var label = '';
    var $subtotal = $("span.subtotal");
    var $weight = $("span.weight");
    var $qty = $("span.qty");
    var condition = $("#carriers_tablerate_condition_name").val();
    switch (condition) {
        case 'weight':
            label = 'From Weight';
            $subtotal.hide();
            $qty.hide();
            $weight.show();
            break;
        case 'qty':
            label = 'From Quantity';
            $subtotal.hide();
            $weight.hide();
            $qty.show();
            break;
        case 'price':
        default:
            label = "From Subtotal";
            $subtotal.show();
            $weight.hide();
            $qty.hide();
            break;
    }

    $("#conditionLabel").html(label);
}

function checkMe(element) {
    var $checkBox = $(element).parent().parent().find('input[type="checkbox"]');
    var checked = $checkBox.prop('checked');
    if (checked === false) {
        $checkBox.prop('checked', true);
    } else {
        $checkBox.prop('checked', false);
    }
}

$(document).ready(function () {
    $('.addRow').on('click', function () {
        var $addRow = $(this),
            $row = $addRow.closest('tr').clone(),
            $rowHtml = $row.html();
        $rowHtml = $rowHtml.replace($addRow.html(), '');
        $row.html($rowHtml);
        $addRow.closest('tr').after($row);
    });

    $('#tableRatesForm').submit(function () {
        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            beforeSend: function () {
                $('#generateBtn').button('loading');
            }
        }).done(function (response, status, xhr) {
            // check for a filename
            var filename = "";
            var disposition = xhr.getResponseHeader('Content-Disposition');
            if (disposition && disposition.indexOf('attachment') !== -1) {
                var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                var matches = filenameRegex.exec(disposition);
                if (matches !== null && matches[1]) {
                    filename = matches[1].replace(/['"]/g, '');
                }
            }

            var type = xhr.getResponseHeader('Content-Type');
            var blob = new Blob([response], {type: type});

            if (typeof window.navigator.msSaveBlob !== 'undefined') {
                // IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created.
                // These URLs will no longer resolve as the data backing the URL has been freed."
                window.navigator.msSaveBlob(blob, filename);
            } else {
                var URL = window.URL || window.webkitURL;
                var downloadUrl = URL.createObjectURL(blob);

                if (filename) {
                    // use HTML5 a[download] attribute to specify filename
                    var a = document.createElement("a");
                    // safari doesn't support this yet
                    if (typeof a.download === 'undefined') {
                        window.location = downloadUrl;
                    } else {
                        a.href = downloadUrl;
                        a.download = filename;
                        document.body.appendChild(a);
                        a.click();
                    }
                } else {
                    window.location = downloadUrl;
                }

                setTimeout(function () {
                    URL.revokeObjectURL(downloadUrl);
                }, 100); // cleanup
            }
        }).fail(function () {
            $('#generateBtn').button('reset');
        }).always(function () {
            $('#generateBtn').button('reset');
        });

        return false;
    });
});
