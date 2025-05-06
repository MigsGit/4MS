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
                    <!-- <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    </table> -->
                    <DataTable
                        width="100%" cellspacing="0"
                        class="table table-bordered mt-2"
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
                        class="table table-bordered mt-2"
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
            TODO: Add Man,Man Details,Special Acceptance
        </template>
        <template #footer>
            <button type="button" id= "closeBtn" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Close</button>
        </template>
    </ModalComponent>
    <ModalComponent icon="fa-user" modalDialog="modal-dialog modal-lg" title="Ecr Details" @add-event="" ref="modalSaveEcrDetail">
        <template #body>
            <div class="row">
                <div class="input flex-nowrap mb-2 input-group-sm">
                        <input   type="hidden" class="form-control form-control" aria-describedby="addon-wrapping">
                </div>
                <div class="input-group flex-nowrap mb-2 input-group-sm">
                    <span class="input-group-text" id="addon-wrapping">Customer Name:</span>
                    <input  type="text" class="form-control form-control" aria-describedby="addon-wrapping">
                </div>
                <div class="col-sm-6">
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Category:</span>
                        <select  class="form-select form-select-sm" aria-describedby="addon-wrapping">
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
                        <input type="text" class="form-control form-control" aria-describedby="addon-wrapping">
                    </div>
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Part Name:</span>
                        <input  type="text" class="form-control" aria-describedby="addon-wrapping">
                    </div>
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Product Line:</span>
                        <input  type="text" class="form-control" aria-describedby="addon-wrapping">
                    </div>
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Section:</span>
                        <input  type="text" class="form-control" aria-describedby="addon-wrapping">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Internal / External:</span>
                        <select  class="form-select form-select-sm" aria-describedby="addon-wrapping">
                            <option value="" selected disabled>Select</option>
                            <option value="Internal">Internal</option>
                            <option value="External">External</option>
                        </select>
                    </div>
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Part Number:</span>
                        <input  type="text" class="form-control" aria-describedby="addon-wrapping">
                    </div>
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Device Name:</span>
                        <input type="text" class="form-control" aria-describedby="addon-wrapping">
                    </div>
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Customer EC No. (If any):</span>
                        <input  type="text" class="form-control" aria-describedby="addon-wrapping">
                    </div>
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Date of Request:</span>
                        <input type="date" class="form-control" aria-describedby="addon-wrapping">
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
    import DataTable from 'datatables.net-vue3';
    import DataTablesCore from 'datatables.net-bs5';
    window.DataTable = DataTable.use(DataTablesCore)

    const tblEcrByStatus = ref(null);
    const tblEcrDetails = ref(null);
    const modalSaveMan = ref(null);
    const modalSaveEcrDetail = ref(null);
    const modal = {}

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
        modal.SaveMan = new Modal(modalSaveMan.value.modalRef,{ keyboard: false });
        modal.SaveEcrDetail = new Modal(modalSaveEcrDetail.value.modalRef,{ keyboard: false });
        // modal.SaveMan.show();
        // modal.SaveEcrDetail.show();
        // tblEcrByStatus.value.dt.ajax.url("api/load_ecr_by_status?status="+'AP').draw()
    })
</script>


