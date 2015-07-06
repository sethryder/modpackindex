function updatePackFinderModSelect() {
    var version = $("#mc_version option:selected").text();
    var modSelect = $('select[name="mods[]"]');
    version = version.replace(/\./g, "-");


    if (version == "All") {
        modSelect.attr('data-placeholder', 'Select a specific Minecraft version to search with mods');
        modSelect.empty();
        modSelect.attr('disabled', 'disabled');
        modSelect.trigger("chosen:updated");
    } else {
        $.getJSON("/api/select/mods/" + version + ".json", function (data) {
            modSelect.empty();
            $.each(data, function () {
                $('select[name="mods[]"]').append('<option value="' + this.value + '">' + this.name + '</option>');
            });
            modSelect.removeAttr('disabled');
            modSelect.attr('data-placeholder', 'Select Mods');
            modSelect.trigger("chosen:updated");
        });
    }
}

$("#mc_version").ready(function () {
    var version = $("#mc_version option:selected").text();
    var fromPost = $("#mc_version").hasClass("from-post");

    if (version != "All" && !fromPost) {
        updatePackFinderModSelect();
    }
});

$("#mc_version").change(function () {
    updatePackFinderModSelect();
});