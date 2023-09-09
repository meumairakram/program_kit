$(document).ready(function(){   

    $("#searchInput").on("click", function() {
        var value = $('#search').val().toLowerCase();
        $("#myTable tr").filter(function() {
            var id = $(this).find("td:eq(0)").text().toLowerCase(); // Search by the first column (id)
            var name = $(this).find("td:eq(1)").text().toLowerCase(); // Search by the second column (name)
            $(this).toggle(id.indexOf(value) > -1 || name.indexOf(value) > -1);
        });
    });

    $("#search").on("input", function() {
        var value = $('#search').val().toLowerCase();
        if (value === "") {
            $("#myTable tr").show();
        }
    });


});


jQuery(function($) {

    $('.website_url').change(autoFillAjaxUrl);

    function autoFillAjaxUrl(e) {

        var website_url = $(e.target).val();
            
        var websiteUrl = new URL(website_url);


        $('.ajax_url').val(websiteUrl.origin + "/wp-admin/admin-ajax.php")
    
    }



    var fieldsToWatch = [".website_url",".ajax_url", ".authentication_key"];

    function handleWebsiteAuthentication(e) {

        var website_url = $('.website_url').val();
        var ajax_url = $('.ajax_url').val();

        var auth_key = $('.authentication_key').val();

        var urlToAuthenticate = ajax_url + "?action=pseo_validate_auth_key" + "&auth_key="  + auth_key;

        if(website_url == "" || ajax_url == "" || auth_key == "") {
            
            return false;
        
        }


    
        $.ajax({

            method:"GET",
            url: urlToAuthenticate,
            success: function(response) {
                
                if(response.success) {
                    $('.verification_status').html("VERIFIED");
                    $('.save-button-wrap').removeClass('d-none');
                    $('.website-verified').val('1');


                } else {
                    $('.verification_status').html("FAILED");
                }
                // console.log(response);
            
            },
            error: function(error) {

                $('.verification_status').html("FAILED");

                console.log(error);
            
            }
        });
            
            
    }


    fieldsToWatch.forEach(function(item) {

        $(item).change(handleWebsiteAuthentication);
    
    });

})