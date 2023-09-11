$(document).ready(function(){   

    $("#myInput").on("change", function() {
        var value = $(this).val().toLowerCase();
        $("#myTable tr").filter(function() {
            var status = $(this).find("td:eq(4)").text().toLowerCase(); // Index 4 corresponds to the "status" column
            $(this).toggle(status.indexOf(value) > -1);
        });
    });


    $("#searchInput").on("click", function() {
        var value = $('#search').val().toLowerCase();
        $("#myTable tr").filter(function() {
        var websiteUrl = $(this).find("td:eq(2)").text().toLowerCase(); // Index 2 corresponds to website_url column
        var title = $(this).find("td:eq(1)").text().toLowerCase(); // Index 1 corresponds to title column
        $(this).toggle(websiteUrl.indexOf(value) > -1 || title.indexOf(value) > -1);
        });
    });

    $("#search").on("input", function() {
        var value = $('#search').val().toLowerCase();
        if (value === "") {
            $("#myTable tr").show();
        }
    });


});
 
 
 // initialize window variable
    window.pkit = {};

    // website type
    document.getElementById('websiteType').addEventListener('change', function(e)
    {
            const selectedType = this.value;
            if (selectedType === '') {
                return;
            }

            const formData = new FormData();
            formData.append('_token', csrfToken);
            formData.append('type', selectedType);

            $.ajax({
                method: "POST",
                url: "/websites_type",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                
                    if (!response.success) {
                        console.error('Error: ', response.message);
                        $('#selectWebSite').html('<option value=""> </option>');
                        return;
                    }

                    const websiteSelect = $('#selectWebSite');
                    websiteSelect.empty();

                    const websites = response.websites;
                    websiteSelect.append('<option value=""> </option>');
                    websites.forEach(websites => {
                        websiteSelect.append(`<option value="${websites.id}">${websites.website_name} | ${websites.website_url}</option>`);
                    });

                    // Update the hidden input value with the selected website ID
                    websiteSelect.on('change', function() {
                        const selectedWebsiteId = $(this).val();
                        $('#selectWebSite').val(selectedWebsiteId);
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle the AJAX error if needed
                    console.error('AJAX Error:', textStatus, errorThrown);
                    $('#selectWebSite').html('<option value="">-- Select WebSite --</option>');
                }
            });
    });

    // geting post types by selecting website
    document.getElementById('selectWebSite').addEventListener('change', function(e)
    {
            $.ajax({
                method: "get",
                url: "/api/get_post_types",
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log(response)
                    if (!response.success) {
                        console.error('Error: ', response.message);
                        $('#postType').html('<option value=""> </option>');
                        return;
                    }

                    const postType = $('#postType');
                    postType.empty();

                    const type = response.data.post_types; // Corrected access to the array
                    postType.append('<option value=""> </option>');

                    type.forEach(typeItem => { // Use typeItem as an element in the forEach loop
                        postType.append(`<option value="${typeItem}">${typeItem}</option>`);
                    });
                    postType.on('change', function() {
                        const postTypeId = $(this).val();
                        // alert(postTypeId);
                        $('#postType').val(postTypeId);
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle the AJAX error if needed
                    console.error('AJAX Error:', textStatus, errorThrown);
                    $('#postType').html('<option value="">-- Select WebSite --</option>');
                }
            });
    });

    // geting template by post type
    document.getElementById('postType').addEventListener('change', function(e)
    {
            const selectedType = this.value;
            if (selectedType === '') {
                return;
            }

            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('post_type', selectedType);

            $.ajax({
                method: "POST",
                url: "/api/get_templates_by_type",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log(response)
                    if (!response.success) {
                        console.error('Error: ', response.message);
                        $('#template').html('<option value=""> </option>');
                        return;
                    }

                    const template = $('#template');
                    template.empty();

                    const get_templates_by_type = response.data.posts;
                    template.append('<option value=""> </option>');

                    get_templates_by_type.forEach(get_templates_by_type => {
                        template.append(`<option name="${get_templates_by_type.title}" value="${get_templates_by_type.id}">${get_templates_by_type.title}</option>`);
                    });

                    template.on('change', function() {
                        const templateId = $(this).val();
                        const templateName = $(this).find('option:selected').attr('name');
                        // alert(templateId);
                        $('#template').val(templateId);
                        $('#templateName').val(templateName);
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle the AJAX error if needed
                    console.error('AJAX Error:', textStatus, errorThrown);
                    $('#template').html('<option value="">-- Select WebSite --</option>');
                }
            });
    });


    // Template variables section
    const variableData = [];
    const templateTextArea = $('#templateTextArea');
    const mapDataFields = $('#mapDataFields');

    document.getElementById('template').addEventListener('change', function(e) {
        
        const selectedType = this.value;
        
        if (selectedType === '') {
            return;
        }

        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('post_id', selectedType);

        $.ajax({
            method: "POST",
            url: "/api/get_template_vars",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                
                if (!response.success) {
                    console.error('Error: ', response.message);
                    $('#tempVariables').val('');
                    return;
                }

                window.pkit.template_vars = response.data;


                if("template_vars" in window.pkit && "datasource_info" in window.pkit) {

                    createDataMap();
                }
                
            }


        });
    });



    const sourceData = [];
    const variableArrayData = [];
    const storeArrayData = [];
    // Existing data source
    var source_headers = [];
    document.getElementById('dataSource').addEventListener('change', function(e) {
        

        var selectedDSId = $(this).val();

        if(selectedDSId == "") {
            alert("Data source cannot be null");
            return ;
        }   

        const formData = new FormData();
        formData.append('ds_id', selectedDSId);

        $.ajax({
            url: '/api/get_datasource_mapping',
            data: formData,
            processData: false,
            contentType: false,
            method:"POST",
            success:function(res) {

                if(res.success) {
                
                    window.pkit.datasource_info = res.data;

                    if("template_vars" in window.pkit && "datasource_info" in window.pkit) {

                        createDataMap();
                    }
                    // create template variable fields
                
                } else {

                    alert("Whoops! there is a problem fetching this Data source.");
                    console.log("Problem with datasource information", res);
                
                }


                
                console.log(res);
            
            }
        })



        // $('#datasourceNext').removeClass('d-none');

        // const csvHeadersSelectId = this.value;
        // $('#csvHeaders').val(csvHeadersSelectId);
        // const dataSource = $(this).find('option:selected').attr('name');
        // $('#dataSourceName').val(dataSource);

        // const selectedFilePath =  $(this).find('option:selected').attr('id');
        // if (selectedFilePath === '') {
        //     return;
        // }

        // const formData = new FormData();
        // formData.append('csv_file', selectedFilePath);

        // $.ajax({
        //     method: "POST",
        //     url: "/api/csv-extract",
        //     data: formData,
        //     processData: false,
        //     contentType: false,
        //     success: function(res) {
        //         console.log(res)
        //         if (!res.success) {
        //             console.error('Error: ', res.message);
        //             $('#csvHeaders').html('<option value="">Nothing Fetched</option>');
        //             return;
        //         }

        //         const csvHeadersSelect = $('#csvHeaders');
        //         csvHeadersSelect.empty();

        //         const headers = res.data.headers;
        //         csvHeadersSelect.append('<option value=""> </option>');
        //         source_headers.push(headers);
        //         // Clear and populate mapDataFields with the variableData
        //         // console.log(variableData);
        //         variableData.forEach(variableDiv => {
        //             const csvHeadersSelect = $('#csvHeaders').clone();
        //             // csvHeadersSelect.prepend(newOption);
                     
        //             headers.forEach(header => {
        //                     csvHeadersSelect.append($(`<option value="${header}">${header}</option>`));
        //             });

        //             const rowDiv = $('<div>', {
        //                 class: 'row'
        //             }).append(
        //                 $('<div>', { class: 'col-md-6 mt-2' }).append(variableDiv),
        //                 $('<div>', { class: 'col-md-6 mt-2' }).append(csvHeadersSelect)
        //             );
                    
        //             mapDataFields.append(rowDiv);

        //             csvHeadersSelect.on('change', function() {
        //                 const csvSelectedVal = $(this).val();
        //                 storeArrayData.push({variableDiv, csvSelectedVal});
        //                 console.log(storeArrayData);
        //             });
                    
                    
        //         });
        //             $('#variableArrayInput').val(storeArrayData);

        //         if (csvHeadersSelect){
        //             const tempVariablesInput = $('#csvHeaders').addClass('d-none');
        //             // const searchInput = $('.search').addClass('d-none');
        //         }

        //         $('#sourceTextArea').val(headers.join('\n'));

        //     },
        //     error: function(jqXHR, textStatus, errorThrown) {
        //         // Handle the AJAX error if needed
        //         console.error('AJAX Error:', textStatus, errorThrown);
        //         $('#csvHeaders').html('<option value="">-- Choose --</option>');
        //     }
        // });


    });




    // New data source
    $('input[name="csv_file"]').change(function(e) {
        var formValues = new FormData();
        formValues.append('csv_file', e.target.files[0]);
        formValues.append('name',"Umair");

        jQuery.ajax({
            method: "POST",
            url: "/api/csv-preview",
            data: formValues,
            processData: false,
            contentType: false, 
            success: function(res) {
                console.log(res)
                if (!res.success) {
                    console.error('Error: ', res.message);
                    $('#csvHeaders').html('<option value="">Nothing Fetched</option>');
                    return;
                }

                const csvHeadersSelect = $('#csvHeaders');
                csvHeadersSelect.empty();

                const headers = res.data.headers;
                console.log(headers);
                csvHeadersSelect.append('<option value=""> </option>');
                source_headers.push(headers);

                headers.forEach(header => {
                    csvHeadersSelect.append($(`<option value="${header}">${header}</option>`));
                });
                    $('#sourceTextArea').val(headers.join('\n'));
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('AJAX Error:', textStatus, errorThrown);
                $('#csvHeaders').html('<option value="">-- Choose --</option>');
            }
        
        })
    })

        // handle sections based on selected type
        document.getElementById('selectType').on('change', function(e) 
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


    // next button dependency section starts
    $('#nextButton').on('click', function(e) {
        e.preventDefault();
        var isValid = true;
        $('.first_sec_required').each(function() {
            if ($(this).val() === '') {
                isValid = false;
            }
        });
        if(isValid == true){
            $('.website').removeClass('d-none');
            $('.firstError').addClass('d-none');
        }
        else{
            $('.firstError').removeClass('d-none');
        }
    });

    $('#webNext').on('click', function(e) {
        e.preventDefault();
        var isValid = true;
        $('.web_sec_required').each(function() {
            if ($(this).val() === '') {
                isValid = false;
            }
        });
        if(isValid == true){
            $('.datasource').removeClass('d-none');
            $('.secondError').addClass('d-none');
        }
        else{
            $('.secondError').removeClass('d-none');
        }
    });

    $('#datasourceNext').on('click', function(e) {
        e.preventDefault();
        var isValid = true;
        $('.datasource_sec_required').each(function() {
            if ($(this).val() === '') {
                isValid = false;
            }
        });
        if(isValid == true){
            $('.mapdata').removeClass('d-none');
            $('.thirdError').addClass('d-none');
        }
        else{
            $('.thirdError').removeClass('d-none');
        }
    });

    $('#add_new_ds').on('click', function(e) {
        e.preventDefault();
       
        $('.newdatasource').removeClass('d-none');
        
    });

    $('#newDatasourceNext').on('click', function(e) {
        e.preventDefault();
        $('.mapdata').removeClass('d-none');

        // var isValid = true;
        // $('.datasource_sec_required').each(function() {
        //     if ($(this).val() === '') {
        //         isValid = false;
        //     }
        // });
        // if(isValid == true){
        //     $('.mapdata').removeClass('d-none');
        //     $('.fourthError').addClass('d-none');
        // }
        // else{
        //     $('.fourthError').removeClass('d-none');
        // }
    });

    $('#mapdataNext').on('click', function(e) {
        e.preventDefault();
        var isValid = true;
        if(isValid == true){
            $('.SaveAndStart').removeClass('d-none');
            $('.fifthError').addClass('d-none');
        }
        else{
            $('.fifthError').removeClass('d-none');
        }
    });
    // next button dependency section ends




    function createDataMap() {

        var template_vars = window.pkit.template_vars;
        
        var tbodyContainer = $('.data-map-table tbody');
        
        tbodyContainer.html('');
        //  $('.source_fields_holder').html('');

        if("variables" in template_vars) {

            var totalVars = template_vars.length;

            template_vars.variables.forEach(function(temp_var, index) {
                var modifiedVariable = temp_var.replace(/[{}"]/g, ''); 
                selectDropdown = $(`<select name="source_${index}" var_name="${modifiedVariable}_selector" class="map_source_options form-control"></select>`)


                var tableRow = $(`<tr class="var_index_${index}"></tr>`);


                tableRow.append(`<td class="var_name"><span>${modifiedVariable}</span></td>`);

                var sourceSelectorCol = $(`<td class="var_source"></td>`).append(selectDropdown).append($(`<input type="hidden" name="${modifiedVariable}" value="" />`));

                tableRow.append(sourceSelectorCol);
                
                
                tableRow.append(`<td class="var_preview"><span>Select a source header</span></td>`);

               tbodyContainer.append(tableRow);
            
            });
            
            loadOptionsinFieldMaps()
        
            // Automatically match template_vars with dataSource headers
            matchTemplateVarsWithHeaders();
        }
        
    }



    function loadOptionsinFieldMaps() {


        var dataSource = window.pkit.datasource_info;

        var dsOptions = [];
        

        if("headers" in dataSource) {

            dsOptions.push($(`<option value="">-- Select header --</option>`));

            dataSource.headers.forEach(function(header, index) {

                dsOptions.push($(`<option header_index="${index}" value="${header}">${header}</option>`)); 
            
            })
        
        }


        $('select.map_source_options').append(dsOptions);

        $('select.map_source_options').change(handleDatasourceHeaderChange);
        // 


    
    }


    function handleDatasourceHeaderChange(e) {

        e.preventDefault();

            
        var selectedHeader = $(this).val();
        var headerIndex = $(this).find(`[value="${selectedHeader}"]`).attr('header_index');

        if(!headerIndex) {
            $(this).parents('tr').find('td.var_preview span').html('Select a source header');
            console.log("Header Index not matched");
        
        }

        if(!("pkit" in  window) || !("datasource_info" in window.pkit) || !("preview_rows" in window.pkit.datasource_info) || window.pkit.datasource_info.preview_rows.length < 1) {

            console.log("Invalid data available");
            return false;
        
        }

        var firstRow = window.pkit.datasource_info.preview_rows[0];

        var previewData = firstRow[headerIndex];

        $(this).parents('td').find('input[type="hidden"]').val(selectedHeader);
        $(this).parents('tr').find('td.var_preview span').html(previewData);

    
    }


    function matchTemplateVarsWithHeaders() {
        var template_vars = window.pkit.template_vars;
        var dataSource = window.pkit.datasource_info;
    
        if ("variables" in template_vars && "headers" in dataSource) {
            template_vars.variables.forEach(function (temp_var, index) {
                var modifiedVariable = temp_var.replace(/[{}"]/g, '');
                var selectField = $(`.var_index_${index} .map_source_options`);
                
                // Check if the modifiedVariable exists in dataSource.headers
                if (dataSource.headers.includes(modifiedVariable)) {
                    // Set the default selected option
                    selectField.val(modifiedVariable).change();
                }
            });
        }
    }
    


    function prepareDataMapJSON() {
    
        var dataMapJson = [];

        $('.var_source').find('[type="hidden"]').each(function(index, item) {

            var dataObj = [];
            
            dataObj.push($(item).attr('name'));
            dataObj.push($(item).val());

            dataMapJson.push(dataObj);

        });

        var mappingJson = JSON.stringify(dataMapJson);

        $('[name="data_maps_json"]').val(mappingJson);

        return mappingJson;
    }


    function handleFormSubmit(e) {

        e.preventDefault();
        
        prepareDataMapJSON();

        $(this).parents('form').submit();
    }


    $(function() {
    
        $('.submit-form-btn').click(handleFormSubmit);
    
    })