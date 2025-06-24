<template>
    <div class="container-fluid px-4">
        <h4 class="mt-4">Material</h4>
        <div class="card mt-5"  style="width: 100%;">
            <div class="card-body overflow-auto">
                <div class="container-fluid px-4">
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Man</li>
                    </ol>
                    <div class="table-responsive">
                        <DataTable
                            width="100%" cellspacing="0"
                            class="table mt-2"
                            ref="tblEcrByCategoryStatus"
                            :columns="ecrColumns"
                            ajax="api/load_ecr_material_by_status?category=Material&&status=AP"
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
    <ModalComponent icon="fa-user" modalDialog="modal-dialog modal-xl" title="Material" @add-event="" ref="modalSaveMaterial">
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
                    <div class="card-body">
                        <div class="row">
                            <div class="input-group flex-nowrap mb-2 input-group-sm">
                                <span class="input-group-text" id="addon-wrapping">ECR Id:</span>
                            <input v-model="frmMaterial.ecrsId" type="text" class="form-control form-control-lg" aria-describedby="addon-wrapping">
                            </div>
                            <div class="input-group flex-nowrap mb-2 input-group-sm">
                                <span class="input-group-text" id="addon-wrapping">Material Id:</span>
                                <input  v-model="frmMaterial.materialId"  type="text" class="form-control form-control-lg" aria-describedby="addon-wrapping">
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group flex-nowrap mb-2 input-group-sm">
                                    <span class="input-group-text" id="addon-wrapping">Parts/Direct Material:</span>
                                    <Multiselect
                                        v-model="frmMaterial.pdMaterial"
                                        :options="commonVar.optCheck"
                                        placeholder="Select an option"
                                        :searchable="true"
                                        :close-on-select="true"
                                    />
                                </div>
                                <div class="input-group flex-nowrap mb-2 input-group-sm">
                                    <span class="input-group-text" id="addon-wrapping">MSDS:</span>
                                    <Multiselect
                                        v-model="frmMaterial.msds"
                                        :options="commonVar.optYesNo"
                                        placeholder="Select an option"
                                        :searchable="true"
                                        :close-on-select="true"
                                    />
                                </div>
                                <div class="input-group flex-nowrap mb-2 input-group-sm">
                                    <span class="input-group-text" id="addon-wrapping">ICP:</span>
                                    <Multiselect
                                        v-model="frmMaterial.icp"
                                        :options="commonVar.optYesNo"
                                        placeholder="Select an option"
                                        :searchable="true"
                                        :close-on-select="true"
                                    />
                                </div>
                                <div class="input-group flex-nowrap mb-2 input-group-sm">
                                    <span class="input-group-text" id="addon-wrapping">GP:</span>
                                    <input v-model="frmMaterial.gp" type="date" class="form-control form-control-lg" aria-describedby="addon-wrapping">
                                </div>
                                <div class="input-group flex-nowrap mb-2 input-group-sm">
                                    <span class="input-group-text" id="addon-wrapping">Qoutation</span>
                                    <Multiselect
                                        v-model="frmMaterial.qoutation"
                                        :options="commonVar.optCheck"
                                        placeholder="Select an option"
                                        :searchable="true"
                                        :close-on-select="true"
                                    />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group flex-nowrap mb-2 input-group-sm">
                                    <span class="input-group-text" id="addon-wrapping">Supplier:</span>
                                    <Multiselect
                                        v-model="frmMaterial.materialSupplier"
                                        :options="materialVar.materialSupplier"
                                        placeholder="Select an option"
                                        :searchable="true"
                                        :close-on-select="true"
                                    />
                                </div>
                                <div class="input-group flex-nowrap mb-2 input-group-sm">
                                    <span class="input-group-text" id="addon-wrapping">Product Color:</span>
                                    <Multiselect
                                        v-model="frmMaterial.materialColor"
                                        :options="materialVar.materialColor"
                                        placeholder="Select an option"
                                        :searchable="true"
                                        :close-on-select="true"
                                    />
                                </div>
                                <div class="input-group flex-nowrap mb-2 input-group-sm">
                                    <span class="input-group-text" id="addon-wrapping">ROHS:</span>
                                    <Multiselect
                                        v-model="frmMaterial.rohs"
                                        :options="commonVar.optResult"
                                        placeholder="Select an option"
                                        :searchable="true"
                                        :close-on-select="true"
                                    />
                                </div>
                                <div class="input-group flex-nowrap mb-2 input-group-sm">
                                    <span class="input-group-text" id="addon-wrapping">Material Sample:</span>
                                    <Multiselect
                                        v-model="frmMaterial.materialSample"
                                        :options="commonVar.optCheck"
                                        placeholder="Select an option"
                                        :searchable="true"
                                        :close-on-select="true"
                                    />
                                </div>
                                <div class="input-group flex-nowrap mb-2 input-group-sm">
                                    <span class="input-group-text" id="addon-wrapping">COC:</span>
                                    <Multiselect
                                        v-model="frmMaterial.coc"
                                        :options="commonVar.optCheck"
                                        placeholder="Select an option"
                                        :searchable="true"
                                        :close-on-select="true"
                                    />
                                </div>
                            </div>
                        </div>
                        <!-- Material Approval -->
                        <div class="card mb-2">
                            <h5 class="mb-0">
                                <button id="" class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="true" aria-controls="collapse2">
                                    Approval:
                                </button>
                            </h5>
                            <div id="collapse2" class="collapse" data-bs-parent="#accordionMain">
                                <div class="card-body shadow">
                                    <div class="row">
                                        <div class="col-12 overflow-auto">
                                            <table class="table table-responsive">
                                                <thead>
                                                    <tr>
                                                    <th scope="col" style="width: 10%;">Section</th>
                                                    <th scope="col" style="width: 30%;">Prepared by</th>
                                                    <th scope="col" style="width: 30%;">Checked by</th>
                                                    <th scope="col" style="width: 30%;">Approved By</th>
                                                    </tr>
                                                </thead>
                                                <!-- @change="onUserChange(qadApprovedByInternalParams)" -->
                                                <!-- @change="onUserChange(prCheckedByParams)" -->
                                                  <!-- @change="onUserChange(prApprovedByParams)" -->

                                                <tbody>
                                                    <tr >
                                                        <td>
                                                            Purchasing
                                                        </td>
                                                        <td>

                                                            <Multiselect
                                                                v-model="frmMaterial.prPreparedBy"
                                                                :close-on-select="true"
                                                                :searchable="true"
                                                                :options="materialVar.prPreparedBy"
                                                            />
                                                        </td>
                                                        <td>
                                                            <Multiselect
                                                                v-model="frmMaterial.prCheckedBy"
                                                                :close-on-select="true"
                                                                :searchable="true"
                                                                :options="materialVar.prCheckedBy"
                                                            />
                                                        </td>
                                                        <td>
                                                            <Multiselect
                                                                v-model="frmMaterial.prApprovedBy"
                                                                :close-on-select="true"
                                                                :searchable="true"
                                                                :options="materialVar.prApprovedBy"
                                                            />
                                                        </td>
                                                    </tr>
                                                    <tr >
                                                        <td>
                                                            Conformed: PPC
                                                        </td>
                                                        <td>
                                                            <Multiselect
                                                                v-model="frmMaterial.ppcPreparedBy"
                                                                :close-on-select="true"
                                                                :searchable="true"
                                                                :options="materialVar.ppcPreparedBy"
                                                            />
                                                        </td>
                                                        <td>
                                                            <Multiselect
                                                                v-model="frmMaterial.ppcCheckedBy"
                                                                :close-on-select="true"
                                                                :searchable="true"
                                                                :options="materialVar.ppcCheckedBy"
                                                            />
                                                        </td>
                                                        <td>
                                                            <Multiselect
                                                                v-model="frmMaterial.ppcApprovedBy"
                                                                :close-on-select="true"
                                                                :searchable="true"
                                                                :options="materialVar.ppcApprovedBy"
                                                            />
                                                        </td>
                                                    </tr>
                                                    <tr >
                                                        <td>
                                                            EMS
                                                        </td>
                                                        <td>
                                                            <Multiselect
                                                                v-model="frmMaterial.emsCheckedBy"
                                                                :close-on-select="true"
                                                                :searchable="true"
                                                                :options="materialVar.optRapidxUser"
                                                            />
                                                        </td>
                                                        <td>
                                                            <Multiselect
                                                                v-model="frmMaterial.emsCheckedBy"
                                                                :close-on-select="true"
                                                                :searchable="true"
                                                                :options="materialVar.optRapidxUser"
                                                            />
                                                        </td>
                                                        <td>
                                                            <Multiselect
                                                                v-model="frmMaterial.emsApprovedBy"
                                                                :close-on-select="true"
                                                                :searchable="true"
                                                                :options="materialVar.optRapidxUser"
                                                            />
                                                        </td>
                                                    </tr>
                                                    <tr >
                                                        <td>
                                                            QC
                                                        </td>
                                                        <td>
                                                            <Multiselect
                                                                v-model="frmMaterial.qcCheckedBy"
                                                                :close-on-select="true"
                                                                :searchable="true"
                                                                :options="materialVar.optRapidxUser"
                                                            />
                                                        </td>
                                                        <td>
                                                            <Multiselect
                                                                v-model="frmMaterial.qcCheckedBy"
                                                                :close-on-select="true"
                                                                :searchable="true"
                                                                :options="materialVar.optRapidxUser"
                                                            />
                                                        </td>
                                                        <td>
                                                            <Multiselect
                                                                v-model="frmMaterial.qcApprovedBy"
                                                                :close-on-select="true"
                                                                :searchable="true"
                                                                :options="materialVar.optRapidxUser"
                                                            />
                                                        </td>
                                                    </tr>
                                                    <tr >
                                                        <td>
                                                            Engineering
                                                        </td>
                                                        <td>
                                                            <Multiselect
                                                                v-model="frmMaterial.enggCheckedBy"
                                                                :close-on-select="true"
                                                                :searchable="true"
                                                                :options="materialVar.optRapidxUser"
                                                            />
                                                        </td>
                                                        <td>
                                                            <Multiselect
                                                                v-model="frmMaterial.enggCheckedBy"
                                                                :close-on-select="true"
                                                                :searchable="true"
                                                                :options="materialVar.optRapidxUser"
                                                            />
                                                        </td>
                                                        <td>
                                                            <Multiselect
                                                                v-model="frmMaterial.enggApprovedBy"
                                                                :close-on-select="true"
                                                                :searchable="true"
                                                                :options="materialVar.optRapidxUser"
                                                            />
                                                        </td>
                                                    </tr>
                                                    <tr >
                                                        <td>
                                                            QA
                                                        </td>
                                                        <td>
                                                            <Multiselect
                                                                v-model="frmMaterial.qaCheckedBy"
                                                                :close-on-select="true"
                                                                :searchable="true"
                                                                :options="materialVar.optRapidxUser"
                                                            />
                                                        </td>
                                                        <td>
                                                            <Multiselect
                                                                v-model="frmMaterial.qaCheckedBy"
                                                                :close-on-select="true"
                                                                :searchable="true"
                                                                :options="materialVar.optRapidxUser"
                                                            />
                                                        </td>
                                                        <td>
                                                            <Multiselect
                                                                v-model="frmMaterial.qaApprovedBy"
                                                                :close-on-select="true"
                                                                :searchable="true"
                                                                :options="materialVar.optRapidxUser"
                                                            />
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- TODO ECR | 4M Requirement View Manager Ni-an
                                <div class="card-footer justify-content-end">
                                    <button type="button" id= "closeBtn" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-success btn-sm"><font-awesome-icon class="nav-icon" icon="fas fa-eye" />&nbsp;     View</button>
                                </div> -->
                            </div>
                        </div>
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
                </div>
            </div>
        </template>
        <template #footer>
            <button type="button" id= "closeBtn" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success btn-sm"><li class="fas fa-save"></li> Save</button>
        </template>
    </ModalComponent>
    <ModalComponent icon="fa-upload" modalDialog="modal-dialog modal-md" title="Upload Material Reference" ref="modalUploadMaterialRef" @add-event="frmUploadMaterialRef()">
        <template #body>
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <input @change="changeMaterialRef" multiple type="file" accept=".pdf" class="form-control form-control-lg" aria-describedby="addon-wrapping">
                    </div>
                </div>
            </div>
        </template>
        <template #footer>
            <button type="button" id= "closeBtn" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success btn-sm"><font-awesome-icon class="nav-icon" icon="fas fa-save" />&nbsp; Save</button>
        </template>
    </ModalComponent>
    <ModalComponent icon="fa-download" modalDialog="modal-dialog modal-md" title="View Material Reference" ref="modalViewMaterialRef">
        <template #body>
            <div class="row mt-3">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">
                                PDF Attachment
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- v-for -->
                        <tr v-for="(arrOriginalFilename, index) in arrOriginalFilenames" :key="arrOriginalFilename.index">
                            <th scope="row">{{ index+1 }}</th>
                            <td>
                                <a href="" class="link-primary" ref="aViewMaterialRef" @click="btnLinkViewMaterialRef(selectedEcrsIdEncrypted,index)">
                                    {{ arrOriginalFilename }}
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </template>
        <template #footer>
        </template>
    </ModalComponent>
