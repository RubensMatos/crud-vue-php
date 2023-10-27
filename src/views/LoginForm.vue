<template>
  <div class="w-25 mx-auto card">
    <div class="card-header">
      <h5 class="p-3">Faça seu login</h5>
    </div>
    <div class="card-body">
      <div class="form-group text-start mt-3">
        <label for="exampleInputEmail1">Username</label>
        <input type="text" v-model="username" class="form-control" id="exampleInputEmail1" aria-describedby="Username" placeholder="Username" autocomplete="username">
      </div>
      <div class="form-group text-start mt-3">
        <label for="exampleInputPassword1">Password</label>
        <input type="password" v-model="password" class="form-control" id="exampleInputPassword1" placeholder="Password" autocomplete="current-password">
      </div>
      <button @click="loginAction" class="btn btn-primary px-5 mt-4">Entrar</button>
      <div class="alert alert-danger p-1 mt-4" role="alert" v-if="alertaForm">{{ mensagemAlerta }}</div>
    </div>
  </div>
</template>

<script>

export default {
  mounted() {

    const authToken = localStorage.getItem('authToken');

    if (authToken == 1) {

      this.$router.push('/');
    }
  },  
  data() {
    return {
      username: "",
      password: "",
      alertaForm: false,
      mensagemAlerta: "",
    };
  },
  computed: {
    isAuthenticated() {
      return this.$store.state.isAuthenticated;
    },
  },
  methods: {
    async loginAction() {

      this.alertaForm = false;

      const credentials = {
        username: this.username,
        password: this.password,
      };

      if (!credentials.username || !credentials.password) {
        this.alertaForm = true;
        this.mensagemAlerta = "Preencha todos os campos.";
        //return;
      }else{

        try {
          const response = await this.$store.dispatch('performLogin', credentials);

          if (response.data[0] === 0) {
            
            this.alertaForm = true;
            this.mensagemAlerta = response.data[1];
          } else {
          
            // Redirecione o usuário para a página de destino após o login
            this.$router.push('/');
          }
        } catch (error) {
          console.error('Erro de login:', error);
          this.alertaForm = true;
          this.mensagemAlerta = "Erro ao fazer login. Tente novamente mais tarde.";
        }
      }
    },
  },
};
</script>