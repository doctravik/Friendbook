<template>
    <button class="btn btn-default" @click="inviteFriend">Add friend</button>
</template>

<script>
    import { REQUEST_SENT_STATE as state } from './../config';

    export default {
        props: ['profileUserId'],
        
        data() {
            return {

            }
        },

        methods: {
            /**
             * @return void
             */
            inviteFriend () {
                axios.post('/friends/requests/' + this.profileUserId)
                    .then(response => {
                        this.$emit('update-state', state);
                    })
                    .catch(error => {
                        //
                    });
            }
        }
    }
</script>