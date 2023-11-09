import axios from 'axios';


document.addEventListener('alpine:init', () => {

    
    var $alpine = null;


    Alpine.store('manage_campaign', {

        init() {
            
            $alpine = this
            getSheetChanges();
            checkCampaignStatus();

        },

        

        modalInstance: $('#campaign_detail_modal'),
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

        showCampaignStatus(camp_id) {

            $alpine.camp_id = camp_id;
            $alpine.showModal();

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
                // axios.get(`/data_api/get_status/${$alpine.campaign_info.title}`)
                // .then(response => {
                //     if(response.success) {
                //        //
                //     }
                // });
                $store.manage_campaign.status_info = `${$alpine.campaign_info.title}'s Status is still running`;
            } else {
                $store.manage_campaign.status_info = ''; 
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

                if(!$alpine.modalInstance.hasClass('show')) {
                   
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


        showModal() {

            $alpine.modalInstance.modal('show');

        },


        hideModal() {

            $alpine.modalInstance.modal('hide');

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