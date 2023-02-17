<script >

import axios from 'axios';

const API_URL = 'http://localhost:8000/api/v1/';

export default {

  data() {
    
    return {

      force_update: false,

      movies_bk: [],
      movies: [],
      genres: [],
      tags: [],

      movie_name: '',
      movie_year: '',
      movie_cashOut: '',

      movie_genre: '',
      movie_tags: [],

      movie_creating: false,
      movie_updating: -1,
      
      test: true
    }
  },
  methods: {
    turnOnCreateMovie() {

      this.movie_creating = true;
    },
    turnOffCreateMovie() {

      this.movie_creating = false;
    },

    turnOnUpdateMovie(id) {
      
      const movie = this.getMovieById(id);
      this.movie_updating = id;

      for (let x=0;x<this.tags.length;x++) {

        const tag = this.tags[x];
        if (this.isTagPresentInMovie(movie, tag)) {

          this.movie_tags.push(tag.id);
        }
      }
    },
    turnOffUpdateMovie() {

      this.movie_updating = -1;
    },

    createMovie(e) {

      e.preventDefault();

      const movie = this.buildMovie();

      axios.post(API_URL + 'movie/store', movie)
        .then(res => {

          const data = res.data;
          const success = data.success;

          console.log('data', data);

          if (success) {

            this.clearForms();
            this.updateMovies();
          }
        }).catch(err => console.error(err));
    },
    cancelCreateMovie(e) {

      e.preventDefault();
      this.clearForms();
    },
    updateMovie(e) {

      e.preventDefault();

      const movie = this.getMovieById(this.movie_updating);
      movie['tags_id'] = this.movie_tags;
      
      axios.post(API_URL + 'movie/update/' + this.movie_updating, movie)
          .then(res => {

            const data = res.data;
            const success = data.success;

            console.log('data', data);

            if (success) {

              this.turnOffUpdateMovie();
              this.updateMovies();
            }
          }).catch(err => console.error(err));
    },
    cancelUpdateMovie(e) {

      e.preventDefault();
      this.turnOffUpdateMovie();
    },

    deleteClick(id) {

      axios.get(API_URL + 'movie/delete/' + id)
           .then(res => {

            const data = res.data;
            const success = data.success;
            
            if (success) {

              this.updateMovies();
            }
           })
           .catch(err => console.error(err));
    },

    clearForms() {

      this.movie_name = '';
      this.movie_year = '';
      this.movie_cashOut = '';
      this.movie_genre = '';
      this.movie_tags = [];

      this.turnOffCreateMovie();
    },
    updateMovies() {

      axios.get(API_URL + 'movie/all')
         .then(res => {

            console.log(res);

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
         })
         .catch(err => console.error(err));
    },

    isTagPresentInMovie(movie, tag) {

      for (let x=0;x<movie.tags.length;x++) {

        const movieTag = movie.tags[x];
        
        if (movieTag.id == tag.id) {

          return true;
        }
      }

      return false;
    },
    buildMovie() {

      return {

        'name': this.movie_name,
        'year': this.movie_year,
        'cashOut': this.movie_cashOut,
        'genre_id': this.movie_genre,
        'tags_id': this.movie_tags,
      };
    },
    getMovieIndexById(id) {

      for (let x=0;x<this.movies.length;x++) {

        const movie = this.movies[x];

        if (movie.id == id)
          return x;
      }

      return null;
    },
    getMovieById(id) {

      return this.movies[this.getMovieIndexById(id)];
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
    <form v-if="movie_creating">
      <label for="name">Name</label>
        <input type="text" name="name" v-model="movie_name">
        <br>
        <label for="year">Year</label>
        <input type="number" name="year" v-model="movie_year">
        <br>
        <label for="cashOut">Cash out</label>
        <input type="number" name="cashOut" v-model="movie_cashOut">
        <br>
        <label for="genre_id">Genre</label>
        <select name="genre_id" v-model="movie_genre">
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
          <input type="checkbox" @click="updateCheckbox" :value="tag.id" :id="tag.id" v-model="movie_tags">
          <label :for="tag.id">{{ tag.name }}</label>
          <br>
        </div>
        <button @click="cancelCreateMovie">CANCEL</button>
        <input @click="createMovie" type="submit" value="CREATE NEW MOVIE">
    </form>
    <button v-else @click="turnOnCreateMovie">CREATE NEW MOVIE</button>
    <ul>
      <li
        v-for="movie in movies"
        :key="movie.id"
      >
        {{ movie.name }}
        <br>
        <div v-if="movie_updating != movie.id">
          <button @click="turnOnUpdateMovie(movie.id)">EDIT</button>
          <button
            @click="deleteClick(movie.id)"
          >DELETE</button> 
        </div>
        <form v-else>
          <label for="name">Name</label>
            <input type="text" name="name" v-model="movie.name">
            <br>
            <label for="year">Year</label>
            <input type="number" name="year" v-model="movie.year">
            <br>
            <label for="cashOut">Cash out</label>
            <input type="number" name="cashOut" v-model="movie.cashOut">
            <br>
            <label for="genre_id">Genre</label>
            <select name="genre_id" v-model="movie.genre_id">
              <option 
                v-for="genre in genres"
                :key="genre.id"
                :value="genre.id"
                >
                {{ genre.name }}
              </option>
            </select>
            <br>
            <label>Tag</label>
            <br>
            <div 
              v-for="tag in tags"
              :key="tag.id">
              <input 
                @click="updateCheckbox" 
                type="checkbox" 
                :value="tag.id" 
                :id="tag.id" 
                :checked="isTagPresentInMovie(movie, tag)"
                v-model="movie_tags"
                >
              <label :for="tag.id">{{ tag.name }}</label>
              <br>
            </div>
            <button @click="cancelUpdateMovie">CANCEL</button>
            <input @click="updateMovie" type="submit" :value="'UPDATE MOVIE: ' + movie.id">
        </form>
      </li>
    </ul>
  </div>
</template>

<style scoped>

</style>
