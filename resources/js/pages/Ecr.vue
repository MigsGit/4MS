<template>
    <div class="container-fluid px-4">
        <h4 class="mt-4">ENGINEERING CHANGE REQUEST</h4>
        <div class="card mt-5"  style="width: 100%;">
            <div class="card-body overflow-auto">
                <div class="container-fluid px-4">

                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Engineering Change Request</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <ModalComponent icon="fa-user" modalDialog="modal-dialog modal-lg" title="ECR" @add-event="frmSaveEcr()" ref="modalSaveEcr">
        <template #body>
                <div class="row">
                    <div class="input flex-nowrap mb-2 input-group-sm">
                            <input type="hidden" class="form-control form-control" aria-describedby="addon-wrapping">
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

                                            <tr v-for="(frmEcrReasonRow, index) in frmEcrReasonRows" :key="frmEcrReasonRows.index">
                                                <td>
                                                    {{index+1}}
                                                </td>
                                                <td>
                                                    <Multiselect
                                                        v-model="frmEcrReasonRows.descriptionOfChange"
                                                        :options="ecrVar.optDescriptionOfChange"
                                                        placeholder="Select an option"
                                                        :searchable="true"
                                                        :close-on-select="true"
                                                    />
                                                </td>
                                                <td>
                                                    <Multiselect
                                                        v-model="frmEcrReasonRows.reasonOfChange"
                                                        :close-on-select="true"
                                                        :searchable="true"
                                                        :options="ecrVar.optReasonOfChange"
                                                        placeholder="Select an option"
                                                    />
                                                </td>
                                                <td>
                                                    <button @click="removeEcrReasonRows(index)" class="btn btn-danger btn-sm" type="button" data-item-process="add">
                                                        <li class="fa fa-trash"></li>
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
                                            <tr  v-for="(frmEcrOtherDispoRows, index) in frmEcrOtherDispoRows" :key="frmEcrOtherDispoRows.index">
                                                <td>
                                                   {{ index+1 }}
                                                </td>
                                                <td>
                                                    <Multiselect
                                                        v-model="frmEcrOtherDispoRows.requestedBy"
                                                        :close-on-select="true"
                                                        :searchable="true"
                                                        :options="ecrVar.requestedBy"
                                                    />
                                                </td>
                                                <td>
                                                    <Multiselect
                                                        v-model="frmEcrOtherDispoRows.technicalEvaluation"
                                                        :close-on-select="true"
                                                        :searchable="true"
                                                        :options="ecrVar.technicalEvaluation"
                                                    />
                                                </td>
                                                <td>
                                                    <Multiselect
                                                        v-model="frmEcrOtherDispoRows.reviewedBy"
                                                        :close-on-select="true"
                                                        :searchable="true"
                                                        :options="ecrVar.reviewedBy"
                                                    />

                                                </td>
                                                <td>
                                                    <button @click="btnRemoveEcrOtherDispoRows(index)" class="btn btn-danger btn-sm" type="button" data-item-process="add">
                                                        <li class="fa fa-trash"></li>
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

                                            <tr  v-for="(frmEcrPmiApproverRows,index) in frmEcrPmiApproverRows" :key="frmEcrPmiApproverRows.index">
                                                <td>
                                                    {{ index+1 }}
                                                </td>
                                                <td>
                                                    <Multiselect
                                                        v-model="frmEcrPmiApproverRows.preparedBy"
                                                        :close-on-select="true"
                                                        :searchable="true"
                                                        :options="ecrVar.preparedBy"
                                                    />

                                                </td>
                                                <td>
                                                    <Multiselect
                                                        v-model="frmEcrPmiApproverRows.checkedBy"
                                                        :close-on-select="true"
                                                        :searchable="true"
                                                        :options="ecrVar.checkedBy"
                                                    />
                                                </td>
                                                <td>
                                                    <Multiselect
                                                        v-model="frmEcrPmiApproverRows.approvedBy"
                                                        :close-on-select="true"
                                                        :searchable="true"
                                                        :options="ecrVar.approvedBy"
                                                    />
                                                </td>
                                                <td>
                                                    <button @click="btnRemoveEcrPmiApproverRows(index)" class="btn btn-danger btn-sm" type="button" data-item-process="add">
                                                        <li class="fa fa-trash"></li>
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
                <button type="submit" class="btn btn-success btn-sm"><li class="fas fa-save"></li> Save</button>
            </template>
    </ModalComponent>
</template>

