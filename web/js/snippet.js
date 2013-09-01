function setError(input)
{
    $(input).parents('.form-group').addClass('has-error');
}

function setNoError(input)
{
    $(input).parents('.form-group').removeClass('has-error');
}

$(function() {
    $("#tags_list").tagHandler({
        availableTags: available_tags,
        assignedTags: assigned_tags,
        autocomplete: true,
        allowAdd: true,
        allowEdit: true,
        sortTags: true,
        afterAdd: function(tag) {
            $('#tags').val($('#tags').val() + tag + ',');
        },
        afterDelete: function(tag) {
            $('#tags').val($('#tags').val().replace(tag + ',', ''));
        }
    });

    $('form').submit(function(){
        // Name, code and type is required
        error = false;

        if ($.trim($('#name').val()) == '') {
            setError('#name');
            error = true;
        } else {
            setNoError('#name');
        }

        if ($.trim($('#value').val()) == '') {
            setError('#value');
            error = true;
        } else {
            setNoError('#value');
        }

        if ($('#category_id').val() == '') {
            setError('#category_id');
            error = true;
        } else {
            setNoError('#category_id');
        }

        return !error;
    })
});