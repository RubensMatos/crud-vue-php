<template>
  <HeaderComponent></HeaderComponent>
  <div class="card  mt-3">
    <div class="card-body">
      <h5 class="card-title my-3">Vendas</h5>
      <div class="row gy-2 align-items-center mt-4">
        <div class="col-sm-4 text-start">
          <label for="productName">Nome do Produto:</label>
          <select id="productName" v-model="newProduct.name" class="form-select" @change="updateValue">
            <option value="">Selecione um Produto</option>
            <option v-for="product in productOptions" :key="product.id" :value="product.name">
              {{ product.name }}
            </option>
          </select> 
        </div>
        <div class="col-sm-2 text-start">
          <label for="quantity">Quantidade:</label>
          <input type="number" v-model="newProduct.quantity" class="form-control" id="quantity" aria-describedby="Quantidade" placeholder="Quantidade" autocomplete="name" @keyup="updateValue">
        </div>
        <div class="col-sm-2 text-start">
          <label for="quantity">Valor unitário:</label>
          <input type="text" v-model="newProduct.valueUnit" class="form-control" id="valueUnit" aria-describedby="Valor" placeholder="Valor Unitário" autocomplete="name" @keyup="updateValue" readonly>
        </div>
        <div class="col-sm-2 text-start">
          <label for="quantity">Valor total:</label>
          <input type="text" v-model="newProduct.value" class="form-control" id="value" aria-describedby="Valor" placeholder="Valor" autocomplete="name" readonly>
          <input type="hidden" v-model="newProduct.tax" id="tax">
        </div>
        <div class="col-sm-2 d-grid gap-1">
          <button @click="addProduct" class="btn btn-primary mt-4">Adicionar</button>
        </div>
      </div>
      <h2 class="mt-3">Itens do Pedido</h2>
      <table class="table table-striped">
        <thead>
          <tr>
            <td>#</td>
            <td>Nome do produto</td>
            <td>Quantidade</td>
            <td>Valor unitário R$</td>
            <td>Valor total R$</td>
            <td>Imposto R$ </td>
            <td></td>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(product, index) in products" :key="index">
            <td>{{ product.id }}</td>
            <td>{{ product.name }}</td>
            <td>{{ product.quantity }}</td>
            <td>{{ formatTotal(product.valueUnit) }}</td>
            <td>{{ formatTotal(product.value) }}</td>
            <td>{{ formatTotal(product.tax) }}</td>
            <td>
              <button @click="removeProduct(index)" class="btn btn-danger btn-sm"> Excluir</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <div>
    <div class="row mt-3 mb-3">
      <div class="col-sm-3 text-start">
        <p>Total de produtos: {{ totalQuantity }}</p>
      </div>
      <div class="col-sm-3 text-start">
        <p>Valor total dos produtos: {{ formatTotal(totalProductValue) }}</p>
      </div>
      <div class="col-sm-3 text-start">
        <p>Valor total dos impostos: {{ formatTotal(totalTaxValue) }}</p>
      </div>
      <div class="col-sm-3 text-start d-grid gap-1">
        <button @click="sendOrder" class="btn btn-success">Gerar Pedido</button>  
      </div>
    </div>
    <table class="table mt-5">
      <thead>
        <tr>
          <td colspan="4">HISTÓRICO DE PEDIDOS</td>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(order, index) in orders" :key="index">
          <td>{{ order.id }}</td>
          <td>{{ order.customer }}</td>
          <td v-html="formatProductData(order.product_data)"></td>
          <td>
            <button @click="removeOrder(order.id)" class="btn btn-danger btn-sm"> Excluir</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>

import { mapState, mapActions } from 'vuex';
import { useRouter } from 'vue-router';
import HeaderComponent from '../components/HeaderComponent.vue'
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import { formatCurrency } from '@/plugins/helpers';
import api from '@/services/api';

