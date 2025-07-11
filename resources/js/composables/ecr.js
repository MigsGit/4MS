import { ref, inject,reactive,nextTick,toRef } from 'vue'
import useFetch from './utils/useFetch';
import useForm from "./utils/useForm";

export default function useEcr(){
    const { axiosFetchData } = useFetch(); // Call  the useFetch function
    const  { axiosSaveData } = useForm();

    //Constant Object
    const modal = {
        SaveEcrDetail : null,
    };
    const modalEcr = {};
    //Reactive State
    const ecrVar = reactive({
        optDescriptionOfChange: [],
        optReasonOfChange: [],

        optQadCheckedBy: [],
        optQadApprovedByInternal: [],

        requestedBy: [],
        technicalEvaluation: [],
        reviewedBy: [],

        preparedBy: [],
        checkedBy: [],
        approvedBy: [],

        optTypeOfPart: [],
    });
    //Ref State
    const frmEcr = ref({
        ecrsId: '',
        ecrNo: '',
        approvalStatus: '',
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
        approvalRemarks: '',
    });
    const frmEcrDetails = ref({
        ecrDetailsId: '',
        ecrsId: '',
        typeOfPart: '',
        changeImpDate: '',
        docSubDate: '',
        docToBeSub: 'test',
        remarks: 'test',
    });
    const frmEcrReasonRows = ref([
        {
            descriptionOfChange: '',
            reasonOfChange: '',
        },
    ]);
    const frmEcrQadRows = ref([
        {
            qadCheckedBy: '',
            qadApprovedByInternal: '',
        },
    ]);
    const frmEcrOtherDispoRows = ref([
        {
            requestedBy: '',
            technicalEvaluation: '',
            reviewedBy: '',
        },
    ]);
    const frmEcrPmiApproverRows = ref([
        {
            preparedBy: '',
            checkedBy: '',
            approvedBy: '',
        },
    ]);
    const frmEcrPmiExternalApproverRows = ref([
        {
            preparedBy: '',
            checkedBy: '',
            approvedBy: '',
        },
    ]);
    const tblEcrDetails = ref(null);

    //Obj Params
    const descriptionOfChangeParams ={
        tblReference : 'ecr_doc',
        globalVar: ecrVar.optDescriptionOfChange,
        formModel: toRef(frmEcrReasonRows.value[0],'descriptionOfChange'), // Good Practice create a reactive reference to a property inside an object
        selectedVal: '',
    };
    const reasonOfChangeParams = {
        tblReference : 'ecr_roc',
        globalVar: ecrVar.optReasonOfChange,
        formModel: toRef(frmEcrReasonRows.value[0],'reasonOfChange'),
        selectedVal: '',
    };
    const typeOfPartParams = {
        tblReference : 'type_of_part',
        globalVar: ecrVar.optTypeOfPart,
        formModel: toRef(frmEcrDetails.typeOfPart,'reasonOfChange'),
        selectedVal: '',
    }
    //Functions
    const addEcrReasonRows = async () => {
        frmEcrReasonRows.value.push({
            descriptionOfChange: '',
            reasonOfChange: '',
        });
    }
    const removeEcrReasonRows = async (index) => {
        frmEcrReasonRows.value.splice(index,1);
    }
    const getDropdownMasterByOpt = async (params) => {
        let apiParams = {
            tblReference : params.tblReference
        }
        await axiosFetchData(apiParams, `api/get_dropdown_master_by_opt`, (response) => { //url
            let data = response.data;
            let dropdownMasterByOpt = data.dropdownMasterByOpt;
             /*
                Multiple option element base on globalVar
                This only reassigns the local globalVar.
                It does NOT modify the original ecrVar.optDropdownMasterDetails, because in Vue's reactive, reassigning won't trigger reactivity.
                You must mutate the array (not replace it) so Vue detects and updates it reactively.
                Use .splice() to update its contents.
            */
            params.globalVar.splice(0, params.globalVar.length,
                { value: '', label: '-Select an option-', disabled:true }, // Push "" option at the start
                // { value: 0, label: 'N/A' }, // Push "N/A" option at the start
                    ...dropdownMasterByOpt.map((value) => {
                    return {
                        value: value.id,
                        label: value.dropdown_masters_details
                    }
                }),
            );

            params.formModel.value = params.selectedVal; //Make sure the data type is correct | String or Array
        });
    }
    const getRapidxUserByIdOpt = async (params) => {
        let apiParams = {}
        //Multiselect, needs to pass reactive state of ARRAY, import vueselect with default css, check the data to the component by using console.log
        await axiosFetchData(apiParams, `api/get_rapidx_user_by_id_opt`, (response) => { //url
            let data = response.data;

            let rapidxUserById = data.rapidxUserById;
            params.globalVar.splice(0, params.globalVar.length,
                { value: '', label: '-Select an option-', disabled:true }, // Push "" option at the start
                { value: 0, label: 'N/A' }, // Push "N/A" option at the start
                // { value: null, label: 'N/A' }, // Push "N/A" option at the start
                    ...rapidxUserById.map((value) => {
                    return {
                        value: value.id,
                        label: value.name
                    }
                }),
            );
            params.formModel.value = params.selectedVal; //Make sure the data type is correct | String or Array
        });
    }
    const getEcrById = async (ecrId) => {
        let params = {
            ecr_id : ecrId
        }
        axiosFetchData(params,'api/get_ecr_by_id',function(response){
            let data = response.data;
            let ecr = data.ecr;
            if(ecr.status === "QA"){
                modalEcr.EcrRequirements.show();
            }
            frmEcr.value.ecrsId = ecr.id;
            frmEcr.value.ecrNo = ecr.ecr_no;;
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
            frmEcr.value.approvalStatus = ecr.approval_status;
            //Multiselect
            frmEcrReasonRows.value = [];
            frmEcrQadRows.value = [];
            frmEcrPmiApproverRows.value = [];
            frmEcrOtherDispoRows.value = [];
            frmEcrPmiExternalApproverRows.value = [];
            let ecrApprovalCollection = data.ecrApprovalCollection;
            let pmiApprovalCollection = data.pmiApprovalCollection;
            let pmiExternalApprovalCollection = data.pmiExternalApprovalCollection;
            let ecrDetails = ecr.ecr_details;
            setTimeout(() => {  //Cannot display data immediately, need to wait for the DOM to be updated
                //Reasons
                if (ecrDetails.length != 0){
                    ecrDetails.forEach((ecrDetailsEl,index) =>{
                        frmEcrReasonRows.value.push({
                            descriptionOfChange : ecrDetailsEl.description_of_change,
                            reasonOfChange : ecrDetailsEl.reason_of_change
                        });
                    })
                }
            }, 100);
            setTimeout(() => { //Cannot display data immediately, need to wait for the DOM to be updated
                //ECR Approval
                if (ecrApprovalCollection.length != 0){
                    let requestedBy = ecrApprovalCollection.OTRB;
                    let technicalEvaluation = ecrApprovalCollection.OTTE;
                    let reviewedBy = ecrApprovalCollection.OTRVB;
                    let qaCheckedBy = ecrApprovalCollection.QACB;
                    let qaInternal = ecrApprovalCollection.QAIN;

                    // Find the key with the longest array, Loops through all keys using Object.keys(),Compares array lengths using .reduce(),Returns the key and array with the highest length
                    // Exclude 'QA' from keys
                    const ecrApprovalCollectionFiltered = Object.keys(ecrApprovalCollection).filter(key => key !== 'QA');
                    const maxKey = ecrApprovalCollectionFiltered.reduce((a, b) =>
                        ecrApprovalCollection[a].length > ecrApprovalCollection[b].length ? a : b
                    );
                    ecrApprovalCollection[maxKey].forEach((ecrApprovalsEl,index) => {
                        console.log('requestedBy',requestedBy[index]);

                        frmEcrOtherDispoRows.value.push({
                            requestedBy: requestedBy[index].rapidx_user_id ?? 0,
                            reviewedBy: technicalEvaluation[index].rapidx_user_id ?? 0,
                            technicalEvaluation:reviewedBy[index].rapidx_user_id ?? 0,
                        });
                    });
                    //QA Approval
                    if (qaCheckedBy.length != 0){
                        frmEcrQadRows.value.qadCheckedBy =  qaCheckedBy[0].rapidx_user_id ?? 0;
                        frmEcrQadRows.value.qadApprovedByInternal = qaInternal[0].rapidx_user_id ?? 0;
                    }

                }
                //PMI Approval
                if (pmiApprovalCollection.length != 0){
                    let preparedBy = pmiApprovalCollection.PB;
                    let checkedBy = pmiApprovalCollection.CB;
                    let approvedBy = pmiApprovalCollection.AB;

                    // Find the key with the longest array, Loops through all keys using Object.keys(),Compares array lengths using .reduce(),Returns the key and array with the highest length
                    const maxKey = Object.keys(pmiApprovalCollection).reduce((a, b) =>
                        pmiApprovalCollection[a].length > pmiApprovalCollection[b].length ? a : b
                    );

                    pmiApprovalCollection[maxKey].forEach((ecrApprovalsEl,index) => {
                        frmEcrPmiApproverRows.value.push({
                            preparedBy: preparedBy[index].rapidx_user_id ?? 0,
                            checkedBy: checkedBy[index].rapidx_user_id ?? 0,
                            approvedBy:approvedBy[index].rapidx_user_id ?? 0,
                        });
                        if(ecr.internal_external === "External"){
                            let externalPreparedBy = pmiApprovalCollection.EXQC;
                            let externalPheckedBy = pmiApprovalCollection.EXOH;
                            let externalPpprovedBy = pmiApprovalCollection.EXQA;
                            frmEcrPmiExternalApproverRows.value.push({
                                preparedBy: externalPreparedBy[index].rapidx_user_id ?? 0,
                                checkedBy: externalPheckedBy[index].rapidx_user_id ?? 0,
                                approvedBy:externalPpprovedBy[index].rapidx_user_id ?? 0,
                            });
                        }
                    });
                }

            }, 1000);
            modalEcr.SaveEcr.show();
        });
    }
    const resetArrEcrRows = async () => {
        frmEcrQadRows.value.qadCheckedBy =  '';
        frmEcrQadRows.value.qadApprovedByInternal = '';
        frmEcrPmiApproverRows.value = [];
        frmEcrPmiApproverRows.value.push({
            preparedBy: '' ,
            checkedBy: '' ,
            approvedBy: '' ,
        });
    }
    const getEcrDetailsId = async (ecrDetailsId) =>
    {
        let params = {
            ecrDetailsId : ecrDetailsId
        }
        axiosFetchData(params,'api/get_ecr_details_id',function(response){
            let ecrDetails = response.data.ecrDetail;
            frmEcrDetails.value.ecrDetailsId = ecrDetailsId;
            frmEcrDetails.value.changeImpDate =ecrDetails.change_imp_date
            frmEcrDetails.value.docSubDate =ecrDetails.doc_sub_date
            frmEcrDetails.value.docToBeSub =ecrDetails.doc_to_be_sub
            frmEcrDetails.value.customerApproval = ecrDetails.customer_approval
            frmEcrDetails.value.remarks =ecrDetails.remarks
            frmEcrDetails.value.typeOfPart = ecrDetails.dropdown_master_detail_type_of_part  === null ? 0: ecrDetails.dropdown_master_detail_type_of_part.id;
            frmEcrReasonRows.value[0].descriptionOfChange = ecrDetails.dropdown_master_detail_description_of_change.id;
            frmEcrReasonRows.value[0].reasonOfChange = ecrDetails.dropdown_master_detail_reason_of_change.id;
        });
    }
    const saveEcrDetails = async () => {
        let formData = new FormData();
        //Append form data
        [
            ["ecr_details_id", frmEcrDetails.value.ecrDetailsId],
            ["change_imp_date", frmEcrDetails.value.changeImpDate],
            ["type_of_part", frmEcrDetails.value.typeOfPart],
            ["doc_sub_date", frmEcrDetails.value.docSubDate],
            ["doc_to_be_sub", frmEcrDetails.value.docToBeSub],
            ["customer_approval", frmEcrDetails.value.customerApproval],
            ["remarks", frmEcrDetails.value.remarks],
        ].forEach(([key, value]) =>
            formData.append(key, value)
        );
        axiosSaveData(formData,'api/save_ecr_details', (response) =>{
            tblEcrDetails.value.dt.draw();
            modalEcr.SaveEcrDetail.hide();
        });
    }
    return {
        modalEcr,
        ecrVar,
        frmEcr,
        frmEcrDetails,
        frmEcrReasonRows,
        frmEcrQadRows,
        frmEcrOtherDispoRows,
        frmEcrPmiApproverRows,
        frmEcrPmiExternalApproverRows,
        descriptionOfChangeParams,
        reasonOfChangeParams,
        typeOfPartParams,
        tblEcrDetails,
        resetArrEcrRows,
        getDropdownMasterByOpt,
        getRapidxUserByIdOpt,
        axiosFetchData,
        getEcrById,
        addEcrReasonRows,
        removeEcrReasonRows,
        getEcrDetailsId,
        saveEcrDetails,
    };
}
