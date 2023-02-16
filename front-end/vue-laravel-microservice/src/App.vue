<script >

import axios from 'axios';

export default {

  mounted() {

    axios.get('http://localhost:8000/api/v1/movie/all')
         .then(res => {

            const data = res.data;
            const success = data.success;
            const movies = data.response;

            console.log(movies);
         })
         .catch(err => console.error(err));
  }
};

</script>

<template>
  <div>
    <h1>Hello, World!</h1>
  </div>
</template>

<style scoped>

</style>
