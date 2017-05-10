<template>
    <div class="btn-group">
        <button type="button" class="btn btn-default dropdown-toggle" 
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Pending request <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="#" @click.prevent="cancelFriendRequest">Cancel request</a></li>
        </ul>
    </div>
</template>

<script>
    import { NOT_FRIEND_STATE as notFriendState } from './../config';

    export default {
        props: ['profileUserId'],

        methods: {
            /** 
             * @return void
             */
            cancelFriendRequest () {
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