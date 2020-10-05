$(document).ready( function(){
    read_ranks();
    read_matches();
    var date = (new Date()).toISOString().split('T')[0];
    $('#date').attr('min', date);
    $('#date').val(date);

    $('#createForm').submit( function(e) {

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

    function read_ranks() {
        $.get( "api/people/read.php", function( data ) {
            number = 1;
            data.records.forEach(element => {
                $('#ranks tbody').append('<tr><th scope="row">'+number+'</th><td>'+element.name+'</td><td>'+element.points+'</td><td>'+element.balls_left+'</td></tr>');
    
                number +=1;
            });
        });
    }

    function read_matches() {
        $.get( "api/matches/read.php", function( data ) {
            data.records.forEach(element => {
                $('#matches tbody').append('<tr>\
                <td><a href="match/index.html?id='+element.id+'">'+element.date+'</a></td>\
                <td><a href="person/index.html?id='+element.solids_id+'">'+element.solids_name+'</a></td>\
                <td class="text-right">'+element.solids_left+'</td><td>-</td>\
                <td>'+element.stripes_left+'</td>\
                <td><a href="person/index.html?id='+element.stripes_id+'">'+element.stripes_name+'</a></td>\
                </tr>');
            });

            if (data.winner != null) {
            }
        });
    }
});