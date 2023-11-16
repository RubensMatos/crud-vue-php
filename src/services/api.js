import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8000/api',
});

export default {
  login(credentials) {
    return api.post('/auth', credentials);
  },
  addProductType(formData) {
    return api.post('/producttype', formData);
  },
  updateProductType(formDataEdit) {
    console.log(formDataEdit);
    return api.put('/producttype', formDataEdit);
  },
  listProductType() {
    return api.get('/producttype');
  },
  deleteProductType(id) {
    return api.delete('/producttype', {data: id});
  },
  addProduct(formData) {
    return api.post('/product', formData);
  },
  updateProduct(formDataEdit) {
    console.log(formDataEdit);
    return api.put('/product', formDataEdit);
  },
  listProduct() {
    return api.get('/product');
  },
  deleteProduct(id) {
    return api.delete('/product', {data: id});
  },
  addOrder(formData) {
    return api.post('/order.php', formData);
  },
  listOrder() {
    return api.get('/order.php');
  },
  deleteOrder(id) {
    return api.delete('/order.php', {data: id});
  },
  listComboProductType() {
    return api.get('/combo.php?productType=1');
  },
  listComboProduct() {
    return api.get('/combo.php?product=1');
  },
};