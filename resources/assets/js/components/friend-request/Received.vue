<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Friend requests you received</h3>
        </div>

       <ul class="media-list" v-if="hasRequests()">
            <li class="media" v-for="sender in requests">
                <div class="media__left">
                    <img class="media__image" src="http://placehold.it/64x64">
                </div>
                <div class="media__body">
                    <a :href="'/' + sender.slug"><strong>{{ getFullName(sender) }}</strong></a>
                </div>
                <div class="media__right">
                    <span class="btn btn-success" @click="acceptRequest(sender)">Accept</span>
                    <span class="btn btn-default" @click="rejectRequest(sender)">Reject</span>
                </div>
            </li>
        </ul>
        <ul class="media-list" v-else>
            <li class="media">You have no friend requests from another users</li>
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
                axios.get('/friends/requests/received')
                    .then(response => {
                        this.requests = response.data.data;
                    })
                    .catch(error => {
                        // 
                    })
            },

            /**
             * @TODO error message
             * @param object sender
             * @return void
             */
            acceptRequest (sender) {
                axios.post('/friends/' + sender.id)
                    .then(response => {
                        this.removeRequest(sender);
                        eventDispatcher.$emit('add-friend', sender);
                    })
                    .catch(error => {
                        // 
                    })
            }
        },

        mixins: [ FriendRequest ]
    }
</script>
