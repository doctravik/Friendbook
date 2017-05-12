<template></template>

<script>
    export default {
        /**
         * Mounted event of component.
         * 
         * @return void
         */
        mounted () {
            this.selectRequestsFromDb();
        },

        data () {
            return {
                /**
                 * @type array|null
                 */
                requests: null
            }
        },

        methods: {
            /**
             * @return void
             */
            selectRequestsFromDb () {
                //
            },

            /**
             * @TODO error message
             * @param object user
             * @return void
             */
            rejectRequest (user) {
                axios.delete('/friends/requests/' + user.id)
                    .then(response => {
                        this.removeRequest(user);
                    })
                    .catch(error => {
                        // 
                    })
            },

            /**
             * @param object user
             * @return void
             */
            removeRequest (user) {
                this.requests = this.requests.filter(request => request.id != user.id);
            },

            /**
             * @return boolean
             */
            hasRequests () {
                return this.requests && this.requests.length;
            },

            /**
             * @param object user
             * @return string
             */
            getFullName (user) {
                return user.first_name + ' ' + user.last_name;
            },
        }
    }
</script>

<style scoped>
    .media-list {
        margin: 0;
        padding: 0;
    }

    .media {
        display: flex;
        align-items: center;
        padding: 1em;
        margin: 0;
    }

    .media + .media {
        border-top: 1px solid #CCC;
    }

    .media__body {
        margin: 0 1em 0 1em;
    }

    .media__right {
        margin-left: auto;
    }
</style>