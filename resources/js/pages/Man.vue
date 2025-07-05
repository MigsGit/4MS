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
                        <!-- :ajax="api/load_ecr_by_status?status=AP" -->
                        <DataTable
                            width="100%" cellspacing="0"
                            class="table mt-2"
                            ref="tblEcrByStatus"
                            :columns="ecrColumns"
                            ajax="api/load_ecr_man_by_status?category=Man"
                            :options="{
                                serverSide: true, //Serverside true will load the network
                                columnDefs:[
                                    // {orderable:false,target:[0]}
                                ]
                            }"
                        >
                            <thead>
                                <tr>
                                    <th>
                                        <font-awesome-icon class="nav-icon" icon="fa-cogs" />
                                    </th>
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
                                <th>
                                    <font-awesome-icon class="nav-icon" icon="fa-cogs" />
                                </th>
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
                    <div class="row mt-2">
                        <div class="col-12">
                            <button @click="addManDetails()" type="button" class="btn btn-primary btn-sm mb-2" style="float: right !important;"><i class="fas fa-plus"></i> Add Man Details</button>
                        </div>
                    </div>
                    <div class="card-body overflow-auto">
                        <DataTable
                        width="100%" cellspacing="0"
                        class="table mt-2"
                        ref="tblManDetails"
                        :columns="tblManColumns"
                        ajax="api/load_man_by_ecr_id"
                        :options="{
                            serverSide: true, //Serverside true will load the network  //ecrsId
                            columnDefs:[
                                // {orderable:false,target:[0]}
                            ]
                        }"
                    >
                        <thead>
                            <tr>
                                <th>
                                    <font-awesome-icon class="nav-icon" icon="fa-cogs" />
                                </th>
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
            <div class="row mt-3">
                <div class="card">
                    <div class="row mt-2">
                        <div class="col-12">
                            <button @click="btnAddSpecialInspection()" type="button" class="btn btn-primary btn-sm mb-2" style="float: right !important;"><i class="fas fa-plus"></i> Add SA Details</button>
                        </div>
                    </div>
                    <div class="card-body overflow-auto">
                        <DataTable
                            width="100%" cellspacing="0"
                            class="table mt-2"
                            ref="tblSpecialInspection"
                            :columns="tblSpecialInspectionColumns"
                            ajax="api/load_special_inspection_by_ecr_id"
                            :options="{
                                serverSide: true, //Serverside true will load the network  //ecrsId
                                columnDefs:[
                                    // {orderable:false,target:[0]}
                                ]
                            }"
                        >
                        <!-- <thead>
                            <tr>
                                <th>
                                    <font-awesome-icon class="nav-icon" icon="fa-cogs" />
                                </th>
                                <th> Product Detail </th>
                                <th> Lot Qty </th>
                                <th> Samples </th>
                                <th> Mod </th>
                                <th> Mod Qty </th>
                                <th> Judgement </th>
                                <th> Inspection Date </th>
                                <th> Inspector </th>
                                <th> Remarks </th>
                            </tr>
                        </thead> -->
                        </DataTable>
                    </div>
                </div>
            </div>
            <!-- v-show="isModal === 'View' && currentStatus === 'PMIAPP'"  -->
            <div class="row mt-3">
                <div class="card mb-2">
                        <h5 class="mb-0">
                            <button id="" class="btn btn-link collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePmiInternalApprovalSummary" aria-expanded="true" aria-controls="collapsePmiInternalApprovalSummary">
                                ECR Approver Summary
                            </button>
                        </h5>
                    <div id="collapsePmiInternalApprovalSummary" class="collapse show" data-bs-parent="#accordionMain">
                        <div class="card-header">
                            <h3> PMI Approvers </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <DataTable
                                        width="100%" cellspacing="0"
                                        class="table mt-2"
                                        ref="tblPmiInternalApproverSummary"
                                        :columns="tblPmiInternalApproverSummaryColumns"
                                        ajax="api/load_pmi_internal_approval_summary"
                                        :options="{
                                            paging:false,
                                            serverSide: true, //Serverside true will load the network
                                            ordering: false,
                                        }"
                                    >
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Role</th>
                                                <th>Approver Name</th>
                                                <th>Remarks</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                    </DataTable>
                                </div>
                            </div>
                        </div>
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
                </div>
            </div>
        </template>
        <template #footer>
            <button type="button" id= "closeBtn" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success btn-sm"><li class="fas fa-save"></li> Save</button>
        </template>
    </ModalComponent>
    <ModalComponent icon="fa-user" modalDialog="modal-dialog modal-lg" title="Man Details" @add-event="saveManDetails()" ref="modalSaveManDetails">
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
                            :options="commonVar.optYesNo"
                            placeholder="Select an option"
                            :searchable="true"
                            :close-on-select="true"
                        />
                    </div>
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">L:</span>
                        <Multiselect
                            v-model="frmMan.longInterval"
                            :options="commonVar.optYesNo"
                            placeholder="Select an option"
                            :searchable="true"
                            :close-on-select="true"
                        />
                    </div>
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">C:</span>
                        <Multiselect
                            v-model="frmMan.change"
                            :options="commonVar.optYesNo"
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
                            :options="commonVar.optUserMaster"
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
                            :options="commonVar.optUserMaster"
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
                            :options="commonVar.optResult"
                            placeholder="Select an option"
                            :searchable="true"
                            :close-on-select="true"
                        />
                    </div>
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">LQC Supervisor:</span>
                        <Multiselect
                            v-model="frmMan.lqcSupervisor"
                            :options="commonVar.optUserMaster"
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
                            :options="commonVar.optJudgment"
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
    <ModalComponent icon="fa-user" modalDialog="modal-dialog modal-lg" title="Man Checklist" ref="modalManChecklist">
        <template #body>
            <div class="row mt-3">
                <!-- Man -->
                <div class="card mb-2">
                        <h5 class="mb-0">
                            <button id="" class="btn btn-link collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMan" aria-expanded="true" aria-controls="collapseMan">
                                Man
                            </button>
                        </h5>
                    <div id="collapseMan" class="collapse show" data-bs-parent="#accordionMain">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <DataTable
                                        width="100%" cellspacing="0"
                                        class="table mt-2"
                                        ref="tblManChecklist"
                                        :columns="tblManChecklistColumns"
                                        ajax="api/load_man_checklist?dropdown_masters_id=7"
                                        :options="{
                                            paging:false,
                                            serverSide: true, //Serverside true will load the network
                                            columnDefs:[
                                                {
                                                    orderable:false,target:[0,1],
                                                }
                                            ]
                                        }"
                                    >
                                        <thead>
                                            <tr>
                                                <th style="width:80%">Requirement</th>
                                                <th style="width:20%">
                                                    <font-awesome-icon class="nav-icon" icon="fa-cogs" />
                                                </th>
                                            </tr>
                                        </thead>
                                    </DataTable>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Material -->
                <div class="card mb-2">
                        <h5 class="mb-0">
                            <button id="" class="btn btn-link collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMat" aria-expanded="true" aria-controls="collapseMat">
                                Material
                            </button>
                        </h5>
                    <div id="collapseMat" class="collapse" data-bs-parent="#accordionMain">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <DataTable
                                        width="100%" cellspacing="0"
                                        class="table mt-2"
                                        ref="tblMatChecklist"
                                        :columns="tblManChecklistColumns"
                                        ajax="api/load_man_checklist?dropdown_masters_id=8"
                                        :options="{
                                            paging:false,
                                            serverSide: true, //Serverside true will load the network
                                            ordering:false,
                                        }"
                                    >
                                        <thead>
                                            <tr>
                                                <th style="width:80%">Requirement</th>
                                                <th style="width:20%">
                                                    <font-awesome-icon class="nav-icon" icon="fa-cogs" />
                                                </th>
                                            </tr>
                                        </thead>
                                    </DataTable>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <template #footer>
            <button type="button" id= "closeBtn" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            <!-- <button type="submit" class="btn btn-success btn-sm"><font-awesome-icon class="nav-icon" icon="fas fa-save" />&nbsp;     Save</button> -->
        </template>
    </ModalComponent>
    <ModalComponent icon="fa-user" modalDialog="modal-dialog modal-lg" title="Special Inspection" @add-event="saveSpecialInspection()" ref="modalSaveSpecialInspection">
        <template #body>
            <ModalSpecialInspectionComponent :commonVar="commonVar" :frmSpecialInspection="frmSpecialInspection">
            </ModalSpecialInspectionComponent>
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
    import ModalSpecialInspectionComponent from '../components/ModalSpecialInspectionComponent.vue';
    import EcrChangeComponent from '../components/EcrChangeComponent.vue';
    import useEcr from '../../js/composables/ecr.js';
    import useMan from '../../js/composables/man.js';
    import useForm from '../../js/composables/utils/useForm.js'
    import DataTable from 'datatables.net-vue3';
    import DataTablesCore from 'datatables.net-bs5';
    import useCommon from '../../js/composables/common.js';
    DataTable.use(DataTablesCore)
    const { axiosSaveData } = useForm(); // Call the useFetch function

    //composables export function
    const {
        modalEcr,
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
        frmMan,
    } = useMan();
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

    //ref state
    const tblEcrByStatus = ref(null);
    const tblManChecklist = ref(null);
    const tblMatChecklist = ref(null);
    const isSelectReadonly  = ref(true);
    const tblManDetails = ref(null);
    const modalSaveMan = ref(null);
    const modalSaveEcrDetail = ref(null);
    const modalSaveManDetails = ref(null);
    const modalManChecklist = ref(null);
    const currentManDetailsId = ref(null);
    const tblPmiInternalApproverSummary = ref(null);

    const ecrColumns = [
        {   data: 'get_actions',
            orderable: false,
            searchable: false,
            createdCell(cell){
                let btnGetEcrId = cell.querySelector('#btnGetEcrId');
                if(btnGetEcrId != null){
                    btnGetEcrId.addEventListener('click',function(){
                        let ecrId = this.getAttribute('ecr-id');
                        frmMan.value.ecrsId = ecrId;
                        frmSpecialInspection.value.ecrsId = ecrId;
                        tblEcrDetails.value.dt.ajax.url("api/load_ecr_details_by_ecr_id?ecr_id="+ecrId).draw()
                        tblManDetails.value.dt.ajax.url("api/load_man_by_ecr_id?ecrsId="+ecrId).draw()
                        tblSpecialInspection.value.dt.ajax.url("api/load_special_inspection_by_ecr_id?ecrsId="+ecrId).draw()
                        modal.SaveMan.show();
                        tblPmiInternalApproverSummary.value.dt.ajax.url("api/load_pmi_internal_approval_summary?ecrsId="+ecrId).draw()
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
                        modalEcr.SaveEcrDetail.show();
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
                let btnManChecklistId = cell.querySelector('#btnManChecklistId');
                if(btnManChecklistId != null){
                    btnManChecklistId.addEventListener('click',function(){
                        let manDetailsId = this.getAttribute('man-details-id');
                        currentManDetailsId.value = manDetailsId;

                        tblManChecklist.value.dt.ajax.url("api/load_man_checklist?dropdown_masters_id=7 && manDetailsId="+manDetailsId).draw();
                        tblMatChecklist.value.dt.ajax.url("api/load_man_checklist?dropdown_masters_id=8 && manDetailsId="+manDetailsId).draw();
                        modal.ManChecklist.show();
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
    const tblManChecklistColumns = [
        {   data: 'dropdown_masters_details'} ,
        {   data: 'get_actions',
            createdCell(cell){
                let btnChangeManChecklistDecision = cell.querySelector('#btnChangeManChecklistDecision');
                if(btnChangeManChecklistDecision != null){
                    btnChangeManChecklistDecision.addEventListener('change',function(){
                        let manChecklistsId = this.getAttribute('man-checklists-id');
                        let manChecklistValue = this.value;
                        let dropdownMasterDetailsId = this.getAttribute('dropdown-master-details-id');
                        let params = {
                            manChecklistsId : manChecklistsId,
                            manChecklistValue : manChecklistValue,
                            dropdownMasterDetailsId : dropdownMasterDetailsId,
                            btnChangeManChecklistDecisionClass: this.classList,
                        }
                        changeManChecklistDecision(params);
                    });
                }
            }
        }
    ];
    const qcInspectorOperatorParams = {
        globalVar: commonVar.optUserMaster,
        formModel: toRef(frmMan.value,'qcInspectorOperator'),
        selectedVal: '',
    };
    const tblPmiInternalApproverSummaryColumns = [
        {   data: 'get_count'} ,
        {   data: 'get_role'} ,
        {   data: 'get_approver_name'} ,
        {   data: 'remarks'},
        {   data: 'get_status'} ,
    ];

    const trainerParams = {
        globalVar: commonVar.optUserMaster,
        formModel: toRef(frmMan.value,'qcInspectorOperator'),
        selectedVal: '',
    };
    const qcSupervisor1Params = {
        globalVar: commonVar.optUserMaster,
        formModel: toRef(frmMan.value,'qcInspectorOperator'),
        selectedVal: '',
    };

    onMounted( async ()=>{
        modal.SaveMan = new Modal(modalSaveMan.value.modalRef,{ keyboard: false });
        modalEcr.SaveEcrDetail = new Modal(modalSaveEcrDetail.value.modalRef,{ keyboard: false });
        modal.SaveManDetails = new Modal(modalSaveManDetails.value.modalRef,{ keyboard: false });
        modal.ManChecklist = new Modal(modalManChecklist.value.modalRef,{ keyboard: false });
        modal.modalSaveSpecialInspection = new Modal(modalSaveSpecialInspection.value.modalRef,{ keyboard: false });
        await getDropdownMasterByOpt(descriptionOfChangeParams);
        await getDropdownMasterByOpt(reasonOfChangeParams);
        await getDropdownMasterByOpt(typeOfPartParams);
        await getRapidxUserByIdOpt(qcInspectorOperatorParams);
        await getRapidxUserByIdOpt(specialInsQcInspectorParams);

    })

    const addManDetails = async () => {
        modal.SaveManDetails.show();
    }
    const btnAddSpecialInspection = async () => {
        modal.modalSaveSpecialInspection.show();
    }
    const getManById = async (manId) =>
    {
        let apiParams = {
            manId : manId
        }
        axiosFetchData(apiParams,'api/get_man_by_id',function(response){
            let data = response.data;
            let man = data.man;
            frmMan.value.manId = man.id;
            frmMan.value.firstAssign = man.first_assign;
            frmMan.value.longInterval = man.long_interval;
            frmMan.value.change = man.change;
            frmMan.value.processName = man.process_name;
            frmMan.value.workingTime = man.working_time;
            frmMan.value.qcInspectorOperator = man.qc_inspector_operator;
            frmMan.value.trainer = man.trainer;
            frmMan.value.trainerSampleSize = man.trainer_sample_size;
            frmMan.value.trainerResult = man.trainer_result;
            frmMan.value.lqcSupervisor = man.lqc_supervisor;
            frmMan.value.lqcSampleSize = man.lqc_sample_size;
            frmMan.value.lqcResult = man.lqc_result;
            frmMan.value.processChangeFactor = man.process_change_factor;
        });
    }
    const changeManChecklistDecision = async (params)=>{
        let apiParams = {
            manChecklistsId : params.manChecklistsId,
            manChecklistValue : params.manChecklistValue,
            dropdownMasterDetailsId : params.dropdownMasterDetailsId,
            manDetailsId : currentManDetailsId.value,
        }
        console.log(apiParams);

        axiosFetchData(apiParams,'api/man_checklist_decision_change',function(response){
            params.btnChangeManChecklistDecisionClass.remove("is-invalid");
            tblManChecklist.value.dt.ajax.url("api/load_man_checklist?dropdown_masters_id=7 && manDetailsId="+currentManDetailsId.value).draw();
            tblMatChecklist.value.dt.ajax.url("api/load_man_checklist?dropdown_masters_id=8 && manDetailsId="+currentManDetailsId.value).draw();
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
            modal.SaveManDetails.hide();
            tblManDetails.value.dt.ajax.url("api/load_man_by_ecr_id?ecrsId="+frmMan.value.ecrsId).draw()
        });
    }
</script>


