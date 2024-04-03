$(document).ready(function(){
    $(document).on('change keyup', '#searchName, #searchCategory', function(e)
    {
        $searchName = $("#searchName").val();
        $searchCategory = $("#searchCategory").val();
        console.log($searchName);
        console.log($searchCategory);
        
        $request = $.ajax({
            method:"POST",
            url:"include/productProcess.php",
            data: {searchName: $searchName, searchCategory: $searchCategory},
        });
        
        $request.done(function(msg)
        {
            $("#products").fadeOut("slow", function()
            {
                $("#products").html(msg);
                $("#products").fadeIn("slow");
            });
            
        });
        
        $request.fail(function(jqXHR, textStatus)
        {
            $("#products").html("Request failed: " + textStatus);
        });
    });
});