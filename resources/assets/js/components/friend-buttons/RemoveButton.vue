<template>
    <button class="btn btn-default" @click="removeFriend">Remove friend</button>
</template>

<script>
    import { NOT_FRIEND_STATE as state } from './../config';

    export default {
        props: ['profileUserId', 'currentUser'],

        methods: {
            /**
             * @return void
             */
            removeFriend () {
                axios.delete('/friends/' + this.profileUserId)
                    .then(response => {
                        this.$emit('update-state', state);
                        eventDispatcher.$emit('remove-friend', this.currentUser.id);
                    })
                    .catch(error => {
                        //
                    });
            }
        }
    }
</script>