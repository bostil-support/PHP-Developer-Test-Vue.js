<template>
  <el-container>
    <el-header>
      <div class="logo">House search</div>
    </el-header>
    <el-container>
      <el-aside width="400px" class="filter">
        <div class="criteria">
          <label>
            <span class="caption">Name</span>
          </label>
          <el-input
              placeholder="Please Input"
              v-model="query.name">
          </el-input>
        </div>
        <div class="criteria">
          <label>
            <span class="caption">Bedrooms</span>

          </label>
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
        </div>
        <div class="criteria">
          <label>
            <span class="caption">Bathrooms</span>
          </label>
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
        </div>
        <div class="criteria">
          <label>
            <span class="caption">Storeys</span>
          </label>
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
        </div>
        <div class="criteria">
          <label>
            <span class="caption">Garages</span>
          </label>
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
        </div>
        <div class="criteria">
          <label>
            <span class="caption caption__price">
                <span class="caption__price--title">Price</span>
                <span class="caption__price--value">{{ price[0] }} - {{ price[1] }}</span>
            </span>
          </label>
          <el-slider
              v-model="price"
              @change="fetchHousesDebounce"
              range
              :show-tooltip="false"
              :min="filter.min_price"
              :max="filter.max_price"
          >
          </el-slider>
        </div>
      </el-aside>
      <el-main>
        <el-pagination
                v-if="meta.total"
                @size-change="fetchHouses"
                @current-change="fetchHouses"
                :current-page.sync="page"
                :page-sizes="[10, 25, 50, 100]"
                :page-size.sync="per_page"
                layout="sizes, prev, pager, next"
                :total="meta.total">
        </el-pagination>
        <el-table
            v-loading="fetching"
            :data="houses"
            @sort-change="applySorting"
            :default-sort="{ prop: query.order_by, order: query.sort }"
            style="width: 100%">
          <el-table-column
              prop="name"
              label="Name"
              sortable="custom"
              width="280">
          </el-table-column>
          <el-table-column
              prop="bedrooms"
              label="Bedrooms"
              sortable="custom"
              width="180">
          </el-table-column>
          <el-table-column
              prop="bathrooms"
              label="Bathrooms"
              sortable="custom">
          </el-table-column>
          <el-table-column
              prop="storeys"
              label="Storeys"
              sortable="custom">
          </el-table-column>
          <el-table-column
              prop="garages"
              label="Garages"
              sortable="custom">
          </el-table-column>
          <el-table-column
              prop="price"
              label="Price"
              sortable="custom">
          </el-table-column>
        </el-table>
        <el-pagination
            v-if="meta.total"
            @size-change="fetchHouses"
            @current-change="fetchHouses"
            :current-page.sync="page"
            :page-sizes="[10, 25, 50, 100]"
            :page-size.sync="per_page"
            layout="sizes, prev, pager, next"
            :total="meta.total">
        </el-pagination>
      </el-main>
    </el-container>
  </el-container>
</template>

<script>
import { debounce } from 'lodash'
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
        name: '',
        bedrooms: [],
        bathrooms: [],
        storeys: [],
        garages: [],
        order_by: '',
        sort: '',
      },
      price: [this.filter.min_price, this.filter.max_price],
      page: 1,
      per_page: 50,
      fetching: true,
      houses: [],
      meta: {}
    }
  },
  computed: {
    queryString() {
      const res = {
        ...this.query,
        price_from: this.price[0],
        price_to: this.price[1],
        page: this.page,
        per_page: this.per_page
      }
      return qs.stringify(res)
    }
  },
  watch: {
    query: {
      deep: true,
      immediate: false,
      handler() {
        this.fetching = true
        this.fetchHousesDebounce()
      }
    }
  },
  methods: {
    fetchHouses() {
      this.fetching = true
      axios.get('/api/houses?' + this.queryString)
        .then(({ data }) => {
          history.replaceState(null, null, location.origin + '?' + this.queryString)
          this.meta = data.meta
          this.houses = data.data
        })
        .finally(() => this.fetching = false)
    },
    fetchHousesDebounce: debounce( function () {
      this.fetchHouses()
    }, 500),
    applySorting({column, prop, order}) {
      this.query.order_by = order ? prop : ''
      this.query.sort = this.query.order_by ? order : ''
    }
  },
  created () {
    Object.entries(qs.parse(location.search.substr(1))).forEach(([name, value]) => {
      if (name === 'page') {
        this.page = parseInt(value)
      } else if (name === 'per_page') {
        this.per_page = parseInt(value)
      } else if (name === 'price_from') {
        this.price[0] = parseInt(value)
      } else if (name === 'price_to') {
        this.price[1] = parseInt(value)
      } else {
        this.query[name] = value
      }
    })
  },
  mounted() {
    this.fetchHouses()
  }
}
</script>

<style scoped lang="scss">
    .caption {
      &.caption__price {
        display: flex;
        justify-content: space-between;

        .caption__price--value {
          font-size: 15px;
          font-weight: normal;
          font-style: italic;
        }
      }
    }

    .el-table {
      margin: 15px 0;
    }
</style>
