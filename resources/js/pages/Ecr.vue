<template>
    <div class="container-fluid px-4">
        <h4 class="mt-4">ENGINEERING CHANGE REQUEST</h4>
        <div class="card mt-5"  style="width: 100%;">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active .menuTab" id="Pending-tab" data-bs-toggle="tab" href="#menu1" role="tab" aria-controls="menu1" aria-selected="true">For Approval</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link .menuTab" id="Completed-tab" data-bs-toggle="tab" href="#menu2" role="tab" aria-controls="menu2" aria-selected="false">QA Approval</a>
                </li>
            </ul>
            <div class="tab-content mt-2" id="myTabContent">
                <div class="tab-pane fade show active" id="menu1" role="tabpanel" aria-labelledby="menu1-tab">
                    <div class="container-fluid px-4">
                        <button @click="btnEcr"type="button" class="btn btn-primary btn-sm mb-2" style="float: right !important;"><i class="fas fa-plus"></i> Create ECR</button>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Engineering Change Request</li>
                        </ol>
                        <div class="table-responsive">
                        <DataTable
                            width="100%" cellspacing="0"
                            class="table mt-2"
                            ref="tblEcr"
                            :columns="tblEcrColumns"
                            ajax="api/load_ecr?status=IA"
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
                <div class="tab-pane fade" id="menu2" role="tabpanel" aria-labelledby="menu1-tab">
                    <div class="container-fluid px-4">
                            <ol class="breadcrumb mb-4">
                                <li class="breadcrumb-item active">QA Approval</li>
                            </ol>
                            <div class="table-responsive">
                            <DataTable
                                width="100%" cellspacing="0"
                                class="table mt-2"
                                ref="tblEcr"
                                :columns="tblEcrColumns"
                                ajax="api/load_ecr?status=QA"
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
    <ModalComponent icon="fa-user" modalDialog="modal-dialog modal-lg" :title="modalTitle+' '+'ECR'" @add-event="frmSaveEcr()" ref="modalSaveEcr">
        <template #body>
                <div class="row">
                    <div class="input flex-nowrap mb-2 input-group-sm">
                        <input  v-model="frmEcr.ecrsId" type="text" class="form-control form-control" aria-describedby="addon-wrapping">
                    </div>

                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Ecr Ctrl No:</span>
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
                <EcrChangeComponent :isSelectReadonly="isSelectReadonly" @remove-ecr-reason-rows-event="removeEcrReasonRows(index)" @add-ecr-reason-rows-event="addEcrReasonRows()":frmEcrReasonRows="frmEcrReasonRows" :optDescriptionOfChange="ecrVar.optDescriptionOfChange" :optReasonOfChange="ecrVar.optReasonOfChange">
                </EcrChangeComponent>
                <!-- Others Disposition -->

                <div v-show="isSelectReadonly === false" class="card mb-2">
                        <h5 class="mb-0">
                            <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="true" aria-controls="collapse2">
                                Others Disposition {{ isSelectReadonly }}
                            </button>
                        </h5>
                    <div id="collapse2" class="collapse" data-bs-parent="#accordionMain">
                        <div class="card-body shadow">
                            <div class="row">
                                <div class="col-12">
                                    <button @click="btnAddEcrOtherDispoRows()" test="dasd" type="button" class="btn btn-primary btn-sm mb-2" style="float: right !important;"><i class="fas fa-plus"></i> Add Validator</button>
                                </div>
                                <div class="col-12">
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
                                                        @change="onUserChange(otherDispoTechnicalEvaluationParams)"
                                                        :disabled="isSelectReadonly"
                                                    />
                                                    {{frmEcrOtherDispoRows.remarks}}
                                                </td>
                                                <td>
                                                    <Multiselect
                                                        v-model="frmEcrOtherDispoRow.technicalEvaluation"
                                                        :close-on-select="true"
                                                        :searchable="true"
                                                        :options="ecrVar.technicalEvaluation"
                                                        @change="onUserChange(otherDispoReviewedByParams)"
                                                        :disabled="isSelectReadonly"
                                                    />
                                                </td>
                                                <td>
                                                    <Multiselect
                                                        v-model="frmEcrOtherDispoRow.reviewedBy"
                                                        :close-on-select="true"
                                                        :searchable="true"
                                                        :options="ecrVar.reviewedBy"
                                                        @change="onUserChange(qadCheckedByParams)"
                                                        :disabled="isSelectReadonly"
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
                 <div class="card mb-2 h-100" v-show="isSelectReadonly === false">
                        <h5 class="mb-0">
                            <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="true" aria-controls="collapse3">
                                QA Dispositions
                            </button>
                        </h5>
                    <div id="collapse3" class="collapse" data-bs-parent="#accordionMain">
                        <div class="card-body shadow">
                            <div class="row">
                                <!-- style="height: 200px;-->
                                <div class="col-12">
                                    <span class="input-group-text" id="addon-wrapping">Agreed By: </span>
                                </div>
                                <div class="col-12">
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
                                                        @change="onUserChange(qadApprovedByInternalParams)"
                                                        :disabled="isSelectReadonly"
                                                        />
                                                    </td>
                                                <td>
                                                    <Multiselect
                                                        v-model="frmEcrQadRows.qadApprovedByInternal"
                                                        :close-on-select="true"
                                                        :searchable="true"
                                                        :options="ecrVar.optQadApprovedByInternal"
                                                        @change="onUserChange(qadApprovedByExternalParams)"
                                                        :disabled="isSelectReadonly"
                                                        />
                                                    </td>
                                                <td>
                                                    <Multiselect
                                                        v-model="frmEcrQadRows.qadApprovedByExternal"
                                                        :close-on-select="true"
                                                        :searchable="true"
                                                        :options="ecrVar.optQadApprovedByExternal"
                                                        @change="onUserChange(pmiApproverPreparedByParams)"
                                                        :disabled="isSelectReadonly"
                                                    />
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- TODO ECR | 4M Requirement Add Edit Sr. Sup |  View Manager
                        <div class="card-footer justify-content-end">
                            <button type="button" id= "closeBtn" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-success btn-sm"><font-awesome-icon class="nav-icon" icon="fas fa-eye" />&nbsp;     View</button>
                        </div> -->
                    </div>
                </div>
                <!-- PMI Approvers -->
                <div class="card mb-2" v-show="isSelectReadonly === false">
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
                                <div class="col-12">
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
                                                        @change="onUserChange(pmiApproverCheckedByParams)"
                                                        :disabled="isSelectReadonly"


                                                    />

                                                </td>
                                                <td>
                                                    <Multiselect
                                                        v-model="frmEcrPmiApproverRow.checkedBy"
                                                        :close-on-select="true"
                                                        :searchable="true"
                                                        :options="ecrVar.checkedBy"
                                                        @change="onUserChange(pmiApproverApprovedByParams)"
                                                        :disabled="isSelectReadonly"
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
                <!-- Approver Summary -->
                <div class="row mt-3" v-show="isSelectReadonly === true">
                    <div class="card mb-2">
                            <h5 class="mb-0">
                                <button id="" class="btn btn-link collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseApprovalSummary" aria-expanded="true" aria-controls="collapseApprovalSummary">
                                    ECR Approver Summary
                                </button>
                            </h5>
                        <div id="collapseApprovalSummary" class="collapse show" data-bs-parent="#accordionMain">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <DataTable
                                            width="100%" cellspacing="0"
                                            class="table mt-2"
                                            ref="tblEcrApproverSummary"
                                            :columns="tblEcrApproverSummaryColumns"
                                            ajax="api/load_ecr_approval_summary?ecrs_id="
                                            :options="{
                                                paging:false,
                                                serverSide: true, //Serverside true will load the network
                                                ordering: false,
                                            }"
                                        >
                                            <thead>
                                                <tr>
                                                    <th>#</th>
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
                <button v-show="isSelectReadonly === false" type="button" id= "closeBtn" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                <button v-show="isSelectReadonly === false" type="submit" class="btn btn-success btn-sm"><font-awesome-icon class="nav-icon" icon="fas fa-save" />&nbsp;     Save</button>
                <button @click="btnEcrApproval('DIS')" v-show="isSelectReadonly === true" type="button" ref= "btnEcrDisapproved" class="btn btn-danger btn-sm">
                    <font-awesome-icon class="nav-icon" icon="fas fa-thumbs-down" />&nbsp;Disapproved
                </button>
                <button @click="btnEcrApproval('APP')" v-show="isSelectReadonly === true" type="button" ref= "btnEcrApproved" class="btn btn-success btn-sm">
                    <font-awesome-icon class="nav-icon" icon="fas fa-thumbs-up" />&nbsp;Approved
                </button>
            </template>
    </ModalComponent>
    <ModalComponent icon="fa-user" modalDialog="modal-dialog modal-lg" title="ECR Requirements" ref="modalEcrRequirements">
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
                                        ref="tblEcrManRequirements"
                                        :columns="tblEcrManRequirementsColumns"
                                        ajax="api/load_ecr_requirements?category=1"
                                        :options="{
                                            paging:false,
                                            serverSide: true, //Serverside true will load the network
                                            columnDefs:[
                                                {
                                                    orderable:false,target:[3],

                                                }
                                            ]
                                        }"
                                    >
                                        <thead>
                                            <tr>
                                                <th>Requirement</th>
                                                <th>Details</th>
                                                <th>Evidence</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    </DataTable>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <!-- Material -->
                <div class="card mb-2">
                        <h5 class="mb-0">
                            <button id="" class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapMat" aria-expanded="true" aria-controls="collapMat">
                                Material
                            </button>
                        </h5>
                    <div id="collapMat" class="collapse" data-bs-parent="#accordionMain">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <DataTable
                                        width="100%" cellspacing="0"
                                        class="table mt-2"
                                        ref="tblEcrMatRequirements"

                                        :columns="tblEcrManRequirementsColumns"
                                        ajax="api/load_ecr_requirements?category=2"
                                        :options="{
                                            paging:false,
                                            serverSide: true, //Serverside true will load the network
                                            columnDefs:[
                                                {orderable:false,target:[3]}
                                            ]
                                        }"
                                    >
                                        <thead>
                                            <tr>
                                                <th>Requirement</th>
                                                <th>Details</th>
                                                <th>Evidence</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    </DataTable>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <!-- Machine -->
                <div class="card mb-2">
                        <h5 class="mb-0">
                            <button id="" class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMac" aria-expanded="true" aria-controls="collapseMac">
                                Machine
                            </button>
                        </h5>
                    <div id="collapseMac" class="collapse" data-bs-parent="#accordionMain">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <DataTable
                                        width="100%" cellspacing="0"
                                        class="table mt-2"
                                        ref="tblEcrMachineRequirements"
                                        :columns="tblEcrManRequirementsColumns"
                                        ajax="api/load_ecr_requirements?category=3"
                                        :options="{
                                            paging:false,
                                            serverSide: true, //Serverside true will load the network
                                            columnDefs:[
                                                {orderable:false,target:[3]}
                                            ]
                                        }"
                                    >
                                        <thead>
                                            <tr>
                                                <th>Requirement</th>
                                                <th>Details</th>
                                                <th>Evidence</th>
                                                <th>Action</th>
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
    <ModalComponent icon="fa-user" modalDialog="modal-dialog modal-md" title="ECR Approval" ref="modalEcrApproval" @add-event="frmSaveEcrApproval()">
        <template #body>
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Remarks:</span>
                        <textarea v-model="frmEcr.approvalRemarks" class="form-control form-control-lg" aria-describedby="addon-wrapping">
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
    import {ref , onMounted,reactive, toRef} from 'vue';
    import ModalComponent from '../components/ModalComponent.vue';
    import EcrChangeComponent from '../components/EcrChangeComponent.vue';
    import useEcr from '../../js/composables/ecr.js';
    import useForm from '../../js/composables/utils/useForm.js'
    import useSettings from '../composables/settings.js';
    import DataTable from 'datatables.net-vue3';
    import DataTablesCore from 'datatables.net-bs5';
    DataTable.use(DataTablesCore);

    const { axiosSaveData } = useForm(); // Call the useFetch function
    //composables export function
    const {
        modal,
        ecrVar,
        frmEcr,
        frmEcrReasonRows,
        frmEcrQadRows,
        frmEcrOtherDispoRows,
        frmEcrPmiApproverRows,
        descriptionOfChangeParams,
        reasonOfChangeParams,
        resetArrEcrRows,
        axiosFetchData,
        getEcrById,
        addEcrReasonRows,
        removeEcrReasonRows,
    } = useEcr();
    const {
        getRapidxUserByIdOpt,
        getDropdownMasterByOpt,
        onUserChange,
    } = useSettings();
    // const item = ref();
    //ref state
    const modalSaveEcr = ref(null);
    const modalTitle = ref(null);
    const modalEcrRequirements = ref(null);
    const modalEcrApproval = ref(null);
    const isSelectReadonly = ref(null);
    const tblEcr = ref(null);
    const tblEcrManRequirements = ref(null);
    const tblEcrMatRequirements = ref(null);
    const tblEcrMachineRequirements = ref(null);
    const tblEcrApproverSummary = ref(null);
    const btnEcrApproved = ref(null);
    const btnEcrDisapproved = ref(null);
    const isApproved = ref(null);

    //Table Column
    const tblEcrColumns = [
        {   data: 'get_actions',
            orderable: false,
            searchable: false,
            createdCell(cell){
                let btnGetEcrId = cell.querySelector('#btnGetEcrId');
                let btnViewEcrId = cell.querySelector('#btnViewEcrId');

                btnGetEcrId.addEventListener('click',function(){
                    let ecrsId = this.getAttribute('ecr-id');
                    modalTitle.value = "Edit";
                    isSelectReadonly.value = false;
                    //:disabled
                    getRapidxUserByIdOpt(otherDispoRequestedByParams);
                    getRapidxUserByIdOpt(otherDispoTechnicalEvaluationParams);
                    getRapidxUserByIdOpt(otherDispoReviewedByParams);
                    getRapidxUserByIdOpt(qadCheckedByParams);
                    getRapidxUserByIdOpt(qadApprovedByInternalParams);
                    getRapidxUserByIdOpt(qadApprovedByExternalParams);
                    getRapidxUserByIdOpt(pmiApproverPreparedByParams);
                    getRapidxUserByIdOpt(pmiApproverCheckedByParams);
                    getRapidxUserByIdOpt(pmiApproverApprovedByParams);
                    getEcrById(ecrsId);
                    tblEcrApproverSummary.value.dt.ajax.url("api/load_ecr_details_by_ecr_id?ecrs_id="+ecrsId).draw()
                });
                btnViewEcrId.addEventListener('click',function(){
                    let ecrsId = this.getAttribute('ecr-id');
                    modalTitle.value = "View";
                    isSelectReadonly.value = true;
                    getRapidxUserByIdOpt(otherDispoRequestedByParams);
                    getRapidxUserByIdOpt(otherDispoTechnicalEvaluationParams);
                    getRapidxUserByIdOpt(otherDispoReviewedByParams);
                    getRapidxUserByIdOpt(qadCheckedByParams);
                    getRapidxUserByIdOpt(qadApprovedByInternalParams);
                    getRapidxUserByIdOpt(qadApprovedByExternalParams);
                    getRapidxUserByIdOpt(pmiApproverPreparedByParams);
                    getRapidxUserByIdOpt(pmiApproverCheckedByParams);
                    getRapidxUserByIdOpt(pmiApproverApprovedByParams);
                    getEcrById(ecrsId);
                    tblEcrApproverSummary.value.dt.ajax.url("api/load_ecr_approval_summary?ecrsId="+ecrsId).draw()
                });
            }
        } ,
        {   data: 'get_status'} ,
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
    const tblEcrManRequirementsColumns = [
        {   data: 'requirement'} ,
        {   data: 'details'} ,
        {   data: 'evidence'} ,
        {   data: 'get_actions',
            createdCell(cell){
                let btnChangeEcrReqDecision = cell.querySelector('#btnChangeEcrReqDecision');
                if(btnChangeEcrReqDecision != null){
                    btnChangeEcrReqDecision.addEventListener('change',function(){
                        let ecrReqId = this.getAttribute('ecr-requirements-id');
                        let ecrReqValue = this.value;
                        let classificationRequirementId = this.getAttribute('classification-requirement-id');
                        let selectedParams = {
                            ecr_req_id : ecrReqId,
                            ecr_req_value : ecrReqValue,
                            classification_requirement_id : classificationRequirementId,
                        }
                        let varParams = {
                            btnChangeEcrReqDecisionClas: this.classList,
                        }
                        ecrReqDecisionChange(selectedParams,varParams);
                    });
                }
            }
        }
    ];

    const tblEcrApproverSummaryColumns = [
        {   data: 'get_count'} ,
        {   data: 'get_approver_name'} ,
        {   data: 'remarks'},
        {   data: 'get_status'} ,
    ];

    //constant object params
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
    const btnEcr = async () => {
        modal.SaveEcr.show();
        isSelectReadonly.value = false;
        await getRapidxUserByIdOpt(otherDispoRequestedByParams);

    }
    // const onUserChange = async (selectedParams)=>{
    //     await getRapidxUserByIdOpt(selectedParams);
    // }
    const ecrReqDecisionChange = async (selectedParams,varParams)=>{
        console.log('ecrReqDecisionChange',selectedParams);
        console.log(varParams);

        axiosFetchData(selectedParams,'api/ecr_req_decision_change',function(response){
            varParams.btnChangeEcrReqDecisionClas.remove("is-invalid");
            tblEcrManRequirements.value.dt.draw();
            tblEcrMatRequirements.value.dt.draw();
            tblEcrMachineRequirements.value.dt.draw();
        });
    }
    onMounted( async ()=>{
        //ModalRef inside the ModalComponent.vue
        //Do not name the Modal it is same new Modal js clas
        modal.SaveEcr = new Modal(modalSaveEcr.value.modalRef,{ keyboard: false });
        modal.EcrRequirements = new Modal(modalEcrRequirements.value.modalRef,{ keyboard: false });
        modal.EcrApproval = new Modal(modalEcrApproval.value.modalRef,{ keyboard: false });
        await getDropdownMasterByOpt(descriptionOfChangeParams);
        await getDropdownMasterByOpt(reasonOfChangeParams);
        modal.EcrRequirements.show();
        // await getRapidxUserByIdOpt(otherDispoRequestedByParams);
        // await getRapidxUserByIdOpt(otherDispoTechnicalEvaluationParams);
        // await getRapidxUserByIdOpt(otherDispoReviewedByParams);
        // await getRapidxUserByIdOpt(qadCheckedByParams);
        // await getRapidxUserByIdOpt(qadApprovedByInternalParams);
        // await getRapidxUserByIdOpt(qadApprovedByExternalParams);
        // await getRapidxUserByIdOpt(pmiApproverPreparedByParams);
        // await getRapidxUserByIdOpt(pmiApproverCheckedByParams);
        // await getRapidxUserByIdOpt(pmiApproverApprovedByParams);
        const btnChangeEcrReqDecision = toRef(btnChangeEcrReqDecision);
        $('#collapse1').addClass('show');
    })
    //Functions
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
    const btnEcrApproval = async (isEcrApproved) => {
        modal.EcrApproval.show();
        isApproved.value = isEcrApproved;
    }
    const frmSaveEcrApproval = async () => {
        let formData = new FormData();
        //Append form data
        [
            ["ecrs_id", frmEcr.value.ecrsId],
            ["status", isApproved.value],
            ["remarks", frmEcr.value.approvalRemarks],
        ].forEach(([key, value]) =>
            formData.append(key, value)
        );
        axiosSaveData(formData,'api/save_ecr_approval', (response) =>{
            tblEcrApproverSummary.value.dt.draw();
            modal.EcrApproval.hide();
            modal.SaveEcr.hide();
        });
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
        //TODO: Save Successfully
        axiosSaveData(formData,'api/save_ecr', (response) =>{
            tblEcr.value.dt.draw();
            modal.SaveEcr.modal();
            console.log(response);
        });
    }

</script>


