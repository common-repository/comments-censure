jQuery(document).ready(function ($) {

    $('.cc-options .nav-tab').click(function (e) {
        var tab = $(this).attr('id');
        $('.nav-tab').removeClass('nav-tab-active');
        $('.tab-content').removeClass('cc-show');
        $(this).addClass('nav-tab-active');
        $('.' + tab + '-content').addClass('cc-show');
        if (tab == 'tab-words') {
            $('.cc-search-words-form').addClass('cc-show');
            $('.cc-search-words').removeClass('cc-hidden');
        } else {
            $('.cc-search-words-form').addClass('cc-hidden');
            $('.cc-search-words-form').removeClass('cc-show');
        }
        e.preventDefault();
    });

    $('button#ccAddMore').click(function () {
        var newRow = $('.cc-clone .cc-row').clone();
        $('.cc-words-wrapper').append(newRow);
    });

    $('.cc-remove-all').click(function (e) {
        if (!confirm(ccJsObj.removeAll)) {
            e.preventDefault();
            return false;
        }
    });

    $(document).delegate('.cc-remove', 'click', function (e) {
        var btn = $(this);
        var id = getId(btn);
        var data = 'id=' + id;
        var ccAjaxRemove = ссGetAjaxObject(ccJsObj.actionRemoveWord, data);
        ccAjaxRemove.done(function (resp) {
            try {
                btn.parents('.cc-row').fadeOut(500, function () {
                    $(this).remove();
                });
            } catch (e) {
                console.log(e);
            }
        });
    });

    function getId(btn) {
        var matches = btn.attr('id').match(/-(\d+)$/);
        var id = 0;
        if (matches != null) {
            id = matches[1];
        }
        return id;
    }

    $('.cc-btn-import-options').click(function (e) {
        var data = $('.cc-import-options-area').val();
        if ($.trim(data) == 0) {
            alert(ccJsObj.importOptionsEmpty);
            e.preventDefault();
            return false;
        }

        if (confirm(ccJsObj.importOptions)) {
            $('#cc-import-options-loading').css('display', 'inline-block');
            var ccImportOptions = ссGetAjaxObject(ccJsObj.actionImportOptions, data);
            ccImportOptions.done(function (resp) {
                alert(resp);
                $('#cc-import-options-loading').css('display', 'none');
                setTimeout(reloadPage, 1000 * 0.5);
            });
        }
    });

    $('.cc-btn-import-words').click(function (e) {
        var data = $('.cc-import-words-area').val();
        if ($.trim(data) == 0) {
            alert(ccJsObj.importWordsEmpty);
            e.preventDefault();
            return false;
        }

        if (confirm(ccJsObj.importWords)) {
            $('#cc-import-words-loading').css('display', 'inline-block');
            var ccImportWords = ссGetAjaxObject(ccJsObj.actionImportWords, data);
            ccImportWords.done(function (resp) {
                alert(resp);
                $('#cc-import-words-loading').css('display', 'none');
                setTimeout(reloadPage, 1000 * 0.5);
            });
        }
    });

    showHideGlobalReplacement();
    $('.cc-global-replacement').change(function () {
        showHideGlobalReplacement();
    });

    function showHideGlobalReplacement() {
        if ($('#globalReplacement').is(':checked')) {
            $('.cc-replace-custom').attr('disabled', 'disabled');
            $('#globalReplacementWrap').show();
            $('.cc-global-replacement-image').show();
        } else {
            $('.cc-replace-custom').removeAttr('disabled');
            $('#globalReplacementWrap').hide();
            $('.cc-global-replacement-image').hide();
        }
    }

    function reloadPage() {
        location.reload(true);
    }

    $('.ccSaveOptions').click(function () {
        $('.cc-col-replace .cc-replace').removeAttr('disabled');
    });

    $('.cc-search-words-button').click(function (e) {
        e.preventDefault();
        var currentButton = $(this);
        var parent = currentButton.parents('.cc-search-words');
        var currentInput = $('.cc-search-words-input', parent);
        var searchText = $.trim(currentInput.val());
        if (searchText.length > 0) {
            currentButton.next('img').css('display', 'block');
            var d = ('s=' + searchText);
            var adminSearchWords = ссGetAjaxObject(ccJsObj.actionSearchWords, d);
            adminSearchWords.done(function (response) {
                try {
                    currentButton.next('img').css('display', 'none');
                    var obj = $.parseJSON(response);
                    if (obj.code == 1) {
                        $('.cc-words-wrapper').html(obj.data);
                        $('.cc-pages').hide();

                    } else {
                        alert(ccJsObj.searchNoResult);
                    }
                } catch (e) {
                    console.log(e);
                }
            });
        } else {
            alert(ccJsObj.searchTextLength);
        }
        return false;
    });

    function ссGetAjaxObject(action, data) {
        return $.ajax({
            type: 'POST',
            url: ccJsObj.ccAjaxUrl,
            data: {
                ccAjaxData: data,
                action: action
            }
        });
    }
});