<template>
    <div class="container-fluid px-4">
        <h4 class="mt-4">Dropdown Master</h4>
        <div class="row">
            <div class="col-md-3 offset-md-4">
                <Multiselect
                    v-model="slctDropdownMaster"
                    :close-on-select="true"
                    :searchable="true"
                    :options="optDropdownMaster"
                    @change="onDropdownMasterChange($event)"
                />
            </div>
        </div>
        <div class="card mt-3"  style="width: 100%;">
            <div class="row justify-content-end">
                <div class="col-md-2 mt-3">
                    <button v-if="frmDropdownMasterDetails.dropdownMastersId != ''" @click="btnAddDropdownMasterDetails" type="button" class="btn btn-primary btn-sm mb-2"><i class="fas fa-plus"></i> Add Dropdown Details</button>
                </div>
            </div>
            <div class="card-body overflow-auto">
                <div class="table-responsive">
                    <DataTable
                        width="100%" cellspacing="0"
                        class="table mt-2"
                        ref="tblDropdownMasterDetails"
                        :columns="tblDropdownMasterDetailsColumns"
                        ajax="api/load_dropdown_master_details"
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
                                <th>Name</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                    </DataTable>
                </div>
            </div>
        </div>
    </div>
    <ModalComponent icon="fa-user" modalDialog="modal-dialog modal-md" title="Dropdown Master Details" @add-event="saveDropdownMasterDetails()" ref="modalSaveDropdownMasterDetails">
        <template #body>
                <div class="row">
                    <div class="input flex-nowrap mb-2 input-group-sm">
                            <input v-model="frmDropdownMasterDetails.dropdownMastersId" type="hidden" class="form-control form-control" aria-describedby="addon-wrapping">
                            <input v-model="frmDropdownMasterDetails.dropdownMasterDetailsId" type="hidden" class="form-control form-control" aria-describedby="addon-wrapping">
                    </div>
                    <div class="col-sm-12">
                        <div class="input-group flex-nowrap mb-2 input-group-sm">
                            <span class="input-group-text" id="addon-wrapping">Details:</span>
                            <input  v-model="frmDropdownMasterDetails.dropdownMastersDetails" type="text" class="form-control form-control" aria-describedby="addon-wrapping">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="input-group flex-nowrap mb-2 input-group-sm">
                            <span class="input-group-text" id="addon-wrapping">Remarks:</span>
                            <textarea v-model="frmDropdownMasterDetails.remarks"class="form-control form-control" aria-describedby="addon-wrapping">
                            </textarea>
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
    import {
        onMounted,
        ref,
    } from 'vue'
    import ModalComponent from '../components/ModalComponent.vue';
    import useSettings from '../composables/settings.js';
    import useForm from '../../js/composables/utils/useForm.js'
    import DataTable from 'datatables.net-vue3';
    import DataTablesCore from 'datatables.net-bs5';
    DataTable.use(DataTablesCore);

    //Ref State
    const modalSaveDropdownMasterDetails = ref(null);
    const optDropdownMaster = ref([]);
    const slctDropdownMaster = ref(null);
    const btnDropdownMasterDetails = ref(null);
    const tblDropdownMasterDetails = ref(null);

    //Constant Object
    const {
        modal,
        frmDropdownMasterDetails,
        axiosFetchData,
        getDropdownMasterByOpt
    } = useSettings();
    const { axiosSaveData } = useForm(); // Call the useFetch function
    const tblDropdownMasterDetailsColumns = [
    {   data: 'get_action',
            createdCell(cell){
                let btnDropdownMasterDetails = cell.querySelector('#btnDropdownMasterDetails');
                if(btnDropdownMasterDetails != null){
                    btnDropdownMasterDetails.addEventListener('click',function(){
                        let dropdownMasterDetailsId = this.getAttribute('dropdown-master-details-id');
                        getDropdownMasterDetailsId(dropdownMasterDetailsId);
                    });
                }
            }
        },
        {   data: 'get_status'} ,
        {   data: 'dropdown_masters_details'} ,
        {   data: 'remarks'} ,
    ];

    const dropDownMasterParams = {
        globalVar: optDropdownMaster,
        formModel: slctDropdownMaster,
        selectedVal: '',
    };

    onMounted(async () => {
        modal.modalSaveDropdownMasterDetails = new Modal(modalSaveDropdownMasterDetails.value.modalRef,{ keyboard: false });
        await getDropdownMaster(dropDownMasterParams);
    })
    //Functions
    const getDropdownMaster = async (params) =>{
        let apiParams = {}
        axiosFetchData(apiParams,'api/get_dropdown_master',function(response){
            let data = response.data;
            let dropdownMaster = data.dropdownMaster;
            params.globalVar.value.splice(0, params.globalVar.value.length,
                { value: '', label: '-Select an option-', disabled:true }, // Push "" option at the start
                    ...dropdownMaster.map((value) => {
                    return {
                        value: value.id,
                        label: value.dropdown_masters
                    }
                }),
            );
            params.formModel.value = params.selectedVal; //selectedValue after the reading data
        });
    }

    const onDropdownMasterChange = async (dropDownMastersId) => {
        tblDropdownMasterDetails.value.dt.ajax.url('api/load_dropdown_master_details?dropDownMastersId='+dropDownMastersId).draw();
        frmDropdownMasterDetails.value.dropdownMastersId = dropDownMastersId;
        // console.log(btnDropdownMasterDetails.value.classList);
    }

    const btnAddDropdownMasterDetails = async () => {
        modal.modalSaveDropdownMasterDetails.show();
    }

    const getDropdownMasterDetailsId = async (dropdownMasterDetailsId) => {
        let apiParams = {
            'dropdownMasterDetailsId' : dropdownMasterDetailsId
        }
        axiosFetchData(apiParams,'api/get_dropdown_master_details_id',function(response){
            let data = response.data;
            let dropdownMasterDetail = data.dropdownMasterDetail[0];
            frmDropdownMasterDetails.value.dropdownMasterDetailsId = dropdownMasterDetail.id;
            frmDropdownMasterDetails.value.dropdownMastersDetails = dropdownMasterDetail.dropdown_masters_details;
            frmDropdownMasterDetails.value.remarks = dropdownMasterDetail.remarks;
            modal.modalSaveDropdownMasterDetails.show();
        });
    }

    const saveDropdownMasterDetails = async () => {
        let formData = new FormData();
        //Append form data
        [
            ["dropdown_masters_id", frmDropdownMasterDetails.value.dropdownMastersId],
            ["dropdown_master_details_id", frmDropdownMasterDetails.value.dropdownMasterDetailsId],
            ["dropdown_masters_details", frmDropdownMasterDetails.value.dropdownMastersDetails],
            ["remarks", frmDropdownMasterDetails.value.remarks],
        ].forEach(([key, value]) =>
            formData.append(key, value)
        );
        axiosSaveData(formData,'api/save_dropdown_master_details', (response) =>{
            modal.modalSaveDropdownMasterDetails.hide();
            tblDropdownMasterDetails.value.dt.ajax.url('api/load_dropdown_master_details?dropDownMastersId='+frmDropdownMasterDetails.value.dropdownMastersId).draw();
        });
    }
</script>

