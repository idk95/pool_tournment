$(document).ready( function(){
    var id = getUrlParameter('id');
    read_one();
    read_matches();

    function read_one() {
        $.post( "../api/people/read_one.php?id="+id, function( data ) {
            $('#info .name').append(data.name);
            $('#info .points').append(data.points);
            $('#info .balls').append(data.balls);
        });
    };

    function read_matches() {
        $.get( "../api/matches/read.php?person_id="+id, function( data ) {
            data.records.forEach(element => {
                $('#matches tbody').append('<tr>\
                <td><a href="../match/index.html?id='+element.id+'">'+element.date+'</a></td>\
                <td><a href="../person/index.html?id='+element.solids_id+'">'+element.solids_name+'</a></td>\
                <td class="text-right">'+element.solids_left+'</td><td>-</td>\
                <td>'+element.stripes_left+'</td>\
                <td><a href="../person/index.html?id='+element.stripes_id+'">'+element.stripes_name+'</a></td>\
                </tr>');
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