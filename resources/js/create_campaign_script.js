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
        ds_source_type: "existing",     // new or existing

        new_ds_type: null,
        gsheet_type:"new_sheet",
        gsheet_id: "",
        variables_exported : null,
        ds_loading: false,
        

        new_sheet_name: null,
        
        requiresMapping: false,
        website_id: null,
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


        variablesMap : {},
        datasourceFields: [],
        firstDataRow: [],


        getVariablesMap() {

            var variables = Object.keys($pThis.variablesMap);

            return variables;

        },


        setTemplateVariables() {
            
            var formValues = new FormData();

            formValues.append('post_id', $pThis.wp_template_id);
            axios.post('/api/get_template_vars', formValues)
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
            
            })
        
        },


        setDatasourceFields() {

            var formValues = new FormData();
            formValues.append('ds_id', $pThis.data_source_id.id);

            axios.post('/api/get_datasource_mapping', formValues)
            .then(response => {

                console.log(response);

                if(response.data.success) {
                    
                    $pThis.datasourceFields = [...response.data.data.headers]
                    $pThis.firstDataRow = [...response.data.data.preview_rows[0]]
                }
            
            
            })
        
        },


        handleTemplateFieldChange(event) {
            
            $pThis.bind_field_on_change(event);

            $pThis.setTemplateVariables();

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


            event.target.attributes;((value, index)=> {

               
            
            });
        
        
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

        formValues.append('title', this.new_sheet_name);
        formValues.append('website_id', $pThis.website_id  );
        formValues.append('template_id', $pThis.wp_template_id);
        
        axios.post('/sheets/create_new', formValues)
        .then(response => {

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

                        $pThis.requiresMapping = false;

                        $pThis.ds_loading = false;

                        $pThis.incrementStep();
                        $pThis.incrementStep();

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


        if($pThis.requiresMapping && mappingComplete) {

            alert("Please Properly map the fields to their relevant variables.");
            return false;
        
        }
        
        
        

        $pThis.incrementStep();
    }   



});
