<template>
    <div class="container-fluid px-4">
        <div class="card-body overflow-auto">
            <div class="container-fluid px-4">
                <div class="card mt-5"  style="width: 100%;">
                    <div class="card-body overflow-auto">
                        <div class="container-fluid px-4">
                            <ol class="breadcrumb mb-4">
                                <li class="breadcrumb-item active">Method</li>
                            </ol>
                            <div class="table-responsive">
                                <!-- :ajax="api/load_ecr_by_status?status=AP" -->
                                <DataTable
                                    width="100%" cellspacing="0"
                                    class="table mt-2"
                                    ref="tblEcrByStatus"
                                    :columns="tblEcrByStatusColumns"
                                    ajax="api/load_method_ecr_by_status?category=Method"
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
                                            <th>Attachment</th>
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
    <ModalComponent icon="fa-user" modalDialog="modal-dialog modal-xl" title="SaveMethod" @add-event="" ref="modalSaveMethod">
        <template #body>
            <div class="row">
                <div class="card">
                    <div class="card-body overflow-auto">
                        <DataTable
                            width="100%" cellspacing="0"
                            class="table  table-responsive mt-2"
                            ref="tblEcrDetails"
                            :columns="tblEcrDetailColumns"
                            ajax="api/load_ecr_details_by_ecr_id?ecr_id=4"
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
                                <th> Doc Sub Date</th>
                                <th> Doc To Be Sub</th>
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
</template>

<script setup>
   import {ref , onMounted,reactive, toRef} from 'vue';
    import ModalComponent from '../../js/components/ModalComponent.vue';
    import EcrChangeComponent from '../components/EcrChangeComponent.vue';
    import ModalSpecialInspectionComponent from '../components/ModalSpecialInspectionComponent.vue';
    import useEcr from '../../js/composables/ecr.js';
    import useMachine from '../../js/composables/machine.js';
    import useForm from '../../js/composables/utils/useForm.js'
    import DataTable from 'datatables.net-vue3';
    import DataTablesCore from 'datatables.net-bs5';
    import useCommon from '../../js/composables/common.js';
    DataTable.use(DataTablesCore);

    const { axiosSaveData } = useForm(); // Call the useForm function
    const {
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
        machineVar,
        frmMachine,
    } = useMachine();
    const {
        modal,
        commonVar,
        tblSpecialInspection,
        tblSpecialInspectionColumns,
        modalSaveSpecialInspection,
        specialInsQcInspectorParams,
        saveSpecialInspection,
        getCurrentApprover,
        getCurrentPmiInternalApprover,
        frmSpecialInspection,
    } = useCommon();

    const modalSaveMethod = ref(null);
    const tblEcrByStatus = ref(null);
    const isModal = ref('Edit');
    const selectedEcrsId = ref(null);
    const selectedMachinesId = ref(null);

    

    const tblEcrByStatusColumns = [
        {   data: 'get_actions',
            orderable: false,
            searchable: false,
            createdCell(cell){
                let btnGetEcrId = cell.querySelector('#btnGetEcrId');
                let btnViewMachineById = cell.querySelector('#btnViewMachineById');
                if(btnGetEcrId != null){
                    btnGetEcrId.addEventListener('click',function(){
                        let ecrsId = this.getAttribute('ecrs-id');
                        let machinesId = this.getAttribute('machines-id');
                        selectedEcrsId.value = ecrsId;
                        selectedMachinesId.value = machinesId;
                        isModal.value = 'Edit';

                        // tblEcrDetails.value.dt.ajax.url("api/load_ecr_details_by_ecr_id?ecr_id="+ecrsId).draw();
                        // tblSpecialInspection.value.dt.ajax.url("api/load_special_inspection_by_ecr_id?ecrsId="+ecrsId).draw();
                        getRapidxUserByIdOpt(prdnAssessedByParams);
                        getRapidxUserByIdOpt(prdnCheckedByParams);
                        getRapidxUserByIdOpt(ppcAssessedByParams);
                        getRapidxUserByIdOpt(ppcCheckedByParams);
                        getRapidxUserByIdOpt(mainEnggAssessedByParams);
                        getRapidxUserByIdOpt(mainEnggCheckedByParams);
                        getRapidxUserByIdOpt(proEnggAssessedByParams);
                        getRapidxUserByIdOpt(proEnggCheckedByParams);
                        getRapidxUserByIdOpt(qcAssessedByParams);
                        getRapidxUserByIdOpt(qcCheckedByParams);
                        modal.SaveMethod.show();
                    });
                }
                if(btnViewMachineById != null){ //madi krstevski
                    btnViewMachineById.addEventListener('click',function(){
                        let ecrsId = this.getAttribute('ecrs-id');
                        let machinesId = this.getAttribute('machines-id');
                        let machineStatus = this.getAttribute('machine-status');
                        let machineApproverParams = {
                            selectedId : machinesId,
                            approvalType : 'machineApproval'
                        }
                        let pmiApproverParams = {
                            selectedId : ecrsId,
                            approvalType : 'pmiApproval'
                        }
                        selectedEcrsId.value = ecrsId;
                        selectedMachinesId.value = machinesId;
                        isModal.value = 'View';
                        currentStatus.value = machineStatus;

                        tblEcrDetails.value.dt.ajax.url("api/load_ecr_details_by_ecr_id?ecr_id="+ecrsId).draw();
                        if( machineStatus === 'FORAPP'){
                            getCurrentApprover(machineApproverParams);
                            tblMachineApproverSummary.value.dt.ajax.url("api/load_machine_approver_summary_material_id?machinesId="+machinesId).draw();
                        }
                        if( machineStatus === 'PMIAPP'){
                            getCurrentApprover(pmiApproverParams);
                            tblPmiInternalApproverSummary.value.dt.ajax.url("api/load_pmi_internal_approval_summary?ecrsId="+ecrsId).draw()
                        }
                        modal.SaveMethod.show();
                    });
                }
            }
        } ,
        {   data: 'get_status'} ,
        {   data: 'get_attachment',
            orderable: false,
            searchable: false,
            createdCell(cell){
                let btnViewMachineRef = cell.querySelector('#btnViewMachineRef');
                if(btnViewMachineRef != null){
                    btnViewMachineRef.addEventListener('click',function(){
                        let ecrsId = this.getAttribute('ecrs-id');
                        getMachineRefByEcrsId(ecrsId);
                    });
                }
            }
        } ,
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

    onMounted( async ()=>{
        modal.SaveMethod = new Modal(modalSaveMethod.value.modalRef,{ keyboard: false });
        // modal.SaveMethod.show();
    })
</script>


