function noteOpen(title,width,height,url){
    layer.open({
        type: 2,
        title: title,
        maxmin: true,
        shadeClose: true,
        area : [width+'px' , height+'px'],
        content: url
    });
}
$('.refresh-button').click(function () {
    $('#iframe').attr('src', $('#iframe').attr('src'));
});
$('.switch-button').click(function () {
    if($('#menu').is(':hidden')){
        $('#menu').show();
    }else{
        $('#menu').hide();
    }
});
$('.left-sidebar .sidebar-nav li').each(function () {
    $(this).click(function () {
        var actionDom = $(this).find('a');
        if(!actionDom.hasClass('active')){
            actionDom.addClass('active');
            $(this).siblings().find('a').removeClass('active');
        }
        var tag = actionDom.attr('data-i');
        var secondDom = $('#second_'+tag);
        if(secondDom.is(':hidden')){
            secondDom.show();
            secondDom.siblings().hide();
        }
    });
});
$('.left-sidebar-second a').each(function () {
    $(this).click(function () {
        if(!$(this).hasClass('active')){
            $(this).addClass('active');
            $(this).siblings().removeClass('active');
            $(this).parent().parent().siblings().find('a').removeClass('active');
        }
    });
});