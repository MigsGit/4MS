import { ref, inject,reactive,nextTick,toRef } from 'vue'
import useFetch from './utils/useFetch';

const { axiosFetchData } = useFetch(); // Call  the useFetch function
//Constant Object
const modal = {
    // SaveEcr : null,
};
//Reactive State
const ecrVar = reactive({
    optDescriptionOfChange: [],
    optReasonOfChange: [],

    optQadCheckedBy: [],
    optQadApprovedByInternal: [],
    optQadApprovedByExternal: [],

    requestedBy: [],
    technicalEvaluation: [],
    reviewedBy: [],

    preparedBy: [],
    checkedBy: [],
    approvedBy: [],
});
//Ref State
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

const frmEcrDetails = ref({
    typeOfPart: '',
    changeImpDate: '',
    docSubDate: '',
    docToBeSub: '',
    remarks: '',
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
        qadApprovedByExternal: '',
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
export default function useEcr(){
    const getDropdownMasterByOpt = async (params) => {
        await axiosFetchData(params, `api/get_dropdown_master_by_opt`, (response) => { //url
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
                { value: 0, label: 'N/A' }, // Push "N/A" option at the start
                    ...dropdownMasterByOpt.map((value) => {
                    return {
                        value: value.id,
                        label: value.dropdown_masters_details
                    }
                }),
            );
            console.log('selectedVal',params.selectedVal);

            params.formModel.value = params.selectedVal; //Make sure the data type is correct | String or Array
        });
    }
    const getRapidxUserByIdOpt = async (params) => {
        //Multiselect, needs to pass reactive state of ARRAY, import vueselect with default css, check the data to the component by using console.log
        await axiosFetchData(params, `api/get_rapidx_user_by_id_opt`, (response) => { //url
            let data = response.data;

            let rapidxUserById = data.rapidxUserById;
            params.globalVar.splice(0, params.globalVar.length,
                { value: '', label: '-Select an option-', disabled:true }, // Push "" option at the start
                { value: 0, label: 'N/A' }, // Push "N/A" option at the start
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

            //Multiselect
            frmEcrReasonRows.value = [];
            frmEcrOtherDispoRows.value = [];
            frmEcrQadRows.value = [];
            frmEcrPmiApproverRows.value = [];
            let ecrApprovalCollection = data.ecrApprovalCollection;
            let pmiApprovalCollection = data.pmiApprovalCollection;
            let ecrDetails = ecr.ecr_details;
            //Reasons
            if (ecrDetails.length != 0){
                ecrDetails.forEach((ecrDetailsEl,index) =>{
                    frmEcrReasonRows.value.push({
                        descriptionOfChange : ecrDetailsEl.description_of_change,
                        reasonOfChange : ecrDetailsEl.reason_of_change
                    });
                })
            }
            //ECR Approval
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

    return {
        modal,
        ecrVar,
        frmEcr,
        frmEcrDetails,
        frmEcrReasonRows,
        frmEcrQadRows,
        frmEcrOtherDispoRows,
        frmEcrPmiApproverRows,
        descriptionOfChangeParams,
        reasonOfChangeParams,
        getDropdownMasterByOpt,
        getRapidxUserByIdOpt,
        axiosFetchData,
        getEcrById,
    };
}