export default {
  data() {
    return {
      newProduct: {
        name: "",
        quantity: 1,
        value: 0,
        tax: 0,
      },
      products: [],
      orders: [],
      productOptions: [],
    };
  },
  mounted() {
    this.loadProductOptions();
    this.listOrders();
  },
  components: {
    HeaderComponent
  },
  computed: {
    totalValue() {
      return this.products.reduce((total, product) => total + product.quantity * product.value, 0);
    },
    totalQuantity() {
      return this.products.reduce((total, product) => total + product.quantity, 0);
    },
    totalProductValue() {
      return this.products.reduce((total, product) => total + product.value, 0);
    },
    totalTaxValue() {
      return this.products.reduce((total, product) => total + product.tax, 0);
    },
    ...mapState(['isAuthenticated']),
  },
  methods: {
    formatProductData(productData) {
      const items = JSON.parse(productData);
      let formattedData = "";
      
      items.forEach((item, index) => {
        formattedData += `Nome: ${item.name}, Quantidade: ${item.quantity}, Imposto: ${this.formatTotal(item.tax)}, Valor Unitário: ${this.formatTotal(item.valueUnit)}, Valor Total: ${this.formatTotal(item.value)}`;
        
        if (index < items.length - 1) {
          formattedData += "<br>";
        }
      });
      
      return formattedData;
    },  
    removeOrder(id) {
      const formDataDel = {
        id: id
      };
      api.deleteOrder(formDataDel)
      .then(response => {
        toast.info('Registro excluído com sucesso!');
        this.listOrders();
        console.log('Registro excluído com sucesso:', response.data);
      })
      .catch(error => {
        console.error('Erro ao atualizar registro: ' + error);
      });
    },  
    async listOrders() {
      try {
        const response = await api.listOrder();
        if (response.data === false) {
          return;
        }else{    
          this.orders = response.data;
        }
      } catch (error) {
        console.error('Erro ao buscar dados da API:', error);
      }
    },
    sendOrder() {
        if(this.products.length > 0){        
        api.addOrder(this.products)
        .then(response => {
          console.log(response);
          this.listOrders();
          toast.info('Pedido gerado com sucesso!');
          this.products = [];
        })
        .catch(error => {
          console.error('Falha ao inserir dados na API: ' + error);
        });
      }else{

        toast.info('Adicione pelo menos 1 item no pedido!');
      }
    },
    removeProduct(index) {
      this.products.splice(index, 1);
    },
    loadProductOptions() {
      
      api.listComboProduct().then(response => {
        
        this.productOptions = response.data;
      });
    },
    formatTotal(value) {
      return formatCurrency(value);
    },
    ...mapActions(['performLogout']),
    redirectToLogin() {
      const router = useRouter();
      if (!this.isAuthenticated) {
        router.push('/login');
      }
    },
    addProduct() {
      if (this.newProduct.name && this.newProduct.quantity > 0 && this.newProduct.value > 0) {
        this.products.push({ ...this.newProduct });
        this.newProduct.id = "";
        this.newProduct.name = "";
        this.newProduct.quantity = 0;
        this.newProduct.value = 0;
        this.newProduct.tax = 0;
      }else{
        
        toast.info('Todos os campos são obrigatórios');
      }
    },
    updateValue() {
      const selectedProduct = this.productOptions.find(
      (product) => product.name === this.newProduct.name
      );
      if (selectedProduct) {
        
        if(this.newProduct.quantity == ''){
          
          this.newProduct.quantity = 1;
        }
        
        this.newProduct.valueUnit = selectedProduct.value;
        this.newProduct.value = (this.newProduct.quantity * selectedProduct.value);
        this.newProduct.tax = (((this.newProduct.quantity * selectedProduct.value) * selectedProduct.tax)/100);
      }
    },
  },
  created() {
    this.redirectToLogin();
  },
  name: 'DashboadDefault',
}

</script>

<style scoped>
h1 {
  color: #333;
}
p {
  color: #666;
}
</style>