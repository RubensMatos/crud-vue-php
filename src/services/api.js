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
    return api.post('/order', formData);
  },
  listOrder() {
    return api.get('/order');
  },
  deleteOrder(id) {
    return api.delete('/order', {data: id});
  },
  listComboProductType() {
    return api.get('/combo?productType=1');
  },
  listComboProduct() {
    return api.get('/combo?product=1');
  },
};