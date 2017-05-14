<template>
    <div class="btn-group">
        <button type="button" class="btn btn-default dropdown-toggle" 
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Reply on inquiry <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="#" @click.prevent="acceptFriendRequest">Accept request</a></li>
            <li><a href="#" @click.prevent="rejectFriendRequest">Reject request</a></li>
        </ul>
    </div>  
</template>

<script>
    import { FRIEND_STATE as friendState } from './../config';
    import { NOT_FRIEND_STATE as notFriendState } from './../config';

    export default {
        props: ['currentUser', 'profileUserId'],

        methods: {
            /**
             * @return void
             */
            acceptFriendRequest () {
                axios.post('/friends/' + this.profileUserId)
                    .then(response => {
                        this.$emit('update-state', friendState);
                        eventDispatcher.$emit('add-friend', this.currentUser);
                    })
                    .catch(error => {
                        //
                    });
            },

            /**
             * @return void
             */
            rejectFriendRequest () {
                axios.delete('/friends/requests/' + this.profileUserId)
                    .then(response => {
                        this.$emit('update-state', notFriendState);
                    })
                    .catch(error => {
                        //
                    });
            }
        }
    }
</script>