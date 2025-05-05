<template>
    <div class="container-fluid px-4">
        <h4 class="mt-4">ENGINEERING CHANGE REQUEST</h4>
        <div class="card mt-5"  style="width: 100%;">
            <div class="card-body overflow-auto">
                <div class="container-fluid px-4">
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Engineering Change Request</li>
                    </ol>
                    <div class="table-responsive">
                    <!-- id="dataTable" -->
                    <!-- <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    </table> -->
                    <DataTable
                        width="100%" cellspacing="0"
                        class="table table-bordered mt-2"
                        ref="tblEcr"
                        :columns="columns"
                        ajax="api/load_ecr"
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
    <ModalComponent icon="fa-user" modalDialog="modal-dialog modal-lg" title="ECR" @add-event="frmSaveEcr()" ref="modalSaveEcr">
        <template #body>
                <div class="row">
                    <div class="input flex-nowrap mb-2 input-group-sm">
                            <input  v-model="frmEcr.ecrId" type="hidden" class="form-control form-control" aria-describedby="addon-wrapping">
                    </div>

                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Customer Name:</span>
                        <input v-model="frmEcr.ecrNo" type="text" class="form-control form-control" aria-describedby="addon-wrapping">
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group flex-nowrap mb-2 input-group-sm">
                            <span class="input-group-text" id="addon-wrapping">Category:</span>
                            <select v-model="frmEcr.category" class="form-select form-select-sm" aria-describedby="addon-wrapping">
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
                            <input v-model="frmEcr.customerName" type="text" class="form-control form-control" aria-describedby="addon-wrapping">
                        </div>
                        <div class="input-group flex-nowrap mb-2 input-group-sm">
                            <span class="input-group-text" id="addon-wrapping">Part Name:</span>
                            <input v-model="frmEcr.partName" type="text" class="form-control" aria-describedby="addon-wrapping">
                        </div>
                        <div class="input-group flex-nowrap mb-2 input-group-sm">
                            <span class="input-group-text" id="addon-wrapping">Product Line:</span>
                            <input v-model="frmEcr.productLine" type="text" class="form-control" aria-describedby="addon-wrapping">
                        </div>
                        <div class="input-group flex-nowrap mb-2 input-group-sm">
                            <span class="input-group-text" id="addon-wrapping">Section:</span>
                            <input v-model="frmEcr.section" type="text" class="form-control" aria-describedby="addon-wrapping">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group flex-nowrap mb-2 input-group-sm">
                            <span class="input-group-text" id="addon-wrapping">Internal / External:</span>
                            <select v-model="frmEcr.internalExternal" class="form-select form-select-sm" aria-describedby="addon-wrapping">
                                <option value="" selected disabled>Select</option>
                                <option value="Internal">Internal</option>
                                <option value="External">External</option>
                            </select>
                        </div>
                        <div class="input-group flex-nowrap mb-2 input-group-sm">
                            <span class="input-group-text" id="addon-wrapping">Part Number:</span>
                            <input v-model="frmEcr.partNumber" type="text" class="form-control" aria-describedby="addon-wrapping">
                        </div>
                        <div class="input-group flex-nowrap mb-2 input-group-sm">
                            <span class="input-group-text" id="addon-wrapping">Device Name:</span>
                            <input v-model="frmEcr.deviceName" type="text" class="form-control" aria-describedby="addon-wrapping">
                        </div>
                        <div class="input-group flex-nowrap mb-2 input-group-sm">
                            <span class="input-group-text" id="addon-wrapping">Customer EC No. (If any):</span>
                            <input v-model="frmEcr.customerEcNo" type="text" class="form-control" aria-describedby="addon-wrapping">
                        </div>
                        <div class="input-group flex-nowrap mb-2 input-group-sm">
                            <span class="input-group-text" id="addon-wrapping">Date of Request:</span>
                            <input v-model="frmEcr.dateOfRequest" type="date" class="form-control" aria-describedby="addon-wrapping">
                        </div>
                    </div>
                </div>
                <!-- Description of Change / Reason for Change -->
                <div class="card mb-2">
                    <h5 class="mb-0">
                        <button id="" class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                            Description of Change / Reason for Change
                        </button>
                    </h5>
                    <div id="collapse1" class="collapse" data-bs-parent="#accordionMain">
                        <div class="card-body shadow">
                            <div class="row">
                                <div class="col-12">
                                    <button @click="addEcrReasonRows"type="button" class="btn btn-primary btn-sm mb-2" style="float: right !important;"><i class="fas fa-plus"></i> Add Reason</button>
                                </div>
                                <div class="col-12 overflow-auto" style="height: 300px;">
                                    <table class="table table-responsive">
                                        <thead>
                                            <tr>
                                            <th scope="col">#</th>
                                            <th scope="col" style="width: 60%;">Description of Change</th>
                                            <th scope="col" style="width: 60%;">Reason of Change</th>
                                            <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <tr v-for="(frmEcrReasonRow, index) in frmEcrReasonRows" :key="frmEcrReasonRow.index">
                                                <td>
                                                    {{index+1}}
                                                </td>
                                                <td>
                                                    <Multiselect
                                                        v-model="frmEcrReasonRow.descriptionOfChange"
                                                        :options="ecrVar.optDescriptionOfChange"
                                                        placeholder="Select an option"
                                                        :searchable="true"
                                                        :close-on-select="true"
                                                    />
                                                </td>
                                                <td>
                                                    <Multiselect
                                                        v-model="frmEcrReasonRow.reasonOfChange"
                                                        :close-on-select="true"
                                                        :searchable="true"
                                                        :options="ecrVar.optReasonOfChange"
                                                        placeholder="Select an option"
                                                    />
                                                </td>
                                                <td>
                                                    <button @click="removeEcrReasonRows(index)" class="btn btn-danger btn-sm" type="button" data-item-process="add">
                                                        <font-awesome-icon class="nav-icon" icon="fas fa-trash" />
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Others Disposition -->
                <div class="card mb-2">
                        <h5 class="mb-0">
                            <button id="" class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="true" aria-controls="collapse3">
                                Others Disposition
                            </button>
                        </h5>
                    <div id="collapse3" class="collapse" data-bs-parent="#accordionMain">
                        <div class="card-body shadow">
                            <div class="row">
                                <div class="col-12">
                                    <button @click="btnAddEcrOtherDispoRows()" test="dasd" type="button" class="btn btn-primary btn-sm mb-2" style="float: right !important;"><i class="fas fa-plus"></i> Add Validator</button>
                                </div>
                                <div class="col-12 overflow-auto" style="height: 300px;">
                                    <table class="table table-responsive">
                                        <thead>
                                            <tr>
                                            <th scope="col">#</th>
                                            <th scope="col" style="width: 30%;">Requested Bysss</th>
                                            <th scope="col" style="width: 30%;">Technical Evaluation / Engineering</th>
                                            <th scope="col" style="width: 30%;">Reviewed By / Section Heads</th>
                                            <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr  v-for="(frmEcrOtherDispoRow, index) in frmEcrOtherDispoRows" :key="frmEcrOtherDispoRow.index">
                                                <td>
                                                   {{ index+1 }}
                                                </td>
                                                <td>
                                                    <Multiselect
                                                        v-model="frmEcrOtherDispoRow.requestedBy"
                                                        :close-on-select="true"
                                                        :searchable="true"
                                                        :options="ecrVar.requestedBy"
                                                    />
                                                </td>
                                                <td>
                                                    <Multiselect
                                                        v-model="frmEcrOtherDispoRow.technicalEvaluation"
                                                        :close-on-select="true"
                                                        :searchable="true"
                                                        :options="ecrVar.technicalEvaluation"
                                                    />
                                                </td>
                                                <td>
                                                    <Multiselect
                                                        v-model="frmEcrOtherDispoRow.reviewedBy"
                                                        :close-on-select="true"
                                                        :searchable="true"
                                                        :options="ecrVar.reviewedBy"
                                                    />

                                                </td>
                                                <td>
                                                    <button @click="btnRemoveEcrOtherDispoRows(index)" class="btn btn-danger btn-sm" type="button" data-item-process="add">
                                                        <font-awesome-icon class="nav-icon" icon="fas fa-trash" />
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                 <!-- QA Dispositions -->
                 <div class="card mb-2 h-100">
                        <h5 class="mb-0">
                            <button id="" class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="true" aria-controls="collapse2">
                                QA Dispositions
                            </button>
                        </h5>
                    <div id="collapse2" class="collapse" data-bs-parent="#accordionMain">
                        <div class="card-body shadow">
                            <div class="row">
                                <!-- style="height: 200px;-->
                                <div class="col-12">
                                    <span class="input-group-text" id="addon-wrapping">Agreed By: </span>
                                </div>
                                <div class="col-12 overflow-auto" style="height: 300px;">
                                    <table class="table table-responsive">
                                        <thead>
                                            <tr>
                                            <th scope="col">#</th>
                                            <th scope="col" style="width: 30%;">Senior Supervisor</th>
                                            <th scope="col" style="width: 30%;">QMS Senior Manager</th>
                                            <th scope="col" style="width: 30%;">External</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr >
                                                <td>
                                                1
                                                </td>
                                                <td>
                                                    <Multiselect
                                                        v-model="frmEcrQadRows.qadCheckedBy"
                                                        :close-on-select="true"
                                                        :searchable="true"
                                                        :options="ecrVar.optQadCheckedBy"
                                                    />
                                                </td>
                                                <td>
                                                    <Multiselect
                                                        v-model="frmEcrQadRows.qadApprovedByInternal"
                                                        :close-on-select="true"
                                                        :searchable="true"
                                                        :options="ecrVar.optQadApprovedByInternal"
                                                    />
                                                </td>
                                                <td>
                                                    <Multiselect
                                                        v-model="frmEcrQadRows.qadApprovedByExternal"
                                                        :close-on-select="true"
                                                        :searchable="true"
                                                        :options="ecrVar.optQadApprovedByExternal"
                                                    />
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- PMI Approvers -->
                <div class="card mb-2">
                        <h5 class="mb-0">
                            <button id="" class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="true" aria-controls="collapse4">
                                PMI Approvers
                            </button>
                        </h5>
                    <div id="collapse4" class="collapse" data-bs-parent="#accordionMain">
                        <div class="card-body shadow">
                            <div class="row">
                                <div class="col-12">
                                    <button @click="btnAddEcrPmiApproverRows"type="button" class="btn btn-primary btn-sm mb-2" style="float: right !important;"><i class="fas fa-plus"></i> Add PMI Approvers</button>
                                </div>
                                <div class="col-12 overflow-auto" style="height: 300px;">
                                    <table class="table table-responsive">
                                        <thead>
                                            <tr>
                                            <th scope="col">#</th>
                                            <th scope="col" style="width: 30%;">Prepared By</th>
                                            <th scope="col" style="width: 30%;">Checked By</th>
                                            <th scope="col" style="width: 30%;">Approved By</th>
                                            <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <tr  v-for="(frmEcrPmiApproverRow,index) in frmEcrPmiApproverRows" :key="frmEcrPmiApproverRows.index">
                                                <td>
                                                    {{ index+1 }}
                                                </td>
                                                <td>
                                                    <Multiselect
                                                        v-model="frmEcrPmiApproverRow.preparedBy"
                                                        :close-on-select="true"
                                                        :searchable="true"
                                                        :options="ecrVar.preparedBy"
                                                    />

                                                </td>
                                                <td>
                                                    <Multiselect
                                                        v-model="frmEcrPmiApproverRow.checkedBy"
                                                        :close-on-select="true"
                                                        :searchable="true"
                                                        :options="ecrVar.checkedBy"
                                                    />
                                                </td>
                                                <td>
                                                    <Multiselect
                                                        v-model="frmEcrPmiApproverRow.approvedBy"
                                                        :close-on-select="true"
                                                        :searchable="true"
                                                        :options="ecrVar.approvedBy"
                                                    />
                                                </td>
                                                <td>
                                                    <button @click="btnRemoveEcrPmiApproverRows(index)" class="btn btn-danger btn-sm" type="button" data-item-process="add">
                                                        <font-awesome-icon class="nav-icon" icon="fas fa-trash" />
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template #footer>
                <button type="button" id= "closeBtn" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success btn-sm"><font-awesome-icon class="nav-icon" icon="fas fa-save" />&nbsp;     Save</button>
            </template>
    </ModalComponent>