</template>
<script setup>
    import {ref , onMounted,reactive, toRef} from 'vue';
    import ModalComponent from '../../js/components/ModalComponent.vue';
    import EcrChangeComponent from '../components/EcrChangeComponent.vue';
    import useCommon from '../../js/composables/common.js';
    import useForm from '../../js/composables/utils/useForm.js'
    import useEcr from '../../js/composables/ecr.js';
    import useMaterial from '../../js/composables/material.js';
    import useSettings from '../composables/settings.js';
    import DataTable from 'datatables.net-vue3';
    import DataTablesCore from 'datatables.net-bs5';
    DataTable.use(DataTablesCore)

    const {
        modal,
        ecrVar,
        tblEcrDetails,
        frmEcrDetails,
        frmEcrReasonRows,
        descriptionOfChangeParams,
        reasonOfChangeParams,
        typeOfPartParams,
        axiosFetchData,
        getEcrDetailsId,
        saveEcrDetails,
    } = useEcr();
     // getDropdownMasterByOpt,
     // getRapidxUserByIdOpt,

    const {
        materialVar,
        frmMaterial,
    } = useMaterial();

    const {
        axiosSaveData
    } = useForm();

    const {
        getRapidxUserByIdOpt,
        getDropdownMasterByOpt,
        onUserChange,
    } = useSettings();

    const {
        commonVar,
        getCurrentApprover,
        getCurrentPmiInternalApprover,
    } = useCommon();

    const modalSaveEcrDetail = ref(null);
    const modalSaveMaterial = ref(null);
    const modalUploadMaterialRef = ref(null);
    const modalViewMaterialRef = ref(null);
    const selectedEcrsId = ref(null);
    const selectedEcrsIdEncrypted = ref(null);
    const arrOriginalFilenames = ref(null);
    const materialRef = ref(null);

    const isSelectReadonly  = ref(true);
    const tblEcrByCategoryStatus = ref(null);

    //Params
    const materialSupplierParams = {
        tblReference : 'material_supplier',
        globalVar: materialVar.materialSupplier,
        formModel: toRef(frmMaterial.value,'materialSupplier'),
        selectedVal: '',
    };
    const materialColorParams = {
        tblReference : 'material_color',
        globalVar: materialVar.materialColor,
        formModel: toRef(frmMaterial.value,'materialColor'),
        selectedVal: '',
    };
    const prPreparedByParams = {
        globalVar: materialVar.prPreparedBy,
        formModel: toRef(frmMaterial.value,'prPreparedBy'),
        selectedVal: '',
    };
    const prCheckedByParams = {
        globalVar: materialVar.prCheckedBy,
        formModel: toRef(frmMaterial.value,'prCheckedBy'),
        selectedVal: '',
    };
    const prApprovedByParams = {
        globalVar: materialVar.prApprovedBy,
        formModel: toRef(frmMaterial.value,'prApprovedBy'),
        selectedVal: '',
    };
    const ppcPreparedByParams = {
        globalVar: materialVar.ppcPreparedBy,
        formModel: toRef(frmMaterial.value,'ppcPreparedBy'),
        selectedVal: '',
    };
    const ppcCheckedByParams = {
        globalVar: materialVar.ppcCheckedBy,
        formModel: toRef(frmMaterial.value,'ppcCheckedBy'),
        selectedVal: '',
    };
    const ppcApprovedByParams = {
        globalVar: materialVar.ppcApprovedBy,
        formModel: toRef(frmMaterial.value,'ppcApprovedBy'),
        selectedVal: '',
    };

    //Columns
    const ecrColumns = [
        {   data: 'get_actions',
            orderable: false,
            searchable: false,
            createdCell(cell){
                let btnGetEcrId = cell.querySelector('#btnGetEcrId');
                let btnDownloadMaterialRef = cell.querySelector('#btnDownloadMaterialRef');
                if(btnGetEcrId != null){
                    btnGetEcrId.addEventListener('click',function(){
                        let ecrId = this.getAttribute('ecr-id');
                        frmMaterial.value.ecrsId = ecrId;
                        tblEcrDetails.value.dt.ajax.url("api/load_ecr_details_by_ecr_id?ecr_id="+ecrId).draw()
                        getMaterialEcrById(ecrId);
                        getRapidxUserByIdOpt(prPreparedByParams);
                        getRapidxUserByIdOpt(prCheckedByParams);
                        getRapidxUserByIdOpt(prApprovedByParams);
                        getRapidxUserByIdOpt(ppcPreparedByParams);
                        getRapidxUserByIdOpt(ppcCheckedByParams);
                        getRapidxUserByIdOpt(ppcApprovedByParams);
                        modal.SaveMaterial.show();
                    });
                }
                if(btnDownloadMaterialRef != null){
                    btnDownloadMaterialRef.addEventListener('click',function(){
                        let ecrsId = this.getAttribute('ecr-id');
                        selectedEcrsId.value = ecrsId;
                        alert('asdas')
                        modal.UploadMaterialRef.show();
                    });
                }
            }
        } ,
        {   data: 'get_status'} ,
        {   data: 'get_attachment',
            orderable: false,
            searchable: false,
            createdCell(cell){
                let btnViewMaterialRef = cell.querySelector('#btnViewMaterialRef');
                if(btnViewMaterialRef != null){
                    btnViewMaterialRef.addEventListener('click',function(){
                        let ecrsId = this.getAttribute('ecr-id');
                        alert('asdasdsa');
                        getMaterialRefByEcrsId(ecrsId);
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
        modal.SaveEcrDetail = new Modal(modalSaveEcrDetail.value.modalRef,{ keyboard: false });
        modal.SaveMaterial = new Modal(modalSaveMaterial.value.modalRef,{ keyboard: false });
        modal.UploadMaterialRef = new Modal(modalUploadMaterialRef.value.modalRef,{ keyboard: false });
        modal.ViewMaterialRef = new Modal(modalViewMaterialRef.value.modalRef,{ keyboard: false });
        // modal.ViewMaterialRef.show();
        await getDropdownMasterByOpt(descriptionOfChangeParams);
        await getDropdownMasterByOpt(reasonOfChangeParams);
        await getDropdownMasterByOpt(typeOfPartParams);
        await getDropdownMasterByOpt(materialSupplierParams);
        await getDropdownMasterByOpt(materialColorParams);
    })
    //Functions

    const changeMaterialRef = async (event)  => {
        materialRef.value =  Array.from(event.target.files);
    }
    const getMaterialRefByEcrsId = async (ecrsId) => {
        let apiParams = {
            ecrsId : ecrsId
        }
        axiosFetchData(apiParams,'api/get_material_ref_by_ecrs_id',function(response){
            let data = response.data;
            let ecrsId = data.ecrsId;
            let originalFilename = data.originalFilename;
            arrOriginalFilenames.value = originalFilename;
            selectedEcrsIdEncrypted.value = ecrsId;
            modal.ViewMaterialRef.show();
        });
    }
    const btnLinkViewMaterialRef = async (selectedEcrsIdEncrypted,index) => {
        window.open(`api/view_material_ref?ecrsId=${selectedEcrsIdEncrypted} && index=${index}`, '_blank');
    }
    const getMaterialEcrById = async (ecrId) => {
        let apiParams = {
            ecrId : ecrId
        }
        axiosFetchData(apiParams,'api/get_material_ecr_by_id',function(response){
            let data = response.data;
            let material = data.material[0];

            frmMaterial.value.ecrsId = material.ecrs_id;
            frmMaterial.value.materialId = material.id;
            frmMaterial.value.pdMaterial = material.pd_material;
            frmMaterial.value.msds = material.msds;
            frmMaterial.value.icp = material.icp;
            frmMaterial.value.gp = material.gp;
            frmMaterial.value.qoutation = material.qoutation;
            frmMaterial.value.materialSupplier = material.material_supplier;
            frmMaterial.value.materialColor = material.material_color;
            frmMaterial.value.rohs = material.rohs;
            frmMaterial.value.materialSample = material.material_sample;
            frmMaterial.value.coc = material.coc;
            frmMaterial.value.remarks = material.remarks;
            console.log(material);
        });
    }
    const saveMaterial = async () =>{
        let formData = new FormData();
        //Append form data
        [
            ["ecrs_id", frmMaterial.value.ecrsId],
            ["material_id", frmMaterial.value.materialId],
            ["pd_material", frmMaterial.value.pdMaterial],
            ["msds", frmMaterial.value.msds],
            ["icp", frmMaterial.value.icp],
            ["gp", frmMaterial.value.gp],
            ["qoutation", frmMaterial.value.qoutation],
            ["material_supplier", frmMaterial.value.materialSupplier],
            ["material_color", frmMaterial.value.materialColor],
            ["rohs", frmMaterial.value.rohs],
            ["material_sample", frmMaterial.value.materialSample],
            ["coc", frmMaterial.value.coc],
            //  , frmMaterial.value.materialSupplier],
            //  , frmMaterial.value.materialColor],
            ["prPreparedBy", frmMaterial.value.prPreparedBy],
            ["prCheckedBy", frmMaterial.value.prCheckedBy],
            ["prApprovedBy", frmMaterial.value.prApprovedBy],
            ["ppcPreparedBy", frmMaterial.value.ppcPreparedBy],
            ["ppcCheckedBy", frmMaterial.value.ppcCheckedBy],
            ["ppcApprovedBy", frmMaterial.value.ppcApprovedBy],

        ].forEach(([key, value]) =>
            formData.append(key, value)
        );
        axiosSaveData(formData,'api/save_material', (response) =>{
            modal.SaveMaterial.hide();
            tblEcrByCategoryStatus.value.dt.draw();
        });
    }
    const frmUploadMaterialRef = async () => {
        let formData = new FormData();
        materialRef.value.forEach((file, index) => {
            formData.append('material_ref[]', file);
        });
        formData.append("ecrsId", selectedEcrsId.value);

        axiosSaveData(formData,'api/upload_material_ref',(response) =>{
            console.log(response);
        });
    }
</script>

