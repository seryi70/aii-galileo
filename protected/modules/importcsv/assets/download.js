$(document).ready(function() {

     /* First Step: file upload */

    var button = $('input#importStep1'), interval;
    
    $.ajax_upload(button, {
        action : '/importcsv/default/upload',
        name : 'myfile',
        onSubmit : function(file, ext) {
            $("div#importCsvFirstStepResult").text('Loading...');
            this.disable();
        },
        onComplete : function(file, response) {
            this.enable();
            $("input#fileName").val(file);
            $("div#importCsvFirstStepResult").html(response);
        }
    });

    /* */
    
    /* Breadcrumbs */

    $("a#importCsvA2").click(function() {
        $("div#importCsvFirstStep").hide(500);
        $("div#importCsvSecondStep").show(500);
        $("div#importCsvThirdStep").hide(500);
        $("span#importCsvBread2").css({"display" : "none"});
    });

    /* */
});

/* Going to Second Step*/

function toSecondStep(uploadfile, delimiterFromFile, tableFromFile) {
       $("span#importCsvForFile").text(uploadfile);
       $("input#thirdFile").val(uploadfile);
       $("input#delimiter").val(delimiterFromFile);
       $("#table").val(tableFromFile);
       
       $("span#importCsvBread1").css({"display" : "inline"});
       $("div#importCsvSecondStep").show(500);
       $("div#importCsvFirstStep").hide(500);
}

/* Going toThird Step*/

function toThirdStep(content, delimiter, table) {
       $("span#importCsvForDelimiter").text(delimiter);
       $("span#importCsvForTable").text(table);
       $("input#thirdDelimiter").val(delimiter);
       $("input#thirdTable").val(table);

       $("span#importCsvBread2").css({"display" : "inline"});
       $("div#importCsvThirdStepPoles").html(content);
       $("div#importCsvThirdStep").show(500);
       $("div#importCsvSecondStep").hide(500);
}

/* Going to the end*/

function toEnd() {
       $("div#importCsvThirdStepPolesAndForm").hide(500);
       $("span#importCsvBread2").css({"display" : "none"});
}