$("input[type='checkbox']").change(function() {
    if ($(this).is(":checked")) {
        $("input[type='checkbox']").not(this).prop("checked", false);
    }
});