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
    <ModalComponent icon="fa-user" modalDialog="modal-dialog modal-lg" title="ECR" @add-event="" ref="modalSaveEcr">
        <template #body>
                <div class="row">
                    <div class="input flex-nowrap mb-2 input-group-sm">
                            <input type="hidden" class="form-control form-control" aria-describedby="addon-wrapping">
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
                            <input v-model="frmEcr.dateOfRequest" type="text" class="form-control" aria-describedby="addon-wrapping">
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
                                    <button @click="addRowSaveDocuments"type="button" class="btn btn-primary btn-sm mb-2" style="float: right !important;"><i class="fas fa-plus"></i> Add Reason</button>
                                </div>
                                <div class="col-12 overflow-auto">
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
                                            <tr >
                                                <td>
                                                1
                                                </td>
                                                <td>
                                                    <!-- nmodify -->
                                                    <MultiselectElement
                                                        v-model="frmEcrReasonRows.descriptionOfChange"
                                                        :close-on-select="true"
                                                        :searchable="true"
                                                        :options="ecrVar.optDropdownMasterDetails"
                                                    />
                                                    <!-- <MultiselectElement
                                                        v-model="frmEcrReasonRows.descriptionOfChange"
                                                        :close-on-select="true"
                                                        :searchable="true"
                                                        :options="ecrVarsss.optDropdownMasterDetails"
                                                        label="label"
                                                        track-by="value"
                                                    /> -->

                                                </td>
                                                <td>
                                                    <select class="form-select form-select-sm" aria-describedby="addon-wrapping">
                                                        <option value="" selected disabled>N/A</option>
                                                    </select>
                                                    <!-- <MultiselectElement
                                                        v-model="frmEcr.rowSaveDocument.approverName"
                                                        :close-on-select="true"
                                                        :searchable="true"
                                                        :options="edocsVar.optApproverName"
                                                    /> -->
                                                </td>
                                                <td>
                                                    <button class="btn btn-danger btn-sm" type="button" data-item-process="add">
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
                <div class="card mb-2">
                        <h5 class="mb-0">
                            <button id="" class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="true" aria-controls="collapse2">
                                QA Dispositions
                            </button>
                        </h5>
                    <div id="collapse2" class="collapse" data-bs-parent="#accordionMain">
                        <div class="card-body shadow">
                            <div class="row">
                                <div class="col-12">
                                    <span class="input-group-text" id="addon-wrapping">Agreed By: </span>
                                </div>
                                <div class="col-12 overflow-auto">
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
                                                    <select class="form-select form-select-sm" aria-describedby="addon-wrapping">
                                                        <option value="" selected disabled>N/A</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-select form-select-sm" aria-describedby="addon-wrapping">
                                                        <option value="" selected disabled>N/A</option>
                                                    </select>
                                                    <!-- <MultiselectElement
                                                        v-model="frmEcr.rowSaveDocument.approverName"
                                                        :close-on-select="true"
                                                        :searchable="true"
                                                        :options="edocsVar.optApproverName"
                                                    /> -->
                                                </td>
                                                <td>
                                                    <select class="form-select form-select-sm" aria-describedby="addon-wrapping">
                                                        <option value="" selected disabled>N/A</option>
                                                    </select>
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
                                    <button @click="addRowSaveDocuments"type="button" class="btn btn-primary btn-sm mb-2" style="float: right !important;"><i class="fas fa-plus"></i> Add Validator</button>
                                </div>
                                <div class="col-12 overflow-auto">
                                    <table class="table table-responsive">
                                        <thead>
                                            <tr>
                                            <th scope="col">#</th>
                                            <th scope="col" style="width: 30%;">Requested By</th>
                                            <th scope="col" style="width: 30%;">Technical Evaluation / Engineering</th>
                                            <th scope="col" style="width: 30%;">Reviewed By / Section Heads</th>
                                            <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr >
                                                <td>
                                                1
                                                </td>
                                                <td>
                                                    <select class="form-select form-select-sm" aria-describedby="addon-wrapping">
                                                        <option value="" selected disabled>N/A</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-select form-select-sm" aria-describedby="addon-wrapping">
                                                        <option value="" selected disabled>N/A</option>
                                                    </select>
                                                    <!-- <MultiselectElement
                                                        v-model="frmEcr.rowSaveDocument.approverName"
                                                        :close-on-select="true"
                                                        :searchable="true"
                                                        :options="edocsVar.optApproverName"
                                                    /> -->
                                                </td>
                                                <td>
                                                    <select class="form-select form-select-sm" aria-describedby="addon-wrapping">
                                                        <option value="" selected disabled>N/A</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <button class="btn btn-danger btn-sm" type="button" data-item-process="add">
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
                                    <button @click="addRowSaveDocuments"type="button" class="btn btn-primary btn-sm mb-2" style="float: right !important;"><i class="fas fa-plus"></i> Add PMI Approvers</button>
                                </div>
                                <div class="col-12 overflow-auto">
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
                                            <tr >
                                                <td>
                                                1
                                                </td>
                                                <td>
                                                    <select class="form-select form-select-sm" aria-describedby="addon-wrapping">
                                                        <option value="" selected disabled>N/A</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-select form-select-sm" aria-describedby="addon-wrapping">
                                                        <option value="" selected disabled>N/A</option>
                                                    </select>
                                                    <!-- <MultiselectElement
                                                        v-model="frmEcr.rowSaveDocument.approverName"
                                                        :close-on-select="true"
                                                        :searchable="true"
                                                        :options="edocsVar.optApproverName"
                                                    /> -->
                                                </td>
                                                <td>
                                                    <select class="form-select form-select-sm" aria-describedby="addon-wrapping">
                                                        <option value="" selected disabled>N/A</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <button class="btn btn-danger btn-sm" type="button" data-item-process="add">
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
import {ref , onMounted} from 'vue';
import ModalComponent from '../components/ModalComponent.vue';

import ecr from '../../js/composables/ecr.js';

    const {
        modal,
        ecrVar,
        frmEcrReasonRows,
        getDropdownMasterByOpt,
    } = ecr();


    //ref state
    const frmEcr = ref({
        category: null,
        customerName: null,
        partName: null,
        productLine: null,
        section: null,
        internalExternal: null,
        partNumber: null,
        deviceName: null,
        customerEcNo: null,
        dateOfRequest: null,
    });

    const modalSaveEcr = ref(null);

    let descriptionOfChangeParams = {
        tblReference : 'ecr_doc',
        // optDescriptionOfChange: frmEcrReasonRows.descriptionOfChange,
    };

    let reasonOfChangeParams = {
        tblReference : 'ecr_roc',
        // optDescriptionOfChange: frmEcrReasonRows.descriptionOfChange,
    };

    const ecrVarsss = {
        optDropdownMasterDetails: [],
    }

    getDropdownMasterByOpt(descriptionOfChangeParams);

    onMounted( ()=>{
        //ModalRef inside the ModalComponent.vue
        //Do not name the Modal it is same new Modal js clas
        modal.SaveEcr = new Modal(modalSaveEcr.value.modalRef,{ keyboard: false });
        modal.SaveEcr.show();
        console.log('test',modal.SaveEcr);
    })

    // const frmSaveEcr = async () => {
    //     await axios.post('/api/save_document',{
    //            name: frmEcr.value,
    //     }).then((response) => {
    //         console.log(response);
    //     }).catch((err) => {
    //         console.log(err);
    //     });
    // }

</script>


