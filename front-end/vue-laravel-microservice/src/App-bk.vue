<script >

import axios from 'axios';

const API_URL = 'http://localhost:8000/api/';
const API_VER = 'v1/'
const API = API_URL + API_VER;

const EMPTY_NEW_MOVIE = {
  tags_id: []
};

export default {

  data() {
    
    return {

      movies: [],
      genres: [],
      tags: [],

      new_movies: EMPTY_NEW_MOVIE,

      state: {
        form: false
      }
    }
  },
  methods: {

    editMovie(movie) {

      console.log(movie);

      this.new_movies = movie;
      this.new_movies.tags_id = [];
      for (let x=0;x<movie.tags.length;x++) {

        const tag = movie.tags[x];
        this.new_movies.tags_id.push(tag.id);
      }

      this.state.form = true;
    },
    submitMovie(e) {

      e.preventDefault();

      const new_movie = this.new_movies;
      const actualApi = API + 'movie/' + (
        new_movie.id ? 'update/' + new_movie.id : 'store'
      );

      console.log(new_movie, actualApi);

      axios.post(actualApi, new_movie)
           .then(res => {

              const data = res.data;
              const success = data.success;

              const movie = data.movie;
              const originalData = data.data;

              console.log('movie', movie);
              console.log('original data', originalData);

              if (success) {

                this.closeForm();
                this.updateMovies();
              }
           });
    }, 
    closeForm() {

      this.new_movies = EMPTY_NEW_MOVIE;
      this.state.form = false;
    },
    updateMovies() {

      axios.get(API + 'movie/all')
           .then(res => {

              const data = res.data;
              const success = data.success;
              const response = data.response;

              const movies = this.movies_bk = response.movies;
              const genres = response.genres;
              const tags = response.tags;

              if (success) {

                this.movies = movies;
                this.genres = genres;
                this.tags = tags;
              }
           });           
    }
  },
  mounted() {

    this.updateMovies();
  }
};

</script>

<template>
  <div>
    <h1>Movies</h1>
    <form v-if="state.form">
      <label for="name">Name</label>
        <input type="text" name="name" v-model="new_movies.name">
        <br>
        <label for="year">Year</label>
        <input type="number" name="year" v-model="new_movies.year">
        <br>
        <label for="cashOut">Cash out</label>
        <input type="number" name="cashOut" v-model="new_movies.cashOut">
        <br>
        <label for="genre_id">Genre</label>
        <select name="genre_id" v-model="new_movies.genre_id">
          <option 
            v-for="genre in genres"
            :key="genre.id"
            :value="genre.id">
            {{ genre.name }}
          </option>
        </select>
        <br>
        <label>Tag</label>
        <br>
        <div 
          v-for="tag in tags"
          :key="tag.id">
          <input type="checkbox" :value="tag.id" :id="tag.id" v-model="new_movies.tags_id">
          <label :for="tag.id">{{ tag.name }}</label>
          <br>
        </div>
        <button @click="closeForm">CANCEL</button>
        <input @click="submitMovie" type="submit" :value="new_movies.id ? 'UPDATE MOVIE: ' + new_movies.id : 'CREATE NEW MOVIE'">
    </form>
    <div v-else>
      <button @click="state.form = true">CREATE NEW MOVIE</button>
      <ul>
        <li v-for='movie in movies' :key="movie.id">
          {{ movie.name }}
          <br>
          <button @click='editMovie(movie)'>EDIT</button>
          <br>
          Tag:
          <ul>
            <li v-for='tag in movie.tags' :key='tag.id'>
              {{ tag.name }}
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</template>

<style scoped>

</style>
