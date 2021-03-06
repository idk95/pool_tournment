$(document).ready( function(){
    var id = getUrlParameter('id');
    read_match();
    get_score();

    $("#createForm img.img-check").click(function(){
        if(!$(this).hasClass('img-disabled')){
            $(this).toggleClass("check");
        }
    });

    var order_balls_check = [];
    $("[type=checkbox]").click(function() {
        var idx = order_balls_check.indexOf($(this).val());
        
        if (idx !== -1) {
            order_balls_check.splice(idx, 1);
        } else{
            order_balls_check.push($(this).val());
        }
    });
    
    $('#submitScore').submit( function(e) {
        e.preventDefault();

        var total_balls = $('#table .img-disabled').length;

        var person_id = $('input[name="person_score"]:checked').val();
        var other_id = $('input[name="person_score"]:not(:checked)').val();

        var person_hash = $('.id_'+person_id).closest('div').attr('id');
        var person_balls = $('#'+person_hash+' .img-disabled').length;
        var other_hash = $('.id_'+other_id).closest('div').attr('id');
        var other_balls = $('#'+other_hash+' .img-disabled').length;

        order_balls_check.forEach(ball => {

            if(total_balls == 0 && order_balls_check.indexOf(ball) == 0){
                if (ball > 8) {
                    $.post( '../api/matches/update.php', JSON.stringify({"stripes_id":person_id,"id":id, "solids_id":other_id}));
                } else if (ball < 8) {
                    $.post( '../api/matches/update.php', JSON.stringify({"stripes_id":other_id,"id":id, "solids_id":person_id}));
                } else {
                    $.post( '../api/matches/update.php', JSON.stringify({"winner":other_id,"id":id}));
                    $.post( '../api/people/update.php', JSON.stringify({"points":parseInt($('.id_'+other_id).text())+3,"id":other_id}));
                    $.post( '../api/people/update.php', JSON.stringify({"points":parseInt($('.id_'+person_id).text())+1,"id":person_id}));
                }
            }

            if (ball == 8 ) {
                if (person_balls == 7) {
                    $.post( '../api/matches/update.php', JSON.stringify({"winner":person_id,"id":id}));
                    $.post( '../api/people/update.php', JSON.stringify({"points":parseInt($('.id_'+person_id).text())+3,"id":person_id}));
                    $.post( '../api/people/update.php', JSON.stringify({"points":parseInt($('.id_'+other_id).text())+1,"id":other_id}));
                } else{
                    $.post( '../api/matches/update.php', JSON.stringify({"winner":other_id,"id":id}));
                    $.post( '../api/people/update.php', JSON.stringify({"points":parseInt($('.id_'+other_id).text())+3,"id":other_id}));
                    $.post( '../api/people/update.php', JSON.stringify({"points":parseInt($('.id_'+person_id).text())+1,"id":person_id}));
                }
            }

            if ($('#person_a .points').hasClass('id_'+person_id)) {
                if (ball < 8 ) {
                    $.post( '../api/matches/update.php', JSON.stringify({"id":id,"solids_left":7-parseInt(person_balls)-1}));
                } else{
                    $.post( '../api/matches/update.php', JSON.stringify({"id":id,"stripes_left":7-parseInt(other_balls)-1}));
                }
            } else if ($('#person_b .points').hasClass('id_'+person_id)) {
                if (ball < 8 ) {
                    $.post( '../api/matches/update.php', JSON.stringify({"id":id,"solids_left":7-parseInt(other_balls)-1}));
                } else{
                    $.post( '../api/matches/update.php', JSON.stringify({"id":id,"stripes_left":7-parseInt(person_balls)-1}));
                }
            }
            $.post( '../api/scores/create.php', JSON.stringify({"match_id":id,"people_id":person_id,"ball":ball}));
        });

        if ($('div.checkbox-group.required .check').length == 0) {
            $.post( '../api/matches/update.php', JSON.stringify({"absencent":person_id,"id":id,"winner":other_id}));
            $.post( '../api/people/update.php', JSON.stringify({"points":parseInt($('.id_'+other_id).text())+3,"id":other_id}));
        }
        location.reload(true);
    });

    function read_match() {
        $.post( "../api/matches/read_one.php",JSON.stringify({"id": id}), function( data ) {
            $('#person_a .name').append(data.solids_name);
            $('#person_a .points').append(data.solids_points);
            $('#person_a .points').addClass('id_'+data.solids_id);
            $('#score_a').val(data.solids_id);
            $('label[for=score_a]').append(data.solids_name);
            $('#person_b .name').append(data.stripes_name);
            $('#person_b .points').append(data.stripes_points);
            $('#person_b .points').addClass('id_'+data.stripes_id);
            $('#score_b').val(data.stripes_id);
            $('label[for=score_b]').append(data.stripes_name);

            if (data.winner != null) {
                $('#table button').hide();
                if (data.winner == data.solids_id) {
                    $('.bg-pool h3').text(data.solids_name+' is the winner!!!');    
                } else{
                    $('.bg-pool h3').text(data.stripes_name+' is the winner!!!');    
                }
                
            }
        });
    }

    function get_score() {
        $.post( "../api/scores/get_score.php?matches_id="+id, function( data ) {
            data.records.forEach(element => {
                $('input[name=ball_'+element.ball+']').prop( "disabled", true );
                $('#createForm img[alt="'+element.ball+'"]').removeClass( "img-check" ).addClass('img-disabled');
                $('#table img[alt="'+element.ball+'"]').addClass('img-disabled');

                $('.score_history').append('<p>\
                <b>'+element.name+'</b> score the ball <b>'+element.ball+'</b>\
                </p>');
            });
        });
    }

    function getUrlParameter(sParam) {
        var sPageURL = window.location.search.substring(1), sURLVariables = sPageURL.split('&'), sParameterName, i;
    
        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');
    
            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
            }
        }
    };
});