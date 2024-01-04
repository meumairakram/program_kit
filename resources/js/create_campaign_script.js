import axios from "axios";
        

document.addEventListener('alpine:init', () => {
            
    var $pThis = null;

    Alpine.store("create_campaign_store", {

        init() {
            this.google_acc_connected = google_acc_connected;
            $pThis = this;

        },

        currentStep: 3,
        google_acc_connected: false,
        sheet_type: null,   //  new or exisitng
        data_source_id: null,
        // create_new_datasource: action_create_new_datasource.bind(this),
        ds_source_type: "new",     // new or existing

        new_ds_type: null,
        gsheet_type:"new_sheet",
        gsheet_id: "",
        variables_exported : null,
        ds_loading: false,
        wb_loading: false,
        existing_sheet_url: null,


        // website variables
        website_type: "wordpress",        
        avl_websites: [],
        avl_post_types: [],
        avl_templates: [],
        website_id: null,
        

        new_sheet_name: null,
        
        requiresMapping: false,
        variablesMap : {},
        datasourceFields: [],
        ignoredDataSourceFields: [],
        firstDataRow: [],


        dataMapJson: '{}',

        

        // Action creators


        getIgnoredColumns() {


            return $pThis.ignoredDataSourceFields.join(",");


        },


        // global setter
        setValue(type, val) {

            $pThis[type] = val;
        
        },


        incrementStep() {

            this.currentStep = this.currentStep + 1;
        
        },

        decrementStep() {

            this.currentStep = this.currentStep - 1;
        
        },



        set_new_ds_type(type) {

            this.new_ds_type = type;

        },

        set_google_sheet_type(event) {  
        
            if(event.target.checked) {

                this.gsheet_type = "new_sheet";
                return;
            }

            this.gsheet_type = "existing_sheet";
            // this.gsheet_type = type;
        
        },
        
        

        set_ds_id(event) {

            var source_id =  event.target.value;
            var source_title = $(event.target).find(`[value="${source_id}"]`).attr('name');
           
            this.requiresMapping = true;
            
            $pThis.setTemplateVariables();

            this.data_source_id = { 
                id: source_id, 
                title: source_title
            };


            $pThis.setDatasourceFields();
            this.incrementStep();


        },


        getSelectedDSLabel() {

            return `${this.data_source_id.title} - #${this.data_source_id.id}` 
        
        },

        validate_create_new_sheet() {

            if(!this.new_sheet_name || this.new_sheet_name == "" || this.website_id == null)  {
                return false;
            }

            return true;
            
        },


        handleWebsiteTypeChange(event) {
            $pThis.bind_field_on_change(event);

            $pThis.loadWebsites();
        },

        handleWebsiteIdChange(event) {

            $pThis.bind_field_on_change(event);

            $pThis.loadPostTypes();

        
        },

        handleWebsitePostTypeChange(event) {

            $pThis.bind_field_on_change(event);

            $pThis.loadAvlTemplates();
        }, 

        
        handleTemplateFieldChange(event) {
            
            $pThis.bind_field_on_change(event);

            $pThis.setTemplateVariables();

        },


        handleExistingSheetUrlChange(event) {
            
            $pThis.bind_field_on_change(event);

            
            if(!$pThis['existing_sheet_url']) { 
                
                // alert("existing sheet url empty")
                // set error message
                return;
            }

            var match_pattern = $pThis['existing_sheet_url'].match(/docs.google.com\/spreadsheets\/d\/[a-zA-Z0-9-_]+/g)
            
            if(match_pattern.length < 1) {
                // sheet url is invalid trigger error
                console.log("invalid sheet url");
            }

            var existing_sheet_id = match_pattern[0].replace("docs.google.com/spreadsheets/d/","");

            $pThis.gsheet_id = existing_sheet_id;

            if(existing_sheet_id == "") {

                // alert 
                alert("invalid sheet url");
                return;

            }


            var formValues = new FormData();

            formValues.append("sheet_id", $pThis.gsheet_id);

            $pThis.ds_loading = true;

            // try to fetch sheet's first row - contians columns
            axios.post('/sheets/get_sheet_row', formValues)
            .then((response) => {

                if(response.data.success) {
                    
                    var sheetColumns = response.data.data.columns;
                    var sheetTitle = response.data.data.title;
                    var ignoredColumns = response.data.data.ignored_columns;

                    var sheetFormValues = new FormData();
                    sheetFormValues.append('title', sheetTitle);
                    sheetFormValues.append('type','google_sheet');
                    sheetFormValues.append('requires_mapping','0');
                    // sheetFormValues.append('file_path','');
                    sheetFormValues.append('sheet_id', $pThis.gsheet_id);


                    // Create new Datasource 
                    axios.post('/api/create_new_data_source', sheetFormValues)
                    .then(function(resp) {

                        if(resp.data.success) {

                            const {id, title} = resp.data.data;

                            $pThis.data_source_id = {
                                id:id, 
                                title:title
                            };

                            // Object.keys($pThis.variablesMap)
                            $pThis.datasourceFields = [...sheetColumns]
                            $pThis.firstDataRow = [...new Array($pThis.datasourceFields.length)];
                            
                            $pThis.ignoredDataSourceFields = [...ignoredColumns];

                            // temporarily enable mapping for google sheets
                            $pThis.requiresMapping = true;

                            $pThis.ds_loading = false;

                            $pThis.incrementStep();

                            $pThis.autoMatchDSFields();
                        

                        }

                    });
                    

                    // // set variables
                    // $pThis.requiresMapping = true;
                    // $pThis.datasourceFields = [...sheetColumns]
                    // $pThis.ds_loading = false;
                    // $pThis.incrementStep();

                    // create a new datasource

                    // mark as used datasource


                } else {

                    alert(response.data.error)

                }

            })
            .catch(err => {

                alert(err);

            })

        },



        setTemplateVariables() {
            
            var formValues = new FormData();
            this.wb_loading = true;
            formValues.append('post_id', $pThis.wp_template_id);


             // getCurrentWebsiteAjaxUrl
            var ajaxurl = $pThis.getCurrentWebsiteAjaxUrl();

            if(!ajaxurl) {

                alert("Error generating website request.");
            }

            
            var requestUrl = `${ajaxurl}?action=pseo_get_post_variables`;
            // var requestUrl = '/api/get_template_vars';   // enable if dont have wordpress, enable above on if testing realtie.




            axios.post(requestUrl, formValues)
            .then(response => {

                if(!response.data.success) {
                    console.error("Got invalid response for template vars");
                    return false;
                }

                if(response.data.data.variables.length < 1) {
                    console.error("got no vars for this template");
                    return false;
                
                }
                
                var varsArray = {};

                response.data.data.variables.forEach((value, index) => {

                    varsArray[value] = {
                        source_field: null,
                        preview_row_data: null,
                    }
                
                })

                $pThis.variablesMap = {...varsArray};
                this.wb_loading = false;

                
                // Attempt automatch of fields.
                $pThis.autoMatchDSFields();
            
            })
        
        },





        loadWebsites() {
            var selectedType = $pThis.website_type;
            this.wb_loading = true;

            if(!selectedType) {
                alert("website type channot be empty");

                return false;
            }

            var formValues  = new FormData();

            formValues.append('type', selectedType);
            axios.post('/websites_type', formValues)
            .then((response) => {
                // console.log(response);
                if(response.data.success) {
                    
                    var websitesStore = [];
                    if( !Array.isArray(response.data.websites) ){
                        return false;
                    }

                    response.data.websites.forEach(item => {
                        websitesStore.push({
                            id: item.id,
                            name: item.website_name,
                            url: item.website_url
                        })
                    })


                    $pThis.avl_websites = [...websitesStore];
                    this.wb_loading = false;

                } else {
                    alert("error loading websites");
                }


            })


        },


        getCurrentWebsiteAjaxUrl() {

            
            if(!$pThis.website_id || $pThis.avl_websites.length < 1) {
                return false;
            }



            var selectedWebsiteInfo = $pThis.avl_websites.find((item) => item.id == $pThis.website_id); 
            
            console.log(selectedWebsiteInfo);
            
            var websiteUrl = selectedWebsiteInfo.url;

            if(!websiteUrl || websiteUrl == "") {
                return false;
            }


            var ajaxurl = `${websiteUrl}/wp-admin/admin-ajax.php`;
            
            // var requestUrl = `${ajaxurl}?action=pseo_get_all_post_types`;

            return ajaxurl;

        },


        loadPostTypes() {

            var selectedWebsite = $pThis.website_id;
            this.wb_loading = true;
            if(!selectedWebsite) {
                alert("website type channot be empty");

                return false;

            }   


            // getCurrentWebsiteAjaxUrl
            var ajaxurl = $pThis.getCurrentWebsiteAjaxUrl();

            if(!ajaxurl) {

                alert("Error generating website request.");
            }

            
            var requestUrl = `${ajaxurl}?action=pseo_get_all_post_types`;
            // var requestUrl = '/api/get_post_types';   // enable if dont have wordpress, enable above on if testing realtie.

            
            // console.log(websiteUrl)

            var formValues  = new FormData();

            // formValues.append('type', selectedType);
            axios.get(requestUrl)
            .then((response) => {

                console.log(response);

                if(response.data.success) {

                    $pThis.avl_post_types = [...response.data.data.post_types];
                    this.wb_loading = false;


                }

            });


        
        
        },


        loadAvlTemplates() {

            var selectedPostType = $pThis.post_type;
            var selectedWebsite = $pThis.website_id;
            this.wb_loading = true;
        
            var formValues = new FormData();
            formValues.append('website_id', selectedWebsite);
            formValues.append('post_type', selectedPostType);

            // getCurrentWebsiteAjaxUrl
            var ajaxurl = $pThis.getCurrentWebsiteAjaxUrl();

            if(!ajaxurl) {

                alert("Error generating website request.");
            }

            
            var requestUrl = `${ajaxurl}?action=pseo_get_posts_by_type`;    // Comment the below one or this one.
            // var requestUrl = '/api/get_templates_by_type';      // enable if you dont have wordpress instance setup

            axios.post( requestUrl , formValues )
            .then((response) => {

                console.log(response);

                if(!response.data.success) {
                    alert("No website found for this settings");
                    return false;
                }

                if(Array.isArray(response.data.data.posts)) {
                
                    $pThis.avl_templates = [...response.data.data.posts];
                    this.wb_loading = false;
                }

            
            })

        
        },

        getAvailableVariablesNames() {

            var variables = Object.keys($pThis.variablesMap);

            return variables;

        },


        


        setDatasourceFields() {

            var formValues = new FormData();
            formValues.append('ds_id', $pThis.data_source_id.id);

            axios.post('/api/get_datasource_mapping', formValues)
            .then(response => {

                console.log(response);

                if(response.data.success) {
                    
                    $pThis.datasourceFields = [...response.data.data.headers]
                    $pThis.firstDataRow = [...response.data.data.preview_rows[0]];


                    // Attempt automatch of fields.
                    $pThis.autoMatchDSFields();
                    
                }
            
            
            })
        
        },


        autoMatchDSFields() {

        
            // Auto matching the fields with the same name
            var allVariables = $pThis.getAvailableVariablesNames();

            allVariables.forEach(element => {

                var cleanVariable = element.replace("{","").replace("}", "");
                var dsIndex = $pThis.datasourceFields.findIndex((item) => {

                    // var cleanedItem = item.replace("{","").replace("}", "");

                    var matchFound = item === cleanVariable;
                    
                    if(matchFound) {
                        console.log(`Match found for ${item} AND ${cleanVariable}`)
                    }
                    
                    return matchFound;
                
                } );


                console.log("Found index" , dsIndex);                

                if(dsIndex > -1){
                    $pThis.variablesMap[element].source_field = cleanVariable;
                    $pThis.variablesMap[element].preview_row_data = $pThis.firstDataRow[dsIndex];
                }


            });

        
        },



        handle_source_field_change(event) {

            event.preventDefault();
            var elem = event.target;

            var dataRowIndex = $pThis.datasourceFields.findIndex((item) => item === $(elem).val());


            $pThis.variablesMap[$(elem).attr('vartarget')].source_field = $(elem).val();
            
            if(dataRowIndex > -1) {
                
                $pThis.variablesMap[$(elem).attr('vartarget')].preview_row_data = $pThis.firstDataRow[dataRowIndex];
            
            }
            
            
        
        },


        bind_field_on_change(event) {

            event.preventDefault();

            if(event.target.attributes.length == 0) {

                console.alert("No attribute for name on", event.target);
            
            }

            for(var i = 0; i < event.target.attributes.length ; i++) {

                var item = event.target.attributes[i];

                if(item.name == "name") {

                    $pThis[item.value] = event.target.value;
                }

            }

        
        },       

        submitCreateCampaign(e) {
            
            e.preventDefault();
            
            $pThis.createDataMapJson();

            setTimeout(() => {
                e.target.submit();
            
            }, 500)
        },


        createDataMapJson() {

            var templateVarNames = $pThis.getAvailableVariablesNames();
            var outputJson = [];

            templateVarNames.forEach(tempvar => {

                
                
                var varmap = [];    

                console.log($pThis.variablesMap[tempvar]);
                varmap.push(tempvar);
                varmap.push($pThis.variablesMap[tempvar].source_field);

                outputJson.push(varmap);

            });

            $pThis.dataMapJson = JSON.stringify(outputJson);


        },



        // Action Creators

        action_create_new_sheet_with_vars,
        action_handle_campaign_info_step,
        action_handle_ds_step,
        action_handle_map_step,
        action_handle_website_submit_step,
        action_removeSelectedDataSource,
        action_switch_ds_type,

    
    });



    function action_create_new_sheet_with_vars(event) {

        event.preventDefault();

        this.ds_loading = true;
        
        if(!$pThis.validate_create_new_sheet()) {

            alert("there is a problem with your input");
            return false;
        }       

        

        var formValues = new FormData();

        formValues.append('title', $pThis.new_sheet_name);
        formValues.append('website_id', $pThis.website_id);
        formValues.append('template_id', $pThis.wp_template_id);
        console.log('title', $pThis.new_sheet_name);
        console.log('website_id', $pThis.website_id);
        console.log('template_id', $pThis.wp_template_id);        

        axios.post('/sheets/create_new', formValues)
        .then(response => {
            console.log(response);

            if(response.data.success) {
                
                var sheetFormValues = new FormData();
                sheetFormValues.append('title', this.new_sheet_name);
                sheetFormValues.append('type','google_sheet');
                sheetFormValues.append('requires_mapping','0');
                // sheetFormValues.append('file_path','');
                sheetFormValues.append('sheet_id', response.data.data.sheet_id);


                // Create new Datasource 
                axios.post('/api/create_new_data_source', sheetFormValues)
                .then(function(resp) {

                    if(resp.data.success) {

                        const {id, title} = resp.data.data;

                        $pThis.data_source_id = {
                            id:id, 
                            title:title
                        };

                        // Object.keys($pThis.variablesMap)
                        $pThis.datasourceFields = [...Object.keys($pThis.variablesMap).map(item => item.replace("{","").replace("}", ""))]
                        $pThis.firstDataRow = [...new Array($pThis.datasourceFields.length)];

                        $pThis.requiresMapping = true;

                        $pThis.ds_loading = false;

                        $pThis.incrementStep();

                        $pThis.autoMatchDSFields();
                      

                    }

                });

                return ;
            }

            alert("There is a problem creating sheet with this account");
        
        })
    
    }






    // Action Creators

    function action_removeSelectedDataSource() {
    
            this.data_source_id = null;
            $pThis.decrementStep();
        
    }


    function action_switch_ds_type(event, type) {

        this.ds_source_type = type;
        this.new_ds_type = null;
        
    }



    function action_handle_campaign_info_step() {

        var title = $('#campaign-title');

        var desc = $('#about');


        if(title.val() == "" || desc.val() == "") {
        
            alert("Please fill in campaign info.");

            return false;

        }

        $pThis.incrementStep();
        
    }



    function action_handle_website_submit_step() {

        var selectedTemplate = $('#template');

        if( selectedTemplate.val() == "" ) {
        
            alert("Please fill in website info.");
            return false;

        }
        
        $pThis.incrementStep();

    }


    function action_handle_ds_step() {
    
        if($pThis.data_source_id == null) {

            alert("Please select a datasource.");
            return false;
        
        }
        

        $pThis.incrementStep();


    }

    function action_handle_map_step() {

        var mappingComplete = true;


        if($pThis.requiresMapping && !mappingComplete) {

            alert("Please Properly map the fields to their relevant variables.");
            return false;
        
        }

        $pThis.incrementStep();
        
    }   



});
