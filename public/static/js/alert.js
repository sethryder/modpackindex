$('.close').click(function(){

    var alert_id = $(this).data('alertid');

    $.ajax({
        type: "POST",
        data: {'alert_id': alert_id},
        url: '/user/alert/dismiss',
        success: function() {}
    });
});
