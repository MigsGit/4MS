import { ref, inject,reactive,nextTick,toRef } from 'vue'
import useFetch from './utils/useFetch';
import useForm from "./utils/useForm";
export default function useCommon(){
    const { axiosFetchData } = useFetch(); // Call  the useFetch function
    const commonVar = ref({
        isSessionApprover : false,
        isSessionPmiInternalApprover : false,
        optUserMaster:[],
        optYesNo : [
            {"value":"","label":"-Select an option-"},
            {"value":"N/A","label":"N/A"},
            {"value":"YES","label":"YES"},
            {"value":"NO","label":"NO"},
        ],
        optResult : [
            {"value":"","label":"-Select an option-"},
            {"value":"N/A","label":"N/A"},
            {"value":"OK","label":"OK"},
            {"value":"NG","label":"NG"},
        ],
        optJudgment : [
            {"value":"","label":"-Select an option-"},
            {"value":"N/A","label":"N/A"},
            {"value":"PASSED","label":"PASSED"},
            {"value":"FAILED","label":"FAILED"},
        ],
        optCheck : [
            {"value":"","label":"-Select an option-"},
            {"value":"N/A","label":"N/A"},
            {"value":"C","label":"√"},
            {"value":"X","label":"X"},
        ],
        optConditions : [
            {"value":"","label":"-Select an option-"},
            {"value":"N/A","label":"N/A"},
            {"value":"R","label":"REQUIRED"},
            {"value":"NR","label":"NOT REQUIRED"},
        ]
    });
    const frmSpecialInspection = ref({
        ecrsId : "",
        productDetail : "N/A",
        lotQty : "10",
        samples : "10",
        mod : "N/A",
        modQty : "10",
        judgement : "PASSED",
        inspectionDate : "2025-05-01",
        inspector : 530,
        remarks : "TEST",
    });
    const getCurrentApprover = async (params) => {
        let apiParams = {
            ecrsId : params.ecrsId
        }
        axiosFetchData(apiParams,'api/get_current_approver_session',function(response){
            let data = response.data;
            commonVar.value.isSessionApprover = data.isSessionApprover;
        });
    }
    const getCurrentPmiInternalApprover = async (params) => {
        let apiParams = {
            ecrsId : params.ecrsId
        }
        axiosFetchData(apiParams,'api/get_current_pmi_internal_approver',function(response){
            let data = response.data;
            commonVar.value.isSessionPmiInternalApprover = data.isSessionPmiInternalApprover;
        });
    }

    return {
        commonVar,
        getCurrentApprover,
        getCurrentPmiInternalApprover,
        frmSpecialInspection,
    }

}
