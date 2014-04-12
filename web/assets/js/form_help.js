/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$('#start').change(function() {
    var start = $("#start").val(); 
    var pieces = start.split("-");
    var startDate = new Date(pieces[0], pieces[1]-1, pieces[2]);
    var endDate = new Date(startDate);

    endDate.setDate(startDate.getDate() + 14);   
    $('#stop').val(endDate);
});

