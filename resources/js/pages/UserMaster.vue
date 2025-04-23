<template>
    <div class="container-fluid px-4">
        <h4 class="mt-4">User Master</h4>
        <div class="card mt-3"  style="width: 100%;">
            <div class="card-body overflow-auto">
                <div class="table-responsive">
                    <!-- id="dataTable" -->
                    <!-- <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    </table> -->
                    <DataTable

                    >
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Status</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Roles</th>
                            </tr>
                        </thead>
                    </DataTable>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import {onMounted, ref, reactive, inject , watch, nextTick} from 'vue'

    import Swal from 'sweetalert2'

</script>
<style lang="scss" scoped>

</style>

