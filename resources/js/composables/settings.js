import { ref, inject,reactive,nextTick,toRef } from 'vue'
import useFetch from './utils/useFetch';
import useForm from "./utils/useForm";



export default function useSettings(){
    const { axiosFetchData } = useFetch(); // Call  the useFetch function
    const frmDropdownMasterDetails = ref({
        dropdownMastersId : '',
        dropdownMasterDetailsId : '',
        dropdownMastersDetails : '',
        remarks : '',
    });
    const modal ={}

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



    return {
        modal,
        frmDropdownMasterDetails,
        axiosFetchData,
        getDropdownMasterByOpt
    }
}