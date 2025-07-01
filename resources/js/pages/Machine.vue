<template>
    <div class="container-fluid px-4">
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
                            :columns="tblEcrByStatusColumns"
                            ajax="api/load_ecr_machine_by_status?category=Machine"
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
    <ModalComponent icon="fa-user" modalDialog="modal-dialog modal-xl" title="SaveMachine" ref="modalSaveMachine">
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
                                <th> Doc Sub Date</th>
                                <th> Doc To Be Sub</th>
                                <th> Remarks</th>
                            </tr>
                        </thead>
                        </DataTable>
                    </div>
                    <!-- Material Approval -->
                    <div class="card mb-2" v-show="isModal === 'Edit'">
                        <h5 class="mb-0">
                            <button id="" class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="true" aria-controls="collapse2">
                                Approval:
                            </button>
                        </h5>
                        <div id="collapse2" class="collapse show" data-bs-parent="#accordionMain">
                            <div class="card-body shadow">
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="input-group flex-nowrap mb-2 input-group-sm">
                                            <input @change="changeMachineRefBefore" multiple type="file" accept=".jpg" class="form-control form-control-lg" aria-describedby="addon-wrapping" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group flex-nowrap mb-2 input-group-sm">
                                            <input @change="changeMachineRefAfter" multiple type="file" accept=".jpg" class="form-control form-control-lg" aria-describedby="addon-wrapping" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 overflow-auto">
                                        <table class="table table-responsive">
                                            <thead>
                                                <tr>
                                                <th scope="col" style="width: 10%;">Section</th>
                                                <th scope="col" style="width: 30%;">Assessed by</th>
                                                <th scope="col" style="width: 30%;">Checked by</th>
                                                </tr>
                                            </thead>
                                            <!-- @change="onUserChange(qadApprovedByInternalParams)" -->
                                            <!-- @change="onUserChange(prCheckedByParams)" -->
                                                <!-- @change="onUserChange(prApprovedByParams)" -->
                                            <tbody>
                                                <tr class="production">
                                                    <td>
                                                        Production
                                                    </td>
                                                    <td>
                                                        <Multiselect
                                                            v-model="frmMachine.prdnAssessedBy"
                                                            :close-on-select="true"
                                                            :searchable="true"
                                                            :options="machineVar.prdnAssessedBy"
                                                        />
                                                    </td>
                                                    <td>
                                                        <Multiselect
                                                            v-model="frmMachine.prdnCheckedBy"
                                                            :close-on-select="true"
                                                            :searchable="true"
                                                            :options="machineVar.prdnCheckedBy"
                                                        />
                                                    </td>
                                                </tr>
                                                <tr class="ppc">
                                                    <td>
                                                        Conformed: PPC
                                                    </td>
                                                    <td>
                                                        <Multiselect
                                                            v-model="frmMachine.ppcAssessedBy"
                                                            :close-on-select="true"
                                                            :searchable="true"
                                                            :options="machineVar.ppcAssessedBy"
                                                        />
                                                    </td>
                                                    <td>
                                                        <Multiselect
                                                            v-model="frmMachine.ppcCheckedBy"
                                                            :close-on-select="true"
                                                            :searchable="true"
                                                            :options="machineVar.ppcCheckedBy"
                                                        />
                                                    </td>
                                                </tr>
                                                <tr class="pro-engineer">
                                                    <td>
                                                        Process Engineering
                                                    </td>
                                                    <td>
                                                        <Multiselect
                                                            v-model="frmMachine.proEnggAssessedBy"
                                                            :close-on-select="true"
                                                            :searchable="true"
                                                            :options="machineVar.proEnggAssessedBy"
                                                        />
                                                    </td>
                                                    <td>
                                                        <Multiselect
                                                            v-model="frmMachine.proEnggCheckedBy"
                                                            :close-on-select="true"
                                                            :searchable="true"
                                                            :options="machineVar.proEnggCheckedBy"
                                                        />
                                                    </td>

                                                </tr>
                                                <tr class="main-engineer">
                                                    <td>
                                                        Maintenance Engineering
                                                    </td>
                                                    <td>
                                                        <Multiselect
                                                            v-model="frmMachine.mainEnggAssessedBy"
                                                            :close-on-select="true"
                                                            :searchable="true"
                                                            :options="machineVar.mainEnggAssessedBy"
                                                        />
                                                    </td>
                                                    <td>
                                                        <Multiselect
                                                            v-model="frmMachine.mainEnggCheckedBy"
                                                            :close-on-select="true"
                                                            :searchable="true"
                                                            :options="machineVar.mainEnggCheckedBy"
                                                        />
                                                    </td>

                                                </tr>
                                                <tr class="qc">
                                                    <td>
                                                        QC
                                                    </td>
                                                    <td>
                                                        <Multiselect
                                                            v-model="frmMachine.qcAssessedBy"
                                                            :close-on-select="true"
                                                            :searchable="true"
                                                            :options="machineVar.qcAssessedBy"
                                                        />
                                                    </td>
                                                    <td>
                                                        <Multiselect
                                                            v-model="frmMachine.qcCheckedBy"
                                                            :close-on-select="true"
                                                            :searchable="true"
                                                            :options="machineVar.qcCheckedBy"
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
                </div>
            </div>
        </template>
        <template #footer>
            <button type="button" id= "closeBtn" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            <button @click="saveMachine()" type="submit" class="btn btn-success btn-sm"><li class="fas fa-save"></li> Save</button>
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
</template>
<script setup>
    import {ref , onMounted,reactive, toRef} from 'vue';
    import ModalComponent from '../../js/components/ModalComponent.vue';
    import EcrChangeComponent from '../components/EcrChangeComponent.vue';
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
    const modalSaveMachine = ref(null);
    const modalSaveEcrDetail = ref(null);
    const isModal = ref('Edit');
    const isSelectReadonly = ref(true);
    const machineRefBefore = ref(null);
    const machineRefAfter = ref(null);
    const selectedEcrsId = ref(null);
    const selectedMachinesId = ref(null);

    const tblEcrDetailColumns = [ //mitch paulsen
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

    const tblEcrByStatusColumns = [
        {   data: 'get_actions',
            orderable: false,
            searchable: false,
            createdCell(cell){
                let btnGetEcrId = cell.querySelector('#btnGetEcrId');
                if(btnGetEcrId != null){
                    btnGetEcrId.addEventListener('click',function(){
                        let ecrsId = this.getAttribute('ecrs-id');
                        let machinesId = this.getAttribute('machines-id');
                        selectedEcrsId.value = ecrsId;
                        selectedMachinesId.value = machinesId;

                        tblEcrDetails.value.dt.ajax.url("api/load_ecr_details_by_ecr_id?ecr_id="+ecrsId).draw();
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

                        modal.SaveMachine.show();
                    });
                }
            }
        } ,
        {   data: 'get_status'} ,
        {   data: 'get_attachment'} ,
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
    //Users Params
    const prdnAssessedByParams = {
        globalVar: machineVar.prdnAssessedBy,
        formModel: toRef(frmMachine.value,'prdnAssessedBy'),
        selectedVal: 530,
    };
    const prdnCheckedByParams = {
        globalVar: machineVar.prdnCheckedBy,
        formModel: toRef(frmMachine.value,'prdnCheckedBy'),
        selectedVal: 237,
    };
    const ppcAssessedByParams = {
        globalVar: machineVar.ppcAssessedBy,
        formModel: toRef(frmMachine.value,'ppcAssessedBy'),
        selectedVal: 530,
    };
    const ppcCheckedByParams = {
        globalVar: machineVar.ppcCheckedBy,
        formModel: toRef(frmMachine.value,'ppcCheckedBy'),
        selectedVal: 237,
    };
    const mainEnggAssessedByParams = {
        globalVar: machineVar.mainEnggAssessedBy,
        formModel: toRef(frmMachine.value,'mainEnggAssessedBy'),
        selectedVal: 530,
    };
    const mainEnggCheckedByParams = {
        globalVar: machineVar.mainEnggCheckedBy,
        formModel: toRef(frmMachine.value,'mainEnggCheckedBy'),
        selectedVal: 237,
    };
    const proEnggAssessedByParams = {
        globalVar: machineVar.proEnggAssessedBy,
        formModel: toRef(frmMachine.value,'proEnggAssessedBy'),
        selectedVal: 530,
    };
    const proEnggCheckedByParams = {
        globalVar: machineVar.proEnggCheckedBy,
        formModel: toRef(frmMachine.value,'proEnggCheckedBy'),
        selectedVal:237,
    };
    const qcAssessedByParams = {
        globalVar: machineVar.qcAssessedBy,
        formModel: toRef(frmMachine.value,'qcAssessedBy'),
        selectedVal: 530,
    };
    const qcCheckedByParams = {
        globalVar: machineVar.qcCheckedBy,
        formModel: toRef(frmMachine.value,'qcCheckedBy'),
        selectedVal:237,
    };

    onMounted( async ()=>{
        modal.SaveMachine = new Modal(modalSaveMachine.value.modalRef,{ keyboard: false });
        modal.SaveEcrDetail = new Modal(modalSaveEcrDetail.value.modalRef,{ keyboard: false });
        await getDropdownMasterByOpt(descriptionOfChangeParams);
        await getDropdownMasterByOpt(reasonOfChangeParams);
        await getDropdownMasterByOpt(typeOfPartParams);
    })
    const changeMachineRefBefore = async (event) => {
        machineRefBefore.value =  Array.from(event.target.files);
    }
    const changeMachineRefAfter = async (event) => {
        machineRefAfter.value =  Array.from(event.target.files);
    }
    const saveMachine = async () => {
        let formData = new FormData();

        //Append form data
        [
            ["ecrsId", selectedEcrsId.value],
            ["machinesId", selectedMachinesId.value],
            ["prdnAssessedBy", frmMachine.value.prdnAssessedBy],
            ["prdnCheckedBy", frmMachine.value.prdnCheckedBy],
            ["ppcAssessedBy", frmMachine.value.ppcAssessedBy],
            ["ppcCheckedBy", frmMachine.value.ppcCheckedBy],
            ["qcAssessedBy", frmMachine.value.qcAssessedBy],
            ["qcCheckedBy", frmMachine.value.qcCheckedBy],
            ["proEnggAssessedBy", frmMachine.value.proEnggAssessedBy],
            ["proEnggCheckedBy", frmMachine.value.proEnggCheckedBy],
            ["mainEnggAssessedBy", frmMachine.value.mainEnggAssessedBy],
            ["mainEnggCheckedBy", frmMachine.value.mainEnggCheckedBy],
        ].forEach(([key, value]) =>
            formData.append(key, value)
        );
        machineRefBefore.value.forEach((file, index) => {
            formData.append('machineRefBefore[]', file);
        });
        machineRefAfter.value.forEach((file, index) => {
            formData.append('machineRefAfter[]', file);
        });
        axiosSaveData(formData,'api/save_machine',(response) =>{
            console.log(response);
        });
    }

</script>


