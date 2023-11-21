var url = 'http://prueba.test/'
window.addEventListener("load", function(){
    $('.btn-like').css('cursor', 'pointer');
    $('.btn-dislike').css('cursor', 'pointer');

    //boton de like
    function like(){
        $('.btn-like').unbind('click').click(function(){
            $(this).addClass('btn-dislike').removeClass('btn-like');
            $(this).attr('src', url+'/img/hearts-64.png');

            $.ajax({
                url: url+'/like/'+$(this).data('id'),
                type:'GET',
                success: function(response){
                    if(response.like){
                        console.log('diste like');
                    }else{
                        console.log('error al dar like')
                    }
                }
            });

            dislike();
        });
    }
    like();

    //boton de dislike
    function dislike(){
        $('.btn-dislike').unbind('click').click(function(){
            $(this).addClass('btn-like').removeClass('btn-dislike');
            $(this).attr('src', url+'/img/favorite-4-64.png');


            $.ajax({
                url: url+'/dislike/'+$(this).data('id'),
                type:'GET',
                success: function(response){
                    if(response.like){
                        console.log('diste dislike');
                    }else{
                        console.log('error al dar dislike')
                    }
                }
            });


            like();
        });
    }
    dislike();

    //buscador
    $('#buscador').submit(function(){
        $(this).attr('action',url+'/gente/'+$('#buscador #search').val());
    })
    
});