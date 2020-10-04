$(document).ready( function(){
    var id = getUrlParameter('id');
    read_match();
    get_score();

    $('#submitScore').submit( function(e) {

        var arr = {"person":[]};
        var input_person = $('input[name=person]').serializeArray();
        input_person.forEach(name => {
            arr["person"].push(name['value']);
        });
        
        $.post( 'api/people/create.php', JSON.stringify(arr), function(data) {
            var ids = data.result;

            while (ids.length != 0) {
                var person_1 = ids.sort(function() { return 0.5 - Math.random();}).pop();
                var person_2 = ids.sort(function() { return 0.5 - Math.random();}).pop();

                $.post( 'api/matches/create.php', JSON.stringify({"person_1":person_1, "person_2":person_2, "date":$('#date').val()}) );
            }

            read_ranks();

        }).fail( function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(errorThrown);
        });

    });

    function read_match() {
        $.post( "../api/matches/read_one.php",JSON.stringify({"id": id}), function( data ) {
            $('#person_a .name').append(data.solids_name);
            $('#person_a .points').append(data.solids_points);
            $('#score_a').val(data.solids_id);
            $('label[for=score_a]').text(data.solids_name);
            $('#person_b .name').append(data.stripes_name);
            $('#person_b .points').append(data.stripes_points);
            $('#score_b').val(data.stripes_id);
            $('label[for=score_b]').text(data.stripes_name);
        });
    }

    function get_score() {
        $.post( "../api/scores/get_score.php",JSON.stringify({"matches_id": id}), function( data ) {
            console.log(data);
        }).fail( function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR.status);
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