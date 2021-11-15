<template>
    <div class="container">
        <main>
            <div class="py-5 text-center">
                <img class="d-block mx-auto mb-4" src="https://cdn-icons-png.flaticon.com/512/2082/2082041.png" alt="" width="72" height="57">
                <h2>ค้นหาร้านอาหารใกล้เคียง</h2>
                <p class="lead">ค้นหาร้านอาหารในพื้นที่ใกล้เคียงตามต้องการ.</p>
            </div>

            <div class="row g-5">
                <div class="col-md-5 col-lg-4 ">
                    <form class="card p-2 mb-3" v-on:submit.prevent="search">
                        <div class="input-group">
                            <input type="text" id="search-input" v-model="search_input" class="form-control" placeholder="ป้อนพื้นที่ค้นหา">
                            <button type="button" class="btn btn-secondary" @click="search">ค้นหา</button>
                        </div>
                    </form>
                    <h4 class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-primary">Restaurants</span>
                        <span class="badge bg-primary rounded-pill">{{ lists.length }}</span>
                    </h4>
                    <div v-for="item in lists" :key="item" class="card my-2" style="width: 100%;">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img :src="item.icon" :alt="item.name" class="img-fluid rounded-start p-3" >
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">{{ item.name }}</h5>
                                    <p class="card-text">{{ item.vicinity }}.</p>
                                    <p class="card-text">
                                        <small class="text-muted">
                                        {{ item.geometry.location.lat }} , {{ item.geometry.location.lng }}
                                        </small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7 col-lg-8 order-md-last">
                    <div id="map"></div>
                </div>
            </div>
        </main>
    </div>
</template>

<style scoped>
    #map {
        height: 450px;
    }
</style>

<script>

export default {
    components: {
    },
    props: {
    },
    data() {
        return {
            search_input: 'Bang Sue',
            lists: []
        }
    },
    mounted() {
        this.getRestaurants();
    },
    methods: {
        initMap() {
        
            const map = new google.maps.Map(document.getElementById("map"), {
                center: { lat: 13.8186419, lng: 100.5386657 },
                zoom: 14,
            });

            var bounds = new google.maps.LatLngBounds();

            this.lists.forEach(function(item){

                var location = new google.maps.LatLng(item.geometry.location.lat, item.geometry.location.lng);

                var marker = new google.maps.Marker({
                    position: location,
                    map,
                    title: item.name,
                });

                var iw = new google.maps.InfoWindow({
                    content: item.name
                });

                google.maps.event.addListener(marker, "click", function (e) {
                    iw.open(map, this);
                });

                bounds.extend(location);
            })

            map.fitBounds(bounds);
        },
        async getRestaurants(search = 'Bang sue') {
            try {
                const response = await axios.get('/api/search', { params: { keyword: search }});
                this.lists = response.data;

                this.initMap()
            } catch (error) {
                console.error(error);
            }
        },
        search(){
            this.getRestaurants(this.search_input)
        }
    }
}
</script>
