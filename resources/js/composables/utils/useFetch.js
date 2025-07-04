
import { ref, onMounted } from "vue";
import axios from "axios";
import { useToast } from 'vue-toast-notification';
const toastr = useToast();
// Global Loading State
const isModalLoadingComponent = ref(false);

// toastr.open({
//     message: 'err.response.data.msg',
//     type: 'error',
//     position: 'top-right',
//     duration: 2000,
// }); // * usage of Toastr notification
// console.log(toastr.open)
export default function useFetch ()
{
    const axiosFetchData = async  (params, apiUrl,responseCallback,modalEl=null) => {
        if (!apiUrl) {
            console.error("🚨 Error: API URL is null or undefined!");
            return;
        }
        try {
            const response = await axios.get(apiUrl, {
                params: params,
                transformRequest: [(data, headers) => {
                    headers['Authorization'] = 'Bearer your-token';
                    // console.log('Request config modified before sending:', headers);
                    isModalLoadingComponent.value = true;
                }]

            });
            if (typeof responseCallback === "function") {
                responseCallback(response);
            }
        } catch (error) {
            let response = error.response;
            console.log(response);
            return;
            let errorMsg = response.data.msg ?? '';
            if( response.status === 500){
                Swal.fire({
                    title: "System Alert !",
                    text: errorMsg ?? "Please Contact ISS ! ",
                    icon: "error",
                    timer: 3000,
                    showConfirmButton: false
                });
            }
            // throw error; // Ensure errors are propagated
        } finally {
            isModalLoadingComponent.value = false;
        }
    }
    return {
        axiosFetchData,isModalLoadingComponent,toastr
    }
};
    // toastr.open({
    //     message: err.response.data.msg,
    //     type: 'error',
    //     position: 'top-right',
    //     duration: 2000,
    // }); // * usage of Toastr notification
    // toastr.open({
    //     message: res.data.msg,
    //     type: 'success',
    //     position: 'top-right',
    //     duration: 2000,
    // }); // * usage of Toastr notification
