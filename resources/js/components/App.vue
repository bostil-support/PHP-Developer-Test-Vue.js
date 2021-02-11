<template>
  <el-container>
    <el-header>
      <div class="logo">House search</div>
    </el-header>
    <el-container>
      <el-aside width="320px" class="filter">
        <div class="criteria">
          <label>
            <span class="caption">Name</span>
            <el-input
                placeholder="Please Input"
                v-model="query.name">
            </el-input>
          </label>
        </div>
        <div class="criteria">
          <label>
            <span class="caption">Bedrooms</span>
            <el-select
                v-model="query.bedrooms"
                multiple
                placeholder="Choose count bedrooms">
              <el-option
                  v-for="(bedroomsCount, index) in filter.bedrooms"
                  :key="index"
                  :label="bedroomsCount"
                  :value="bedroomsCount">
              </el-option>
            </el-select>
          </label>
        </div>
        <div class="criteria">
          <label>
            <span class="caption">Bathrooms</span>
            <el-select
                v-model="query.bathrooms"
                multiple
                placeholder="Choose count bathrooms">
              <el-option
                  v-for="(bathroomsCount, index) in filter.bathrooms"
                  :key="index"
                  :label="bathroomsCount"
                  :value="bathroomsCount">
              </el-option>
            </el-select>
          </label>
        </div>
        <div class="criteria">
          <label>
            <span class="caption">Storeys</span>
            <el-select
                v-model="query.storeys"
                multiple
                placeholder="Choose count storeys">
              <el-option
                  v-for="(storeysCount, index) in filter.storeys"
                  :key="index"
                  :label="storeysCount"
                  :value="storeysCount">
              </el-option>
            </el-select>
          </label>
        </div>
        <div class="criteria">
          <label>
            <span class="caption">Garages</span>
            <el-select
                v-model="query.garages"
                multiple
                placeholder="Choose count garages">
              <el-option
                  v-for="(garagesCount, index) in filter.garages"
                  :key="index"
                  :label="garagesCount"
                  :value="garagesCount">
              </el-option>
            </el-select>
          </label>
        </div>
        <div class="criteria">
          <label>
            <span class="capition">Price</span>
            <el-slider
                v-model="query.price"
                range
                :min="filter.min_price"
                :max="filter.max_price"
            >
            </el-slider>
          </label>
        </div>
        <div class="criteria">
          <el-button @click="fetchHouses" type="primary">Apply</el-button>
        </div>
      </el-aside>
      <el-main>
        <el-table
            v-loading="fetching"
            :data="houses"
            style="width: 100%">
          <el-table-column
              prop="name"
              label="Name"
              width="280">
          </el-table-column>
          <el-table-column
              prop="bedrooms"
              label="Bedrooms"
              width="180">
          </el-table-column>
          <el-table-column
              prop="bathrooms"
              label="Bathrooms">
          </el-table-column>
          <el-table-column
              prop="storeys"
              label="Storeys">
          </el-table-column>
          <el-table-column
              prop="garages"
              label="Garages">
          </el-table-column>
          <el-table-column
              prop="price"
              label="Price">
          </el-table-column>
        </el-table>
        <el-pagination
            @size-change="fetchHouses"
            @current-change="fetchHouses"
            :current-page.sync="query.page"
            :page-sizes="[10, 25, 50, 100]"
            :page-size.sync="query.per_page"
            layout="sizes, prev, pager, next"
            :total="totalHouses">
        </el-pagination>
      </el-main>
    </el-container>
  </el-container>
</template>
<script>

export default {
  props: {
    filter: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      query: {
        name: undefined,
        bedrooms: [],
        bathrooms: [],
        storeys: [],
        garages: [],
        price: [this.filter.min_price, this.filter.max_price],
        page: undefined,
        per_page: undefined,
      },
      fetching: true,
      houses: [],
      totalHouses: 0
    }
  },

  computed: {
    queryString() {
      const res = {...this.query, price_from: this.query.price[0], price_to: this.query.price[1]}
      delete(res.price)
      return qs.stringify(res)
    }
  },

  watch: {
    query: {
      deep: true,
      handler() {
        history.replaceState(null, null, location.origin + `?${qs.stringify(this.query)}`)
      }
    }
  },

  methods: {
    fetchHouses() {
      this.fetching = true
      axios.get('/api/houses?' + this.queryString)
      .then(({ data }) => {
        this.totalHouses = data.meta.total
        this.houses = data.data
      }).finally(() => this.fetching = false)
    }
  },

  created () {
    Object.entries(qs.parse(location.search.substr(1))).forEach(([name, value]) => {
      if (name === 'page') {
        value = parseInt(value)
      }
      this.query = {...this.query, [name]: value}
    })
  },

  mounted() {
    this.fetchHouses()
  }
}
</script>
?