</template>

<script setup>
    import {ref , onMounted,reactive, toRef} from 'vue';
    import ModalComponent from '../components/ModalComponent.vue';
    import ecr from '../../js/composables/ecr.js';
    import useForm from '../../js/composables/utils/useForm.js'
    const { axiosSaveData } = useForm(); // Call the useFetch function
    import DataTable from 'datatables.net-vue3';
    import DataTablesCore from 'datatables.net-bs5';
import { forEach } from 'lodash';
    DataTable.use(DataTablesCore)
    //composables export function
    const {
        modal,
        ecrVar,
        frmEcrReasonRows,
        frmEcrQadRows,
        frmEcrOtherDispoRows,
        frmEcrPmiApproverRows,
        getDropdownMasterByOpt,
        getRapidxUserByIdOpt,
        axiosFetchData
    } = ecr();
    //ref state
    const frmEcr = ref({
        ecrId: '',
        ecrNo: '',
        category: '',
        customerName: '',
        partName: '',
        productLine: '',
        section: '',
        internalExternal: '',
        partNumber: '',
        deviceName: '',
        customerEcNo: '',
        dateOfRequest: '',
    });
    const modalSaveEcr = ref(null);
    const tblEcr = ref(null);
    // <font-awesome-icon class='nav-icon' icon='fas fa-trash' />
    const columns = [
        {   data: 'get_actions',
            orderable: false,
            searchable: false,
            createdCell(cell){
                let btnGetEcrId = cell.querySelector('#btnGetEcrId');
                if(btnGetEcrId != null){
                    btnGetEcrId.addEventListener('click',function(){
                        let ecrId = this.getAttribute('ecr-id');
                        getEcrById(ecrId);
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
    //constant object params
    const descriptionOfChangeParams ={
        tblReference : 'ecr_doc',
        globalVar: ecrVar.optDescriptionOfChange,
        formModel: toRef(frmEcrReasonRows.value[0],'descriptionOfChange'), // Good Practice create a reactive reference to a property inside an object
        selectedVal: 0,
    };
    const reasonOfChangeParams = {
        tblReference : 'ecr_roc',
        globalVar: ecrVar.optReasonOfChange,
        formModel: toRef(frmEcrReasonRows.value[0],'reasonOfChange'),
        selectedVal: 0,
    };
    const qadCheckedByParams = {
        globalVar: ecrVar.optQadCheckedBy,
        formModel: toRef(frmEcrQadRows.value,'qadCheckedBy'),
        selectedVal: '',
    };
    const qadApprovedByInternalParams = {
        globalVar: ecrVar.optQadApprovedByInternal,
        formModel: toRef(frmEcrQadRows.value,'qadApprovedByInternal'),
        selectedVal: '',
    };
    const qadApprovedByExternalParams = {
        globalVar: ecrVar.optQadApprovedByExternal,
        formModel: toRef(frmEcrQadRows.value,'qadApprovedByExternal'),
        selectedVal: '',
    };
    const otherDispoRequestedByParams = {
        globalVar: ecrVar.requestedBy,
        formModel: toRef(frmEcrOtherDispoRows.value[0],'requestedBy'),
        selectedVal: '',
    };
    const otherDispoTechnicalEvaluationParams = {
        globalVar: ecrVar.technicalEvaluation,
        formModel: toRef(frmEcrOtherDispoRows.value[0],'technicalEvaluation'),
        selectedVal: '',
    };
    const otherDispoReviewedByParams = {
        globalVar: ecrVar.reviewedBy,
        formModel: toRef(frmEcrOtherDispoRows.value[0],'reviewedBy'),
        selectedVal: '',
    };
    const pmiApproverPreparedByParams = {
        globalVar: ecrVar.preparedBy,
        formModel: toRef(frmEcrPmiApproverRows.value[0],'preparedBy'),
        selectedVal: '',
    };
    const pmiApproverCheckedByParams = {
        globalVar: ecrVar.checkedBy,
        formModel: toRef(frmEcrPmiApproverRows.value[0],'checkedBy'),
        selectedVal: '',
    };
    const pmiApproverApprovedByParams = {
        globalVar: ecrVar.approvedBy,
        formModel: toRef(frmEcrPmiApproverRows.value[0],'approvedBy'),
        selectedVal: '',
    };
    onMounted( async ()=>{
        //ModalRef inside the ModalComponent.vue
        //Do not name the Modal it is same new Modal js clas
        modal.SaveEcr = new Modal(modalSaveEcr.value.modalRef,{ keyboard: false });
        // modal.SaveEcr.show();
        await getDropdownMasterByOpt(descriptionOfChangeParams);
        await getDropdownMasterByOpt(reasonOfChangeParams);
        await getRapidxUserByIdOpt(qadCheckedByParams);
        await getRapidxUserByIdOpt(qadApprovedByInternalParams);
        await getRapidxUserByIdOpt(qadApprovedByExternalParams);
        await getRapidxUserByIdOpt(otherDispoRequestedByParams);
        await getRapidxUserByIdOpt(otherDispoTechnicalEvaluationParams);
        await getRapidxUserByIdOpt(otherDispoReviewedByParams);
        await getRapidxUserByIdOpt(pmiApproverPreparedByParams);
        await getRapidxUserByIdOpt(pmiApproverCheckedByParams);
        await getRapidxUserByIdOpt(pmiApproverApprovedByParams);
    })
    const getEcrById = async (ecrId) => {
        let params = {
            ecr_id : ecrId
        }
        axiosFetchData(params,'api/get_ecr_by_id',function(response){
            let data = response.data;
            let ecr = data.ecr;

            frmEcr.value.ecrId =ecr.ecrs_id;
            frmEcr.value.ecrNo =ecr.ecr_no;;
            frmEcr.value.category = ecr.category;
            frmEcr.value.customerName = ecr.customer_name;
            frmEcr.value.partNumber = ecr.part_no;
            frmEcr.value.partName = ecr.part_name;
            frmEcr.value.productLine = ecr.product_line;
            frmEcr.value.section = ecr.section;
            frmEcr.value.internalExternal = ecr.internal_external;
            frmEcr.value.deviceName = ecr.device_name;
            frmEcr.value.customerEcNo = ecr.customer_ec_no;
            frmEcr.value.dateOfRequest = ecr.date_of_request;

            //ECR Approval
            frmEcrOtherDispoRows.value = [];
            frmEcrQadRows.value = [];
            frmEcrPmiApproverRows.value = [];
            let ecrApprovalCollection = data.ecrApprovalCollection;
            let pmiApprovalCollection = data.pmiApprovalCollection;

            if (ecrApprovalCollection.length != 0){
                let requestedBy = ecrApprovalCollection.OTRB;
                let technicalEvaluation = ecrApprovalCollection.OTTE;
                let reviewedBy = ecrApprovalCollection.OTRVB;
                let qaCheckedBy = ecrApprovalCollection.QA;
                // Find the key with the longest array, Loops through all keys using Object.keys(),Compares array lengths using .reduce(),Returns the key and array with the highest length
                 // Exclude 'QA' from keys
                const ecrApprovalCollectionFiltered = Object.keys(ecrApprovalCollection).filter(key => key !== 'QA');
                const maxKey = ecrApprovalCollectionFiltered.reduce((a, b) =>
                    ecrApprovalCollection[a].length > ecrApprovalCollection[b].length ? a : b
                );
                ecrApprovalCollection[maxKey].forEach((ecrApprovalsEl,index) => {
                    frmEcrOtherDispoRows.value.push({
                        requestedBy: requestedBy[index] === undefined ? 0: requestedBy[index].rapidx_user_id ,
                        reviewedBy: technicalEvaluation[index] === undefined ? 0: technicalEvaluation[index].rapidx_user_id ,
                        technicalEvaluation:reviewedBy[index] === undefined ? 0: reviewedBy[index].rapidx_user_id,
                    });
                    console.log('pmiApprovalCollection',maxKey);
                });
                //QA Approval
                if (qaCheckedBy.length != 0){
                    frmEcrQadRows.value.qadCheckedBy =  qaCheckedBy[0].rapidx_user_id === undefined ? 0: qaCheckedBy[0].rapidx_user_id; //nmodify
                    frmEcrQadRows.value.qadApprovedByInternal = qaCheckedBy[1].rapidx_user_id === undefined ? 0: qaCheckedBy[1].rapidx_user_id; //nmodify
                    frmEcrQadRows.value.qadApprovedByExternal = qaCheckedBy[2].rapidx_user_id === undefined ? 0: qaCheckedBy[2].rapidx_user_id; //nmodify
                }

            }





            //PMI Approval
            if (pmiApprovalCollection.length != 0){
                let preparedBy = pmiApprovalCollection.PB;
                let checkedBy = pmiApprovalCollection.CB                ;
                let approvedBy = pmiApprovalCollection.AB                ;
                // Find the key with the longest array, Loops through all keys using Object.keys(),Compares array lengths using .reduce(),Returns the key and array with the highest length
                const maxKey = Object.keys(pmiApprovalCollection).reduce((a, b) =>
                    pmiApprovalCollection[a].length > pmiApprovalCollection[b].length ? a : b
                );
                console.log('pmiApprovalCollection',pmiApprovalCollection[maxKey]);

                pmiApprovalCollection[maxKey].forEach((ecrApprovalsEl,index) => {
                    frmEcrPmiApproverRows.value.push({
                        preparedBy: preparedBy[index] === undefined ? 0: preparedBy[index].rapidx_user_id ,
                        checkedBy: checkedBy[index] === undefined ? 0: checkedBy[index].rapidx_user_id ,
                        approvedBy:approvedBy[index] === undefined ? 0: approvedBy[index].rapidx_user_id,
                    });
                });
            }
            modal.SaveEcr.show();
        });
    }
    const addEcrReasonRows = async () => {
        frmEcrReasonRows.value.push({
            descriptionOfChange: '',
            reasonOfChange: '',
        });
    }
    const removeEcrReasonRows = async (index) => {
        frmEcrReasonRows.value.splice(index,1);
    }
    const btnAddEcrOtherDispoRows = async () => {
        frmEcrOtherDispoRows.value.push({
            requestedBy: '',
            technicalEvaluation: '',
            reviewedBy: '',
        });
    }
    const btnRemoveEcrOtherDispoRows = async (index) => {
        frmEcrOtherDispoRows.value.splice(index,1);
    }
    const btnAddEcrPmiApproverRows = async () => {
        frmEcrPmiApproverRows.value.push({
            preparedBy: [],
            checkedBy: [],
            approvedBy: [],
        });
    }
    const btnRemoveEcrPmiApproverRows = async (index) => {
        frmEcrPmiApproverRows.value.splice(index,1);
    }
    const frmSaveEcr = async () => {
        let formData = new FormData();
        //Append form data
        [
            ["ecr_no", frmEcr.value.ecrNo],
            ["category", frmEcr.value.category],
            ["customer_name", frmEcr.value.customerName],
            ["part_name", frmEcr.value.partName],
            ["productLine", frmEcr.value.productLine],
            ["section", frmEcr.value.section],
            ["internal_external", frmEcr.value.internalExternal],
            ["part_no", frmEcr.value.partNumber],
            ["device_name", frmEcr.value.deviceName],
            ["customer_ec_no", frmEcr.value.customerEcNo],
            ["date_of_request", frmEcr.value.dateOfRequest],
        ].forEach(([key, value]) =>
            formData.append(key, value)
        );

        for (let index = 0; index < frmEcrReasonRows.value.length; index++) {
            const descriptionOfChange = frmEcrReasonRows.value[index].descriptionOfChange;
            const reasonOfChange = frmEcrReasonRows.value[index].reasonOfChange;
            [
                ["description_of_change[]", descriptionOfChange],
                ["reason_of_change[]", reasonOfChange],
            ].forEach(([key, value]) =>
                formData.append(key, value)
            );
        }

        [
            ["qad_approved_by_external", frmEcrQadRows.value.qadApprovedByExternal],
            ["qad_approved_by_internal", frmEcrQadRows.value.qadApprovedByInternal],
            ["qad_checked_by", frmEcrQadRows.value.qadCheckedBy],
        ].forEach(([key, value]) =>
            formData.append(key, value)
        );

        for (let index = 0; index < frmEcrOtherDispoRows.value.length; index++) {
            const requestedBy = frmEcrOtherDispoRows.value[index].requestedBy;
            const technicalEvaluation = frmEcrOtherDispoRows.value[index].technicalEvaluation;
            const reviewedBy = frmEcrOtherDispoRows.value[index].reviewedBy;
            [
                ["requested_by[]", requestedBy],
                ["technical_evaluation[]", technicalEvaluation],
                ["reviewed_by[]", reviewedBy],
            ].forEach(([key, value]) =>
                formData.append(key, value)
            );
        }
        for (let index = 0; index < frmEcrPmiApproverRows.value.length; index++) {
            const preparedBy = frmEcrPmiApproverRows.value[index].preparedBy;
            const checkedBy = frmEcrPmiApproverRows.value[index].checkedBy;
            const approvedBy = frmEcrPmiApproverRows.value[index].approvedBy;
            [
                ["prepared_by[]", preparedBy],
                ["checked_by[]", checkedBy],
                ["approved_by[]", approvedBy],
            ].forEach(([key, value]) =>
                formData.append(key, value)
            );
        }

        axiosSaveData(formData,'api/save_ecr', (response) =>{
            // tblEdocs.value.dt.draw();
            console.log(response);
        });
    }
</script>


