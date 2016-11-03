/**
 * Created by miso on 3. 11. 2016.
 */


jQuery(document).ready(function ($) {

    var counter = 2;

    $("#addButton").click(function () {


        var newTextBoxDiv = $(document.createElement('div'))
            .attr("id", 'TextBoxDiv' + counter);

        newTextBoxDiv.after().html('<input type="text" value="Label #' + counter + '" name="label'+ counter +'">' +
            ' :  <input type="text" name="textbox' + counter +
            '" id="textbox' + counter + '" value="" >');

        newTextBoxDiv.appendTo("#webauto-fields");


        counter++;
    });

    $("#removeButton").click(function () {
        if (counter == 1) {
            alert("No more textbox to remove");
            return false;
        }

        counter--;

        $("#TextBoxDiv" + counter).remove();

    });


});
