<!-- assets/components/ProduitVue.vue -->

<template>
    <div>
        <h1>Liste des Produits</h1>
        <ul>
            <li v-for="product in products" :key="product.id">
                {{ product.libeller }} - Prix : {{ product.prix_unitaire }}€
            </li>
        </ul>
    </div>
</template>
<script>
import axios from 'axios';

export default {
  name: 'ProduitVue',
  data() {
    return {
      products: []
    }
  },
  mounted() {
    this.fetchProducts();
  },
  methods: {
    async fetchProducts() {
      try {
        const response = await axios.get('/vue/products');
        this.products = response.data;
      } catch (error) {
        console.error('Erreur lors de la récupération des produits:', error);
      }
    }
  }
}

</script>
</style>
