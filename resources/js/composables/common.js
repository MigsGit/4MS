import { ref, inject,reactive,nextTick,toRef } from 'vue'
import useFetch from './utils/useFetch';
import useForm from "./utils/useForm";
export default function useCommon(){
    const { axiosFetchData } = useFetch(); // Call  the useFetch function
    const commonVar = ref({
        isSessionApprover : false,
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
    const getSession = async () => {
        let apiParams = {}
        axiosFetchData(apiParams,'api/check_user',function(response){
            return response;
            // console.log(response);
        });
    }

    return {
        commonVar,
        getCurrentApprover,
        getSession,
    }

}
