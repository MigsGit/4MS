<template>
    <div class="container-fluid px-4">
        <h4 class="mt-4">4M Change Control Management System</h4>
        <div class="card mt-5"  style="width: 100%;">
            <div class="card-body overflow-auto">
                <div class="container-fluid px-4">
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-dark text-white mb-4">
                                <div class="card-body">
                                    <h4><font-awesome-icon class="nav-icon" icon="fa-user" />&nbsp;User Master</h4>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-end">
                                    <!-- <router-link class="small text-white stretched-link" :to="{ name: 'user_master' }">
                                        View Details
                                    </router-link> -->
                                    <div class="small text-white">more info &nbsp;<i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- <div class="col-xl-3 col-md-6">
                                <div class="card bg-dark text-white mb-4">
                                    <div class="card-body">
                                        <h4><font-awesome-icon class="nav-icon" icon="ticket-alt" />&nbsp; Man</h4>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <router-link class="small text-white stretched-link" :to="{ name: 'Man' }">
                                            View Details
                                        </router-link>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-dark text-white mb-4">
                                    <div class="card-body">
                                        <h4><font-awesome-icon class="nav-icon" icon="fa-cog" />&nbsp;Settings</h4>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <router-link class="small text-white stretched-link" :to="{ name: 'Settings' }">
                                            View Details
                                        </router-link>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    // await axios.get('/check_ses').then((res) => {
    //         // console.log(res);
    //         columns.value = res.data;
    // }).catch((err) => {
    //     // console.log(err);
    // });
</script>

<style lang="scss" scoped>

</style>
