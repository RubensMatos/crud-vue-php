import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8000/api',
});

export default {
  login(credentials) {
    return api.post('/auth.php', credentials);
  },
  addProductType(formData) {
    return api.post('/producttype.php', formData);
  },
  updateProductType(formDataEdit) {
    console.log(formDataEdit);
    return api.put('/producttype.php', formDataEdit);
  },
  listProductType() {
    return api.get('/producttype.php');
  },
  deleteProductType(id) {
    return api.delete('/producttype.php', {data: id});
  },
  addProduct(formData) {
    return api.post('/product.php', formData);
  },
  updateProduct(formDataEdit) {
    console.log(formDataEdit);
    return api.put('/product.php', formDataEdit);
  },
  listProduct() {
    return api.get('/product.php');
  },
  deleteProduct(id) {
    return api.delete('/product.php', {data: id});
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