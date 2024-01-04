import axios from 'axios';


document.addEventListener('alpine:init', () => {

    
    var $alpine = null;


    Alpine.store('manage_campaign', {

        init() {
            
            $alpine = this
            getSheetChanges();
            checkCampaignStatus();

        },

        

        startCampaignModel: $('#campaign_detail_modal'),
        resyncCampaignModel: $('#action_confirm_model'),

        loading: true,
        status_info: "",
        campaign_info: {
            title:"",
            status: "",
            website_pinged: false,
            pages_published: 0,
        },
        sync_status: "",
        camp_id: null,
        message: '',

        updateInfoTimeout: null,


        confirmResyncCampaign(camp_id) {

            $alpine.camp_id = camp_id;


            $alpine.showModal($alpine.resyncCampaignModel);

        },


        resyncCampaign() {

           var actionConfirmed =  $('#confirm_text_window').val().toLowerCase() === "yes";

           if(!actionConfirmed) {
                
                $alpine.hideModal($alpine.resyncCampaignModel);
                $alpine.camp_id = null;
                return;

           }

           var camp_id = $alpine.camp_id;

           // action cofnirmed proceed

            $alpine.hideModal($alpine.resyncCampaignModel);
            $alpine.showModal($alpine.startCampaignModel);

            $alpine.setCampaignStatus("Resetting your Campaign Status");

            // Reste campaign status via backend

            axios.get(`/data_api/reset_campaign_status/${camp_id}`)
            .then(response => {

                if(!response.data.success) {

                    alert(response.data.error);
                    return ;
                }


                // get campaign info
                axios.get(`/data_api/campaign_info/${camp_id}`)
                .then(response => {
                    $alpine.loading = false;
                    if(response.data.success) {
                        $alpine.campaign_info = {...$alpine.campaign_info , ...response.data.data}
                        $alpine.setCampaignStatus('Testing website ping...');




                        $alpine.testWebsitePing()
                        .then(response => {
                           
                            if(response.data.success) {
                            // if(true) {
                            
                                $alpine.campaign_info = {...$alpine.campaign_info , ...{website_pinged: true}}
                                $alpine.setCampaignStatus('Testing website Successful, Starting campaign sync...');

                                $alpine.setWatchTimeout();

                                if($alpine.campaign_info.status == "idle") {
                                
                                    $alpine.startCampaignSync();
                                }

                            } else {

                                $alpine.setCampaignStatus('Website ping failed...');
                            
                            }
                        
                        });





                    }
                });




            });
            

            // get campaign info
            // axios.get(`/data_api/campaign_info/${camp_id}`)
            // .then(response => {
            //     $alpine.loading = false;
            //     if(response.data.success) {
            //         $alpine.campaign_info = {...$alpine.campaign_info , ...response.data.data}
            //         $alpine.setCampaignStatus('Testing website ping...');
            //     }
            // });




            // $alpine.testWebsitePing()
            // .then(response => {
            //    
            //     // if(response.data.success) {
            //     if(true) {
                
            //         $alpine.campaign_info = {...$alpine.campaign_info , ...{website_pinged: true}}
            //         $alpine.setCampaignStatus('Testing website Successful, Starting campaign sync...');

            //         $alpine.setWatchTimeout();

            //         if($alpine.campaign_info.status == "idle") {
                      
            //             $alpine.startCampaignSync();
            //         }

            //     } else {

            //         $alpine.setCampaignStatus('Website ping failed...');
                
            //     }
            
            // });




        },


        showCampaignStatus(camp_id) {

            $alpine.camp_id = camp_id;
            $alpine.showModal($alpine.startCampaignModel);

            axios.get(`/data_api/campaign_info/${camp_id}`)
            .then(response => {
                $alpine.loading = false;
                if(response.data.success) {
                    $alpine.campaign_info = {...$alpine.campaign_info , ...response.data.data}
                    $alpine.setCampaignStatus('Testing website ping...');
                }
            });


            $alpine.testWebsitePing()
            .then(response => {
                // console.log(response.data);
                // if(response.data.success) {
                if(true) {
                
                    $alpine.campaign_info = {...$alpine.campaign_info , ...{website_pinged: true}}
                    $alpine.setCampaignStatus('Testing website Successful, Starting campaign sync...');

                    $alpine.setWatchTimeout();

                    if($alpine.campaign_info.status == "idle") {
                      
                        $alpine.startCampaignSync();
                    }

                } else {

                    $alpine.setCampaignStatus('Website ping failed...');
                
                }
            
            });

            
            
            
            // setTimeout(function() {
            
            //     $alpine.campaign_info = {...$alpine.campaign_info , ...{            
            //         title: "New Campaign",
            //         status: "ready",
            //         website_pinged: false,
            //         pages_published: 0,
            //     }}
            //     $alpine.loading = false;

            //     $alpine.setCampaignStatus('Testing website ping...');
            
            // }, 1500);
            
            

            // $alpine.loading = false;


            
            

        
        },






        testWebsitePing () {

            return axios.get(`data_api/ping_website/${$alpine.camp_id}`);

        },

        startCampaignSync() {

            $alpine.setCampaignStatus("Campaign sync started");
            axios.get(`/data_api/start_campaign/${$alpine.camp_id}`)
            .then(response => {
                if(response.success) {
                    // $alpine.campaign_info = {...$alpine.campaign_info , ...response.data}
                    
                    $alpine.setCampaignStatus("Syncing");
                    
                    $alpine.updateCampaignInfo();


                }
            });

        },

        updateCampaignInfo() {

            axios.get(`/data_api/campaign_info/${$alpine.camp_id}`)
            .then(response => {
                if(response.data.success) {
                    $alpine.campaign_info = {...$alpine.campaign_info , ...response.data.data}
                    
                }
            });

        
        },

        checkCampaignStatus() {
            
            if (
                $alpine.campaign_info.status !== 'synced' &&
                $alpine.campaign_info.status !== 'failed'
            ) {
                $alpine.status_info = `${$alpine.campaign_info.title}'s Status is still running`;
            } else {
                $alpine.status_info = ''; 
            }
        },

        setWatchTimeout() {

            $alpine.updateInfoTimeout = setInterval(function() {

                // Check the campaign status
                $alpine.checkCampaignStatus();

                if($alpine.campaign_info.status == 'synced' || $alpine.campaign_info.status == 'failed') {
                        $alpine.setCampaignStatus("Sync Completed");
                    if($alpine.updateInfoTimeout != null) {
                        clearInterval($alpine.updateInfoTimeout);
                    }

                    
                    return ;
                }

                if(!$alpine.startCampaignModel.hasClass('show')) {
                   
                    clearInterval($alpine.updateInfoTimeout)
                }

                $alpine.updateCampaignInfo();

            }, 1000);   

        },

        
        setCampaignStatus(status) {

            setTimeout(function() {
                
                $alpine.sync_status = status;
                $alpine.checkCampaignStatus();
            
            }, 500);
        
        },


        showModal(targetModel) {

            targetModel.modal('show');

        },


        hideModal(targetModel) {

            targetModel.modal('hide');

        },

        getSheetChanges() {
            alert('hii');

            axios.post('sheets/listen/changes')
            .then(response => {

                console.log(response);
                console.log(response.data.message);
                this.message = response;
            })
            .catch(error => {

                console.error(error);
            });
        }

    })



});