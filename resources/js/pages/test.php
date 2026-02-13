<ModalComponent icon="fa-user" modalDialog="modal-dialog modal-lg" title="Ecr Details" @add-event="saveEcrDetails()" ref="modalSaveEcrDetail">
        <template #body>
             <!-- Description of Change / Reason for Change -->
             <EcrChangeComponent :isSelectReadonly="isSelectReadonly" :frmEcrReasonRows="frmEcrReasonRows" :optDescriptionOfChange="ecrVar.optDescriptionOfChange" :optReasonOfChange="ecrVar.optReasonOfChange">
            </EcrChangeComponent>
            <div class="row d-none">
                <div class="input-group flex-nowrap mb-2 input-group-sm d-none">
                    <span class="input-group-text" id="addon-wrapping">ECR Details Id:</span>
                    <input v-model="frmEcrDetails.ecrDetailsId"  type="hidden" class="form-control form-control-lg" aria-describedby="addon-wrapping" readonly>
                </div>
                <div class="row">
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
                            <button @click="reloadDropdown()" class="btn btn-outline-warning btn-sm" type="button" data-item-process="remove">
                                <font-awesome-icon class="nav-icon" icon="refresh" />
                            </button>
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
            </div>
        </template>
        <template #footer>
            <button type="button" id= "closeBtn" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success btn-sm"><li class="fas fa-save"></li> Save</button>
        </template>
    </ModalComponent>