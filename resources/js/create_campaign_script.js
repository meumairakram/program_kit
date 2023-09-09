

    

    document.addEventListener('alpine:init', () => {
      
        Alpine.store("create_campaign_store", {

            init() {


                this.google_acc_connected = google_acc_connected;
            
            },
            google_acc_connected: false,
            ds_source_type: "existing",     // new or existing
            sheet_type: null,   //  new or exisitng
            data_source_id: null,
            create_new_datasource: action_create_new_datasource.bind(this),
            
            
            switch_ds_type(event, type) {
                console.log(type);
                this.ds_source_type = type;
            
            },

            set_ds_id(event) {

                this.data_source_id = event.target.value;

            
            }



        
        });


    });
    
    

    // Store Ends here


    // Handlers START here

    function action_create_new_datasource() {
        

    }   





    // Store Starts here