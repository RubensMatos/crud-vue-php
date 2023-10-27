<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-light" v-if="isAuthenticated">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Painel de controle</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <router-link to="/" class="nav-link">Nova Venda</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/product" class="nav-link">Produtos</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/product-type" class="nav-link">Tipos de Produtos</router-link>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" @click="logoutAction">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>

import { mapState } from 'vuex';

export default {
    name: 'HeaderComponent',
    computed: {
        ...mapState(['isAuthenticated']),
    },
    methods:{
        async logoutAction() {
            
            await this.$store.dispatch('performLogout');
            
            this.$router.push('/login');
        },
    }
}
</script>