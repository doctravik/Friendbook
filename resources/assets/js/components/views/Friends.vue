<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <span class="panel-title" v-text="title"></span>
            <span v-text="(count)"></span>
        </div>

       <ul class="media-list" v-if="hasUsers()">
            <li class="media" v-for="user in users">
                <div class="media__left">
                    <img class="media__image" src="http://placehold.it/64x64">
                </div>
                <div class="media__body">
                    <a :href="'/' + user.slug"><strong>{{ getFullName(user) }}</strong></a>
                </div>
                <div class="media__right" v-if="isAuthorized()">
                    <span class="btn btn-default" @click="remove(user)">Remove</span>
                </div>
            </li>
        </ul>
        <ul class="media-list" v-else>
            <li class="media">User has no friends</li>
        </ul>
    </div>
</template>

<script>
    export default {
        props: ['currentUser', 'profileUser', 'friends', 'friendsCount'],

        data () {
            return {
                /**
                 * @type array|null
                 */
                users: this.friends,

                /**
                 * @type string
                 */
                title: 'Friends',

                /**
                 * @type int
                 */
                count: this.friendsCount
            }
        },

        methods: {
            /**
             * @TODO error message
             * @param object user
             * @return void
             */
            remove (user) {
                axios.delete('/friends/' + user.id)
                    .then(response => {
                        this.removeUserOnClient(user.id);
                    })
                    .catch(error => {
                        // 
                    })
            },

            /**
             * @param int userId
             * @return void
             */
            removeUserOnClient (userId) {
                this.users = this.users.filter(user => user.id != userId);
                this.count--;
            },

            /**
             * @return boolean
             */
            hasUsers () {
                return this.users && this.users.length;
            },

            /**
             * @return boolean
             */
            isAuthorized () {
                return this.currentUser.id == this.profileUser.id;
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