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

    $('input[name="csv_file"]').change(function(e) {

        var formValues = new FormData();

       
        formValues.append('csv_file', e.target.files[0]);
        formValues.append('name',"Umair");

        jQuery.ajax({
            method: "POST",
            url: "/api/csv-preview",
            data: formValues,
            processData: false,
            contentType: false, // Set contentType to false to allow the browser to set the correct content type for the FormData object

            success: function(res) {


                if(!res.success ) {

                    if(!$('.data-preview').hasClass('d-none')) {
                        
                        $('.data-preview').addClass('d-none');
                        
                    }

                    $('.data-preview thead tr').html("");
                    $('.data-preview tbody tr').html("");
                }


                var headers = res.data.headers;

                var rows = res.data.preview_rows;


                if(headers.length < 1){
                    return false;
                }


                $('.data-preview').removeClass('d-none');

                $('.data-preview thead tr').html("");
                $('.data-preview tbody tr').html("");


                var headersToPreview = headers.length < 7 ? headers : headers.splice(0, 6);

                const csvHeadersSelect = $('#csvHeaders');
                csvHeadersSelect.empty();

                headersToPreview.forEach(function(item, index) {
                    $('.data-preview thead tr').append(`<th>${item}</th>`);
                    csvHeadersSelect.append($(`<option value="${item}">${item}</option>`));
                });

                
                rows.forEach(function(item, index) {

                    elemRow = $('<tr></tr>');

                    item.forEach(function(rowItem, rowIndex) {
                        elemRow.append(`<td>${rowItem}</td>`)
                    })
                    
                    
                    $('.data-preview tbody').append(elemRow);

                    // elemRow
                })
                const randomUniqueNumber = generateRandomUniqueNumber();
                console.log(randomUniqueNumber);
                $('.primaryKey').append('"' + randomUniqueNumber +'"');
                $('#primary_id').val(randomUniqueNumber);
            }
        })

    })

})

function generateRandomUniqueNumber() {
    const timestamp = new Date().getTime(); // Current timestamp in milliseconds
    const randomPart = Math.floor(Math.random() * 100000000); // Random number between 0 and 99999999
    const uniqueNumber = parseInt(`${timestamp}${randomPart}`, 10) % 100000000; // Ensure 8 digits
    return uniqueNumber.toString().padStart(8, '0'); // Pad with zeros if necessary
}

// handle sections based on selected type
document.getElementById('selectType').addEventListener('change', function(e) 
{
    var selectedValue = $(this).val();
    if(selectedValue == 'csv'){
        $('.uploadCSV').removeClass('d-none');
        $('.uploadGoogleSheets').addClass('d-none');
    }
    else if(selectedValue == 'google_sheet'){
        $('.uploadGoogleSheets').removeClass('d-none');
        $('.uploadCSV').addClass('d-none');
    }
});

$('.gsheets').on('click', function(e) {
    jQuery.ajax({
            method: "GET",
            url: "/sheets",
            success: function(response) {
                console.log(response);
                var sheetId = response.spreadsheetId; 
                var sheetName = response.sheetName; 

                var formValues = new FormData();
                formValues.append('sheet_id', JSON.stringify(sheetId));
                formValues.append('sheet_name', sheetName);

                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                jQuery.ajax({
                    method: "POST",
                    url: "/store-sheet-data",
                    data: formValues,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log(response);
                    }
                });
            }
    });

}); 


// handle sections based on selected type
document.getElementById('selectType').addEventListener('change', function(e) 
{
var selectedValue = $(this).val();
if(selectedValue == 'csv'){
    $('.uploadCSV').removeClass('d-none');
    $('.uploadGoogleSheets').addClass('d-none');
}
else if(selectedValue == 'google_sheet'){
    $('.uploadGoogleSheets').removeClass('d-none');
    $('.uploadCSV').addClass('d-none');
}
});

$(document).ready(function() {
    var selectedValue = $('#selectType').val(); 
    if (selectedValue === 'csv') {
        $('.uploadCSV').removeClass('d-none');
        $('.uploadGoogleSheets').addClass('d-none');
    } else if (selectedValue === 'google_sheet') {
        $('.uploadGoogleSheets').removeClass('d-none');
        $('.uploadCSV').addClass('d-none');
    }
});