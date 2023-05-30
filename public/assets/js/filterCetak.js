const loader = $('#loader-container');
const printWrapper = $('#print-wrapper');
const previewBtn = $('.previewBtn');
const radioEl = $('[type=radio]');
const inputElRad = $('.rOpt');
const checkboxEl = $('[type=checkbox]');
const inputElCek = $('.cOpt');
let form = $('form#filter');

$(window).on('load', function() {
    radioOptions(radioEl, inputElRad);
    checkboxOptions(checkboxEl, inputElCek);
    loader.hide();
    //lock a while
    //getRes(form.attr('action'), form.serialize());
    $(document).ready(function () {
        // fungsi get request form
        $(document).on('submit', 'form#filter', onFilterForm);
        previewBtn.mouseover(getFormAction);
        previewBtn.on('click', onPreview);
        radioEl.on('click', function () {
            radioOptions(radioEl, inputElRad);
        });
        checkboxEl.on('click', function () {
            checkboxOptions(checkboxEl, inputElCek);
        });
    });
});

/**
 * mengambil value attribute action dari form filter beserta params
 * untuk disematkan ke attribute href pada tag a.previewBtn
 **/

function getFormAction(e) {
    $(this).attr('href', form.attr('action')+'?'+form.serialize()+'&type='+$(this).attr('data-type'))
}

//printPreview
function onPreview(e) {
    let form = $('form#filter');
    let params = form.serialize()+'&type='+$(this).attr('data-type');
    getRes(form.attr('action'), params, $(this).attr('data-type'));
}

function onFilterForm(e) {
    e.preventDefault();
    // console.log(e.cancelable);
    // return false if form still have some validation errors
    if (form.find('.has-error').length)
    {
        console.log(form);
    }
    getRes(url = form.attr('action'), data = form.serialize());
    e.stopPropagation();
}

function getRes(url = '', data = '', type = '') {
    // printWrapper.hide();
    if(!type) loader.show();
    $.ajax({
        type    : 'POST',
        url     : url,
        data    : data,
        success: function (data, textStatus, fetch) {
            // printWrapper.show();
            if(!type) {
                loader.hide(); printWrapper.html(data);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('An error occured!');
            alert('Error in ajax request');
            loader.hide();
        }
    });
}

/**
 * Fungsi Radio Option untuk memilih field,
 * attribute data-ropt pada field == attribute value pada radio button
 * adalah penghubung antara field dengan radio button,
 * berlaku juga multi radio button dengan name yang berbeda
 **/
function radioOptions(radioEL, inputEl) {
    radioEL.each( (i,radio)=> {
        inputEl.each((i2,input)=>{
            if($(radio).prop('checked') && $(radio).val() == $(input).attr('data-ropt')) {
                $(input).prop('disabled', null);
                $($(input).siblings('.kv-field-separator')).removeAttr('disabled');
                $($(input).siblings('.kv-date-picker')).removeAttr('disabled');
                $($(input).siblings('.kv-date-remove')).removeAttr('disabled');
            } else if ($(radio).val() == $(input).attr('data-ropt')) {
                $(input).val('');
                $(input).attr('disabled', true);
                $($(input).siblings('.kv-field-separator')).attr('disabled', true);
                $($(input).siblings('.kv-date-picker')).attr('disabled', true);
                $($(input).siblings('.kv-date-remove')).attr('disabled', true);
            }
        });
    });
}

/**
 * Fungsi CheckBox Option untuk memilih field atau sebaliknya,
 * attribute data-copt pada field == attribute value pada checkbox
 * adalah penghubung antara field dengan checkbox,
 * attribute data-copt-reverse pada element checkbox
 * akan membuat fungsi CheckBox Option berlaku sebaliknya
 * bila memiliki value 1
 **/
function checkboxOptions(checkboxEL, inputEl) {
    checkboxEL.each( (i,checkbox)=> {
        inputEl.each((i,input) => {
            if($(checkbox).attr('data-copt-reverse') == true) {
                if($(checkbox).val() == $(input).attr('data-copt') && $(checkbox).prop('checked')) {
                    if($(input).attr('type') != 'button') {
                        $(input).val(null).trigger('change').trigger('select2:unselecting');
                        $(input).attr('disabled', true);
                    } else {
                        $(input).attr('disabled', true);
                    }
                    $($(input).siblings('.kv-field-separator')).attr('disabled', true);
                    $($(input).siblings('.kv-date-picker')).attr('disabled', true);
                    $($(input).siblings('.kv-date-remove')).attr('disabled', true);
                } else if ($(checkbox).val() == $(input).attr('data-copt')) {
                    $(input).prop('disabled', null);
                    $($(input).siblings('.kv-field-separator')).removeAttr('disabled');
                    $($(input).siblings('.kv-date-picker')).removeAttr('disabled');
                    $($(input).siblings('.kv-date-remove')).removeAttr('disabled');
                }
            } else {
                if($(checkbox).val() == $(input).attr('data-copt') && $(checkbox).prop('checked')) {
                    $(input).prop('disabled', null);
                    $($(input).siblings('.kv-field-separator')).removeAttr('disabled');
                    $($(input).siblings('.kv-date-picker')).removeAttr('disabled');
                    $($(input).siblings('.kv-date-remove')).removeAttr('disabled');
                } else if ($(checkbox).val() == $(input).attr('data-copt')) {
                    if($(input).attr('type') != 'button') {
                        $(input).val(null).trigger('change').trigger('select2:unselecting');
                        $(input).attr('disabled', true);
                    } else {
                        $(input).attr('disabled', true);
                    }
                    $($(input).siblings('.kv-field-separator')).attr('disabled', true);
                    $($(input).siblings('.kv-date-picker')).attr('disabled', true);
                    $($(input).siblings('.kv-date-remove')).attr('disabled', true);
                }
            }
        });
    });
}