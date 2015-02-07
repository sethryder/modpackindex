$(document).ready(function() {
    if (location.hash.length)
    {
        var activeTab = $('[href=' + location.hash + ']');
        activeTab.tab('show');
    }
    // add a hash to the URL when the user clicks on a tab
    $('a[data-toggle="tab"]').on('click', function(e) {
        history.pushState(null, null, $(this).attr('href'));
    });
    // navigate to a tab when the history changes
    window.addEventListener("popstate", function(e) {
        //var activeTab = $('[href=' + location.hash + ']');
        if (location.hash.length) {
            var activeTab = $('[href=' + location.hash + ']');
            activeTab.tab('show');
        } else {
            $('.nav-tabs a:first').tab('show');
        }
    });
});