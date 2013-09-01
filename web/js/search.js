function addTagInUrl(tag)
{
    var url = document.location.href;

    if (url.match(/search\/tags\/[a-z0-9,-]+$/)) {
        url += ',' + tag;
    } else if (url.match(/search\/[a-z0-9-]+\/tags\/[a-z0-9,-]+$/)) {
        url += ',' + tag;
    } else if (url.match(/search\/[a-z0-9-]+$/)) {
        url += '/tags/' + tag;
    } else if (url.match(/snippet\/[0-9]+$/)) {
        url = url.replace(/snippet\/[0-9]+$/, 'search/tags/' + tag);
    } else if (url.match(/\/$/)) {
        url += 'search/tags/' + tag;
    }

    return url;
}

function removeTagInUrl(tag)
{
    var url = document.location.href;

    var regexp = new RegExp(',' + tag);
    if (url.match(regexp)) {
        return url.replace(regexp, '');
    }

    regexp = new RegExp('/' + tag + ',');
    if (url.match(regexp)) {
        return url.replace(regexp, '/');
    }

    regexp = new RegExp('/search/[a-z0-9-]+/tags/' + tag);
    if (url.match(regexp)) {
        return url.replace(/\/tags\/.*/, '');
    }

    regexp = new RegExp('/search/tags/' + tag);
    if (url.match(regexp)) {
        return url.replace(regexp, '/');
    }
}

$(function() {
    $("#tags_list").tagHandler({
        availableTags: available_tags,
        assignedTags: assigned_tags,
        autocomplete: true,
        allowAdd: false,
        allowEdit: true,
        sortTags: true,
        afterAdd: function(tag) {
            if (!tag) {
                return;
            }
            document.location.href = addTagInUrl(tag);
        },
        afterDelete: function(tag) {
            if (!tag) {
                return;
            }
            document.location.href = removeTagInUrl(tag);
        }
    });

    $('section .tags a').click(function(){
        var tag = $(this).attr('data-tag');
        if (tag) {
            document.location.href = addTagInUrl(tag);
        }

        return false;
    });
});