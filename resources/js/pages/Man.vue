<template>
    <div class="container-fluid px-4">
        <h4 class="mt-4">Man</h4>
        <div class="card mt-5"  style="width: 100%;">
            <div class="card-body overflow-auto">
                <div class="container-fluid px-4">
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Man</li>
                    </ol>
                    <div class="table-responsive">
                    <!-- id="dataTable" -->
                    <!-- <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    </table> -->
                    <DataTable
                        width="100%" cellspacing="0"
                        class="table table-bordered mt-2"
                        ref="tblEcrByStatus"
                        :columns="columns"
                        ajax="api/load_ecr_by_status?status=AP"
                        :options="{
                            serverSide: true, //Serverside true will load the network
                            columnDefs:[
                                // {orderable:false,target:[0]}
                            ]
                        }"
                    >
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Status</th>
                                <th>ECR Ctrl No.</th>
                                <th>Category</th>
                                <th>Internal or External</th>
                                <th>Customer Name</th>
                                <th>Part Number</th>
                                <th>Part Name</th>
                                <th>Device Name</th>
                                <th>Product Line</th>
                                <th>Section</th>
                                <th>Customer Ec. No</th>
                                <th>Date Of Request</th>
                            </tr>
                        </thead>
                    </DataTable>
                </div>
                </div>
            </div>
        </div>
    </div>
    <ModalComponent icon="fa-user" modalDialog="modal-dialog modal-xl" title="Man" @add-event="" ref="modalSaveMan">
        <template #body>
            <div class="row">
                <div class="card">
                    <div class="card-body overflow-auto">
                        <DataTable
                        width="100%" cellspacing="0"
                        class="table table-bordered mt-2"
                        ref="tblEcrDetails"
                        :columns="tblEcrDetailColumns"
                        ajax="api/load_ecr_details_by_ecr_id"
                        :options="{
                            serverSide: true, //Serverside true will load the network
                            columnDefs:[
                                // {orderable:false,target:[0]}
                            ]
                        }"
                    >
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th> Description Of Change</th>
                                <th> Reason Of Change</th>
                                <th> Type Of Part</th>
                                <th> Change Imp Date</th>
                                <th> Doc Sub Date</th>
                                <th> Doc To Be Sub</th>
                                <th> Remarks</th>
                            </tr>
                        </thead>
                    </DataTable>
                    </div>
                </div>
            </div>
            TODO: Add Man,Man Details,Special Acceptance
        </template>
        <template #footer>
            <button type="button" id= "closeBtn" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Close</button>
        </template>
    </ModalComponent>
    <ModalComponent icon="fa-user" modalDialog="modal-dialog modal-lg" title="Ecr Details" @add-event="" ref="modalSaveEcrDetail">
        <template #body>
            <div class="row">
                <div class="input flex-nowrap mb-2 input-group-sm">
                        <input   type="hidden" class="form-control form-control" aria-describedby="addon-wrapping">
                </div>
                <div class="input-group flex-nowrap mb-2 input-group-sm">
                    <span class="input-group-text" id="addon-wrapping">Customer Name:</span>
                    <input  type="text" class="form-control form-control" aria-describedby="addon-wrapping">
                </div>
                <div class="col-sm-6">
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Category:</span>
                        <select  class="form-select form-select-sm" aria-describedby="addon-wrapping">
                            <option value="" selected disabled>Select</option>
                            <option value="Man">Man</option>
                            <option value="Material">Material</option>
                            <option value="Machine">Machine</option>
                            <option value="Method">Method</option>
                            <option value="Environment">Environment</option>
                        </select>
                    </div>
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Customer Name:</span>
                        <input type="text" class="form-control form-control" aria-describedby="addon-wrapping">
                    </div>
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Part Name:</span>
                        <input  type="text" class="form-control" aria-describedby="addon-wrapping">
                    </div>
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Product Line:</span>
                        <input  type="text" class="form-control" aria-describedby="addon-wrapping">
                    </div>
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Section:</span>
                        <input  type="text" class="form-control" aria-describedby="addon-wrapping">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Internal / External:</span>
                        <select  class="form-select form-select-sm" aria-describedby="addon-wrapping">
                            <option value="" selected disabled>Select</option>
                            <option value="Internal">Internal</option>
                            <option value="External">External</option>
                        </select>
                    </div>
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Part Number:</span>
                        <input  type="text" class="form-control" aria-describedby="addon-wrapping">
                    </div>
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Device Name:</span>
                        <input type="text" class="form-control" aria-describedby="addon-wrapping">
                    </div>
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Customer EC No. (If any):</span>
                        <input  type="text" class="form-control" aria-describedby="addon-wrapping">
                    </div>
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Date of Request:</span>
                        <input type="date" class="form-control" aria-describedby="addon-wrapping">
                    </div>
                </div>
            </div>
            <!-- Description of Change / Reason for Change -->
            <EcrChangeComponent :frmEcrReasonRows="frmEcrReasonRows" :optDescriptionOfChange="ecrVar.optDescriptionOfChange" :optReasonOfChange="ecrVar.optReasonOfChange">
            </EcrChangeComponent>
        </template>
        <template #footer>
            <button type="button" id= "closeBtn" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success btn-sm"><li class="fas fa-save"></li> Save</button>
        </template>
    </ModalComponent>
