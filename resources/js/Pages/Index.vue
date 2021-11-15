<template>
    <div class="container">
        <main>
            <div class="py-5 text-center">
                <img class="d-block mx-auto mb-4" src="https://cdn-icons-png.flaticon.com/512/2082/2082041.png" alt="" width="72" height="57">
                <h2>ค้นหาร้านอาหารใกล้เคียง</h2>
                <p class="lead">ค้นหาร้านอาหารใกล้เคียง.</p>
            </div>

            <div class="row g-5">
                <div class="col-md-5 col-lg-4 ">
                    <form class="card p-2 mb-3">
                        <div class="input-group">
                            <input type="text" id="search-input" v-model="search_input" class="form-control" placeholder="ป้อนคำค้นหา">
                            <button type="button" class="btn btn-secondary" @click="search">ค้นหา</button>
                        </div>
                    </form>
                    <h4 class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-primary">ผลการค้นหา</span>
                        <!-- <span class="badge bg-primary rounded-pill">3</span> -->
                    </h4>
                    <ul class="list-group mb-3">
                        <li v-for="item in lists" :key="item" class="list-group-item d-flex justify-content-between lh-sm">
                            <!--
                            <div>
                                <h6 class="my-0">{{ item.name }}</h6>
                                <small class="text-muted"></small>
                                <img :src="item.icon" :alt="item.name">
                            </div>
                            <span class="text-muted"></span>
                            -->

                            <div class="card mb-3" style="max-width: 540px;">
  <div class="row g-0">
    <div class="col-md-4">
      <img :src="item.icon" :alt="item.name" class="img-fluid rounded-start p-3" >
    </div>
    <div class="col-md-8">
      <div class="card-body">
        <h5 class="card-title">{{ item.name }}</h5>
        <p class="card-text">{{ item.vicinity }}.</p>
        <p class="card-text"><small class="text-muted">{{ item.geometry.location.lat }} {{ item.geometry.location.lng }} </small></p>
      </div>
    </div>
  </div>
</div>
                        </li>
                    </ul>
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
        this.initMap()
    },
    methods: {
        initMap() {

            var search_result = this.getRestaurants();

            // const options = {
            //     fields: ["formatted_address", "geometry", "name"],
            //     strictBounds: false,
            //     types: ["establishment"],
            // };

            // const autocomplete = new google.maps.places.Autocomplete(document.getElementById("search-input"), options)
        
            const map = new google.maps.Map(document.getElementById("map"), {
                center: { lat: 13.8186419, lng: 100.5386657 },
                zoom: 13,
                // mapTypeControl: false,
            });

            // const infowindow = new google.maps.InfoWindow();
            // const infowindowContent = document.getElementById("infowindow-content");

            // infowindow.setContent(infowindowContent);
            // console.log(search_result);
            var self = this;
            search_result.then(function (response) {
                self.lists = response;

                self.lists.forEach(function(item){

                    console.log(item);

                    var marker = new google.maps.Marker({
                        position: item.geometry.location,
                        map,
                        title: item.name,
                    });

                    var iw = new google.maps.InfoWindow({
                        content: item.name
                    });

                    google.maps.event.addListener(marker, "click", function (e) {
                        iw.open(map, this);
                    });
                })
            })
            .catch(function (error) {
                console.log(error);
            })

            /*
            const marker = new google.maps.Marker({
                map,
                anchorPoint: new google.maps.Point(0, -29),
            });

            autocomplete.addListener("place_changed", () => {
                infowindow.close();
                // marker.setVisible(false);

                const place = autocomplete.getPlace();

                if (!place.geometry || !place.geometry.location) {
                // User entered the name of a Place that was not suggested and
                // pressed the Enter key, or the Place Details request failed.
                window.alert("No details available for input: '" + place.name + "'");
                return;
                }

                // If the place has a geometry, then present it on a map.
                if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
                } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);
                }

                marker.setPosition(place.geometry.location);
                marker.setVisible(true);
                infowindowContent.children["place-name"].textContent = place.name;
                infowindowContent.children["place-address"].textContent =
                place.formatted_address;
                infowindow.open(map, marker);
            });
            */

        },
        async getRestaurants() {
            try {
                const response = await axios.get('/api/search');
                // console.log(response.data);
                return response.data;
            } catch (error) {
                console.error(error);
            }
        },
        search(){
            console.log(this.search_input);
        }
    }
}
</script>
