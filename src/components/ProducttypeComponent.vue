<template>
    <div>
      <select v-model="localSelectedOption" id="combo" class="form-select" @change="handleSelection">
        <option value=""> Tipo</option>
        <option v-for="option in comboOptions" :key="option.id" :value="option.id">
          {{ option.name }}
        </option>
      </select>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      comboOptions: Array, // Receba os dados do combo como prop
      selectedOption: String, // Receba a opção selecionada como prop
    },
    data() {
      return {
        localSelectedOption: this.selectedOption, // Crie uma cópia local
      };
    },
    watch: {
      selectedOption(newVal) {
        // Atualize a cópia local quando a prop mudar
        this.localSelectedOption = newVal;
      },
    },
    methods: {
      handleSelection() {
        // Emita o evento com a cópia local
        this.$emit("selected", this.localSelectedOption);
      },
    },
  };
  </script>  