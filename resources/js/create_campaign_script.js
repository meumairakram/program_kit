    import axios from "axios";

    

    document.addEventListener('alpine:init', () => {
      
        Alpine.store("create_campaign_store", {

            init() {
            
                this.google_acc_connected = google_acc_connected;
            
            },
            google_acc_connected: false,
            sheet_type: null,   //  new or exisitng
            data_source_id: null,
            create_new_datasource: action_create_new_datasource.bind(this),
            ds_source_type: "new",     // new or existing

            new_ds_type: "google_sheet",
            gsheet_type:"new_sheet",
            gsheet_id: "",
            variables_exported : null,
            ds_loading: false,

            new_sheet_name: null,

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
            
            switch_ds_type(event, type) {
                console.log(type);
                this.ds_source_type = type;
                this.new_ds_type = null;
            },

            set_ds_id(event) {
                this.data_source_id = event.target.value;
            },


            validate_create_new_sheet() {

                console.log(this.new_sheet_name);
                if(!this.new_sheet_name || this.new_sheet_name == "") {
                    return false;
                }

                return true;
                
            },



            create_new_sheet_with_vars(event) {
                
                event.preventDefault();
                
                if(!this.validate_create_new_sheet()) {

                    alert("there is a problem with your input");
                
                }       

                var formValues = new FormData();

                formValues.append('title', this.new_sheet_name);

                axios.post('/sheets/create_new', formValues)
                .then(response => {

                    console.log(response);
                
                })
              
              

            }



        
        });


    });
    
    

    // Store Ends here


    // Handlers START here

    function action_create_new_datasource() {
        

    }   





    // Store Starts here