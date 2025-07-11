import { ref, inject,reactive,nextTick,toRef } from 'vue'
import useFetch from './utils/useFetch';
import useForm from "./utils/useForm";
export default function useCommon(){
    const { axiosFetchData } = useFetch(); // Call  the useFetch function
    const  { axiosSaveData } = useForm();
    const modal ={}
    //Reactive State
    const commonVar = reactive({
        isSessionApprover : false,
        isSessionPmiInternalApprover : false,
        optUserMaster:[],
        optAdminAccess : [
            // {"value":"","label":"-Select an Admin-", "disabled":true },
            {"value":"all","label":"Show All"},
        ],
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
    //Ref State
    const tblSpecialInspection = ref(null);
    const modalSaveSpecialInspection = ref(null);
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
        lqcSectionHead : 530,
        remarks : "TEST",
    });
    //Params
    const specialInsQcInspectorParams = {
        globalVar: commonVar.optUserMaster,
        formModel: toRef(frmSpecialInspection.value,'inspector'),
        selectedVal: '',
    };
    //DT Columns
    const tblSpecialInspectionColumns = [
        {   title: '<i class="fa fa-cogs"></i>',
            data: 'get_actions',
            orderable: false,
            searchable: false,
            createdCell(cell){
                let btnGetSpecialInspectionId = cell.querySelector('#btnGetSpecialInspectionId');
                if(btnGetSpecialInspectionId != null){
                    btnGetSpecialInspectionId.addEventListener('click',function(){
                        let specialInspectionsId = this.getAttribute('special-inspections-id');
                        getSpecialInspectionById(specialInspectionsId);
                        modal.modalSaveSpecialInspection.show();
                    });
                }
            }
        } ,
        {  title: "Product Detail" , data: 'product_detail'} ,
        {  title: "Lot Qty" , data: 'lot_qty'} ,
        {  title: "Samples" , data: 'samples' } ,
        {  title: "Mod" , data: 'mod' } ,
        {  title: "Mod Qty" , data: 'mod_qty' } ,
        {  title: "Judgement" , data: 'judgement' } ,
        {  title: "Inspection Date" , data: 'inspection_date' } ,
        {  title: "Inspector" , data: 'get_inspector' } ,
        {  title: "Remarks" , data: 'remarks' } ,
    ];
    //Functions
    const getCurrentApprover = async (params) => {
        let apiParams = {
            selectedId : params.selectedId,
            approvalType : params.approvalType,
        }
        axiosFetchData(apiParams,'api/get_current_approver_session',function(response){
            let data = response.data;
            commonVar.isSessionApprover = data.isSessionApprover;

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
            ["special_inspections_id" , frmSpecialInspection.value.specialInspectionsId ?? ""],
            ["product_detail" , frmSpecialInspection.value.productDetail],
            ["lot_qty" , frmSpecialInspection.value.lotQty],
            ["samples" , frmSpecialInspection.value.samples],
            ["mod" , frmSpecialInspection.value.mod],
            ["mod_qty" , frmSpecialInspection.value.modQty],
            ["judgement" , frmSpecialInspection.value.judgement],
            ["inspection_date" , frmSpecialInspection.value.inspectionDate],
            ["inspector" , frmSpecialInspection.value.inspector],
            ["lqc_section_head" , frmSpecialInspection.value.lqcSectionHead],
            ["remarks" , frmSpecialInspection.value.remarks],
        ].forEach(([key, value]) =>
            formData.append(key, value)
        );
        axiosSaveData(formData,'api/save_special_inspection', (response) =>{
            console.log(response);
            tblSpecialInspection.value.dt.ajax.url("api/load_special_inspection_by_ecr_id?ecrsId="+frmSpecialInspection.value.ecrsId).draw();
            modal.SaveSpecialInspection.hide();
        });
    }
    const getSpecialInspectionById = async (specialInspectionsId) => {
        let apiParams = {
            specialInspectionsId : specialInspectionsId
        }
        axiosFetchData(apiParams,'api/get_special_inspection_by_id',function(response){
            let data = response.data;
            let specialInspection = response.data.specialInspection;
            frmSpecialInspection.value.specialInspectionsId = specialInspection.id;
            frmSpecialInspection.value.ecrsId = specialInspection.ecrs_id;
            frmSpecialInspection.value.productDetail = specialInspection.product_detail;
            frmSpecialInspection.value.lotQty = specialInspection.lot_qty;
            frmSpecialInspection.value.samples = specialInspection.samples;
            frmSpecialInspection.value.mod = specialInspection.mod;
            frmSpecialInspection.value.modQty = specialInspection.mod_qty;
            frmSpecialInspection.value.judgement = specialInspection.judgement;
            frmSpecialInspection.value.inspectionDate = specialInspection.inspection_date;
            frmSpecialInspection.value.inspector = specialInspection.inspector;
            frmSpecialInspection.value.remarks = specialInspection.remarks;
        });
    }

    return {
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
    }

}
