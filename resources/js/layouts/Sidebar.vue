<script setup>
import { useAuthStore } from '../stores';
const useAuth = useAuthStore();
</script>
<template>
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <router-link class="nav-link" :to="{ name: 'dashboard' }">
                    <font-awesome-icon class="nav-icon" icon="gauge-high" />&nbsp;Dashboard
                    <!-- <i class="fas fa-tachometer-alt" icon="fa-tachometer-alt"></i>&nbsp;Dashboard get_bulletin_pages_by_id-->
                </router-link>
                <div class="sb-sidenav-menu-heading">ECR</div>
                <router-link class="sb-nav-link-icon nav-link" :to="{ name: 'Ecr' }">
                    <font-awesome-icon class="nav-icon" icon="ticket-alt" />&nbsp; Ecr
                </router-link>
                <router-link class="sb-nav-link-icon nav-link" :to="{ name: 'Man' }">
                    <font-awesome-icon class="nav-icon" icon="ticket-alt" />&nbsp; Man
                </router-link>
                <router-link class="sb-nav-link-icon nav-link" :to="{ name: 'Material' }">
                    <font-awesome-icon class="nav-icon" icon="ticket-alt" />&nbsp; Material
                </router-link>
                <router-link class="sb-nav-link-icon nav-link" :to="{ name: 'Machine' }">
                    <font-awesome-icon class="nav-icon" icon="ticket-alt" />&nbsp; Machine
                </router-link>
                <router-link class="sb-nav-link-icon nav-link" :to="{ name: 'Method' }">
                    <font-awesome-icon class="nav-icon" icon="ticket-alt" />&nbsp; Method
                </router-link>
                <router-link class="sb-nav-link-icon nav-link" :to="{ name: 'Environment' }">
                    <font-awesome-icon class="nav-icon" icon="ticket-alt" />&nbsp; Environment
                </router-link>
                <div class="sb-sidenav-menu-heading">Admin</div>
                <router-link class="sb-nav-link-icon nav-link" :to="{ name: 'UserMaster' }">
                    <i class="fas fa-user"></i>&nbsp; User Master
                </router-link>
                <router-link class="sb-nav-link-icon nav-link" :to="{ name: 'DropdownMaster' }">
                    <font-awesome-icon class="nav-icon" icon="ticket-alt" />&nbsp; Dropdown Master
                </router-link>
                <!--  <router-link class="sb-nav-link-icon nav-link" :to="{ name: 'Settings' }">
                    <i class="fas fa-tasks"></i>&nbsp; Settings
                </router-link> -->
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as: </div>
        </div>
    </nav>
</template>
