<template>
    <div class="container-fluid px-4">
        <div class="card-body overflow-auto">
            <div class="container-fluid px-4">
                <div class="card mt-5"  style="width: 100%;">
                    <div class="card-body overflow-auto">
                        <div class="container-fluid px-4">
                            <ol class="breadcrumb mb-4">
                                <li class="breadcrumb-item active">Environment</li>
                            </ol>
                            <div class="table-responsive">
                                <!-- :ajax="api/load_ecr_by_status?status=AP" -->
                                <DataTable
                                    width="100%" cellspacing="0"
                                    class="table mt-2"
                                    ref="tblEcrByStatus"
                                    :columns="tblEcrByStatusColumns"
                                    ajax="api/load_ecr_by_status?category=Environment"
                                    :options="{
                                        serverSide: true, //Serverside true will load the network
                                        columnDefs:[
                                            {orderable:false,target:[0]}
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
        </div>
    </div>
    <ModalComponent icon="fa-user" modalDialog="modal-dialog modal-xl" title="SaveEnvironment" @add-event="" ref="modalSaveEnvironment">
        <template #body>
            <div class="row">
                <div class="card">
                    <div class="card-body overflow-auto">
                        <DataTable
                            width="100%" cellspacing="0"
                            class="table  table-responsive mt-2"
                            ref="tblEcrDetails"
                            :columns="tblEcrDetailColumns"
                            ajax="api/load_ecr_details_by_ecr_id"
                            :options="{
                                serverSide: true, //Serverside true will load the network loadEcrDetailsByEcrId
                                columnDefs:[
                                    {orderable:false,target:[0]}
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
                                <th> Docs Sub Date</th>
                                <th> Docs To Be Sub</th>
                                <th> Customer Approval</th>
                                <th> Remarks</th>
                            </tr>
                        </thead>
                        </DataTable>
                    </div>
                </div>
            </div>
        </template>
        <template #footer>
            <button type="button" id= "closeBtn" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            <button @click="saveMaterial()" type="submit" class="btn btn-success btn-sm"><li class="fas fa-save"></li> Save</button>
        </template>
    </ModalComponent>
    <ModalComponent icon="fa-user" modalDialog="modal-dialog modal-lg" title="Ecr Details" @add-event="saveEcrDetails()" ref="modalSaveEcrDetail">
        <template #body>
             <!-- Description of Change / Reason for Change -->
             <EcrChangeComponent :isSelectReadonly="isSelectReadonly" :frmEcrReasonRows="frmEcrReasonRows" :optDescriptionOfChange="ecrVar.optDescriptionOfChange" :optReasonOfChange="ecrVar.optReasonOfChange">
            </EcrChangeComponent>
            <div class="row">
                <div class="input-group flex-nowrap mb-2 input-group-sm">
                    <span class="input-group-text" id="addon-wrapping">ECR Details Id:</span>
                    <input v-model="frmEcrDetails.ecrDetailsId"  type="text" class="form-control form-control-lg" aria-describedby="addon-wrapping">
                </div>
                <div class="col-sm-6">
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Type of Part:</span>
                        <Multiselect
                            v-model="frmEcrDetails.typeOfPart"
                            :options="ecrVar.optTypeOfPart"
                            placeholder="Select an option"
                            :searchable="true"
                            :close-on-select="true"
                        />
                    </div>
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Change Imp Date:</span>
                        <input v-model="frmEcrDetails.changeImpDate" type="date" class="form-control form-control-lg" aria-describedby="addon-wrapping">
                    </div>
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Docs To Be Submitted</span>
                        <input v-model="frmEcrDetails.docToBeSub" type="text" class="form-control form-control-lg" aria-describedby="addon-wrapping">
                    </div>
                 </div>
                <div class="col-sm-6">
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Docs Submission Date:</span>
                        <input v-model="frmEcrDetails.docSubDate"  type="date" class="form-control form-control-lg" aria-describedby="addon-wrapping">
                    </div>
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Remarks:</span>
                        <input v-model="frmEcrDetails.remarks"  type="text" class="form-control form-control-lg" aria-describedby="addon-wrapping">
                    </div>
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Customer Approval</span>
                        <Multiselect
                            v-model="frmEcrDetails.customerApproval"
                            :options="commonVar.optConditions"
                            placeholder="Select an option"
                            :searchable="true"
                            :close-on-select="true"
                        />
                    </div>
                </div>
            </div>
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
    import useMan from '../../js/composables/man.js';
    import useForm from '../../js/composables/utils/useForm.js'
    import useCommon from '../../js/composables/common.js';
    import DataTable from 'datatables.net-vue3';
    import DataTablesCore from 'datatables.net-bs5';
    DataTable.use(DataTablesCore);
    const {
        modal,
        ecrVar,
        tblEcrDetails,
        frmEcrDetails,
        frmEcrReasonRows,
        descriptionOfChangeParams,
        reasonOfChangeParams,
        typeOfPartParams,
        getDropdownMasterByOpt,
        getRapidxUserByIdOpt,
        axiosFetchData,
        getEcrDetailsId,
        saveEcrDetails,
    } = useEcr();
    const {
        commonVar,
        getCurrentApprover,
        getSession,
    } = useCommon();
    const modalSaveEnvironment = ref(null);
    const modalSaveEcrDetail = ref(null);
    const isSelectReadonly = ref(null);
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
        {   data: 'get_customer_approval'} ,
        {   data: 'remarks'} ,
    ];
    const tblEcrByStatusColumns = [
        {   data: 'get_actions',
            orderable: false,
            searchable: false,
            createdCell(cell){
                let btnGetEcrId = cell.querySelector('#btnGetEcrId');
                if(btnGetEcrId != null){
                    btnGetEcrId.addEventListener('click',function(){
                        let ecrId = this.getAttribute('ecr-id');
                        // frmEnviroment.value.ecrsId = ecrId;
                        tblEcrDetails.value.dt.ajax.url("api/load_ecr_details_by_ecr_id?ecr_id="+ecrId).draw()
                        modal.SaveEnvironment.show();
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
    onMounted( async ()=>{
        modal.SaveEnvironment = new Modal(modalSaveEnvironment.value.modalRef,{ keyboard: false });
        modal.SaveEcrDetail = new Modal(modalSaveEcrDetail.value.modalRef,{ keyboard: false });
        await getDropdownMasterByOpt(descriptionOfChangeParams);
        await getDropdownMasterByOpt(reasonOfChangeParams);
        await getDropdownMasterByOpt(typeOfPartParams);
        // modal.SaveEnvironment.show();
    })
</script>


