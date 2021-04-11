jQuery(document).ready(function () {
    var $collectionHolderж

    $collectionHolder = $('ul.synonym');
    $collectionHolder.data('index', $collectionHolder.find('input').length);
    $('body').on('click', '#add_synonym', function (e) {
        var $collectionHolderClass = $(e.currentTarget).data('collectionHolderClass');
        addFormToCollection($collectionHolderClass);
    })
    $collectionHolder.find('li').each(function () {
        addTagFormDeleteLink($(this));
    });


    $collectionHolder = $('ul.polygon');
    $collectionHolder.data('index', $collectionHolder.find('input').length);
    $('body').on('click', '#add_polygon', function (e) {
        var $collectionHolderClass = $(e.currentTarget).data('collectionHolderClass');
        addFormToCollection($collectionHolderClass);
    })
    $collectionHolder.find('li').each(function () {
        addTagFormDeleteLink($(this));
    });

});

function addTagFormDeleteLink($tagFormLi) {
    var $removeFormButton = $('<button type="button" class="btn btn-outline-secondary btn-sm del">X</button>');
    $tagFormLi.append($removeFormButton);
    $removeFormButton.on('click', function (e) {
        $tagFormLi.remove();
    });
}


function addFormToCollection($collectionHolderClass) {
    var $collectionHolder = $('.' + $collectionHolderClass);
    var prototype = $collectionHolder.data('prototype');
    var index = $collectionHolder.data('index');
    var newForm = prototype;
    newForm = newForm.replace(/__name__label__/g, index);
    newForm = newForm.replace(/__name__/g, index);
    $collectionHolder.data('index', index + 1);
    var $newFormLi = $('<li></li>').append(newForm);
    $collectionHolder.append($newFormLi)
}