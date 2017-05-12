<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Friend requests you sent</h3>
        </div>

       <ul class="media-list" v-if="hasRequests()">
            <li class="media" v-for="recipient in requests">
                <div class="media__left">
                    <img class="media__image" src="http://placehold.it/64x64">
                </div>
                <div class="media__body">
                    <a :href="'/' + recipient.slug"><strong>{{ getFullName(recipient) }}</strong></a>
                </div>
                <div class="media__right">
                    <span class="btn btn-default" @click="rejectRequest(recipient)">Cancel</span>
                </div>
            </li>
        </ul>
        <ul class="media-list" v-else>
            <li class="media">You have no friend requests that you have sent.</li>
        </ul>
    </div>
</template>

<script>
    import FriendRequest from './FriendRequest.vue';

    export default {
        methods: {
            /**
             * @return void
             * @TODO error message
             */
            selectRequestsFromDb () {
                axios.get('/friends/requests/sent')
                    .then(response => {
                        this.requests = response.data.data;
                    })
                    .catch(error => {
                        // 
                    })
            }
        },

        mixins: [ FriendRequest ]
    }
</script>
