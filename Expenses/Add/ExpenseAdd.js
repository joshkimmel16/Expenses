var RowCount = 1;
$(document).ready(function () {
            
    //disable true submit button and replace with an imposter
    $("#sub").prop({'disabled':true}).hide().parent().append("<input type=\"button\" value=\"Submit\" id=\"fakesubmit\" />");
            
    //EVENT HANDLER: add a new row to the table when the user clicks the add button
    $("#add").on('click', function () {
        RowCount++;
        var HTML = "<tr><td><select id=\"B"+RowCount+"\" class=\"form-control\"><option>Rent</option><option>LADWP</option><option>SoCal Gas</option><option>ATT</option><option>TWC</option><option>Visa</option><option>Car Gas</option><option>Groceries</option><option>Lunch</option><option>Dinner</option><option>Geico</option><option>Bars</option><option>Needs</option><option>401K</option><option>Health</option><option>Gym</option><option>Laundry</option><option>Brunch</option></select></td><td><span class=\"inline\">$</span><input type=\"text\" id=\"A"+RowCount+"\" class=\"form-control inline\"/></td><td><textarea rows=\"3\" cols=\"30\" id=\"D"+RowCount+"\" class=\"form-control\"></textarea></td><td><input type=\"date\" id=\"W"+RowCount+"\" class=\"form-control\"/></td><td class=\"hidden\"><img src=\"Delete.png\" class=\"del\" id=\""+RowCount+"\" /></td></tr>";
        
        $("tbody").append(HTML);
        
        $("td:nth-child(5)").removeClass("hidden");
    });
    
    //EVENT HANDLER: remove the given row and update ID's when a delete button is clicked
    $("table").on('click', ".del", function () {
        RowCount--;
        var ID = $(this).attr('id');
        $(this).closest('tr').remove();
        
        var Bottom = parseInt(ID)+1;
        var Top = RowCount+1;
        for (var i=Bottom; i<=Top; i++)
        {
            var NewID = i-1;
            $("#B"+i).attr('id', 'B'+NewID);
            $("#A"+i).attr('id', 'A'+NewID);
            $("#D"+i).attr('id', 'D'+NewID);
            $("#W"+i).attr('id', 'W'+NewID);
            $("#"+i).attr('id', NewID);
        }
        
        if (RowCount == 1)
        {
            $("td:nth-child(5)").addClass("hidden");     
        }
    });
            
    //EVENT HANDLER: check the inputs, if all good generate XML string from table for server
    $("#fakesubmit").on('click', function () {
       if (validateInputs())
       {
           var XML = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
           for (var i=1; i<=RowCount; i++)
            {
                var Bucket = $("#B"+i);
                var Amount = $("#A"+i);
                var Desc = $("#D"+i);
                var Date = $("#W"+i);
                XML += serializeRow(Bucket.val(), Amount.val(), Desc.val(), Date.val());
            }

           $("#XML").val(XML);
           
           $("#sub").prop({'disabled':false}).click();
       }
       else
       {
           console.log("No!!");
       }
    });

});
        
    //ensure non-empty descriptions, dates and valid dollar inputs before submitting
    function validateInputs ()
    {
        var Check = true;

        for (var i=1; i<=RowCount; i++)
        {
            var Amount = $("#A"+i);
            var Desc = $("#D"+i);
            var Date = $("#W"+i);

            if (Desc.val() == "")
            {
               Check = false;
               Desc.addClass("Error");     
            }  
            else
            {
               Desc.removeClass("Error");   
            }  
            if (!checkAmount(Amount.val()))
            {
                Check = false;
                Amount.addClass("Error");   
            }
            else
            {
                Amount.removeClass("Error");
            }
            if (Date.val() == "")
            {
                Check = false;
                Date.addClass("Error");
            }
            else
            {
                Date.removeClass("Error");
            }
        }

        return Check;
    }
        
    //use regular expression to check for a valid dollar amount
    function checkAmount (Am)
    {
        var pattern = /^[123456789]\d*\.\d{2}$/;
        return pattern.test(Am);
    }

    //create an XML row for an expense
    function serializeRow (B, A, D, W)
    {
        var XML = "<expense><bucket>"+B+"</bucket><amount>"+A+"</amount><desc>"+D+"</desc><date>"+W+"</date></expense>";
        return XML;
    }