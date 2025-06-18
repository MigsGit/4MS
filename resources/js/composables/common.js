import { ref, inject,reactive,nextTick,toRef } from 'vue'
import useFetch from './utils/useFetch';
import useForm from "./utils/useForm";
export default function useCommon(){
    const { axiosFetchData } = useFetch(); // Call  the useFetch function
    const  { axiosSaveData } = useForm();
    const modal ={}
    const tblSpecialInspection = ref(null);
    const modalSaveSpecialInspection = ref(null);
    const commonVar = reactive({
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
    //Params
    const specialInsQcInspectorParams = {
        globalVar: commonVar.optUserMaster,
        formModel: toRef(frmSpecialInspection.value,'inspector'),
        selectedVal: '',
    };
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

    const saveSpecialInspection = async () => {
        let formData = new FormData();
         //Append form data
         [
            ["ecrs_id" , frmSpecialInspection.value.ecrsId],
            ["product_detail" , frmSpecialInspection.value.productDetail],
            ["lot_qty" , frmSpecialInspection.value.lotQty],
            ["samples" , frmSpecialInspection.value.samples],
            ["mod" , frmSpecialInspection.value.mod],
            ["mod_qty" , frmSpecialInspection.value.modQty],
            ["judgement" , frmSpecialInspection.value.judgement],
            ["inspection_date" , frmSpecialInspection.value.inspectionDate],
            ["inspector" , frmSpecialInspection.value.inspector],
            ["remarks" , frmSpecialInspection.value.remarks],
        ].forEach(([key, value]) =>
            formData.append(key, value)
        );
        axiosSaveData(formData,'api/save_special_inspection', (response) =>{
            console.log(response);
            tblSpecialInspection.value.dt.ajax.url("api/load_special_inspection_by_ecr_id?ecrsId="+frmSpecialInspection.value.ecrsId).draw()
            modal.modalSaveSpecialInspection.hide();
        });
    }

    return {
        modal,
        commonVar,
        tblSpecialInspection,
        modalSaveSpecialInspection,
        specialInsQcInspectorParams,
        saveSpecialInspection,
        getCurrentApprover,
        getCurrentPmiInternalApprover,
        frmSpecialInspection,
    }

}