<script setup>
    import {ref , onMounted, beforeMounted,reactive, toRef} from 'vue';
    import ModalComponent from '../components/ModalComponent.vue';
    import ecr from '../../js/composables/ecr.js';
    import useForm from '../../js/composables/utils/useForm.js'
    const { axiosSaveData } = useForm(); // Call the useFetch function


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
    } = ecr();
    //ref state
    const frmEcr = ref({
        ecrNo: '1',
        category: '1',
        customerName: 'test',
        partName: 'test',
        productLine: 'test',
        section: 'test',
        internalExternal: '2',
        partNumber: 'test',
        deviceName: 'test',
        customerEcNo: 'test',
        dateOfRequest: '',
    });

    const modalSaveEcr = ref(null);
    //constant object params
    const descriptionOfChangeParams ={
        tblReference : 'ecr_doc',
        globalVar: ecrVar.optDescriptionOfChange,
        formModel: toRef(frmEcrReasonRows.value,'descriptionOfChange'), // Good Practice create a reactive reference to a property inside an object
        selectedVal: '',
    };
    const reasonOfChangeParams = {
        tblReference : 'ecr_roc',
        globalVar: ecrVar.optReasonOfChange,
        formModel: toRef(frmEcrReasonRows.value,'reasonOfChange'), // Good Practice create a reactive reference to a property inside an object
        selectedVal: '',
    };
    const qadCheckedByParams = {
        globalVar: ecrVar.optQadCheckedBy,
        formModel: toRef(frmEcrQadRows.value,'qadCheckedBy'), // Good Practice create a reactive reference to a property inside an object
        selectedVal: '',
    };
    const qadApprovedByInternalParams = {
        globalVar: ecrVar.optQadApprovedByInternal,
        formModel: toRef(frmEcrQadRows.value,'qadApprovedByInternal'), // Good Practice create a reactive reference to a property inside an object
        selectedVal: '',
    };
    const qadApprovedByExternalParams = {
        globalVar: ecrVar.optQadApprovedByExternal,
        formModel: toRef(frmEcrQadRows.value,'qadApprovedByExternal'), // Good Practice create a reactive reference to a property inside an object
        selectedVal: '',
    };
    const otherDispoRequestedByParams = {
        globalVar: ecrVar.requestedBy,
        formModel: toRef(frmEcrOtherDispoRows.value,'otherDispoRequestedBy'), // Good Practice create a reactive reference to a property inside an object
        selectedVal: '',
    };
    const otherDispoTechnicalEvaluationParams = {
        globalVar: ecrVar.technicalEvaluation,
        formModel: toRef(frmEcrOtherDispoRows.value,'otherDispoTechnicalEvaluation'), // Good Practice create a reactive reference to a property inside an object
        selectedVal: '',
    };
    const otherDispoReviewedByParams = {
        globalVar: ecrVar.reviewedBy,
        formModel: toRef(frmEcrOtherDispoRows.value,'otherDispoReviewedBy'), // Good Practice create a reactive reference to a property inside an object
        selectedVal: '',
    };
    const pmiApproverPreparedByParams = {
        globalVar: ecrVar.preparedBy,
        formModel: toRef(frmEcrPmiApproverRows.value,'pmiApproverPreparedBy'), // Good Practice create a reactive reference to a property inside an object
        selectedVal: '',
    };
    const pmiApproverCheckedByParams = {
        globalVar: ecrVar.checkedBy,
        formModel: toRef(frmEcrPmiApproverRows.value,'pmiApproverCheckedBy'), // Good Practice create a reactive reference to a property inside an object
        selectedVal: '',
    };
    const pmiApproverApprovedByParams = {
        globalVar: ecrVar.approvedBy,
        formModel: toRef(frmEcrPmiApproverRows.value,'pmiApproverApprovedBy'), // Good Practice create a reactive reference to a property inside an object
        selectedVal: '',
    };
    onMounted( async ()=>{
        //ModalRef inside the ModalComponent.vue
        //Do not name the Modal it is same new Modal js clas
        modal.SaveEcr = new Modal(modalSaveEcr.value.modalRef,{ keyboard: false });
        modal.SaveEcr.show();
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


    const addEcrReasonRows = async () => {
        frmEcrReasonRows.value.push({
            descriptionOfChange: '',
            reasonOfChange: [],
        });
    }

    const removeEcrReasonRows = async (index) => {
        frmEcrReasonRows.value.splice(index,1);
    }
    const btnAddEcrOtherDispoRows = async () => {
        frmEcrOtherDispoRows.value.push({
            requestedBy: [],
            technicalEvaluation: [],
            reviewedBy: [],
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

<style scoped>

</style>

