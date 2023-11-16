<template>
    <HeaderComponent></HeaderComponent>
    <div class="card  mt-3">
        <div class="card-body">
            <h5 class="card-title my-3">Produtos</h5>
            <div class="row gy-2 align-items-center mt-4">
                <div class="col-sm-4">
                    <input type="text" v-model="name" class="form-control" id="exampleInputEmail1" aria-describedby="Nome" placeholder="Nome" autocomplete="name">
                </div>
                <div class="col-sm-2">
                    <PercentInput v-model="amount" placeholder="Valor R$" />
                </div>
                <div class="col-sm-2">
                    <ProducttypeComponent :comboOptions="comboOptions" @selected="updateProductType" />
                </div>
                <div class="col-sm-2 d-grid gap-1">
                    <button @click="newProductAction" class="btn btn-primary">Cadastrar</button>
                </div>
                <div class="col-sm-1 d-grid gap-1">
                    <button @click="list" class="btn btn-secondary"> &#128472; </button>
                </div>
                <div class="col-sm-12 mt-3">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col" class="text-start">Nome</th>
                                <th scope="col" class="text-start">Valor R$</th>
                                <th scope="col" class="text-start">Tipo</th>
                                <th scope="col">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in listItens" :key="index">
                                <th scope="row">{{ item.id }}</th>
                                <td class="text-start">
                                    <input v-model="item.name" :disabled="!item.editing" class="w-100" />
                                </td>
                                <td class="text-start">
                                    <PercentInput v-model="item.amount" :disabled="!item.editing" class="w-100" />
                                </td>
                                <td> <ProducttypeComponent :comboOptions="comboOptions" :selectedOption="item.producttype_id" @selected="updateProductType" /></td>
                                <td>
                                    <button v-if="!item.editing" @click="editItem(index)" class="btn btn-secondary">Editar</button>
                                    <button v-if="item.editing" @click="saveEditItem(index)" class="btn btn-primary">Salvar</button>
                                    <button @click="deleteItem(index)" class="btn btn-danger ms-2">Excluir</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="alert alert-danger p-1 mt-4" role="alert" v-if="alertaForm">{{ mensagemAlerta }}</div>
        </div>
    </div>
</template>

<script>

import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import PercentInput from "@/components/PercentInput.vue";
import ProducttypeComponent from "@/components/ProducttypeComponent.vue";
import HeaderComponent from '../components/HeaderComponent.vue'
import api from '@/services/api';

export default {
    components: {
        HeaderComponent: HeaderComponent,
        PercentInput: PercentInput,
        ProducttypeComponent: ProducttypeComponent
    },
    data() {
        return {
            comboOptions: [],
            listItens: [],
            name: "",
            amount: "",
            producttype_id: "",
            alertaForm: false,
            mensagemAlerta: "",
            formattedValue: this.value
        };
    },
    created() {
        this.loadComboOptions();
    },
    mounted() {
        const authToken = localStorage.getItem('authToken');
        this.list();
        if (authToken != 1) {
            this.$router.push('/login');
        }
    },
    computed: {
        isAuthenticated() {
            return this.$store.state.isAuthenticated;
        },
    },
    methods: {
        loadComboOptions() {
            this.listComboProductType();
        },
        async listComboProductType() {
            try {
                const response = await api.listComboProductType();
                if (response.data === false) {
                    return;
                }
                this.comboOptions = response.data;
            } catch (error) {
                console.error('Erro ao buscar dados da API:', error);
            }
        },
        updateProductType(optionId) {
            this.producttype_id = optionId;
        },
        updateValue() {
            this.formattedValue = "0.00"; 
            this.$emit("input", this.formattedValue);
        },
        editItem(index) {
            this.listItens[index].editing = true;
        },
        deleteItem(index) {
            const deletedItem = this.listItens[index];
            const formDataDel = {
                id: deletedItem.id
            };
            api.deleteProduct(formDataDel)
            .then(response => {
                this.list();
                
                if (response.data.length === 0) {
                    
                    toast.info('Registro removido com sucesso!');
                }else{
                    
                    toast.error('Houve uma falha, tente novamente!');
                }
                
            })
            .catch(error => {
                console.error('Erro ao atualizar registro: ' + error);
            });
        },
        saveEditItem(index) {
            this.listItens[index].editing = false;
            const editedItem = this.listItens[index];
            api.updateProduct(editedItem)
            .then(response => {
                this.list();
                
                if (response.data.length === 0) {
                    
                    toast.info('Registro atualizado com sucesso!');
                }else{
                    
                    toast.error('Houve uma falha, tente novamente!');
                }
                
            })
            .catch(error => {
                console.error('Erro ao atualizar registro: ' + error);
            });
        },
        async list() {
            try {
                const response = await api.listProduct();
                if (response.data === false) {
                    return;
                }            
                this.listItens = response.data.map(item => {
                    item.editing = false;
                    item.percentvalue = parseFloat(item.percentvalue);
                    return item;
                });
            } catch (error) {
                console.error('Erro ao buscar dados da API:', error);
            }
        },
        async newProductAction() {
            this.alertaForm = false;
            console.log(this.producttype_id);
            const formData = {
                name: this.name,
                amount: this.amount,
                producttype_id: this.producttype_id
            };
            if (!formData.name || formData.name.length < 3) {
                this.alertaForm = true;
                this.mensagemAlerta = "O Nome deve conter no mínimo 3 dígitos.";
            } else {
                await api.addProduct(formData)
                .then(response => {
                    console.log(response)
                    this.list();
                    
                    if (response.data.length === 0) {
                        
                        toast.info('Registro inserido com sucesso!');
                    }else{
                        
                        toast.error('Houve uma falha, tente novamente!');
                    }
                    
                    this.name   = "";
                    this.amount = "0.00";
                })
                .catch(error => {
                    console.error('Falha ao inserir dados na API: ' + error);
                });
            }
        }
    }
};
</script>