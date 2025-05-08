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
                    <!-- <table class="table" id="dataTable" width="100%" cellspacing="0">
                    </table> -->
                        <DataTable
                            width="100%" cellspacing="0"
                            class="table mt-2"
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
                        class="table  table-responsive mt-2"
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
            <div class="row mt-3">
                <div class="card">
                    <div class="card-body overflow-auto">
                        <DataTable
                        width="100%" cellspacing="0"
                        class="table mt-2"
                        ref="tblManDetails"
                        :columns="tblManColumns"
                        ajax="api/load_man_by_ecr_id"
                        :options="{
                            serverSide: true, //Serverside true will load the network
                            columnDefs:[
                                // {orderable:false,target:[0]}
                            ]
                        }"
                    >
                        <thead>
                            <tr>
                                <th> Action </th>
                               <th> First Assign </th>
                               <th> Long Interval </th>
                               <th> Change </th>
                               <th> Process Name </th>
                               <th> Working Time </th>
                               <th> Qc Inspector /Operator </th>
                               <th> Trainer </th>
                               <th> Trainer SampleSize </th>
                               <th> Trainer Result </th>
                               <th> Lqc Supervisor </th>
                               <th> Lqc SampleSize </th>
                               <th> Lqc Result </th>
                               <th> Process Change Factor </th>
                            </tr>
                        </thead>
                    </DataTable>
                    </div>
                </div>
            </div>
        </template>
        <template #footer>
            <button type="button" id= "closeBtn" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Close</button>
        </template>
    </ModalComponent>
    <ModalComponent icon="fa-user" modalDialog="modal-dialog modal-lg" title="Ecr Details" @add-event="saveEcrDetails()" ref="modalSaveEcrDetail">
        <template #body>
             <!-- Description of Change / Reason for Change -->
             <EcrChangeComponent :frmEcrReasonRows="frmEcrReasonRows" :optDescriptionOfChange="ecrVar.optDescriptionOfChange" :optReasonOfChange="ecrVar.optReasonOfChange">
            </EcrChangeComponent>
            <div class="row">
                <div class="input-group flex-nowrap mb-2 input-group-sm">
                    <span class="input-group-text" id="addon-wrapping">ECR Id:</span>
                    <input v-model="frmEcrDetails.ecrsId" type="text" class="form-control form-control-lg" aria-describedby="addon-wrapping">
                </div>
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
                </div>
            </div>
        </template>
        <template #footer>
            <button type="button" id= "closeBtn" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success btn-sm"><li class="fas fa-save"></li> Save</button>
        </template>
    </ModalComponent>
    <ModalComponent icon="fa-user" modalDialog="modal-dialog modal-lg" title="Man Details" @add-event="saveManDetails" ref="modalSaveManDetails">
        <template #body>
            <div class="row">
                <div class="input-group flex-nowrap mb-2 input-group-sm">
                    <span class="input-group-text" id="addon-wrapping">ECR Id:</span>
                    <input v-model="frmMan.ecrsId" type="text" class="form-control form-control-lg" aria-describedby="addon-wrapping">
                </div>
                <div class="input-group flex-nowrap mb-2 input-group-sm">
                    <span class="input-group-text" id="addon-wrapping">Man Id:</span>
                    <input  v-model="frmMan.manId"  type="text" class="form-control form-control-lg" aria-describedby="addon-wrapping">
                </div>
                <div class="col-sm-6">
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">F:</span>
                        <Multiselect
                            v-model="frmMan.firstAssign"
                            :options="manVar.optYesNo"
                            placeholder="Select an option"
                            :searchable="true"
                            :close-on-select="true"
                        />
                    </div>
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">L:</span>
                        <Multiselect
                            v-model="frmMan.longInterval"
                            :options="manVar.optYesNo"
                            placeholder="Select an option"
                            :searchable="true"
                            :close-on-select="true"
                        />
                    </div>
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">C:</span>
                        <Multiselect
                            v-model="frmMan.change"
                            :options="manVar.optYesNo"
                            placeholder="Select an option"
                            :searchable="true"
                            :close-on-select="true"
                        />
                    </div>
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Process Name:</span>
                        <input v-model="frmMan.processName" type="date" class="form-control form-control-lg" aria-describedby="addon-wrapping">
                    </div>
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Working Time</span>
                        <input v-model="frmMan.workingTime" type="time" class="form-control form-control-lg" aria-describedby="addon-wrapping">
                    </div>
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Qc Inspector/ Operator:</span>
                        <Multiselect
                            v-model="frmMan.qcInspectorOperator"
                            :options="manVar.optUserMaster"
                            placeholder="Select an option"
                            :searchable="true"
                            :close-on-select="true"
                        />
                    </div>
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Process Change Factor:</span>
                        <textarea v-model="frmMan.processChangeFactor" class="form-control form-control-lg" aria-describedby="addon-wrapping">
                        </textarea>
                    </div>
                 </div>
                <div class="col-sm-6">
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Trainer:</span>
                        <Multiselect
                            v-model="frmMan.trainer"
                            :options="manVar.optUserMaster"
                            placeholder="Select an option"
                            :searchable="true"
                            :close-on-select="true"
                        />
                    </div>
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Trainer Sample Size:</span>
                        <input v-model="frmMan.trainerSampleSize" type="number" class="form-control form-control-lg" aria-describedby="addon-wrapping">
                    </div>
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Trainer Result:</span>
                        <Multiselect
                            v-model="frmMan.trainerResult"
                            :options="manVar.optResult"
                            placeholder="Select an option"
                            :searchable="true"
                            :close-on-select="true"
                        />
                    </div>
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">LQC Supervisor:</span>
                        <Multiselect
                            v-model="frmMan.lqcSupervisor"
                            :options="manVar.optUserMaster"
                            placeholder="Select an option"
                            :searchable="true"
                            :close-on-select="true"
                        />
                    </div>
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">LQC Sample Size:</span>
                        <input v-model="frmMan.lqcSampleSize" type="number" class="form-control form-control-lg" aria-describedby="addon-wrapping">
                    </div>
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">LQC Result:</span>
                        <Multiselect
                            v-model="frmMan.lqcResult"
                            :options="manVar.optJudgment"
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
    import DataTable from 'datatables.net-vue3';
    import DataTablesCore from 'datatables.net-bs5';
    DataTable.use(DataTablesCore)

    const { axiosSaveData } = useForm(); // Call the useFetch function
    //composables export function
    const {
        modal,
        ecrVar,
        frmEcrDetails,
        frmEcrReasonRows,
        descriptionOfChangeParams,
        reasonOfChangeParams,
        typeOfPartParams,
        getDropdownMasterByOpt,
        getRapidxUserByIdOpt,
        axiosFetchData,
    } = useEcr();

    const {
        frmMan,
        manVar,
    } = useMan();

    //ref state
    const tblEcrByStatus = ref(null);
    const tblEcrDetails = ref(null);
    const tblManDetails = ref(null);
    const modalSaveMan = ref(null);
    const modalSaveEcrDetail = ref(null);
    const modalSaveManDetails = ref(null);

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
    const tblManColumns = [
        {   data: 'get_actions',
            orderable: false,
            searchable: false,
            createdCell(cell){
                let btnManDetailsId = cell.querySelector('#btnManDetailsId');
                if(btnManDetailsId != null){
                    btnManDetailsId.addEventListener('click',function(){
                        let manDetailsId = this.getAttribute('man-details-id');
                        getManById(manDetailsId);
                        modal.SaveManDetails.show();
                    });
                }
            }
        } ,
        {   data: 'first_assign'} ,
        {   data: 'long_interval'} ,
        {   data: 'change'} ,
        {   data: 'process_name'} ,
        {   data: 'working_time'} ,
        {   data: 'qc_inspector_operator'} ,
        {   data: 'trainer'} ,
        {   data: 'trainer_sample_size'} ,
        {   data: 'trainer_result'} ,
        {   data: 'lqc_supervisor'} ,
        {   data: 'lqc_sample_size'} ,
        {   data: 'lqc_result'} ,
        {   data: 'process_change_factor'} ,
    ];

    const qcInspectorOperatorParams = {
        globalVar: manVar.optUserMaster,
        formModel: toRef(frmMan.value,'qcInspectorOperator'),
        selectedVal: '',
    };
    const trainerParams = {
        globalVar: manVar.optUserMaster,
        formModel: toRef(frmMan.value,'qcInspectorOperator'),
        selectedVal: '',
    };
    const qcSupervisor1Params = {
        globalVar: manVar.optUserMaster,
        formModel: toRef(frmMan.value,'qcInspectorOperator'),
        selectedVal: '',
    };



    onMounted( async ()=>{
        modal.SaveMan = new Modal(modalSaveMan.value.modalRef,{ keyboard: false });
        modal.SaveEcrDetail = new Modal(modalSaveEcrDetail.value.modalRef,{ keyboard: false });
        modal.SaveManDetails = new Modal(modalSaveManDetails.value.modalRef,{ keyboard: false });
        // modal.SaveManDetails.show();
        await getDropdownMasterByOpt(descriptionOfChangeParams);
        await getDropdownMasterByOpt(reasonOfChangeParams);
        await getDropdownMasterByOpt(typeOfPartParams);
        await getRapidxUserByIdOpt(qcInspectorOperatorParams);


    })

    const getEcrDetailsId = async (ecrDetailsId) =>
    {
        let params = {
            ecrDetailsId : ecrDetailsId
        }
        axiosFetchData(params,'api/get_ecr_details_id',function(response){
            let ecrDetails = response.data.ecrDetail;
            console.log('ecrDetailsId',ecrDetailsId);

            frmEcrDetails.value.ecrDetailsId = ecrDetailsId;
            frmEcrDetails.value.changeImpDate =ecrDetails.change_imp_date
            frmEcrDetails.value.docSubDate =ecrDetails.doc_sub_date
            frmEcrDetails.value.docToBeSub =ecrDetails.doc_to_be_sub
            frmEcrDetails.value.remarks =ecrDetails.remarks
            frmEcrDetails.value.typeOfPart = ecrDetails.dropdown_master_detail_type_of_part  === null ? 0: ecrDetails.dropdown_master_detail_type_of_part.id;
            frmEcrReasonRows.value[0].descriptionOfChange = ecrDetails.dropdown_master_detail_description_of_change.id;
            frmEcrReasonRows.value[0].reasonOfChange = ecrDetails.dropdown_master_detail_reason_of_change.id;
            console.log('ecrDetails',ecrDetails);
        });
    }
    const getManById = async (manId) =>
    {
        let params = {
            manId : manId
        }
        axiosFetchData(params,'api/get_man_by_id',function(response){
            let data = response.data;
            let man = data.man;

            frmMan.value.firstAssign = man.first_assign;
            frmMan.value.longInterval = man.long_interval;
            frmMan.value.change = man.change;
            frmMan.value.processName = man.process_name;
            frmMan.value.workingTime = man.working_time;
            frmMan.value.qcInspectorOperator = man.qc_inspector_operator;
            man.trainer;
            man.trainer_sample_size;
            man.trainer_result;
            man.lqc_supervisor;
            man.lqc_sample_size;
            man.lqc_result;
            man.process_change_factor;


        });
    }
    const saveEcrDetails = async () => {
        let formData = new FormData();
        //Append form data
        [
            ["ecr_details_id", frmEcrDetails.value.ecrDetailsId],
            ["change_imp_date", frmEcrDetails.value.changeImpDate],
            ["type_of_part", frmEcrDetails.value.typeOfPart],
            ["doc_sub_date", frmEcrDetails.value.docSubDate],
            ["doc_to_be_sub", frmEcrDetails.value.docToBeSub],
            ["remarks", frmEcrDetails.value.remarks],
        ].forEach(([key, value]) =>
            formData.append(key, value)
        );
        axiosSaveData(formData,'api/save_ecr_details', (response) =>{
            console.log(response);
        });
    }
    const saveManDetails = async () => {
        let formData = new FormData();
        //Append form data
        [
            ["ecrs_id", frmMan.value.ecrsId],
            ["first_assign", frmMan.value.firstAssign],
            ["long_interval", frmMan.value.longInterval],
            ["change", frmMan.value.change],
            ["process_name", frmMan.value.processName],
            ["working_time", frmMan.value.workingTime],
            ["trainer", frmMan.value.trainer],
            ["qc_inspector_operator", frmMan.value.qcInspectorOperator],
            ["trainer_sample_size", frmMan.value.trainerSampleSize],
            ["trainer_result", frmMan.value.trainerResult],
            ["lqc_supervisor", frmMan.value.lqcSupervisor],
            ["lqc_sample_size", frmMan.value.lqcSampleSize],
            ["lqc_result", frmMan.value.lqcResult],
            ["process_change_factor", frmMan.value.processChangeFactor],
        ].forEach(([key, value]) =>
            formData.append(key, value)
        );
        axiosSaveData(formData,'api/save_man', (response) =>{
            console.log(response);


        });
    }
</script>