</template>

<script setup>
    import {ref , onMounted,reactive, toRef} from 'vue';
    import ModalComponent from '../../js/components/ModalComponent.vue';
    import EcrChangeComponent from '../components/EcrChangeComponent.vue';
    import useEcr from '../../js/composables/ecr.js';
    import DataTable from 'datatables.net-vue3';
    import DataTablesCore from 'datatables.net-bs5';
    DataTable.use(DataTablesCore)

    //composables export function
    const {
        modal,
        ecrVar,
        frmEcrReasonRows,
        descriptionOfChangeParams,
        reasonOfChangeParams,
        getDropdownMasterByOpt,
        axiosFetchData,
    } = useEcr();

    //ref state
    const tblEcrByStatus = ref(null);
    const tblEcrDetails = ref(null);
    const modalSaveMan = ref(null);
    const modalSaveEcrDetail = ref(null);

    const columns = [
        {   data: 'get_actions',
            orderable: false,
            searchable: false,
            createdCell(cell){
                let btnGetEcrId = cell.querySelector('#btnGetEcrId');
                if(btnGetEcrId != null){
                    btnGetEcrId.addEventListener('click',function(){
                        let ecrId = this.getAttribute('ecr-id');
                        tblEcrDetails.value.dt.ajax.url("api/load_ecr_details_by_ecr_id?ecr_id="+ecrId).draw()
                        modal.SaveMan.show();
                    });
                }
            }
        } ,
        {   data: 'status'} ,
        {   data: 'ecr_no'} ,
        {   data: 'category'} ,
        {   data: 'internal_external'} ,
        {   data: 'customer_name'} ,
        {   data: 'part_no'} ,
        {   data: 'part_name'} ,
        {   data: 'device_name'} ,
        {   data: 'product_line'} ,
        {   data: 'section'} ,
        {   data: 'customer_ec_no'} ,
        {   data: 'date_of_request'} ,
    ];
    const tblEcrDetailColumns = [
        {   data: 'get_actions',
            orderable: false,
            searchable: false,
            createdCell(cell){
                let btnGetEcrDetailsId = cell.querySelector('#btnGetEcrDetailsId');
                if(btnGetEcrDetailsId != null){
                    btnGetEcrDetailsId.addEventListener('click',function(){
                        let ecrDetailsId = this.getAttribute('ecr-details-id');
                        getEcrDetailsId(ecrDetailsId);
                        modal.SaveEcrDetail.show();
                    });
                }
            }
        } ,
        {   data: 'description_of_change'} ,
        {   data: 'reason_of_change'} ,
        {   data: 'type_of_part'} ,
        {   data: 'change_imp_date'} ,
        {   data: 'doc_sub_date'} ,
        {   data: 'doc_to_be_sub'} ,
        {   data: 'remarks'} ,
    ];

    const getEcrDetailsId = async (ecrDetailsId) => {
        let params = {
            ecrDetailsId : ecrDetailsId
        }
        axiosFetchData(params,'api/get_ecr_details_id',function(response){
            let ecrDetails = response.data.ecrDetail;

            frmEcrReasonRows.value[0].descriptionOfChange = ecrDetails.dropdown_master_detail_description_of_change.id;
            frmEcrReasonRows.value[0].reasonOfChange = ecrDetails.dropdown_master_detail_reason_of_change.id;
            // console.log(ecrDetails.dropdown_master_detail_description_of_change.id);
            return;
            frmEcrReasonRows.value = [];
            //Reasons
            if (ecrDetails.length != 0){
                ecrDetails.forEach((ecrDetailsEl,index) =>{
                    frmEcrReasonRows.value.push({
                        descriptionOfChange : ecrDetailsEl.description_of_change,
                        reasonOfChange : ecrDetailsEl.reason_of_change
                    });
                })
            }
        });
    }

    onMounted( async ()=>{
        modal.SaveMan = new Modal(modalSaveMan.value.modalRef,{ keyboard: false });
        modal.SaveEcrDetail = new Modal(modalSaveEcrDetail.value.modalRef,{ keyboard: false });
        await getDropdownMasterByOpt(descriptionOfChangeParams);
        await getDropdownMasterByOpt(reasonOfChangeParams);
        // modal.SaveMan.show();
        // modal.SaveEcrDetail.show();
        // tblEcrByStatus.value.dt.ajax.url("api/load_ecr_by_status?status="+'AP').draw()
    })
</script>


