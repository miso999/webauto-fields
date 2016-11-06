/**
 * Created by miso on 3. 11. 2016.
 */


function createInput(counter) {
    var newTextBoxDiv = jQuery(document.createElement('div')).attr("id", 'TextBoxDiv' + counter);
    newTextBoxDiv.after().html('<input type="text" value="Názov" name="label' + counter + '">' +
        ' :  <input type="text" name="textbox' + counter + '" id="textbox' + counter + '" value="" >' +
        ' <button role="presentation" type="button" class="removeButton" id="' + counter + '" >x</button>');
    newTextBoxDiv.appendTo("#webauto-fields");
}

jQuery(document).ready(function ($) {
    // Set counter to always start from 1
    var counter = 1;
    $("#addButton").click(function () {
        // While input name already exists, increase counter
        while ($('input[name="label' + counter + '"]').length) {
            counter++;
        }
        //Then create new input field
        createInput(counter);
    });


    $(".removeButton").live('click', function () {
        jQuery("#TextBoxDiv" + this.id).remove();
        $("#deleted").append('<input type="hidden" name="label' + this.id + '" value="DeleteThisCustomMeta">');
    });

});

