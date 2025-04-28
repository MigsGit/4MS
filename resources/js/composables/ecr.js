import { ref, inject,reactive } from 'vue'
import useFetch from './utils/useFetch';

export default function ecr()
{
    const { axiosFetchData } = useFetch(); // Call the useFetch function
    //Constant Object
    const modal = {
        SaveEcr : null,
    };
    //Reactive State
    const ecrVar = reactive({
        optDescriptionOfChange: [],
        optReasonOfChange: [],
        optQadCheckedBy: [],
        optQadApprovedByInternal: [],
        optQadApprovedByExternal: [],
    });
    //Ref State
    const frmEcrReasonRows = ref([
        {
            descriptionOfChange: [],
            reasonOfChange: [],
        },
    ]);
    //qadApprovedByInternal
    const frmEcrQadRows = ref([
        {
            qadCheckedBy: [],
            qadApprovedByInternal: [],
            qadApprovedByExternal: [],
        },
    ]);

    const getDropdownMasterByOpt = async (params) => {
        //Multiselect, needs to pass reactive state of ARRAY, import vueselect with default css, check the data to the component by using console.log
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
                params.globalVar.splice(0, params.globalVar.length, ...dropdownMasterByOpt.map((value) => {
                return {
                    value: value.id,
                    label: value.dropdown_masters_details
                }
            }));
        });
    }
    const getRapidxUserByIdOpt = async (params) => {
        //Multiselect, needs to pass reactive state of ARRAY, import vueselect with default css, check the data to the component by using console.log
        await axiosFetchData(params, `api/get_rapidx_user_by_id_opt`, (response) => { //url
            let data = response.data;
            let rapidxUserById = data.rapidxUserById;
                params.globalVar.splice(0, params.globalVar.length, ...rapidxUserById.map((value) => {
                return {
                    value: value.id,
                    label: value.name
                }
            }));
        });
    }

    return {
        modal,
        ecrVar,
        frmEcrReasonRows,
        frmEcrQadRows,
        getDropdownMasterByOpt,
        getRapidxUserByIdOpt,
    };
}
