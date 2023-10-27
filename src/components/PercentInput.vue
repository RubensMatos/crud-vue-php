<template>
    <input :value="formattedValue" @input="formatInput" class="form-control w-100" />
  </template>
  
  <script>
  export default {
    props: {
      modelValue: Number,
    },
    data() {
      return {
        formattedValue: this.formatDecimalValue(this.modelValue || 0),
      };
    },
    methods: {
      formatInput(event) {
        let numericValue = event.target.value.replace(/[^0-9]/g, "");
  
        if (this.formattedValue == "0.00" && numericValue == "0000") {
          return;
        }
  
        if (numericValue.length > 2) {
          const integerPart = numericValue.slice(0, -2);
          const decimalPart = numericValue.slice(-2);

            console.log(numericValue);
            
          numericValue = integerPart + "." + decimalPart;
        } else if (numericValue.length === 1) {
          numericValue = "0.0" + numericValue;
        } else if (numericValue.length === 2) {
          numericValue = "0." + numericValue;
        }
  
        this.formattedValue = this.formatDecimalValue(numericValue);
      },
      formatDecimalValue(value) {
        return parseFloat(value).toFixed(2);
      },
    },
    watch: {
      formattedValue(newValue) {
        this.$emit("update:modelValue", parseFloat(newValue));
      },
    },
  };
  </script>  