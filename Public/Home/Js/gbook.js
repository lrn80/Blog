

$(function(){
    //我要留言
    $('.liu2').resize(function(){
        if($('.liu2').css('display')=='block'){
            $('.screen').lock();
        }
    })
    
    $('.liu1').click(function(){
        $('.liu2').css('display','block').animate({
            attr  : 'y',
            target: 50,
        });
        $('.screen').lock();
      
    })

    $('.close').click(function(){
        $('.liu2').css('display','none').animate({
            attr  : 'y',
            target: -50,
        });;
        $('.screen').unlock();
    })
})