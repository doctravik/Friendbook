<template>
    <button class="btn btn-default" @click="removeFriend">Remove friend</button>
</template>

<script>
    import { NOT_FRIEND_STATE as state } from './../config';

    export default {
        props: ['profileUserId'],

        methods: {
            /**
             * @return void
             */
            removeFriend () {
                axios.delete('/friends/' + this.profileUserId)
                    .then(response => {
                        this.$emit('update-state', state);
                    })
                    .catch(error => {
                    });
            }
        }
    }
</script>